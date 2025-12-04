<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Profil Alumni -->
<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">
  <div class="flex items-center mb-6">
    <img src="<?= base_url('images/logo.png') ?>" alt="Tracer Study" class="h-10 mr-3">
    <h2 class="text-xl font-bold">Profil</h2>
  </div>

  <div class="flex items-center mb-6 gap-6">
    <!-- FOTO ALUMNI -->
    <div class="flex flex-col items-center relative">
      <img id="fotoPreview"
        src="<?= (!empty($alumni) && !empty($alumni->foto))
                ? base_url('uploads/foto_alumni/' . $alumni->foto) . '?t=' . time()
                : base_url('uploads/default.png') ?>"
        class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg mb-3 cursor-pointer">

    </div>


    <!-- INFO ALUMNI -->
    <div>
      <p class="text-lg font-semibold">Nama : <?= esc($alumni->nama_lengkap) ?></p>
      <p class="text-gray-600">NIM : <?= esc($alumni->nim) ?></p>
      <p class="text-gray-600">Program Studi : <?= esc($alumni->nama_prodi ?? '-') ?></p>
      <p class="text-gray-600">Jurusan : <?= esc($alumni->nama_jurusan ?? '-') ?></p>
      <p class="text-gray-600">Alamat : <?= esc($alumni->alamat ?? '-') ?></p>
    </div>
  </div>
</div>

<!-- MODAL FOTO (Ubah Foto Profil) -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
  <div class="bg-white p-6 rounded-xl w-80 flex flex-col gap-4 shadow-lg relative z-50">
    <h3 class="text-lg font-semibold text-center">Ubah Foto Profil</h3>

    <input type="file" id="fileInput" accept="image/*" class="border rounded px-2 py-1">
    <video id="video" autoplay class="w-40 h-40 rounded-full border mx-auto hidden object-cover"></video>
    <canvas id="canvas" style="display:none;"></canvas>
    <input type="hidden" id="foto_camera" name="foto_camera">
    <button id="captureBtn" class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700 hidden">Ambil Foto</button>

    <div class="flex justify-between mt-4">
      <button type="button" onclick="useCamera()" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 flex-1 mr-1">Kamera</button>
      <button type="button" onclick="submitFoto()" class="bg-purple-600 text-white px-3 py-1 rounded-md hover:bg-purple-700 flex-1 mx-1">Simpan</button>
      <button type="button" onclick="window.closeModal()" class="bg-gray-400 text-white px-3 py-1 rounded-md hover:bg-gray-500 flex-1 ml-1">Batal</button>
    </div>
  </div>
</div>

<div id="toast" class="fixed bottom-5 right-5 bg-gray-800 text-white px-4 py-2 rounded shadow-lg opacity-0 transform translate-y-5 transition-all duration-300 z-50"></div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal');
    const fotoPreview = document.getElementById('fotoPreview');
    const fileInput = document.getElementById('fileInput');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const fotoCamera = document.getElementById('foto_camera');
    const captureBtn = document.getElementById('captureBtn');
    const toast = document.getElementById('toast');
    let stream = null;

    function openModal() {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      fileInput.value = '';
      fotoCamera.value = '';
      video.classList.add('hidden');
      captureBtn.classList.add('hidden');
    }

    window.closeModal = function() {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
      }
    };

    modal.addEventListener('click', (e) => {
      if (e.target === modal) window.closeModal();
    });

    window.useCamera = function() {
      fileInput.value = '';
      video.classList.remove('hidden');
      captureBtn.classList.remove('hidden');

      navigator.mediaDevices.getUserMedia({
          video: true
        })
        .then(s => {
          stream = s;
          video.srcObject = stream;
        })
        .catch(err => showToast('Gagal akses kamera: ' + err, 'bg-red-600'));
    };

    captureBtn.addEventListener('click', () => {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0);
      fotoCamera.value = canvas.toDataURL('image/png');
      fotoPreview.src = fotoCamera.value;
      showToast('Foto berhasil diambil', 'bg-green-600');
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
      }
      video.classList.add('hidden');
      captureBtn.classList.add('hidden');
    });

    fileInput.addEventListener('change', () => {
      const file = fileInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          fotoPreview.src = e.target.result;
          showToast('Preview siap diupload', 'bg-blue-600');
        };
        reader.readAsDataURL(file);
      }
    });

    function showToast(msg, color = 'bg-gray-800') {
      toast.textContent = msg;
      toast.className = `fixed bottom-5 right-5 ${color} text-white px-4 py-2 rounded shadow-lg opacity-100 transform translate-y-0 transition-all duration-300 z-50`;
      setTimeout(() => {
        toast.className = `fixed bottom-5 right-5 ${color} text-white px-4 py-2 rounded shadow-lg opacity-0 transform translate-y-5 transition-all duration-300 z-50`;
      }, 3000);
    }

    window.submitFoto = function() {
      const formData = new FormData();
      if (fileInput.files[0]) formData.append('foto', fileInput.files[0]);
      if (fotoCamera.value) formData.append('foto_camera', fotoCamera.value);
      formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

      fetch('<?= base_url("alumni/profil/update-foto/" . session()->get("id_account")) ?>', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            showToast('✅ Foto berhasil disimpan', 'bg-green-600');
            window.closeModal();
            const sidebarFoto = document.getElementById('sidebarFoto');
            if (sidebarFoto) sidebarFoto.src = data.fotoUrl;
          } else {
            showToast('❌ Gagal menyimpan: ' + (data.message || 'Error'), 'bg-red-600');
          }
        })
        .catch(err => showToast('❌ Terjadi error: ' + err, 'bg-red-600'));
    };

    fotoPreview.addEventListener('click', openModal);
  });
</script>
<script>
  // ✅ SweetAlert2 untuk notifikasi flashdata sukses
  <?php if (session()->getFlashdata('success')): ?>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '<?= esc(session()->getFlashdata('success')) ?>',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'OK'
    });
  <?php endif; ?>

  // ✅ SweetAlert2 untuk notifikasi gagal (opsional)
  <?php if (session()->getFlashdata('error')): ?>
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: '<?= esc(session()->getFlashdata('error')) ?>',
      confirmButtonColor: '#d33',
      confirmButtonText: 'Coba Lagi'
    });
  <?php endif; ?>
</script>

<?= $this->endSection() ?>
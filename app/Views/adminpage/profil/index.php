<?php $layout = 'layout/sidebar'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/profil.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Profil Admin -->
<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">
  <div class="profile-body">
    <!-- FOTO ADMIN -->
    <div class="profile-sidebar">
      <img id="fotoPreview"
        src="<?= !empty($admin['foto'])
                ? base_url('uploads/foto_admin/' . $admin['foto']) . '?t=' . time()
                : base_url('uploads/default.png') ?>"
        alt="Foto Profil">
      <p class="foto-change-text">Klik Untuk Mengganti Foto</p>
    </div>

    <!-- INFO AKUN -->
    <div class="profile-details">
      <p><strong>Nama Lengkap :</strong> <span><?= esc($admin['nama_lengkap'] ?? $admin['full_name'] ?? '-') ?></span></p>
      <p><strong>Username :</strong> <span><?= esc($admin['username'] ?? '-') ?></span></p>
      <p><strong>Email :</strong> <span><?= esc($admin['email'] ?? '-') ?></span></p>

      <?php
        $hp = null;
        if (!empty($admin['no_hp'])) {
          $hp = $admin['no_hp'];
        } elseif (!empty($admin['phone'])) {
          $hp = $admin['phone'];
        } elseif (!empty($admin['hp'])) {
          $hp = $admin['hp'];
        }
        if (empty($hp)) {
          $id_for_dummy = isset($admin['id']) ? intval($admin['id']) : 0;
          $hp = '0812-' . str_pad($id_for_dummy, 3, '0', STR_PAD_LEFT) . '-000';
          $hp_note = true;
        } else {
          $hp_note = false;
        }
      ?>
      <p>
        <strong>Nomor Telepon / WhatsApp :</strong> <span><?= esc($hp) ?></span>
        <?php if ($hp_note): ?>
          <span class="dummy-note"></span>
        <?php endif ?>
      </p>

      <p><strong>Status :</strong> <?= esc($admin['status'] ?? '-') ?></p>
      <p><strong>Role ID :</strong> <?= esc($admin['id_role'] ?? '-') ?></p>
      <p><strong>Dibuat :</strong> <?= esc($admin['created_at'] ?? '-') ?></p>
      <p><strong>Diupdate :</strong> <?= esc($admin['updated_at'] ?? '-') ?></p>

      <div class="profile-actions">
        <a href="<?= base_url('admin/profil/edit/' . session()->get('id_account')) ?>" class="btn-edit">Edit Profil</a>
        <a href="<?= base_url('admin/profil/ubah-password') ?>" class="btn-pass">Ubah Password</a>
      </div>
    </div>
  </div>
</div>

<!-- MODAL FOTO -->
<div id="modal" class="modal">
  <div class="modal-content">
    <h3>Ubah Foto Profil</h3>

    <!-- Upload File -->
    <input type="file" id="fileInput" accept="image/*">

    <div class="crop-container">
      <img id="cropImage" class="hidden" />
    </div>

    <!-- Kamera -->
    <div class="camera-section">
      <button type="button" onclick="openCamera()" class="btn-camera">📷 Buka Kamera</button>
      <div id="cameraWrapper" class="hidden">
        <video id="camera" autoplay playsinline class="camera-preview"></video>
        <button type="button" onclick="takeSnapshot()" class="btn-capture">📸 Ambil Foto</button>
        <canvas id="snapshot" class="hidden"></canvas>
      </div>
    </div>

    <div class="modal-actions">
      <button type="button" onclick="submitFoto()" class="btn-save">Simpan</button>
      <button type="button" onclick="window.closeModal()" class="btn-cancel">Batal</button>
    </div>
  </div>
</div>

<div id="toast" class="toast"></div>

<!-- Cropper.js -->
<link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal');
    const fotoPreview = document.getElementById('fotoPreview');
    const fileInput = document.getElementById('fileInput');
    const cropImage = document.getElementById('cropImage');
    let cropper = null;

    let cameraStream = null;
    let fotoDariKamera = null;

    // ====== Open Modal ======
    function openModal() {
      modal.classList.add('show');
      fileInput.value = '';
      cropImage.classList.add('hidden');
      if (cropper) { cropper.destroy(); cropper = null; }

      // reset kamera & snapshot
      fotoDariKamera = null;
      const video = document.getElementById('camera');
      const canvas = document.getElementById('snapshot');
      const wrapper = document.getElementById('cameraWrapper');
      const btnKamera = document.querySelector('.btn-camera');

      // Tampilkan kembali tombol Buka Kamera
      if (btnKamera) btnKamera.classList.remove('hidden');

      video.classList.remove('hidden');
      video.srcObject = null;
      canvas.classList.add('hidden');
      canvas.classList.remove('snapshot-preview');
      wrapper.classList.add('hidden');

      // kembalikan tombol capture kalau hilang
      let btnCapture = wrapper.querySelector('.btn-capture');
      if (!btnCapture) {
        const newBtn = document.createElement('button');
        newBtn.type = "button";
        newBtn.className = "btn-capture";
        newBtn.textContent = "📸 Ambil Foto";
        newBtn.onclick = takeSnapshot;
        wrapper.insertBefore(newBtn, canvas);
      } else {
        btnCapture.classList.remove('hidden');
      }
    }

    // ====== Close Modal (global) ======
    window.closeModal = function() {
      modal.classList.remove('show');

      // stop kamera
      const video = document.getElementById('camera');
      if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
        video.srcObject = null;
      }
      cameraStream = null;
    };

    modal.addEventListener('click', (e) => {
      if (e.target === modal) window.closeModal();
    });

    fotoPreview.addEventListener('click', openModal);

    // ====== Upload File ======
    fileInput.addEventListener('change', () => {
      const file = fileInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          cropImage.src = e.target.result;
          cropImage.classList.remove('hidden');
          if (cropper) cropper.destroy();
          cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
          });
        };
        reader.readAsDataURL(file);
      }
    });

    // ====== Kamera ======
    window.openCamera = function() {
      const wrapper = document.getElementById('cameraWrapper');
      const btnKamera = document.querySelector('.btn-camera');
      
      // SEMBUNYIKAN tombol Buka Kamera
      if (btnKamera) btnKamera.classList.add('hidden');
      
      // Tampilkan wrapper
      wrapper.classList.remove('hidden');
      
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
          cameraStream = stream;
          document.getElementById('camera').srcObject = stream;
        })
        .catch(err => {
          Swal.fire('Error', 'Tidak bisa akses kamera: ' + err, 'error');
          // Tampilkan kembali tombol jika gagal
          if (btnKamera) btnKamera.classList.remove('hidden');
          wrapper.classList.add('hidden');
        });
    };

    window.takeSnapshot = function() {
      const video = document.getElementById('camera');
      const canvas = document.getElementById('snapshot');
      const btnCapture = document.querySelector('.btn-capture');
      const wrapper = document.getElementById('cameraWrapper');

      // Ambil frame dari video ke canvas
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0);

      // Hentikan stream kamera
      if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
      }

      // Sembunyikan video dan tombol capture, tampilkan canvas sementara
      video.classList.add('hidden');
      if (btnCapture) btnCapture.classList.add('hidden');
      canvas.classList.remove('hidden');
      canvas.classList.add('snapshot-preview');

      // Langsung inisialisasi Cropper: Convert canvas ke dataURL dan set ke cropImage
      cropImage.src = canvas.toDataURL('image/png');
      cropImage.classList.remove('hidden');
      if (cropper) cropper.destroy();
      cropper = new Cropper(cropImage, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 1,
      });

      // Sembunyikan camera wrapper setelah init cropper
      wrapper.classList.add('hidden');

      // Tampilkan alert setelah cropper siap
      Swal.fire('Berhasil!', 'Foto berhasil diambil. Crop foto sesuai keinginan, lalu klik Simpan untuk menyimpan.', 'success');
    };

    // ====== Simpan Foto ======
    window.submitFoto = function() {
      const formData = new FormData();
      if (cropper) {
        // Jika ada cropper (dari file atau kamera), ambil cropped blob
        cropper.getCroppedCanvas({ width: 400, height: 400 })
          .toBlob(blob => {
            formData.append('foto', blob, 'cropped.png');
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            uploadFoto(formData);
          });
        return;
      } else if (fotoDariKamera) {
        // Fallback jika tidak crop
        formData.append('foto', fotoDariKamera, 'camera.png');
      } else {
        Swal.fire('Oops', 'Pilih atau ambil foto dulu', 'warning');
        return;
      }

      formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
      uploadFoto(formData);
    };

    function uploadFoto(formData) {
      fetch('<?= base_url("admin/profil/update-foto/" . session()->get("id_account")) ?>', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            Swal.fire('Sukses', 'Foto berhasil diperbarui!', 'success');
            window.closeModal();
            fotoPreview.src = data.fotoUrl + '?t=' + new Date().getTime();
            const sidebarFoto = document.getElementById('sidebarFoto');
            if (sidebarFoto) sidebarFoto.src = data.fotoUrl + '?t=' + new Date().getTime();
          } else {
            Swal.fire('Error', data.message || 'Gagal upload foto', 'error');
          }
        })
        .catch(err => Swal.fire('Error', 'Terjadi kesalahan: ' + err, 'error'))
        .finally(() => {
          if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
          }
        });
    }
  });
</script>

<!-- SweetAlert untuk flashdata -->
<?php if (session()->getFlashdata('success')): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '<?= session()->getFlashdata('success') ?>',
  confirmButtonColor: '#3085d6',
  timer: 2000,
  timerProgressBar: true
});
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Oops...',
  text: '<?= session()->getFlashdata('error') ?>',
  confirmButtonColor: '#d33'
});
</script>
<?php endif; ?>

<?= $this->endSection() ?>
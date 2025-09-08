<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">

  <!-- ✅ Alert Notification -->
  <div id="alertBox"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[9999]">
    <div id="alertContent"
      class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-11/12 text-center relative transform transition-all duration-300 scale-90 opacity-0">
      <!-- Close Button -->
      <button onclick="closeAlert()"
        class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 transition-colors">
        <span class="text-gray-600 font-bold text-lg">×</span>
      </button>

      <!-- Alert Icon -->
      <div id="alertIcon"
        class="mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center"></div>

      <!-- Alert Title -->
      <h3 id="alertTitle" class="text-xl font-bold mb-2 text-gray-800"></h3>

      <!-- Alert Message -->
      <p id="alertMessage" class="text-gray-600 mb-6 leading-relaxed"></p>
    </div>
  </div>

  <!-- Header Profil -->
  <div class="flex items-center mb-6">
    <?php 
      $foto = $kaprodi['foto'] ?? 'default.png';
      $fotoUrl = base_url('uploads/kaprodi/' . $foto);
    ?>
    <div class="relative">
      <img src="<?= $fotoUrl ?>" alt="Foto Kaprodi"
           class="w-24 h-24 rounded-full border object-cover">

      <!-- Tombol Ubah Foto -->
      <button onclick="openModal()" 
              class="absolute bottom-1 right-1 bg-blue-600 text-white text-xs px-2 py-1 rounded-full shadow hover:bg-blue-700">
        <i class="fa fa-camera"></i>
      </button>
    </div>

    <div class="ml-4">
      <h2 class="text-2xl font-bold">Profil Kaprodi</h2>
      <p class="text-gray-700">Nama: <span class="font-medium"><?= esc($kaprodi['nama_lengkap'] ?? '-') ?></span></p>
      <p class="text-gray-700">NIDN: <span class="font-medium"><?= esc($kaprodi['nidn'] ?? '-') ?></span></p>
      <p class="text-gray-700">Jabatan: <span class="font-medium"><?= esc($kaprodi['jabatan'] ?? '-') ?></span></p>
    </div>
  </div>

  <!-- Data Pribadi -->
  <div class="mb-6">
    <h3 class="text-lg font-semibold mb-2">Data Pribadi</h3>
    <table class="w-full border-collapse">
      <tr class="border-b">
        <td class="py-2 font-medium">Email</td>
        <td class="py-2"><?= esc($kaprodi['email'] ?? '-') ?></td>
      </tr>
      <tr class="border-b">
        <td class="py-2 font-medium">No HP</td>
        <td class="py-2"><?= esc($kaprodi['notlp'] ?? '-') ?></td>
      </tr>
    </table>
  </div>

  <!-- Tombol Edit Profil -->
  <div class="flex justify-end">
    <a href="<?= base_url('kaprodi/profil/edit') ?>" 
       class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
      Edit Profil
    </a>
  </div>
</div>

<!-- Modal Pilihan Upload -->
<div id="fotoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-lg p-6 w-80">
    <h2 class="text-lg font-bold mb-4 text-center">Ubah Foto Profil</h2>

    <div class="space-y-3">
      <!-- Upload dari File -->
      <form action="<?= base_url('kaprodi/profil/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="file" name="foto" id="fotoInput" class="hidden" onchange="this.form.submit()">
        <label for="fotoInput"
          class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg cursor-pointer hover:bg-blue-700">
          <i class="fa fa-upload mr-2"></i> Upload dari File
        </label>
      </form>

      <!-- Ambil dari Kamera -->
      <button onclick="openCameraModal()" 
              class="block w-full text-center bg-green-600 text-white py-2 rounded-lg cursor-pointer hover:bg-green-700">
        <i class="fa fa-camera mr-2"></i> Ambil dari Kamera
      </button>

      <!-- Batal -->
      <button onclick="closeModal()" 
              class="w-full bg-gray-300 text-gray-800 py-2 rounded-lg hover:bg-gray-400">
        Batal
      </button>
    </div>
  </div>
</div>

<!-- Modal Kamera -->
<div id="cameraModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
  <div class="bg-white p-6 rounded-2xl shadow-xl text-center w-[400px] relative">
    <h3 class="text-lg font-bold mb-4">Ambil Foto Profil</h3>

    <!-- Preview Kamera -->
    <div class="relative mx-auto w-60 h-60 rounded-full overflow-hidden shadow-lg border-4 border-gray-200">
      <video id="video" autoplay playsinline class="absolute inset-0 w-full h-full object-cover"></video>
      <canvas id="canvas" class="hidden absolute inset-0 w-full h-full rounded-full object-cover"></canvas>
    </div>

    <!-- Tombol Aksi Kamera -->
    <div id="cameraButtons" class="mt-5 flex justify-center gap-3">
      <button onclick="takeSnapshot()" class="bg-green-600 text-white px-5 py-2 rounded-full shadow">
        📸 Ambil
      </button>
      <button onclick="closeCameraModal()" class="bg-gray-500 text-white px-5 py-2 rounded-full shadow">
        ✖ Batal
      </button>
    </div>

    <!-- Tombol Aksi Preview -->
    <div id="previewButtons" class="hidden mt-5 flex justify-center gap-3">
      <button onclick="saveSnapshot()" class="bg-blue-600 text-white px-5 py-2 rounded-full shadow">
        ✅ Simpan
      </button>
      <button onclick="retakeSnapshot()" class="bg-yellow-500 text-white px-5 py-2 rounded-full shadow">
        🔄 Ulangi
      </button>
    </div>
  </div>
</div>

<!-- Form upload hasil kamera -->
<form id="uploadCameraForm" action="<?= base_url('kaprodi/profil/update') ?>" method="post" enctype="multipart/form-data" class="hidden">
  <?= csrf_field() ?>
  <input type="hidden" name="foto_camera" id="fotoCamera">
</form>

<script>
  let videoStream;
  let snapshotData = null;

  function openModal() {
    document.getElementById('fotoModal').classList.remove('hidden');
  }
  function closeModal() {
    document.getElementById('fotoModal').classList.add('hidden');
  }

  function openCameraModal() {
    closeModal();
    document.getElementById('cameraModal').classList.remove('hidden');
    document.getElementById('cameraButtons').classList.remove('hidden');
    document.getElementById('previewButtons').classList.add('hidden');

    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        videoStream = stream;
        document.getElementById('video').srcObject = stream;
      })
      .catch(err => {
        showAlert("Tidak bisa akses kamera!", "error");
      });
  }

  function closeCameraModal() {
    document.getElementById('cameraModal').classList.add('hidden');
    if (videoStream) videoStream.getTracks().forEach(track => track.stop());
  }

  function takeSnapshot() {
    const canvas = document.getElementById('canvas');
    const video = document.getElementById('video');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    snapshotData = canvas.toDataURL("image/png");

    video.classList.add("hidden");
    canvas.classList.remove("hidden");
    document.getElementById('cameraButtons').classList.add('hidden');
    document.getElementById('previewButtons').classList.remove('hidden');
  }

  function retakeSnapshot() {
    snapshotData = null;
    document.getElementById('video').classList.remove("hidden");
    document.getElementById('canvas').classList.add("hidden");
    document.getElementById('cameraButtons').classList.remove('hidden');
    document.getElementById('previewButtons').classList.add('hidden');
  }

  function saveSnapshot() {
    if (!snapshotData) return;
    document.getElementById('fotoCamera').value = snapshotData;
    document.getElementById('uploadCameraForm').submit();
    closeCameraModal();
  }

  // ✅ Alert Function
  function showAlert(message, type = "success") {
    const alertBox = document.getElementById("alertBox");
    const alertContent = document.getElementById("alertContent");
    const alertIcon = document.getElementById("alertIcon");
    const alertTitle = document.getElementById("alertTitle");
    const alertMessage = document.getElementById("alertMessage");

    if (type === "success") {
      alertIcon.innerHTML = `<svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
      </svg>`;
      alertIcon.className = "mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center bg-green-500";
      alertTitle.textContent = "Success";
    } else if (type === "error") {
      alertIcon.innerHTML = `<svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
      </svg>`;
      alertIcon.className = "mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center bg-red-500";
      alertTitle.textContent = "Error";
    } else {
      alertIcon.innerHTML = `<svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
      </svg>`;
      alertIcon.className = "mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center bg-blue-500";
      alertTitle.textContent = "Information";
    }

    alertMessage.textContent = message;

    alertBox.classList.remove("hidden");
    setTimeout(() => {
      alertContent.classList.remove("scale-90", "opacity-0");
      alertContent.classList.add("scale-100", "opacity-100");
    }, 50);

    setTimeout(() => {
      closeAlert();
    }, 4000);
  }

  function closeAlert() {
    const alertBox = document.getElementById("alertBox");
    const alertContent = document.getElementById("alertContent");
    alertContent.classList.remove("scale-100", "opacity-100");
    alertContent.classList.add("scale-90", "opacity-0");
    setTimeout(() => {
      alertBox.classList.add("hidden");
    }, 300);
  }

  document.getElementById('alertBox').addEventListener('click', function(e) {
    if (e.target === this) closeAlert(); 
  });
</script>

<!-- ✅ Flashdata Auto Show -->
<?php if (session()->getFlashdata('success')): ?>
  <script>
    window.addEventListener('load', function() {
      setTimeout(() => {
        showAlert("<?= session()->getFlashdata('success') ?>", "success");
      }, 500);
    });
  </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <script>
    window.addEventListener('load', function() {
      setTimeout(() => {
        showAlert("<?= session()->getFlashdata('error') ?>", "error");
      }, 500);
    });
  </script>
<?php endif; ?>

<?= $this->endSection() ?>

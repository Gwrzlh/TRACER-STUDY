<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">

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
      <p class="text-gray-700">Jurusan: <span class="font-medium"><?= esc($kaprodi['nama_jurusan'] ?? '-') ?></span></p>
      <p class="text-gray-700">Prodi: <span class="font-medium"><?= esc($kaprodi['nama_prodi'] ?? '-') ?></span></p>
      <p class="text-gray-700">No HP: <span class="font-medium"><?= esc($kaprodi['notlp'] ?? '-') ?></span></p>
    </div>
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
      <form action="<?= base_url('kaprodi/profil/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="file" name="foto" id="fotoInput" class="hidden" onchange="this.form.submit()">
        <label for="fotoInput"
          class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg cursor-pointer hover:bg-blue-700">
          <i class="fa fa-upload mr-2"></i> Upload dari File
        </label>
      </form>

      <button onclick="openCameraModal()"
        class="block w-full text-center bg-green-600 text-white py-2 rounded-lg cursor-pointer hover:bg-green-700">
        <i class="fa fa-camera mr-2"></i> Ambil dari Kamera
      </button>

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
    <div class="relative mx-auto w-60 h-60 rounded-full overflow-hidden shadow-lg border-4 border-gray-200">
      <video id="video" autoplay playsinline class="absolute inset-0 w-full h-full object-cover"></video>
      <canvas id="canvas" class="hidden absolute inset-0 w-full h-full rounded-full object-cover"></canvas>
    </div>
    <div id="cameraButtons" class="mt-5 flex justify-center gap-3">
      <button onclick="takeSnapshot()" class="bg-green-600 text-white px-5 py-2 rounded-full shadow">
        📸 Ambil
      </button>
      <button onclick="closeCameraModal()" class="bg-gray-500 text-white px-5 py-2 rounded-full shadow">
        ✖ Batal
      </button>
    </div>
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

    navigator.mediaDevices.getUserMedia({
        video: true
      })
      .then(stream => {
        videoStream = stream;
        document.getElementById('video').srcObject = stream;
      })
      .catch(() => showAlert("Tidak bisa akses kamera!", "error"));
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

  function showAlert(message, type = "success") {
    const alertBox = document.getElementById("alertBox");
    const alertContent = document.getElementById("alertContent");
    const alertIcon = document.getElementById("alertIcon");
    const alertTitle = document.getElementById("alertTitle");
    const alertMessage = document.getElementById("alertMessage");

    let bgClass = "bg-blue-500",
      title = "Information",
      icon = "";
    if (type === "success") {
      bgClass = "bg-green-500";
      title = "Success";
      icon = `<svg class="w-8 h-8 text-white" ...></svg>`;
    } else if (type === "error") {
      bgClass = "bg-red-500";
      title = "Error";
      icon = `<svg class="w-8 h-8 text-white" ...></svg>`;
    }

    alertIcon.innerHTML = icon;
    alertIcon.className = `mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center ${bgClass}`;
    alertTitle.textContent = title;
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

<?php if (session()->getFlashdata('success')): ?>
  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        showAlert("<?= session()->getFlashdata('success') ?>", "success");
      }, 500);
    });
  </script>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        showAlert("<?= session()->getFlashdata('error') ?>", "error");
      }, 500);
    });
  </script>
<?php endif; ?>

<?= $this->endSection() ?>
<?= $this->extend('layout/sidebar_alumni2') ?>
<?= $this->section('content') ?>

<!-- Profil Alumni (View Mode) -->
<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">

  <!-- ✅ New Alert Notification Design -->
  <div id="alertBox"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[9999]">
    <div id="alertContent" class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-11/12 text-center relative transform transition-all duration-300 scale-90 opacity-0">
      <!-- Close Button -->
      <button onclick="closeAlert()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 transition-colors">
        <span class="text-gray-600 font-bold text-lg">×</span>
      </button>
      <!-- Alert Icon -->
      <div id="alertIcon" class="mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center">
        <!-- Icon will be inserted by JavaScript -->
      </div>
      <!-- Alert Title -->
      <h3 id="alertTitle" class="text-xl font-bold mb-2 text-gray-800">
        <!-- Title will be inserted by JavaScript -->
      </h3>
      <!-- Alert Message -->
      <p id="alertMessage" class="text-gray-600 mb-6 leading-relaxed">
        <!-- Message will be inserted by JavaScript -->
      </p>
    </div>
  </div>

  <!-- Header -->
  <div class="flex items-center mb-6">
    <img src="<?= base_url('images/logo.png') ?>" alt="Tracer Study" class="h-10 mr-3">
    <h2 class="text-xl font-bold">Profil</h2>
  </div>

  <!-- Foto Profil -->
  <div class="flex items-center mb-6">
    <div class="relative">
      <img id="profileFoto"
        src="<?= $alumni->foto ? base_url('uploads/foto_alumni/' . $alumni->foto) : base_url('uploads/default.png') ?>"
        alt="Foto Profil" class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-white cursor-pointer">
      <!-- Tombol Ubah Foto -->
      <button onclick="openChoice()"
        class="absolute bottom-1 right-1 bg-blue-600 text-white text-xs px-2 py-1 rounded shadow">
        Ubah
      </button>
    </div>
    <div class="ml-6">
      <p class="text-lg font-semibold">Nama : <?= $alumni->nama_lengkap ?></p>
      <p class="text-gray-600">NIM : <?= $alumni->nim ?></p>
      <p class="text-gray-600">
        Program Studi : <?= isset($prodiMap[$alumni->id_prodi]) ? $prodiMap[$alumni->id_prodi] : 'Belum ada' ?>
      </p>
      <p class="text-gray-600">
        Jurusan : <?= isset($jurusanMap[$alumni->id_jurusan]) ? $jurusanMap[$alumni->id_jurusan] : 'Belum ada' ?>
      </p>
    </div>
  </div>

  <!-- Modal Pilihan Upload/Kamera -->
  <div id="choiceModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center space-y-4 w-80">
      <h3 class="text-lg font-bold">Pilih Opsi</h3>
      <button onclick="document.getElementById('fotoInput').click()"
        class="bg-blue-600 text-white px-4 py-2 rounded w-full">
        Upload dari File
      </button>
      <button onclick="openCamera()"
        class="bg-green-600 text-white px-4 py-2 rounded w-full">
        Ambil dari Kamera
      </button>
      <button onclick="closeChoice()"
        class="bg-gray-400 text-white px-4 py-2 rounded w-full">
        Batal
      </button>
    </div>
  </div>

  <!-- Modal Kamera -->
  <div id="cameraModal"
    class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-2xl shadow-xl text-center w-[400px] relative">
      <h3 class="text-lg font-bold mb-4">Ambil Foto Profil</h3>
      <!-- Preview Kamera Lingkaran -->
      <div class="relative mx-auto w-60 h-60 rounded-full overflow-hidden shadow-lg border-4 border-gray-200">
        <video id="video" autoplay playsinline class="absolute inset-0 w-full h-full object-cover"></video>
        <canvas id="canvas" class="hidden absolute inset-0 w-full h-full rounded-full object-cover"></canvas>
      </div>
      <!-- Tombol Aksi Kamera -->
      <div id="cameraButtons" class="mt-5 flex justify-center gap-3">
        <button onclick="takeSnapshot()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-full shadow">
          📸 Ambil
        </button>
        <button onclick="closeCamera()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-full shadow">
          ✖ Batal
        </button>
      </div>

      <!-- Tombol Aksi Preview -->
      <div id="previewButtons" class="hidden mt-5 flex justify-center gap-3">
        <button onclick="saveSnapshot()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full shadow">
          ✅ Simpan
        </button>
        <button onclick="retakeSnapshot()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-full shadow">
          🔄 Ulangi
        </button>
      </div>
    </div>
  </div>

  <!-- Form Upload Foto -->
  <form id="uploadForm" method="post" enctype="multipart/form-data"
    action="<?= base_url('alumni/updateFoto/' . $alumni->id_account) ?>" class="hidden">
    <input type="file" name="foto" id="fotoInput" accept="image/*" onchange="previewFile(this)">
    <input type="hidden" name="foto_camera" id="fotoCamera">
  </form>

  <!-- Data Diri -->
  <h3 class="text-lg font-bold mb-3">Data Diri</h3>
  <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
    <tbody>
      <tr>
        <td class="px-4 py-2 border font-medium">NIK</td>
        <td class="px-4 py-2 border"><?= $alumni->nik ?? 'Belum ada' ?></td>
      </tr>
      <tr>
        <td class="px-4 py-2 border font-medium">NPWP</td>
        <td class="px-4 py-2 border"><?= $alumni->npwp ?? 'Belum ada' ?></td>
      </tr>
      <tr>
        <td class="px-4 py-2 border font-medium">Alamat</td>
        <td class="px-4 py-2 border"><?= $alumni->alamat ?? 'Belum ada' ?></td>
      </tr>
    </tbody>
  </table>

  <!-- Riwayat Pekerjaan -->
  <h3 class="text-lg font-bold mt-8 mb-3">Riwayat Pekerjaan</h3>
  <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
    <thead class="bg-gray-100 text-gray-700">
      <tr>
        <th class="px-4 py-2 border">Tahun Masuk</th>
        <th class="px-4 py-2 border">Tahun Selesai</th>
        <th class="px-4 py-2 border">Perusahaan</th>
        <th class="px-4 py-2 border">Alamat</th>
        <th class="px-4 py-2 border">No Telepon</th>
        <th class="px-4 py-2 border">Jabatan</th>
      </tr>
    </thead>
    <tbody>
      <tr class="text-center">
        <td class="px-4 py-2 border">2020</td>
        <td class="px-4 py-2 border">2022</td>
        <td class="px-4 py-2 border">PT Sejahtera</td>
        <td class="px-4 py-2 border">Jakarta</td>
        <td class="px-4 py-2 border">021-123456</td>
        <td class="px-4 py-2 border">Staff IT</td>
      </tr>
      <tr class="text-center">
        <td class="px-4 py-2 border">2023</td>
        <td class="px-4 py-2 border">Sekarang</td>
        <td class="px-4 py-2 border">PT Teknologi Maju</td>
        <td class="px-4 py-2 border">Bandung</td>
        <td class="px-4 py-2 border">022-654321</td>
        <td class="px-4 py-2 border">Programmer</td>
      </tr>
    </tbody>
  </table>

  <!-- Tombol Aksi -->
  <div class="mt-6 flex justify-end gap-4">
    <a href="<?= base_url('alumni/profil/edit') ?>"
      class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
      Edit Profil
    </a>
  </div>
</div>

<style>
  #profileFoto {
    width: 128px;
    height: 128px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  }
</style>

<script>
  let videoStream;
  let snapshotData = null;

  function openChoice() {
    document.getElementById('choiceModal').classList.remove('hidden');
  }

  function closeChoice() {
    document.getElementById('choiceModal').classList.add('hidden');
  }

  function openCamera() {
    closeChoice();
    document.getElementById('cameraModal').classList.remove('hidden');
    document.getElementById('cameraButtons').classList.remove('hidden');
    document.getElementById('previewButtons').classList.add('hidden');
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        videoStream = stream;
        document.getElementById('video').srcObject = stream;
      })
      .catch(err => {
        showAlert("Tidak dapat mengakses kamera!", "error");
      });
  }

  function closeCamera() {
    document.getElementById('cameraModal').classList.add('hidden');
    if (videoStream) videoStream.getTracks().forEach(track => track.stop());
  }

  function takeSnapshot() {
    const canvas = document.getElementById('canvas');
    const video = document.getElementById('video');
    const container = video.parentElement;
    const size = container.offsetWidth;

    canvas.width = size;
    canvas.height = size;

    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, size, size);

    ctx.save();
    ctx.beginPath();
    ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2, true);
    ctx.closePath();
    ctx.clip();

    const videoAspect = video.videoWidth / video.videoHeight;
    let drawWidth, drawHeight, offsetX, offsetY;

    if (videoAspect > 1) {
      drawHeight = size;
      drawWidth = video.videoWidth * (size / video.videoHeight);
      offsetX = (size - drawWidth) / 2;
      offsetY = 0;
    } else {
      drawWidth = size;
      drawHeight = video.videoHeight * (size / video.videoWidth);
      offsetX = 0;
      offsetY = (size - drawHeight) / 2;
    }

    ctx.drawImage(video, offsetX, offsetY, drawWidth, drawHeight);
    ctx.restore();

    snapshotData = canvas.toDataURL("image/png");
    document.getElementById('profileFoto').src = snapshotData;

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
    closeCamera();
    setTimeout(() => {
      document.getElementById('uploadForm').submit();
      showAlert("Foto berhasil diperbarui!", "success");
    }, 500);
  }

  function previewFile(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('profileFoto').src = e.target.result;
      setTimeout(() => {
        document.getElementById('uploadForm').submit();
        showAlert("Foto berhasil diupload!", "success");
      }, 1000);
    }
    reader.readAsDataURL(file);
  }

  // ✅ Updated Alert Function
  function showAlert(message, type = "success") {
    const alertBox = document.getElementById("alertBox");
    const alertContent = document.getElementById("alertContent");
    const alertIcon = document.getElementById("alertIcon");
    const alertTitle = document.getElementById("alertTitle");
    const alertMessage = document.getElementById("alertMessage");

    // Set content based on type
    if (type === "success") {
      alertIcon.innerHTML = `
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
      `;
      alertIcon.className = "mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center bg-green-500";
      alertTitle.textContent = "Success message";
      alertTitle.className = "text-xl font-bold mb-2 text-gray-800";
    } else if (type === "error") {
      alertIcon.innerHTML = `
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
      `;
      alertIcon.className = "mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center bg-red-500";
      alertTitle.textContent = "Error message";
      alertTitle.className = "text-xl font-bold mb-2 text-gray-800";
    } else {
      alertIcon.innerHTML = `
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
      `;
      alertIcon.className = "mx-auto mb-4 w-16 h-16 rounded-full flex items-center justify-center bg-blue-500";
      alertTitle.textContent = "Information";
      alertTitle.className = "text-xl font-bold mb-2 text-gray-800";
    }

    alertMessage.textContent = message;

    // Show alert with animation
    alertBox.classList.remove("hidden");
    setTimeout(() => {
      alertContent.classList.remove("scale-90", "opacity-0");
      alertContent.classList.add("scale-100", "opacity-100");
    }, 50);

    // Auto close after 4 seconds
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

  // Close alert when clicking outside the modal
  document.getElementById('alertBox').addEventListener('click', function(e) {
    if (e.target === this) {
      closeAlert();
    }
  });
</script>

<?= $this->endSection() ?>
<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>

<!-- Profil Alumni (View Mode) -->
<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">

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
        alt="Foto Profil" class="w-28 h-28 rounded-full border mr-6 cursor-pointer">
      <!-- Tombol Ubah Foto -->
      <button onclick="openChoice()"
        class="absolute bottom-0 right-0 bg-blue-600 text-white text-xs px-2 py-1 rounded">
        Ubah
      </button>
    </div>
    <div>
      <p class="text-lg font-semibold">Nama : <?= $alumni->nama_lengkap ?></p>
      <p class="text-gray-600">NIM : <?= $alumni->nim ?></p>
      <p class="text-gray-600">Program Studi : <?= $alumni->nama_prodi ?? '-' ?></p>
      <p class="text-gray-600">Jurusan : <?= $alumni->nama_jurusan ?? '-' ?></p>
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
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
      <video id="video" width="320" height="240" autoplay class="mx-auto"></video>
      <canvas id="canvas" width="320" height="240" class="hidden"></canvas>

      <div class="mt-4 flex justify-center gap-2">
        <button onclick="takeSnapshot()" class="bg-green-600 text-white px-4 py-2 rounded">Ambil</button>
        <button onclick="closeCamera()" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
      </div>
    </div>
  </div>

  <!-- Form Upload Foto -->
  <form id="uploadForm" method="post" enctype="multipart/form-data"
    action="<?= base_url('alumni/updateFoto/' . $alumni->id_account) ?>" class="hidden">
    <input type="file" name="foto" id="fotoInput" accept="image/*" onchange="previewFile(this)">
    <input type="hidden" name="foto_camera" id="fotoCamera">
  </form>

  <script>
    let videoStream;

    function openChoice() {
      document.getElementById('choiceModal').classList.remove('hidden');
    }

    function closeChoice() {
      document.getElementById('choiceModal').classList.add('hidden');
    }

    function openCamera() {
      closeChoice();
      document.getElementById('cameraModal').classList.remove('hidden');

      navigator.mediaDevices.getUserMedia({
          video: true
        })
        .then(stream => {
          videoStream = stream;
          document.getElementById('video').srcObject = stream;
        })
        .catch(err => alert("Kamera tidak bisa diakses"));
    }

    function closeCamera() {
      document.getElementById('cameraModal').classList.add('hidden');
      if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
      }
    }

    function takeSnapshot() {
      let canvas = document.getElementById('canvas');
      let video = document.getElementById('video');
      canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

      let dataUrl = canvas.toDataURL("image/png");
      document.getElementById('fotoCamera').value = dataUrl;

      // Preview langsung di halaman
      document.getElementById('profileFoto').src = dataUrl;

      closeCamera();
      document.getElementById('uploadForm').submit();
    }

    function previewFile(input) {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('profileFoto').src = e.target.result;
        }
        reader.readAsDataURL(file);
        document.getElementById('uploadForm').submit();
      }
    }
  </script>

  <!-- Data Diri -->
  <h3 class="text-lg font-bold mb-3">Data Diri</h3>
  <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
    <tbody>
      <tr>
        <td class="px-4 py-2 border font-medium">NIK</td>
        <td class="px-4 py-2 border"><?= $alumni->nik ?? '-' ?></td>
      </tr>
      <tr>
        <td class="px-4 py-2 border font-medium">NPWP</td>
        <td class="px-4 py-2 border"><?= $alumni->npwp ?? '-' ?></td>
      </tr>
      <tr>
        <td class="px-4 py-2 border font-medium">Alamat</td>
        <td class="px-4 py-2 border"><?= $alumni->alamat ?? '-' ?></td>
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

<?= $this->endSection() ?>
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

<!-- Modal Upload Foto -->
<div id="fotoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-xl shadow-lg p-6 w-80">
    <h2 class="text-lg font-bold mb-4 text-center">Ubah Foto Profil</h2>

    <div class="space-y-3">
      <!-- Upload dari File -->
      <form action="<?= base_url('kaprodi/profil/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="file" name="foto" id="fotoInput" class="hidden" onchange="this.form.submit()">
        <label for="fotoInput" class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg cursor-pointer hover:bg-blue-700">
          <i class="fa fa-upload mr-2"></i> Upload dari File
        </label>
      </form>

      <!-- Ambil dari Kamera -->
      <form action="<?= base_url('kaprodi/profil/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="file" name="foto" accept="image/*" capture="camera" id="cameraInput" class="hidden" onchange="this.form.submit()">
        <label for="cameraInput" class="block w-full text-center bg-green-600 text-white py-2 rounded-lg cursor-pointer hover:bg-green-700">
          <i class="fa fa-camera mr-2"></i> Ambil dari Kamera
        </label>
      </form>

      <!-- Batal -->
      <button onclick="closeModal()" 
              class="w-full bg-gray-300 text-gray-800 py-2 rounded-lg hover:bg-gray-400">
        Batal
      </button>
    </div>
  </div>
</div>

<script>
  function openModal() {
    document.getElementById('fotoModal').classList.remove('hidden');
  }
  function closeModal() {
    document.getElementById('fotoModal').classList.add('hidden');
  }
</script>

<?= $this->endSection() ?>

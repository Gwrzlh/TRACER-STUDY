<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>

<!-- Profil Alumni (View Mode) -->
<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">

 <!-- Header -->
  <div class="flex items-center mb-6">
    <img src="/images/logo.png" alt="Tracer Study" class="h-10 mr-3">
    <h2 class="text-xl font-bold">Profile</h2>
  </div>


  <!-- Foto Profil -->
  <div class="flex items-center mb-6">
    <a href="<?= base_url('alumni/profil/edit') ?>">
      <img src="<?= $alumni->foto ? base_url('uploads/' . $alumni->foto) : base_url('uploads/default.png') ?>" 
           alt="Foto Profil" class="w-28 h-28 rounded-full border mr-6 cursor-pointer"> 
    </a>
    <div>
      <p class="text-lg font-semibold">Nama : <?= $alumni->nama_lengkap ?></p>
      <p class="text-gray-600">Nim : <?= $alumni->nim ?></p>
      <p class="text-gray-600"><?= $alumni->nama_prodi ?? 'Program Studi :' ?></p>
       <p class="text-gray-600"><?= $alumni->nama_prodi ?? 'Jurusan :' ?></p>
    </div>
  </div>

   <!-- Data Diri -->
  <h3 class="text-lg font-bold mb-3">Data</h3>
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

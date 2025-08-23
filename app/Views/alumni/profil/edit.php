<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>

<!-- Profil Alumni -->
<div class="bg-white rounded-xl shadow-md p-6 max-w-6xl mx-auto">

  <!-- Logo di kiri atas -->
  <div class="flex items-center mb-4">
    <img src="/images/logo.png" alt="Tracer Study" class="h-10 mr-3">
    <h2 class="text-xl font-bold">Data Diri</h2>
  </div>

  <div class="grid grid-cols-2 gap-8">
    <!-- Kolom Kiri -->
    <div class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input type="text" value="Nama Alumni" class="w-full border rounded-lg px-3 py-2" readonly>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">
          NIM <span class="text-xs text-gray-500">(tidak bisa diubah)</span>
        </label>
        <input type="text" value="123456789" class="w-full border rounded-lg px-3 py-2 bg-gray-100" readonly>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">NIK</label>
        <input type="text" value="-" class="w-full border rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">NPWP</label>
        <input type="text" value="-" class="w-full border rounded-lg px-3 py-2">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Alamat</label>
        <textarea class="w-full border rounded-lg px-3 py-2" rows="3">Alamat tinggal...</textarea>
      </div>
    </div>

    <!-- Kolom Kanan -->
    <div class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Jurusan</label>
        <input type="text" value="Teknik" class="w-full border rounded-lg px-3 py-2" readonly>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Program Studi</label>
        <input type="text" value="Teknik Informatika" class="w-full border rounded-lg px-3 py-2" readonly>
      </div>
    </div>
  </div>

  <!-- Riwayat Pekerjaan -->
  <h2 class="text-xl font-bold mt-10 mb-4">Riwayat Pekerjaan</h2>
  <div class="overflow-x-auto">
    <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="px-4 py-2 border">Tahun Masuk</th>
          <th class="px-4 py-2 border">Tahun Selesai</th>
          <th class="px-4 py-2 border">Nama Perusahaan</th>
          <th class="px-4 py-2 border">Alamat Perusahaan</th>
          <th class="px-4 py-2 border">No Telepon</th>
          <th class="px-4 py-2 border">Jabatan</th>
        </tr>
      </thead>
      <tbody>
        <!-- Contoh data -->
        <tr class="text-center">
          <td class="px-4 py-2 border">2020</td>
          <td class="px-4 py-2 border">2022</td>
          <td class="px-4 py-2 border">PT Contoh Sejahtera</td>
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
  </div>

  <!-- Tombol Tambah Riwayat -->
  <div class="mt-6">
    <a href="/riwayat/tambah" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
      + Tambah Riwayat
    </a>
  </div>

  <!-- Tombol Simpan / Batal -->
  <div class="mt-8 flex justify-end gap-4">
    <a href="/alumni/profil" class="px-5 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
      Batal
    </a>
    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
      Simpan
    </button>
  </div>

</div>

<?= $this->endSection() ?>

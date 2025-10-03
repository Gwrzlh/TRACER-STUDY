<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-md p-6 max-w-5xl mx-auto">
  <h2 class="text-2xl font-bold mb-6">Edit Profil Kaprodi</h2>

  <form action="<?= base_url('kaprodi/profil/update') ?>" method="post" class="space-y-6">
    <?= csrf_field() ?>

    <!-- Nama Lengkap -->
    <div>
      <label class="block font-medium mb-1">Nama Lengkap</label>
      <input type="text" name="nama_lengkap" value="<?= esc($kaprodi['nama_lengkap'] ?? '') ?>"
        class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-300" required>
    </div>
    <!-- No HP -->
    <div>
      <label class="block font-medium mb-1">No HP</label>
      <input type="text" name="notlp" value="<?= esc($kaprodi['notlp'] ?? '') ?>"
        class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-300">
    </div>

    <!-- Tombol -->
    <div class="flex justify-end gap-3">
      <a href="<?= base_url('kaprodi/profil') ?>"
        class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600">
        Batal
      </a>
      <button type="submit"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
        Simpan Perubahan
      </button>
    </div>
  </form>
</div>

<?= $this->endSection() ?>
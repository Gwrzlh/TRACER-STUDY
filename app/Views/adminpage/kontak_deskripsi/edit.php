<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<h1 class="text-xl font-bold mb-4">Edit Konten Halaman Kontak</h1>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('admin/kontak-deskripsi/update') ?>" method="post">
    <div class="mb-4">
        <label for="isi" class="block font-semibold mb-2">Isi Konten</label>
        <textarea name="isi" id="isi" rows="15" class="w-full p-3 border border-gray-300 rounded"><?= esc($deskripsi['isi']) ?></textarea>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
</form>

<?= $this->endSection() ?>
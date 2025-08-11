<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<h1 class="text-xl font-bold mb-4">Edit Deskripsi Kontak</h1>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('admin/kontak-deskripsi/update/' . $deskripsi['id']) ?>" method="post">
    <div class="mb-4">
        <label for="isi" class="block mb-2 font-medium">Deskripsi</label>
        <textarea name="isi" rows="15" class="border p-3 w-full"><?= esc($deskripsi['isi']) ?></textarea>
    </div>
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
</form>

<?= $this->endSection() ?>
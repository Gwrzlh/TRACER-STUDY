<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">Tambah Jurusan</h2>
    <form action="<?= base_url('satuanorganisasi/jurusan/store') ?>" method="post">


        <?= csrf_field() ?>
        <div>
            <label class="block mb-1">Nama Jurusan</label>
            <input type="text" name="nama_jurusan" required class="w-full border px-3 py-2 rounded">
        </div>
        <div class="flex justify-between mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="/satuanorganisasi" class="text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
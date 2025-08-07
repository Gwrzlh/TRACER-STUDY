<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">Edit Jurusan</h2>
    <form action="<?= base_url('satuanorganisasi/jurusan/update/' . $jurusan['id']) ?>" method="post" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="block mb-1">Nama Jurusan</label>
            <input type="text" name="nama_jurusan" value="<?= esc($jurusan['nama_jurusan']) ?>" required class="w-full border px-3 py-2 rounded">
        </div>
        <div class="flex justify-between">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
            <a href="<?= base_url('satuanorganisasi/jurusan') ?>" class="text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
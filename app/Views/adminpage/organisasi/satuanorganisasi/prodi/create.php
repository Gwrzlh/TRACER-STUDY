<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">Tambah Prodi</h2>
    <form action="<?= base_url('satuanorganisasi/prodi/store') ?>" method="post">


        <?= csrf_field() ?>
        <div>
            <label class="block mb-1">Nama Prodi</label>
            <input type="text" name="nama_prodi" required class="w-full border px-3 py-2 rounded">
        </div>
        <div>
            <label class="block mb-1">Jurusan</label>
            <select name="id_jurusan" required class="w-full border px-3 py-2 rounded">
                <option value="">-- Pilih Jurusan --</option>
                <?php foreach ($jurusan as $j): ?>
                    <option value="<?= $j['id'] ?>"><?= esc($j['nama_jurusan']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="flex justify-between">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="<?= base_url('satuanorganisasi/prodi') ?>" class="text-gray-600 hover:underline">Batal</a>

        </div>
    </form>
</div>

<?= $this->endSection() ?>
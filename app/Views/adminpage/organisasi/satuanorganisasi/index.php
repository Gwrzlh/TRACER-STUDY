<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<!-- Tab Buttons (optional, kalau mau tampilkan) -->
<div class="flex space-x-4 border-b border-gray-300 mb-6">
    <a href="<?= base_url('satuanorganisasi') ?>" class="tab-btn border-b-2 pb-2 font-semibold border-blue-600 text-blue-600">Satuan Organisasi</a>
    <a href="<?= base_url('satuanorganisasi/jurusan') ?>" class="tab-btn border-b-2 pb-2 font-semibold border-transparent text-gray-600 hover:text-blue-600">Jurusan</a>
    <a href="<?= base_url('satuanorganisasi/prodi') ?>" class="tab-btn border-b-2 pb-2 font-semibold border-transparent text-gray-600 hover:text-blue-600">Prodi</a>
</div>

<!-- Hanya konten Satuan Organisasi -->
<div class="max-w-5xl mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Satuan Organisasi</h2>
        <a href="<?= base_url('satuanorganisasi/create') ?>" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">+ Tambah</a>
    </div>
    <table class="w-full border border-gray-300 rounded shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Nama Satuan</th>
                <th class="border px-4 py-2">Singkatan</th>
                <th class="border px-4 py-2">Slug</th>
                <th class="border px-4 py-2">Tipe Organisasi</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($satuan as $row): ?>
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2"><?= esc($row['nama_satuan']) ?></td>
                    <td class="border px-4 py-2"><?= esc($row['nama_singkatan']) ?></td>
                    <td class="border px-4 py-2"><?= esc($row['nama_slug']) ?></td>
                    <td class="border px-4 py-2"><?= esc($row['nama_tipe']) ?></td>
                    <td class="border px-4 py-2 space-x-2">
                        <a href="<?= base_url('satuanorganisasi/edit/' . $row['id']) ?>" class="text-blue-600 hover:underline">Edit</a>
                        <form action="<?= base_url('satuanorganisasi/delete/' . $row['id']) ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
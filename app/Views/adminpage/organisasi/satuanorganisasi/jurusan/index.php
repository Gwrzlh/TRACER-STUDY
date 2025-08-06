    <?= $this->extend('layout/sidebar') ?>
    <?= $this->section('content') ?>

    <div class="flex space-x-4 border-b border-gray-300 mb-6">
        <a href="<?= base_url('satuanorganisasi') ?>" class="tab-btn border-b-2 pb-2 font-semibold <?= uri_string() == 'satuanorganisasi' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-blue-600' ?>">Satuan Organisasi</a>
        <a href="<?= base_url('satuanorganisasi/jurusan') ?>" class="tab-btn border-b-2 pb-2 font-semibold <?= strpos(uri_string(), 'jurusan') !== false ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-blue-600' ?>">Jurusan</a>
        <a href="<?= base_url('satuanorganisasi/prodi') ?>" class="tab-btn border-b-2 pb-2 font-semibold <?= strpos(uri_string(), 'prodi') !== false ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-blue-600' ?>">Prodi</a>
    </div>
    <div class="max-w-4xl mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Daftar Jurusan</h2>
            <a href="<?= base_url('satuanorganisasi/jurusan/create') ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif ?>

        <table class="min-w-full border border-gray-300 rounded shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nama Jurusan</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jurusan as $row): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border"><?= esc($row['id']) ?></td>
                        <td class="px-4 py-2 border"><?= esc($row['nama_jurusan']) ?></td>
                        <td class="px-4 py-2 border space-x-2">
                            <a href="<?= base_url('satuanorganisasi/jurusan/edit/' . $row['id']) ?>" class="bg-yellow-400 text-white px-3 py-1 rounded text-sm">Edit</a>
                            <form action="<?= base_url('satuanorganisasi/jurusan/delete/' . $row['id']) ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                <?= csrf_field() ?>
                                <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <?= $this->endSection() ?>
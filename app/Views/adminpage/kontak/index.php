<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<h1 class="text-xl font-bold mb-4">Daftar Kontak</h1>
<a href="<?= base_url('admin/kontak/create') ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Kontak</a>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<table class="w-full border border-gray-300">
    <thead>
        <tr class="bg-gray-100">
            <th class="p-2 border">No</th>
            <th class="p-2 border">Tipe</th>
            <th class="p-2 border">Nama</th>
            <th class="p-2 border">Kontak</th>
            <th class="p-2 border">Prodi/Jurusan</th>
            <th class="p-2 border">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kontaks as $i => $k): ?>
            <tr>
                <td class="p-2 border"><?= $i + 1 ?></td>
                <td class="p-2 border"><?= esc($k['tipe_kontak']) ?></td>
                <td class="p-2 border"><?= esc($k['nama']) ?></td>
                <td class="p-2 border"><?= esc($k['kontak']) ?></td>
                <td class="p-2 border">
                    <?php
                    if ($k['tipe_kontak'] == 'surveyor') {
                        echo esc($k['nama_prodi'] ?? '-');
                    } elseif ($k['tipe_kontak'] == 'coordinator') {
                        echo esc($k['nama_jurusan'] ?? '-');
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                <td class="p-2 border">
                    <a href="<?= base_url('admin/kontak/edit/' . $k['id']) ?>" class="text-blue-600 hover:underline">Edit</a>
                    <form action="<?= base_url('admin/kontak/delete/' . $k['id']) ?>" method="post" onsubmit="return confirm('Yakin ingin hapus?')" style="display:inline">
                        <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?= $this->endSection() ?>
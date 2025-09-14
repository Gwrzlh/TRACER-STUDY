<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-6xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Riwayat Pekerjaan</h2>

    <table class="w-full table-auto border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border-b">#</th>
                <th class="px-4 py-2 border-b">Perusahaan</th>
                <th class="px-4 py-2 border-b">Jabatan</th>
                <th class="px-4 py-2 border-b">Tahun Masuk</th>
                <th class="px-4 py-2 border-b">Tahun Keluar</th>
                <th class="px-4 py-2 border-b">Alamat</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($riwayat)): ?>
                <?php foreach ($riwayat as $i => $r): ?>
                    <tr class="<?= $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' ?>">
                        <td class="px-4 py-2 border-b"><?= $i + 1 ?></td>
                        <td class="px-4 py-2 border-b"><?= esc($r->perusahaan) ?></td>
                        <td class="px-4 py-2 border-b"><?= esc($r->jabatan) ?></td>
                        <td class="px-4 py-2 border-b"><?= esc($r->tahun_masuk) ?></td>
                        <td class="px-4 py-2 border-b"><?= esc($r->tahun_keluar) ?></td>
                        <td class="px-4 py-2 border-b"><?= esc($r->alamat_perusahaan) ?></td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-4">Belum ada data</td>
                </tr>
            <?php endif ?>
            <div class="bg-white rounded-xl shadow-md p-8 w-full max-w-6xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <a href="<?= base_url('alumni/profil') ?>"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                        Kembali ke Profil
                    </a>
                </div>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
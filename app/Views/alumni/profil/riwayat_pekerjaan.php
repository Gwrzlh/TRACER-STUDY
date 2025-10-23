<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-6xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Riwayat Pekerjaan</h2>

    <form action="<?= base_url('alumni/delete-riwayat') ?>" method="post" id="deleteForm">
        <?= csrf_field() ?>

        <div class="flex justify-between mb-3">
            <a href="<?= base_url('alumni/profil') ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                Kembali ke Profil
            </a>

            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                onclick="return confirm('Apakah kamu yakin ingin menghapus data yang dipilih?')">
                🗑️ Hapus yang Dipilih
            </button>
        </div>

        <table class="w-full table-auto border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border-b text-center">
                        <input type="checkbox" id="selectAll">
                    </th>
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
                            <td class="px-4 py-2 border-b text-center">
                                <input type="checkbox" name="selected_ids[]" value="<?= $r->id ?>" class="rowCheckbox">
                            </td>
                            <td class="px-4 py-2 border-b text-center"><?= $i + 1 ?></td>
                            <td class="px-4 py-2 border-b"><?= esc($r->perusahaan) ?></td>
                            <td class="px-4 py-2 border-b"><?= esc($r->jabatan) ?></td>
                            <td class="px-4 py-2 border-b"><?= esc($r->tahun_masuk) ?></td>
                            <td class="px-4 py-2 border-b">
                                <?= ($r->tahun_keluar == '0000' || $r->masih == 1) ? 'Masih bekerja' : esc($r->tahun_keluar) ?>
                            </td>
                            <td class="px-4 py-2 border-b"><?= esc($r->alamat_perusahaan) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-4">Belum ada data</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </form>
</div>

<script>
    // ✅ Pilih semua checkbox
    document.getElementById('selectAll')?.addEventListener('click', function() {
        document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = this.checked);
    });
</script>

<?= $this->endSection() ?>

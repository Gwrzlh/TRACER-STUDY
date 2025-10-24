<!-- app/Views/atasan/perusahaan/detail.php -->
<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <a href="<?= base_url('atasan/perusahaan') ?>" class="btn btn-secondary mb-3">⬅️ Kembali</a>

    <h3 class="mb-3">🏢 Detail Perusahaan</h3>
    <div class="card p-3 mb-4">
        <h5><?= esc($perusahaan['nama_perusahaan']) ?></h5>
        <p>
            <?= esc($perusahaan['alamat1']) ?><br>
            <?= esc($perusahaan['alamat2']) ?><br>
            <strong>Kota:</strong> <?= esc($perusahaan['id_kota'] ?? '-') ?><br>
            <strong>Provinsi:</strong> <?= esc($perusahaan['id_provinsi'] ?? '-') ?><br>
            <strong>Kode Pos:</strong> <?= esc($perusahaan['kodepos'] ?? '-') ?><br>
            <strong>Telepon:</strong> <?= esc($perusahaan['noTlp'] ?? '-') ?>
        </p>
    </div>

    <h4>👩‍🎓 Alumni yang Bekerja di Sini</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr class="text-center">
                <th>No</th>
                <th>Nama Alumni</th>
                <th>Jabatan</th>
                <th>Tahun Masuk</th>
                <th>Tahun Keluar</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($alumni)): ?>
                <?php foreach ($alumni as $i => $a): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td><?= esc($a['nama_lengkap']) ?></td>
                        <td><?= esc($a['jabatan']) ?></td>
                        <td class="text-center"><?= esc($a['tahun_masuk']) ?></td>
                        <td class="text-center"><?= $a['masih'] ? 'Masih bekerja' : esc($a['tahun_keluar']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">Belum ada alumni yang bekerja di perusahaan ini.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>

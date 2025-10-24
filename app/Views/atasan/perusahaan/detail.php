<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <a href="<?= base_url('atasan/perusahaan') ?>" class="btn btn-outline-secondary mb-3">⬅️ Kembali</a>

    <div class="card shadow-sm border-0 p-4">
        <h3 class="mb-3">🏢 Detail Perusahaan</h3>

        <h5 class="fw-bold text-primary"><?= esc($perusahaan['nama_perusahaan']) ?></h5>
        <p class="mb-1"><strong>Alamat 1:</strong> <?= esc($perusahaan['alamat1']) ?></p>
        <p class="mb-1"><strong>Alamat 2:</strong> <?= esc($perusahaan['alamat2']) ?></p>
        <p class="mb-1"><strong>Kota:</strong> <?= esc($perusahaan['kota'] ?? '-') ?></p>
        <p class="mb-1"><strong>Provinsi:</strong> <?= esc($perusahaan['provinsi'] ?? '-') ?></p>
        <p class="mb-1"><strong>Kode Pos:</strong> <?= esc($perusahaan['kodepos'] ?? '-') ?></p>
        <p><strong>Telepon:</strong> <?= esc($perusahaan['noTlp'] ?? '-') ?></p>
    </div>

    <h4 class="mt-5">👩‍🎓 Alumni yang Bekerja di Perusahaan Ini</h4>

    <table class="table table-bordered table-hover mt-3 align-middle">
        <thead class="table-primary text-center">
            <tr>
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
                        <td class="text-center"><?= $a['masih'] ? '<span class="badge bg-success">Masih bekerja</span>' : esc($a['tahun_keluar']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">
                        Belum ada alumni yang bekerja di perusahaan ini.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>

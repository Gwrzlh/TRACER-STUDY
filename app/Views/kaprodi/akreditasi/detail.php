<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Jawaban: <strong><?= esc($opsi) ?></strong></h5>
            <a href="<?= base_url('kaprodi/akreditasi') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($alumni)): ?>
                <div class="alert alert-warning text-center mb-0">
                    Belum ada alumni yang menjawab "<?= esc($opsi) ?>".
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Jurusan</th>
                                <th>Prodi</th>
                                <th>Angkatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($alumni as $a): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td class="text-start"><?= esc($a['nama']) ?></td>
                                    <td><?= esc($a['nim']) ?></td>
                                    <td><?= esc($a['jurusan']) ?></td>
                                    <td class="text-start"><?= esc($a['prodi']) ?></td>
                                    <td><?= esc($a['angkatan']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
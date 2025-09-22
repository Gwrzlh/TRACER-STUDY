<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Jawaban: <strong><?= esc($opsi) ?></strong></h5>
            <a href="<?= base_url('kaprodi/ami') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <p class="mb-3">
                Menampilkan data alumni yang memilih jawaban:
                <span class="fw-bold"><?= esc($opsi) ?></span>
            </p>

            <?php if (!empty($alumni)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:5%;">#</th>
                                <th class="text-start">Nama</th>
                                <th>NIM</th>
                                <th class="text-start">Jurusan</th>
                                <th class="text-start">Program Studi</th>
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
                                    <td class="text-start"><?= esc($a['jurusan']) ?></td>
                                    <td class="text-start"><?= esc($a['prodi']) ?></td>
                                    <td><?= esc($a['angkatan']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0 text-center">
                    Belum ada alumni yang memilih jawaban ini.
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
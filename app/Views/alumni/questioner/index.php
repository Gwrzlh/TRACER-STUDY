<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/kuesioner/index.css') ?>">

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Daftar Kuesioner Alumni</h3>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">NO</th>
                                    <th style="width: 35%;">KUESIONER</th>
                                    <!-- <th style="width: 15%;">STATUS KUESIONER</th> -->
                                    <th style="width: 15%;">STATUS ISI</th>
                                    <th style="width: 30%;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($row['judul']) ?></td>



                                        <!-- Status pengerjaan user -->
                                        <td class="text-center">
                                            <?php if ($row['statusIsi'] === 'Belum Mengisi'): ?>
                                                <span class="badge bg-secondary">Belum Mengisi</span>
                                            <?php elseif ($row['statusIsi'] === 'On Going'): ?>
                                                <span class="badge bg-warning">On Going</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Finish</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Aksi -->
                                        <td class="text-center">
                                            <?php if ($row['statusIsi'] === 'Belum Mengisi'): ?>
                                                <a href="<?= base_url('alumni/questionnaire/mulai/' . $row['id']) ?>" class="btn btn-primary btn-sm">Isi</a>
                                            <?php elseif ($row['statusIsi'] === 'On Going'): ?>
                                                <a href="<?= base_url('alumni/questionnaire/lanjutkan/' . $row['id']) ?>" class="btn btn-warning btn-sm">Lanjutkan</a>
                                            <?php else: ?>
                                                <a href="<?= base_url('alumni/questionnaire/lihat/' . $row['id']) ?>" class="btn btn-success btn-sm">Lihat Jawaban</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted">
                    <small>Menampilkan <?= count($data) ?> kuesioner</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
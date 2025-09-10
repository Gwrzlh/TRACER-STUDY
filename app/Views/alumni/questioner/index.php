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
                                    <th style="width: 8%;">NO</th>
                                    <th style="width: 50%;">KUESIONER</th>
                                    <th style="width: 20%;">STATUS</th>
                                    <th style="width: 22%;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; if (empty($data)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada kuesioner yang tersedia untuk Anda saat ini.</td>
                                    </tr>
                                <?php else: foreach ($data as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($row['judul']) ?></td>
                                        <td class="text-center">
                                            <?php if ($row['statusIsi'] == 'Belum Mengisi'): ?>
                                                <span class="badge bg-secondary">Belum Mengisi</span>
                                            <?php elseif ($row['statusIsi'] == 'On Going'): ?>
                                                <span class="badge bg-warning">On Going 
                                                    <?php if (isset($row['progress']) && $row['progress'] > 0): ?>
                                                        (<?= round($row['progress'], 1) ?>%)
                                                    <?php endif; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Finish</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['statusIsi'] == 'Belum Mengisi'): ?>
                                                <a href="<?= base_url('alumni/questionnaires/mulai/' . $row['id']) ?>" class="btn btn-primary btn-sm">Mulai Isi</a>
                                            <?php elseif ($row['statusIsi'] == 'On Going'): ?>
                                                <a href="<?= base_url('alumni/questionnaires/lanjutkan/' . $row['id']) ?>" class="btn btn-warning btn-sm">Lanjutkan</a>
                                            <?php else: ?>
                                                <a href="<?= base_url('alumni/questionnaires/lihat/' . $row['id']) ?>" class="btn btn-success btn-sm">Lihat Jawaban</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted">
                    <small>Menampilkan <?= count($data ?? []) ?> kuesioner yang sesuai dengan profil Anda.</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
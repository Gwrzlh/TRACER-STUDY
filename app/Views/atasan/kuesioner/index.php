<?= $this->extend('layout/sidebar_atasan') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/atasan/kuesioner/index.css') ?>">

<h3 class="page-title">Daftar Kuesioner Atasan</h3>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Judul Kuesioner</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= esc($row['judul']) ?></td>
                        <td>
                            <?php if ($row['statusIsi'] == 'Belum Mengisi'): ?>
                                <span class="badge badge-warning">Belum Mengisi</span>
                            <?php elseif ($row['statusIsi'] == 'On Going'): ?>
                                <span class="badge badge-info">On Going</span>
                            <?php elseif ($row['statusIsi'] == 'Finish'): ?>
                                <span class="badge badge-success">Finish</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['progress'] ?>%</td>
                        <td>
                            <?php if ($row['statusIsi'] == 'Belum Mengisi'): ?>
                                <a href="<?= base_url('atasan/kuesioner/mulai/' . $row['id']) ?>" class="btn btn-primary">Mulai</a>
                            <?php elseif ($row['statusIsi'] == 'On Going'): ?>
                                <a href="<?= base_url('atasan/kuesioner/lanjutkan/' . $row['id']) ?>" class="btn btn-warning">Lanjutkan</a>
                            <?php elseif ($row['statusIsi'] == 'Finish'): ?>
                                <a href="<?= base_url('atasan/kuesioner/lihat/' . $row['id']) ?>" class="btn btn-success">Lihat</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
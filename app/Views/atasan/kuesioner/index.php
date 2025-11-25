<!-- app/Views/atasan/kuesioner/index.php -->
<?= $this->extend('layout/sidebar_atasan') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/atasan/kuesioner/index.css') ?>">

<h3 class="page-title">Daftar Kuesioner Atasan</h3>

<table class="table table-bordered table-hover">
    <thead class="table-primary">
        <tr>
            <th>Judul Kuesioner</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
            <tr>
                <td><strong><?= esc($row['judul']) ?></strong></td>
                <td>
                    <?= $row['completed_count'] ?> / <?= $row['total_alumni'] ?> alumni
                    <div class="progress mt-1" style="height: 6px;">
                        <div class="progress-bar" style="width: <?= $row['progress'] ?>%"></div>
                    </div>
                </td>
                <td>
                    <?php if ($row['progress'] == 100): ?>
                        <span class="badge bg-success">Selesai</span>
                    <?php elseif ($row['progress'] > 0): ?>
                        <span class="badge bg-warning">Berlangsung</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Belum Mulai</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= base_url('atasan/kuesioner/daftar-alumni/' . $row['id']) ?>"
                       class="btn btn-sm <?= $row['progress'] == 100 ? 'btn-success' : 'btn-primary' ?>">
                        <?php if ($row['progress'] == 100): ?>
                            Lihat Hasil
                        <?php else: ?>
                            lihat & nilai alumni
                        <?php endif; ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?= $this->endSection() ?>
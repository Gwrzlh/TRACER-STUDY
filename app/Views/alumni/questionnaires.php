<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/kuesioner/index.css') ?>">

<div class="container-fluid">
    <!-- Statistik (optional bisa dihitung dari controller) -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon blue">📋</div>
            <div class="stat-content">
                <h3><?= count($questionnaires) ?></h3>
                <p>Total Kuesioner</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✅</div>
            <div class="stat-content">
                <h3><?= count(array_filter($questionnaires, fn($q) => $q['alumni_status'] === 'Finish')) ?></h3>
                <p>Selesai</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">🕐</div>
            <div class="stat-content">
                <h3><?= count(array_filter($questionnaires, fn($q) => $q['alumni_status'] === 'On Going')) ?></h3>
                <p>Sedang Berjalan</p>
            </div>
        </div>
    </div>

    <!-- Tabel Kuesioner -->
    <div class="dashboard-card">
        <div class="table-header">
            <h4 class="table-title">
                <div class="title-icon">📝</div>
                Daftar Questioner Alumni
            </h4>
        </div>

        <div class="table-container">
            <table class="table-modern">
                <thead>
                    <tr class="table-head-row">
                        <th>NO</th>
                        <th>KUESIONER</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($questionnaires)): ?>
                        <tr class="table-body-row">
                            <td colspan="4" class="text-center">Belum ada kuesioner tersedia</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($questionnaires as $row): ?>
                            <tr class="table-body-row">
                                <td class="table-cell-number"><?= $no++ ?></td>
                                <td class="table-cell-title">
                                    <div class="kuesioner-title"><?= esc($row['title'] ?? $row['judul'] ?? 'Tanpa Judul') ?></div>
                                    <div class="kuesioner-desc"><?= esc($row['description'] ?? 'Tidak ada deskripsi') ?></div>
                                </td>
                                <td class="table-cell-status">
                                    <?php if ($row['alumni_status'] === 'Belum Mengisi'): ?>
                                        <span class="status-badge belum">Belum Mengisi</span>
                                    <?php elseif ($row['alumni_status'] === 'On Going'): ?>
                                        <span class="status-badge ongoing">On Going</span>
                                    <?php else: ?>
                                        <span class="status-badge finish">Finish</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-cell-action">
                                    <?php if ($row['alumni_status'] === 'Belum Mengisi'): ?>
                                        <a href="<?= base_url('alumni/questionnaire/' . $row['id']) ?>" class="btn-action primary">
                                            Isi
                                        </a>
                                    <?php elseif ($row['alumni_status'] === 'On Going'): ?>
                                        <a href="<?= base_url('alumni/questionnaire/' . $row['id']) ?>" class="btn-action warning">
                                            Lanjutkan
                                        </a>
                                    <?php else: ?>
                                        <a href="#" class="btn-action success">
                                            Lihat Jawaban
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p class="footer-text">Menampilkan <?= count($questionnaires) ?> questioner</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
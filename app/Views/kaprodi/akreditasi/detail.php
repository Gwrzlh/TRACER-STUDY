<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/kaprodi/akreditasi/detail.css') ?>" rel="stylesheet">

<div class="questionnaire-container">
    <div class="page-wrapper">
        <div class="page-container">
            <div class="page-header">
                <h2 class="page-title">Detail Jawaban: <strong><?= esc($opsi) ?></strong></h2>
                <a href="<?= base_url('kaprodi/akreditasi') ?>" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="detail-title">
                        <i class="fas fa-users detail-icon"></i>
                        Daftar Alumni yang Menjawab
                    </h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($alumni)): ?>
                        <div class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-user-graduate empty-state-icon"></i>
                                <h3 class="empty-state-title">Belum Ada Alumni</h3>
                                <p class="empty-state-description">Belum ada alumni yang menjawab "<?= esc($opsi) ?>".</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <div class="table-wrapper">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th>Nama</th>
                                            <th style="width: 15%;">NIM</th>
                                            <th>Jurusan</th>
                                            <th>Prodi</th>
                                            <th style="width: 12%;">Angkatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($alumni as $a): ?>
                                            <tr>
                                                <td>
                                                    <span class="row-number"><?= $no++ ?></span>
                                                </td>
                                                <td>
                                                    <div class="user-info">
                                                        <div class="user-avatar">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <span class="user-name"><?= esc($a['nama']) ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="nim-badge"><?= esc($a['nim']) ?></span>
                                                </td>
                                                <td class="jurusan-text"><?= esc($a['jurusan']) ?></td>
                                                <td class="prodi-text"><?= esc($a['prodi']) ?></td>
                                                <td>
                                                    <span class="angkatan-badge"><?= esc($a['angkatan']) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/kaprodi/ami/detail.css') ?>" rel="stylesheet">

<div class="page-wrapper">
    <div class="page-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Detail Jawaban: <strong><?= esc($opsi) ?></strong></h1>
            <a href="<?= base_url('kaprodi/ami') ?>" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Content Card -->
        <div class="content-card">
            <div class="card-header-custom">
                <h2 class="detail-title">
                    <i class="fas fa-users detail-icon"></i>
                    Daftar Alumni yang Menjawab
                </h2>
            </div>
            
            <div class="card-body-custom">
                <p class="mb-3">
                    Menampilkan data alumni yang memilih jawaban:
                    <span class="fw-bold"><?= esc($opsi) ?></span>
                </p>

                <?php if (!empty($alumni)): ?>
                    <div class="table-container">
                        <div class="table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width:5%;">#</th>
                                        <th>Nama</th>
                                        <th>NIM</th>
                                        <th>Jurusan</th>
                                        <th>Program Studi</th>
                                        <th>Angkatan</th>
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
                                                    <div class="user-name"><?= esc($a['nama']) ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="nim-badge"><?= esc($a['nim']) ?></span>
                                            </td>
                                            <td>
                                                <span class="jurusan-text"><?= esc($a['jurusan']) ?></span>
                                            </td>
                                            <td>
                                                <span class="prodi-text"><?= esc($a['prodi']) ?></span>
                                            </td>
                                            <td>
                                                <span class="angkatan-badge"><?= esc($a['angkatan']) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-content">
                            <i class="fas fa-user-slash"></i>
                            <h3 class="empty-state-title">Belum Ada Alumni</h3>
                            <p class="empty-state-description">Belum ada alumni yang memilih jawaban ini.</p>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
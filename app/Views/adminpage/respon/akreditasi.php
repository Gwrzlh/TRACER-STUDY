<?= $this->extend('layout/sidebar'); ?>
<?= $this->section('content'); ?>

<link href="<?= base_url('css/respon/akreditasi.css') ?>" rel="stylesheet">

<div class="flex-1 overflow-y-auto bg-gray-50">
    <div class="max-w-7xl mx-auto px-8 py-8">

        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav mb-6">
            <a href="<?= base_url('admin/respon') ?>" class="breadcrumb-item">
                📋 Respon
            </a>
            <span class="breadcrumb-separator">›</span>
            <a href="<?= base_url('admin/respon/ami') ?>" class="breadcrumb-item">
                🧾 AMI
            </a>
            <span class="breadcrumb-separator">›</span>
            <a href="<?= base_url('admin/respon/akreditasi') ?>" class="breadcrumb-item active">
                📊 Akreditasi
            </a>
        </div>

        <!-- Header -->
        <div class="header-section mb-8">
            <div class="header-icon">📊</div>
            <h1 class="header-title">Data Akreditasi</h1>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div class="card-header">
                <h2 class="card-title">DAFTAR PERTANYAAN AKREDITASI</h2>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <?php if (empty($pertanyaan)) : ?>
                    <div class="empty-state">
                        <div class="empty-icon">📊</div>
                        <p class="empty-text">Belum ada pertanyaan untuk Akreditasi.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($pertanyaan as $q) : ?>
                        <div class="question-item">
                            <!-- Question Header -->
                            <div class="question-header">
                                <div class="question-info">
                                    <h3 class="question-text"><?= esc($q['teks']); ?></h3>
                                    <span class="badge-akreditasi">Akreditasi</span>
                                </div>
                                <a href="<?= base_url('admin/respon/remove_from_accreditation/' . $q['id']); ?>" 
                                   class="btn-hapus" 
                                   onclick="return confirm('Yakin hapus pertanyaan ini dari Akreditasi?')">
                                    Hapus
                                </a>
                            </div>

                            <!-- Answer Table -->
                            <div class="table-container">
                                <table class="answer-table">
                                    <thead>
                                        <tr>
                                            <th class="col-opsi">OPSI JAWABAN</th>
                                            <th class="col-jumlah">JUMLAH</th>
                                            <th class="col-detail">DETAIL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($q['jawaban'] as $a) : ?>
                                            <tr class="answer-row">
                                                <td class="opsi-cell"><?= esc($a['opsi']); ?></td>
                                                <td class="jumlah-cell">
                                                    <span class="jumlah-badge"><?= esc($a['jumlah']); ?></span>
                                                </td>
                                                <td class="detail-cell">
                                                    <a href="<?= base_url('admin/respon/akreditasi/detail/' . urlencode($a['opsi'])); ?>" 
                                                       class="btn-detail">
                                                        Lihat Alumni
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/kaprodi/questioner/index.css') ?>" rel="stylesheet">

<div class="page-wrapper">
    <div class="page-container">
        <!-- Notifikasi Flash Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Page Title -->
        <h1 class="page-title">Daftar Kuesioner Aktif</h1>

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-wrapper">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:40%;">Judul</th>
                            <th style="width:25%;">Program Studi</th>
                            <th style="width:15%;">Status</th>
                            <th style="width:15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kuesioner)): ?>
                            <?php $no = 1;
                            foreach ($kuesioner as $k): ?>
                                <tr>
                                    <td>
                                        <span class="row-number"><?= $no++ ?></span>
                                    </td>
                                    <td>
                                        <div class="questionnaire-info">
                                            <div class="questionnaire-title"><?= esc($k['title']) ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="prodi-text"><?= esc($k['nama_prodi'] ?? $kaprodi['nama_prodi'] ?? '-') ?></span>
                                    </td>
                                    <td>
                                        <?php if ($k['is_active'] === 'active'): ?>
                                            <span class="status-badge status-active">Active</span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <a href="<?= base_url('kaprodi/questioner/pertanyaan/' . $k['id']) ?>" class="btn-action btn-edit">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <div class="empty-content">
                                        <i class="fas fa-inbox"></i>
                                        <p>Tidak ada kuesioner aktif untuk prodi Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?= $this->endSection() ?><?= $this->extend('layout/sidebar_kaprodi') ?>
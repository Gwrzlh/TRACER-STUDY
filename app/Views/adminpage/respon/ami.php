<?= $this->extend('layout/sidebar'); ?>
<?= $this->section('content'); ?>

<link href="<?= base_url('css/respon/ami.css') ?>" rel="stylesheet">

<div class="flex-1 overflow-y-auto" style="background-color: #f9fafb;">
    <div style="max-width: 80rem; margin-left: auto; margin-right: auto; padding-left: 2rem; padding-right: 2rem; padding-top: 2rem; padding-bottom: 2rem;">
        
       <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav mb-6">
            <a href="<?= base_url('admin/respon') ?>" class="breadcrumb-item <?= (uri_string() == 'admin/respon') ? 'active' : '' ?>">
                <svg class="breadcrumb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Respon</span>
            </a>
            <span class="breadcrumb-separator">›</span>
            <a href="<?= base_url('admin/respon/ami') ?>" class="breadcrumb-item <?= (uri_string() == 'admin/respon/ami') ? 'active' : '' ?>">
                <svg class="breadcrumb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span>AMI</span>
            </a>
            <span class="breadcrumb-separator">›</span>
            <a href="<?= base_url('admin/respon/akreditasi') ?>" class="breadcrumb-item <?= (uri_string() == 'admin/respon/akreditasi') ? 'active' : '' ?>">
                <svg class="breadcrumb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0h2a2 2 0 012 2h2a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6z"/>
                </svg>
                <span>Akreditasi</span>
            </a>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="page-title"> Data AMI</h1>
        </div>

        <!-- Main Panel Card -->
        <div class="panel-card">
            
            <!-- Panel Header -->
            <div class="panel-header">
                <h2>DAFTAR PERTANYAAN AMI</h2>
            </div>

            <!-- Panel Content -->
            <div class="panel-content">
                <?php if (empty($pertanyaan)) : ?>
                    <div class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="empty-text">Belum ada pertanyaan untuk AMI.</p>
                    </div>
                <?php else : ?>
                    <div class="questions-container">
                        <?php foreach ($pertanyaan as $q) : ?>
                            <!-- Question Card -->
                            <div class="question-card">
                                
                                <!-- Question Header -->
                                <div class="question-header">
                                    <div class="question-title-wrapper">
                                        <h3 class="question-title"><?= esc($q['teks']); ?></h3>
                                        <span class="badge-ami">AMI</span>
                                    </div>
                                    <a href="<?= base_url('admin/respon/remove_from_ami/' . $q['id']); ?>" 
                                       class="btn-delete" 
                                       onclick="return confirm('Yakin hapus pertanyaan ini dari AMI?')">
                                        Hapus
                                    </a>
                                </div>

                                <!-- Answer Table -->
                                <div class="table-wrapper">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>Opsi Jawaban</th>
                                                <th class="col-count">Jumlah</th>
                                                <th class="col-action">Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($q['jawaban'] as $a) : ?>
                                                <tr>
                                                    <td><?= esc($a['opsi']); ?></td>
                                                    <td>
                                                        <span class="badge-count"><?= esc($a['jumlah']); ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url('admin/respon/ami/detail/' . urlencode($a['opsi'])); ?>" 
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
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>
</div>

<?= $this->endSection(); ?>
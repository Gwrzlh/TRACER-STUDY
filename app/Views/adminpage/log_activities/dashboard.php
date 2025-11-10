<?= $this->extend('layout/sidebar'); ?>
<?= $this->section('content'); ?>

<link href="<?= base_url('css/log_activities/dashboard.css') ?>" rel="stylesheet">

<div class="flex-1 overflow-y-auto bg-gray-50">
    <div class="max-w-7xl mx-auto px-8 py-8">
        
        <!-- Alert Messages -->
        <?php if (session()->has('message')): ?>
            <div class="alert-success">
                ✅ <?= esc(session()->getFlashdata('message')) ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->has('error')): ?>
            <div class="alert-error">
                ⚠️ <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="page-title">Dashboard Pengelolaan Log</h1>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <!-- Log Aktif Card -->
            <div class="stat-card">
                <div class="stat-content">
                    <h3 class="stat-title">Log Aktif</h3>
                    <p class="stat-number"><?= number_format($stats['main_count']) ?></p>
                    <small class="stat-subtitle">Log Tertua: <?= $stats['oldest_main'] ?? 'Tidak ada' ?></small>
                </div>
            </div>

            <!-- Log Arsip Card -->
            <div class="stat-card">
                <div class="stat-content">
                    <h3 class="stat-title">Log Tersimpan di Arsip</h3>
                    <p class="stat-number"><?= number_format($stats['archive_count']) ?></p>
                    <small class="stat-subtitle">Log Tertua: <?= $stats['oldest_archive'] ?? 'Tidak ada' ?></small>
                </div>
            </div>
        </div>

        <!-- Retention Policy Panel Card -->
        <div class="panel-card">
            
            <!-- Panel Header -->
            <div class="panel-header">
                <h2>KEBIJAKAN RETENSI</h2>
            </div>

            <!-- Panel Content -->
            <div class="panel-content">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Severity</th>
                                <th>Periode Retensi</th>
                                <th>Jumlah Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($retention_config->retentionPeriods as $severity => $days): ?>
                                <?php
                                $count = 0;
                                foreach ($stats['by_severity'] as $sev) {
                                    if ($sev['severity'] === $severity) {
                                        $count = $sev['count'];
                                        break;
                                    }
                                }
                                ?>
                                <tr>
                                    <td>
                                        <span class="severity-badge severity-<?= strtolower($severity) ?>">
                                            <?= $severity ?>
                                        </span>
                                    </td>
                                    <td><?= $days ?> hari</td>
                                    <td><?= number_format($count) ?> catatan</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Action Buttons Panel Card -->
        <div class="panel-card">
            
            <!-- Panel Header -->
            <div class="panel-header">
                <h2>AKSI MANUAL</h2>
            </div>

            <!-- Panel Content -->
            <div class="panel-content">
                <div class="action-buttons">
                    <button onclick="runArchive()" class="btn-warning">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        Jalankan Arsip Sekarang
                    </button>
                    <button onclick="runCleanup()" class="btn-danger">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Jalankan Pembersihan Sekarang
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function runArchive() {
    if (confirm('Arsipkan log yang lebih lama dari 30 hari?')) {
        window.location.href = '<?= base_url('admin/log_activities/manual-archive') ?>';
    }
}
function runCleanup() {
    if (confirm('Hapus log lama berdasarkan kebijakan retensi?')) {
        window.location.href = '<?= base_url('admin/log_activities/manual-cleanup') ?>';
    }
}
</script>
<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function runArchive() {
    Swal.fire({
        title: 'Arsipkan Log?',
        text: 'Log yang lebih lama dari 30 hari akan diarsipkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, arsipkan!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/log_activities/manual-archive') ?>';
        }
    });
}

function runCleanup() {
    Swal.fire({
        title: 'Hapus Log Lama?',
        text: 'Log lama akan dihapus sesuai kebijakan retensi.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/log_activities/manual-cleanup') ?>';
        }
    });
}
</script>
<?php $this->endSection(); ?>

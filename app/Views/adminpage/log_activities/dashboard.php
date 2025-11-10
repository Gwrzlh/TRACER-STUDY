<?php $this->extend('layout/sidebar'); // Ganti dengan layout Anda jika ada ?>
<?php $this->section('content'); ?>
<div class="container-fluid mt-4">
    <!-- Alert Sukses -->
    <?php if (session()->has('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('message')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <!-- Alert Error -->
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <h2 class="mb-4">Dashboard Pengelolaan Log</h2>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Log Aktif</h5>
                    <p class="card-text display-4"><?= number_format($stats['main_count']) ?></p>
                    <small class="text-muted">Log Tertua: <?= $stats['oldest_main'] ?? 'Tidak ada' ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Log Tersimpan di Arsip</h5>
                    <p class="card-text display-4"><?= number_format($stats['archive_count']) ?></p>
                    <small class="text-muted">Log Tertua: <?= $stats['oldest_archive'] ?? 'Tidak ada' ?></small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">Kebijakan Retensi</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
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
                                <td><span class="severity-pill <?= strtolower($severity) ?>"><?= $severity ?></span></td>
                                <td><?= $days ?> hari</td>
                                <td><?= number_format($count) ?> catatan</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <h5>Aksi Manual</h5>
        <div class="d-flex gap-2">
            <button onclick="runArchive()" class="btn btn-warning"><i class="fas fa-archive"></i> Jalankan Arsip Sekarang</button>
            <button onclick="runCleanup()" class="btn btn-danger"><i class="fas fa-trash"></i> Jalankan Pembersihan Sekarang</button>
        </div>
    </div>
</div>

<style>
.severity-pill {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}
.severity-pill.critical { background: #dc3545; color: white; }
.severity-pill.error { background: #fd7e14; color: white; }
.severity-pill.warning { background: #ffc107; color: #000; }
.severity-pill.info { background: #0dcaf0; color: #000; }
.severity-pill.debug { background: #6c757d; color: white; }
</style>

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
<?php $this->extend('layout/sidebar'); ?>
<?php $this->section('content'); ?>

<link href="<?= base_url('css/error_logs.css') ?>" rel="stylesheet">


<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-bug me-2 text-danger"></i>Riwayat Error Pengguna</h2>
        <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (!empty($logs)): ?>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>User ID</th>
                        <th>Aksi</th>
                        <th>Pesan Error</th>
                        <th>File</th>
                        <th>IP</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $i => $log): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($log['created_at']) ?></td>
                            <td><?= esc($log['user_id'] ?? '-') ?></td>
                            <td><span class="badge bg-danger"><?= strtoupper($log['action']) ?></span></td>
                            <td style="max-width: 300px;"><?= esc($log['message']) ?></td>
                            <td><?= esc($log['file_name'] ?? '-') ?></td>
                            <td><?= esc($log['ip_address'] ?? '-') ?></td>
                            <td style="max-width: 300px;"><?= esc($log['user_agent'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center shadow-sm">
            <i class="fas fa-info-circle me-2"></i>Belum ada log error yang tersimpan.
        </div>
    <?php endif; ?>
</div>

<?php $this->endSection(); ?>

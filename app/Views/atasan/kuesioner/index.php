<?php $layout = 'layout/sidebar_atasan'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>

<style>
/* Container utama */
.questionnaire-container {
    padding: 20px;
    background: #f9fafb;
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Wrapper */
.page-wrapper {
    display: flex;
    justify-content: center;
}

.page-container {
    width: 100%;
    max-width: 1100px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

/* Header */
.top-controls {
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(90deg, #0d47a1, #1976d2);
    color: #fff;
}

.page-title {
    font-size: 20px;
    font-weight: bold;
    margin: 0;
}

/* Table container */
.table-container {
    padding: 20px;
}

.table-wrapper {
    overflow-x: auto;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

/* Table */
.user-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
    color: #374151;
}

.user-table thead {
    background: #f3f4f6;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.03em;
}

.user-table th,
.user-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.user-table tr:hover {
    background: #f9fafb;
    transition: background 0.2s ease-in-out;
}

/* Status badges */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

.status-badge.belum-mengisi {
    background: #fee2e2;
    color: #b91c1c;
}

.status-badge.on-going {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.finish {
    background: #dcfce7;
    color: #166534;
}

/* Tombol aksi */
.action-cell {
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* Variasi tombol */
.btn-mulai {
    background: #3b82f6;
    color: #fff;
}

.btn-mulai:hover {
    background: #2563eb;
}

.btn-lanjutkan {
    background: #f59e0b;
    color: #fff;
}

.btn-lanjutkan:hover {
    background: #d97706;
}

.btn-lihat {
    background: #10b981;
    color: #fff;
}

.btn-lihat:hover {
    background: #059669;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.empty-state-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #374151;
}

.empty-state-description {
    font-size: 14px;
    color: #6b7280;
}
</style>

<div class="questionnaire-container">
    <div class="page-wrapper">
        <div class="page-container">
            <div class="top-controls">
                <h3 class="page-title">Daftar Kuesioner Atasan</h3>
            </div>

            <div class="table-container">
                <div class="table-wrapper">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KUESIONER</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            if (empty($kuesioner)): ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <div class="empty-state-title">Tidak ada kuesioner yang tersedia saat ini.</div>
                                        <div class="empty-state-description">
                                            Menampilkan <?= count($kuesioner ?? []) ?> kuesioner untuk Anda.
                                        </div>
                                    </td>
                                </tr>
                            <?php else: foreach ($kuesioner as $row): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= esc($row['judul']) ?></td>
                                    <td class="text-center">
                                        <?php if ($row['status'] == 'Belum Mengisi'): ?>
                                            <span class="status-badge belum-mengisi">Belum Mengisi</span>
                                        <?php elseif ($row['status'] == 'On Going'): ?>
                                            <span class="status-badge on-going">On Going
                                                <?php if (isset($row['progress']) && $row['progress'] > 0): ?>
                                                    (<?= round($row['progress'], 1) ?>%)
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge finish">Finish</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <?php if ($row['status'] == 'Belum Mengisi'): ?>
                                                <a href="<?= base_url('atasan/kuesioner/mulai/' . $row['id']) ?>" class="btn-action btn-mulai">▶</a>
                                            <?php elseif ($row['status'] == 'On Going'): ?>
                                                <a href="<?= base_url('atasan/kuesioner/lanjutkan/' . $row['id']) ?>" class="btn-action btn-lanjutkan">⏵</a>
                                            <?php else: ?>
                                                <a href="<?= base_url('atasan/kuesioner/lihat/' . $row['id']) ?>" class="btn-action btn-lihat">👁️</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

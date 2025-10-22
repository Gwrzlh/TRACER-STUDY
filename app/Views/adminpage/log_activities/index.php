<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="<?= base_url('css/log_activities/index.css') ?>" rel="stylesheet">
<style>
    .severity-pill {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    margin-left: 8px;
}
.severity-pill.critical { background: #dc3545; color: white; }
.severity-pill.error { background: #fd7e14; color: white; }
.severity-pill.warning { background: #ffc107; color: #000; }
.severity-pill.info { background: #0dcaf0; color: #000; }
.severity-pill.debug { background: #6c757d; color: white; }
</style>


<div class="page-wrapper">
    <div class="page-container">
        <h1 class="page-title">Log Aktivitas</h1>
        
        <!-- Filter Form -->
        <form method="get" id="filterForm" class="top-controls">
            <div class="controls-container">
                <div class="filter-row">
                    <div class="search-wrapper">
                        <input type="text" name="search" id="search" class="search-input" 
                               placeholder="Cari nama, aktivitas, atau IP..." 
                               value="<?= esc($search ?? '') ?>">
                        <button type="button" class="search-btn" style="display: none;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="date-wrapper">
                        <input type="text" name="date_range" id="dateRange" class="date-input" 
                               placeholder="Pilih rentang tanggal..." 
                               value="<?= esc($date_range ?? '') ?>">
                        <button type="button" class="date-btn" style="display: none;">
                            <i class="fas fa-calendar"></i>
                        </button>
                    </div>
                    <div class="severity-wrapper">
                        <select name="severity" id="severity" class="severity-select">
                            <option value="ALL" <?= ($severity ?? 'ALL') === 'ALL' ? 'selected' : '' ?>>Semua Severity</option>
                            <option value="CRITICAL" <?= ($severity ?? '') === 'CRITICAL' ? 'selected' : '' ?>>🔴 Critical</option>
                            <option value="ERROR" <?= ($severity ?? '') === 'ERROR' ? 'selected' : '' ?>>🟠 Error</option>
                            <option value="WARNING" <?= ($severity ?? '') === 'WARNING' ? 'selected' : '' ?>>🟡 Warning</option>
                            <option value="INFO" <?= ($severity ?? '') === 'INFO' ? 'selected' : '' ?>>🔵 Info</option>
                            <option value="DEBUG" <?= ($severity ?? '') === 'DEBUG' ? 'selected' : '' ?>>⚪ Debug</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-filter"
                style="background-color: <?= esc($settings['filter_button_color'] ?? '#0d6efd') ?>;
                       color: <?= esc($settings['filter_button_text_color'] ?? '#ffffff') ?>;">
                <i class="fas fa-filter"></i> <?= esc($settings['filter_button_text'] ?? 'Filter') ?>
            </button>

            <a href="<?= base_url('admin/log_activities') ?>" class="btn-reset"
               style="background-color: <?= esc($settings['reset_button_color'] ?? '#6c757d') ?>;
                      color: <?= esc($settings['reset_button_text_color'] ?? '#ffffff') ?>;">
                <i class="fas fa-undo"></i> <?= esc($settings['reset_button_text'] ?? 'Reset') ?>
            </a>

           <button type="button" class="btn-export" id="exportCsv"
    style="background-color: <?= esc($settings['export_button_color'] ?? '#198754') ?>;
           color: <?= esc($settings['export_button_text_color'] ?? '#ffffff') ?>;">
    <i class="fas fa-download"></i> <?= esc($settings['export_button_text'] ?? 'Export CSV') ?>
</button>

<style>
    /* Hover untuk export sesuai setting */
    .btn-export:hover {
        background-color: <?= esc($settings['export_button_hover_color'] ?? '#157347') ?> !important;
        color: <?= esc($settings['export_button_text_color'] ?? '#ffffff') ?> !important;
    }
</style>
        </form>

        <!-- Table Log -->
        <div class="table-container">
            <div class="table-wrapper">
                <table id="logTable" class="log-table table">
                    <thead>
                        <tr>
                            <th>Nama Akun</th>
                            <th>Jenis Aktivitas</th>
                            <th>IP Address</th>
                            <th>Tanggal Waktu</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-folder-open"></i>
                                        <h3>Tidak ada log ditemukan</h3>
                                        <p>Coba ubah filter atau rentang tanggal untuk melihat data.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <?php 
                                            $initial = $log['nama_lengkap'] ? substr($log['nama_lengkap'], 0, 1) : 'G';
                                            $displayName = $log['nama_lengkap'] ?: 'Guest';
                                            $userId = $log['user_id'] ?? 'N/A';
                                            ?>
                                            <div class="user-avatar" data-initial="<?= strtoupper($initial) ?>">
                                                <?= esc($initial) ?>
                                            </div>
                                            <div class="user-details">
                                                <span class="user-name"><?= esc($displayName) ?></span>
                                                <?php if ($userId !== 'N/A'): ?>
                                                    <span class="user-id">ID: <?= esc($userId) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="action-badge <?= strtolower($log['severity'] ?? 'info') ?>">
                                            <?= esc($log['action_type']) ?>
                                        </span>
                                        <span class="severity-pill <?= strtolower($log['severity'] ?? 'info') ?>">
                                            <?= esc($log['severity'] ?? 'INFO') ?>
                                        </span>
                                    </td>
                                    <td><?= esc($log['ip_adress']) ?></td>
                                    <td><?= esc(date('d M Y H:i:s', strtotime($log['created_at']))) ?></td>
                                    <td>
                                        <?php if ($log['description']): ?>
                                            <button class="btn-detail" type="button" data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal" 
                                                    data-description="<?= esc($log['description']) ?>">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </button>
                                        <?php else: ?>
                                            <span class="no-detail">Tidak ada detail</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- ✅ Pagination dan Info -->
                <?php if (!empty($logs)): ?>
                <div class="table-footer d-flex justify-content-between align-items-center">
                    <small>
                        Menampilkan <?= count($logs) ?> log dari <?= esc($pager->getTotal()) ?> total data
                        (<?= esc($settings['log_perpage_default'] ?? 10) ?> per halaman)
                    </small>
                    <div>
                        <?= $pager->links('default', 'pagination') ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Aktivitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre id="modalDescription"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Handle date range
    var dateRangeValue = "<?= esc($date_range ?? '') ?>";
    var defaultDates = [];
    if (dateRangeValue) {
        var parts = dateRangeValue.split(' s/d ');
        if (parts.length === 2) {
            defaultDates = [parts[0], parts[1]];
        }
    }

    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: defaultDates,
        locale: { rangeSeparator: " s/d " }
    });

    // Modal detail
    $('#detailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var description = button.attr('data-description');
        $('#modalDescription').text(description || 'Detail tidak tersedia');
    });

    // Export CSV
    $('#exportCsv').click(function () {
        var search = $('#search').val();
        var dateRange = $('#dateRange').val();
        window.location.href = "<?= base_url('admin/log_activities/export') ?>?search=" + encodeURIComponent(search) + "&date_range=" + encodeURIComponent(dateRange);
    });

    // Submit form on Enter
    $('#search, #dateRange').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#filterForm').submit();
        }
    });
</script>
<?= $this->endSection() ?>

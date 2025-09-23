<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
/* ===== Global Styles ===== */
* {
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: #f8fafc;
    color: #1e293b;
    line-height: 1.6;
}

/* ===== Page Layout ===== */
.page-wrapper {
    padding: 24px;
    background-color: #f8fafc;
    min-height: 100vh;
}

.page-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 32px;
    letter-spacing: -0.5px;
}

/* ===== Top Controls Layout ===== */
.top-controls {
    display: flex;
    justify-content: space-between;
    align-items: end;
    margin-bottom: 24px;
    gap: 20px;
    flex-wrap: wrap;
}

.controls-container {
    flex: 1;
    min-width: 300px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.filter-row {
    display: flex;
    gap: 12px;
    align-items: end;
    flex-wrap: wrap;
}

.search-wrapper,
.date-wrapper {
    display: flex;
    gap: 0;
    align-items: center;
    min-width: 280px;
}

.search-input,
.date-input {
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    background-color: white;
    transition: all 0.2s ease;
    flex: 1;
}

.search-input:focus,
.date-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-btn,
.date-btn {
    display: none; /* Hide individual search buttons */
}

/* ===== Button Container (Kanan) ===== */
.button-container {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-shrink: 0;
}

.btn-filter,
.btn-reset,
.btn-export {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    white-space: nowrap;
}

.btn-filter {
    background-color: #3b82f6;
    color: white;
    min-width: 100px;
    justify-content: center;
}

.btn-filter:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    color: white;
    text-decoration: none;
}

.btn-reset {
    background-color: #6b7280;
    color: white;
    min-width: 100px;
    justify-content: center;
}

.btn-reset:hover {
    background-color: #4b5563;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    color: white;
    text-decoration: none;
}

.btn-export {
    background-color: #10b981;
    color: white;
    min-width: 120px;
    justify-content: center;
}

.btn-export:hover {
    background-color: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    color: white;
    text-decoration: none;
}

/* ===== Table Container ===== */
.table-container {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.table-wrapper {
    overflow-x: auto;
}

/* ===== Modern Table Styles ===== */
.log-table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    font-size: 14px;
}

/* Table Header */
.log-table thead {
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.log-table th {
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e5e7eb;
}

.log-table th:last-child {
    width: 120px;
    text-align: center;
}

/* Table Body */
.log-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s ease;
}

.log-table tbody tr:hover {
    background-color: #f9fafb;
}

.log-table tbody tr:last-child {
    border-bottom: none;
}

.log-table td {
    padding: 16px 20px;
    vertical-align: middle;
    color: #374151;
}

/* User Info Cell - Avatar Sederhana */
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0; /* Hide text nodes between elements */
}

.user-info > * {
    font-size: 14px; /* Restore font size for child elements */
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-weight: 600;
    font-size: 14px;
    color: white;
    text-shadow: none;
    position: relative;
}

/* Warna avatar berdasarkan huruf pertama */
.user-avatar[data-initial="A"] { background-color: #f59e0b; }
.user-avatar[data-initial="B"] { background-color: #10b981; }
.user-avatar[data-initial="C"] { background-color: #3b82f6; }
.user-avatar[data-initial="D"] { background-color: #8b5cf6; }
.user-avatar[data-initial="E"] { background-color: #ef4444; }
.user-avatar[data-initial="F"] { background-color: #06b6d4; }
.user-avatar[data-initial="G"] { background-color: #84cc16; }
.user-avatar[data-initial="H"] { background-color: #f97316; }
.user-avatar[data-initial="I"] { background-color: #ec4899; }
.user-avatar[data-initial="J"] { background-color: #14b8a6; }
.user-avatar[data-initial="K"] { background-color: #6366f1; }
.user-avatar[data-initial="L"] { background-color: #a855f7; }
.user-avatar[data-initial="M"] { background-color: #22c55e; }
.user-avatar[data-initial="N"] { background-color: #f59e0b; }
.user-avatar[data-initial="O"] { background-color: #ef4444; }
.user-avatar[data-initial="P"] { background-color: #8b5cf6; }
.user-avatar[data-initial="Q"] { background-color: #06b6d4; }
.user-avatar[data-initial="R"] { background-color: #ef4444; }
.user-avatar[data-initial="S"] { background-color: #10b981; }
.user-avatar[data-initial="T"] { background-color: #f97316; }
.user-avatar[data-initial="U"] { background-color: #8b5cf6; }
.user-avatar[data-initial="V"] { background-color: #22c55e; }
.user-avatar[data-initial="W"] { background-color: #f59e0b; }
.user-avatar[data-initial="X"] { background-color: #ec4899; }
.user-avatar[data-initial="Y"] { background-color: #84cc16; }
.user-avatar[data-initial="Z"] { background-color: #6366f1; }

.user-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.user-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 14px;
}

.user-details .user-id {
    font-size: 13px;
    color: #64748b;
}

/* Action Type Badge */
.action-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 500;
    text-transform: capitalize;
    background-color: #ede9fe;
    color: #6d28d9;
    border: 1px solid #c4b5fd;
}

/* Detail Button */
.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    background-color: #f0f9ff;
    color: #0369a1;
    border: 1px solid #b4d4ff;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-detail:hover {
    background-color: #0369a1;
    color: white;
    border-color: #0369a1;
    text-decoration: none;
}

/* No Detail Text */
.no-detail {
    color: #64748b;
    font-size: 13px;
    font-style: italic;
}

/* ===== Empty State ===== */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.empty-state i {
    font-size: 48px;
    color: #9ca3af;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 20px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.empty-state p {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 24px;
}

/* ===== DataTables Custom Styles for Minimalist Pagination ===== */
.dataTables_wrapper {
    padding: 0;
}

.dataTables_wrapper .dataTables_info {
    display: none; /* Hide info for minimalism */
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    display: none; /* Hide length and search since custom */
}

.dataTables_wrapper .dataTables_paginate {
    margin-top: 16px;
    padding: 16px 20px;
    background-color: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 6px;
    margin: 0 2px;
    padding: 8px 12px;
    background: white;
    border: 1px solid #d1d5db;
    color: #374151;
    transition: all 0.2s ease;
    font-size: 13px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ===== Modal Styles ===== */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 12px 12px 0 0;
    padding: 20px 24px;
}

.modal-title {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.modal-body {
    padding: 24px;
}

.modal-body pre {
    background: #f8f9fa;
    padding: 16px;
    border-radius: 8px;
    font-size: 14px;
    color: #374151;
    white-space: pre-wrap;
    word-wrap: break-word;
    border: 1px solid #e5e7eb;
}

.modal-footer {
    border-top: 1px solid #e5e7eb;
    padding: 16px 24px;
    border-radius: 0 0 12px 12px;
}

.modal-footer .btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
}

/* Custom Footer */
.table-footer {
    background-color: #f9fafb;
    padding: 12px 20px;
    text-align: center;
    color: #64748b;
    font-size: 13px;
    border-top: 1px solid #e5e7eb;
}

/* ===== Responsive Design ===== */
@media (max-width: 768px) {
    .page-wrapper {
        padding: 16px;
    }
    
    .page-title {
        font-size: 24px;
        margin-bottom: 24px;
    }
    
    .top-controls {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }
    
    .filter-row {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-wrapper,
    .date-wrapper {
        min-width: auto;
    }
    
    .button-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-filter,
    .btn-reset,
    .btn-export {
        min-width: auto;
    }
    
    .log-table {
        font-size: 13px;
    }
    
    .log-table th,
    .log-table td {
        padding: 12px 16px;
    }
    
    .user-info {
        gap: 8px;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
    
    .user-name {
        font-size: 13px;
    }
    
    .user-details .user-id {
        font-size: 12px;
    }
    
    .btn-detail {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        padding: 12px 16px;
    }
}

@media (max-width: 576px) {
    .table-container {
        margin: 0 -8px;
    }
    
    .log-table th,
    .log-table td {
        padding: 10px 12px;
    }
    
    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    
    .user-avatar {
        width: 28px;
        height: 28px;
        font-size: 11px;
    }
    
    /* Hide IP column on very small screens */
    .log-table th:nth-child(3),
    .log-table td:nth-child(3) {
        display: none;
    }
    
    .empty-state {
        padding: 40px 16px;
    }
    
    .empty-state i {
        font-size: 36px;
    }
}
</style>

<div class="page-wrapper">
    <div class="page-container">
        <h1 class="page-title">Log Aktivitas</h1>
        
        <!-- Filter Form -->
        <form method="get" id="filterForm" class="top-controls">
            <div class="controls-container">
                <div class="filter-row">
                    <div class="search-wrapper">
                        <input type="text" name="search" id="search" class="search-input" placeholder="Cari nama, aktivitas, atau IP..." value="<?= esc($search ?? '') ?>">
                        <button type="button" class="search-btn" style="display: none;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="date-wrapper">
                        <input type="text" name="date_range" id="dateRange" class="date-input" placeholder="Pilih rentang tanggal..." value="<?= esc($date_range ?? '') ?>">
                        <button type="button" class="date-btn" style="display: none;">
                            <i class="fas fa-calendar"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="<?= base_url('admin/log_activities') ?>" class="btn-reset">
                    <i class="fas fa-undo"></i> Reset
                </a>
                <button type="button" class="btn-export" id="exportCsv">
                    <i class="fas fa-download"></i> Export CSV
                </button>
            </div>
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
                                        <span class="action-badge"><?= esc($log['action_type']) ?></span>
                                    </td>
                                    <td><?= esc($log['ip_adress']) ?></td>
                                    <td><?= esc(date('d M Y H:i:s', strtotime($log['created_at']))) ?></td>
                                    <td>
                                        <?php if ($log['description']): ?>
                                            <button class="btn-detail" type="button" data-bs-toggle="modal" data-bs-target="#detailModal" data-description="<?= esc($log['description']) ?>">
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
                
                <?php if (!empty($logs)): ?>
                <div class="table-footer">
                    <small>Menampilkan <?= count($logs) ?> log aktivitas</small>
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
    $(document).ready(function () {
        // Initialize DataTables with full numbers pagination
        var table = $('#logTable').DataTable({
            "pageLength": 20,
            "pagingType": "full_numbers",
            "order": [[3, 'desc']], // Sort by Tanggal Waktu (desc)
            "info": false, // Hide info
            "lengthChange": false, // No length menu
            "searching": false, // No search box
            "language": {
                "paginate": {
                    "first": "«",
                    "last": "»",
                    "next": "›",
                    "previous": "‹"
                },
                "emptyTable": "Tidak ada data yang tersedia dalam tabel"
            },
            "dom": 't<"dataTables_paginate"p>' // Only table and pagination
        });

        // Handle date range consistently
        var dateRangeValue = "<?= esc($date_range ?? '') ?>";
        var defaultDates = [];
        if (dateRangeValue) {
            // Parse the date range string assuming format "YYYY-MM-DD s/d YYYY-MM-DD"
            var parts = dateRangeValue.split(' s/d ');
            if (parts.length === 2) {
                defaultDates = [parts[0], parts[1]];
            }
        }

        // Initialize flatpickr for date range
        var fp = flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: defaultDates,
            locale: {
                rangeSeparator: " s/d "
            },
            onReady: function(selectedDates, dateStr, instance) {
                // Ensure the input looks like a proper date input
                instance.calendarContainer.style.position = "absolute";
                instance._input.style.borderRadius = "8px";
            },
            onClose: function(selectedDates, dateStr, instance) {
                // Ensure value is set correctly
                $('#dateRange').val(dateStr);
            }
        });

        // Modal detail - ensure description is string
        $('#detailModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var description = button.attr('data-description'); // Use attr instead of data() to get raw string
            if (typeof description === 'string') {
                $('#modalDescription').text(description);
            } else {
                $('#modalDescription').text('Detail tidak tersedia');
            }
        });

        // Export CSV
        $('#exportCsv').click(function () {
            var search = $('#search').val();
            var dateRange = $('#dateRange').val();
            window.location.href = "<?= base_url('admin/log_activities/export') ?>?search=" + encodeURIComponent(search) + "&date_range=" + encodeURIComponent(dateRange);
        });

        // Form submission on Enter in search input
        $('#search').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#filterForm').submit();
            }
        });

        // Same for date input
        $('#dateRange').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#filterForm').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>
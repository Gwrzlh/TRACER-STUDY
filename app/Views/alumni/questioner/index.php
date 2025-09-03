<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/kuesioner/index.css') ?>">
<style>
/* Consistent styling with dashboard theme */
.container-fluid {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 2rem;
}

.dashboard-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: none;
    margin-bottom: 1.5rem;
}

.stats-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    flex: 1;
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: none;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.stat-icon.blue { background: #e3f2fd; color: #1976d2; }
.stat-icon.green { background: #e8f5e8; color: #2e7d32; }
.stat-icon.yellow { background: #fff8e1; color: #f57f17; }

.stat-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3436;
    margin: 0;
}

.stat-content p {
    font-size: 0.875rem;
    color: #636e72;
    margin: 0;
}

/* Table Header with Simple Background */
.table-header {
    background: #f8f9fa;
    color: #2d3436;
    padding: 1.5rem 2rem;
    border-radius: 15px 15px 0 0;
    border-bottom: 1px solid #e9ecef;
}

.table-title {
    font-size: 1.4rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.title-icon {
    width: 32px;
    height: 32px;
    background: #28a745;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

/* Modern Table Styling */
.table-container {
    overflow-x: auto;
    background: white;
}

.table-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    font-size: 0.9rem;
    border: 1px solid #e9ecef;
}

.table-head-row {
    background: #f8f9fa;
    color: #636e72;
}

.table-head-row th {
    font-weight: 600;
    font-size: 0.85rem;
    text-align: center;
    padding: 1rem 1.5rem;
    border: 1px solid #e9ecef;
    white-space: nowrap;
    letter-spacing: 0.5px;
}

.table-head-row th:first-child {
    border-left: none;
}

.table-head-row th:last-child {
    border-right: none;
}

.table-body-row {
    background: white;
    transition: background-color 0.2s ease;
}

.table-body-row:hover {
    background-color: #f8f9fa;
}

.table-body-row td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border: 1px solid #e9ecef;
    border-top: none;
}

.table-body-row:last-child td {
    border-bottom: 1px solid #e9ecef;
}

.table-body-row td:first-child {
    border-left: none;
}

.table-body-row td:last-child {
    border-right: none;
}

.table-cell-number {
    font-weight: 600;
    color: #2d3436;
    text-align: center;
    width: 8%;
}

.table-cell-title {
    font-weight: 500;
    color: #2d3436;
    width: 50%;
}

.table-cell-status {
    text-align: center;
    width: 20%;
}

.table-cell-action {
    text-align: center;
    width: 22%;
}

.kuesioner-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3436;
    margin: 0 0 0.25rem 0;
}

.kuesioner-desc {
    font-size: 0.85rem;
    color: #636e72;
    margin: 0;
    line-height: 1.4;
}

/* Status Badges - Soft Colors */
.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.3px;
    display: inline-block;
    min-width: 100px;
    text-align: center;
}

.status-badge.belum {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f1aeb5;
}

.status-badge.ongoing {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-badge.finish {
    background-color: #d1edff;
    color: #0c63e4;
    border: 1px solid #b8daff;
}

/* Action Buttons - Soft Colors */
.btn-action {
    padding: 0.5rem 1.2rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: capitalize;
    display: inline-block;
    min-width: 100px;
    text-align: center;
}

.btn-action.primary {
    background-color: #e3f2fd;
    color: #1976d2;
    border-color: #bbdefb;
}

.btn-action.warning {
    background-color: #fff3e0;
    color: #f57c00;
    border-color: #ffcc02;
}

.btn-action.success {
    background-color: #e8f5e8;
    color: #2e7d32;
    border-color: #c8e6c9;
}

.btn-action:hover {
    opacity: 0.8;
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-action.primary:hover {
    color: #1976d2;
}

.btn-action.warning:hover {
    color: #f57c00;
}

.btn-action.success:hover {
    color: #2e7d32;
}

/* Table Footer */
.table-footer {
    background-color: #f8f9fa;
    border: none;
    padding: 1rem 2rem;
    border-radius: 0 0 15px 15px;
    border-top: 1px solid #e9ecef;
}

.footer-text {
    font-size: 0.8rem;
    color: #636e72;
    margin: 0;
}

.text-center {
    text-align: center;
}

@media (max-width: 768px) {
    .stats-row {
        flex-direction: column;
    }
    
    .container-fluid {
        padding: 1rem;
    }
    
    .table-header {
        padding: 1rem 1.5rem;
    }
    
    .table-title {
        font-size: 1.2rem;
    }
    
    .table-head-row th {
        padding: 0.75rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .table-body-row td {
        padding: 1rem 0.5rem;
    }
    
    .kuesioner-title {
        font-size: 0.9rem;
    }
    
    .kuesioner-desc {
        font-size: 0.75rem;
    }
    
    .status-badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
        min-width: 80px;
    }
    
    .btn-action {
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
        min-width: 80px;
    }
    
    .table-footer {
        padding: 1rem;
    }
}
</style>

<div class="container-fluid">
    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon blue">
                📋
            </div>
            <div class="stat-content">
                <h3>5</h3>
                <p>Total Kuesioner</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon green">
                ✅
            </div>
            <div class="stat-content">
                <h3>2</h3>
                <p>Selesai</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon yellow">
                🕐
            </div>
            <div class="stat-content">
                <h3>1</h3>
                <p>Sedang Berjalan</p>
            </div>
        </div>
    </div>

    <!-- Kuesioner Table -->
    <div class="dashboard-card">
        <!-- Header with simple background -->
        <div class="table-header">
            <h4 class="table-title">
                <div class="title-icon">📝</div>
                Daftar Questioner Alumni
            </h4>
        </div>
        
        <div class="table-container">
            <table class="table-modern">
                <thead>
                    <tr class="table-head-row">
                        <th>NO</th>
                        <th>KUESIONER</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $data = [
                        [
                            "id" => 1,
                            "judul" => "Tracer Study Tahun 2025", 
                            "status" => "Belum Mengisi"
                        ],
                        [
                            "id" => 2,
                            "judul" => "Survey Kepuasan Alumni 2024", 
                            "status" => "On Going"
                        ],
                        [
                            "id" => 3,
                            "judul" => "Tracer Study Tahun 2024", 
                            "status" => "Finish"
                        ],
                        [
                            "id" => 4,
                            "judul" => "Survey Alumni Prodi Teknik", 
                            "status" => "Belum Mengisi"
                        ],
                        [
                            "id" => 5,
                            "judul" => "Evaluasi Kurikulum Alumni", 
                            "status" => "Finish"
                        ]
                    ];

                    $no = 1;
                    foreach ($data as $row): ?>
                        <tr class="table-body-row">
                            <td class="table-cell-number"><?= $no++ ?></td>
                            <td class="table-cell-title">
                                <div class="kuesioner-title"><?= $row["judul"] ?></div>
                                <div class="kuesioner-desc">Isi kuesioner tracer study untuk membantu evaluasi dan pengembangan program studi alumni.</div>
                            </td>
                            <td class="table-cell-status">
                                <?php if ($row["status"] == "Belum Mengisi"): ?>
                                    <span class="status-badge belum">Belum Mengisi</span>
                                <?php elseif ($row["status"] == "On Going"): ?>
                                    <span class="status-badge ongoing">On Going</span>
                                <?php else: ?>
                                    <span class="status-badge finish">Finish</span>
                                <?php endif; ?>
                            </td>
                            <td class="table-cell-action">
                                <?php if ($row["status"] == "Belum Mengisi"): ?>
                                    <a href="<?= base_url('alumni/questioner/mulai/' . $row['id']) ?>" class="btn-action primary">
                                        Isi
                                    </a>
                                <?php elseif ($row["status"] == "On Going"): ?>
                                    <a href="<?= base_url('alumni/questioner/lanjutkan/' . $row['id']) ?>" class="btn-action warning">
                                        Lanjutkan
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('alumni/questioner/lihat/' . $row['id']) ?>" class="btn-action success">
                                        Lihat Jawaban
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="table-footer">
            <p class="footer-text">Menampilkan <?= count($data) ?> questioner</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->extend('layout/sidebar_alumni2') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Daftar Questioner Alumni</h3>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 8%;">NO</th>
                                    <th style="width: 50%;">KUESIONER</th>
                                    <th style="width: 20%;">STATUS</th>
                                    <th style="width: 22%;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Data dummy dengan status yang sesuai foto
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
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row["judul"] ?></td>
                                        <td class="text-center">
                                            <?php if ($row["status"] == "Belum Mengisi"): ?>
                                                <span class="badge bg-secondary">Belum Mengisi</span>
                                            <?php elseif ($row["status"] == "On Going"): ?>
                                                <span class="badge bg-warning">On Going</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Finish</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row["status"] == "Belum Mengisi"): ?>
                                                <a href="<?= base_url('alumni/questioner/mulai/' . $row['id']) ?>" class="btn btn-primary btn-sm">
                                                    Isi
                                                </a>
                                            <?php elseif ($row["status"] == "On Going"): ?>
                                                <a href="<?= base_url('alumni/questioner/lanjutkan/' . $row['id']) ?>" class="btn btn-warning btn-sm">
                                                    Lanjutkan
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('alumni/questioner/lihat/' . $row['id']) ?>" class="btn btn-success btn-sm">
                                                    Lihat Jawaban
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted">
                    <small>Menampilkan <?= count($data) ?> questioner</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card styling */
    .card {
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        background-color: #ffffff;
    }

    .card-footer {
        border-top: 1px solid #ddd;
        padding: 12px 20px;
        background-color: #f8f9fa;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    /* Table styling */
    .table {
        background-color: #ffffff;
        border: 1px solid #ddd;
        table-layout: fixed;
        width: 100%;
    }

    .table th {
        background-color: #f1f3f4;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #333;
        border-bottom: 2px solid #ddd;
        padding: 12px 15px;
        border-right: 1px solid #ddd;
        text-align: center;
    }

    .table th:last-child {
        border-right: none;
    }

    .table td {
        vertical-align: middle;
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        border-right: 1px solid #ddd;
        background-color: #ffffff;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .table td:last-child {
        border-right: none;
    }

    .table-striped>tbody>tr:nth-of-type(odd)>td {
        background-color: #f8f9fa;
    }

    .table-striped>tbody>tr:hover>td {
        background-color: #e8f4fd;
    }

    /* ===================================
   IMPROVED BADGE & BUTTON COLORS
   =================================== */

    /* Badge Base Styling */
    .badge {
        font-size: 0.75rem;
        padding: 6px 12px;
        font-weight: 600;
        border-radius: 20px;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    /* Status Badge Colors - Improved Psychology Colors */
    .badge.bg-secondary {
        background-color: #dc3545 !important;
        /* Red - Urgent/Not Started */
        color: white;
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
    }

    .badge.bg-warning {
        background-color: #fd7e14 !important;
        /* Orange - In Progress */
        color: white;
        box-shadow: 0 2px 4px rgba(253, 126, 20, 0.2);
    }

    .badge.bg-success {
        background-color: #198754 !important;
        /* Green - Completed */
        color: white;
        box-shadow: 0 2px 4px rgba(25, 135, 84, 0.2);
    }

    /* Button Base Styling */
    .btn-sm {
        padding: 8px 20px;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        min-width: 100px;
        text-align: center;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    /* Action Button Colors - Matching Status Psychology */
    .btn-primary {
        background-color: #0d6efd;
        /* Professional Blue - Start Action */
        border-color: #0d6efd;
        color: white;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }

    .btn-warning {
        background-color: #fd7e14;
        /* Orange - Continue Action (matches On Going status) */
        border-color: #fd7e14;
        color: white;
        box-shadow: 0 2px 4px rgba(253, 126, 20, 0.2);
    }

    .btn-warning:hover {
        background-color: #e8690b;
        border-color: #d56101;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(253, 126, 20, 0.3);
    }

    .btn-success {
        background-color: #198754;
        /* Green - View Action (matches Finish status) */
        border-color: #198754;
        color: white;
        box-shadow: 0 2px 4px rgba(25, 135, 84, 0.2);
    }

    .btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(25, 135, 84, 0.3);
    }

    /* Add subtle animation for better UX */
    .badge,
    .btn-sm {
        transition: all 0.2s ease-in-out;
    }

    .badge:hover {
        transform: scale(1.05);
    }

    /* Title styling */
    h3 {
        color: #333;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    /* Container adjustments */
    .table-responsive {
        border-radius: 8px 8px 0 0;
        overflow: hidden;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-sm {
            min-width: 80px;
            padding: 6px 16px;
            font-size: 0.8rem;
        }

        .badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }
    }
</style>

<?= $this->endSection() ?>
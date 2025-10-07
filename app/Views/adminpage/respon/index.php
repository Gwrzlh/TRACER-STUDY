<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/respon/index.css') ?>" rel="stylesheet">
<!-- Navbar Tab -->
<div class="respon-navbar mb-3">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link <?= (uri_string() == 'admin/respon' || uri_string() == 'admin/respon/') ? 'active' : '' ?>"
                href="<?= base_url('admin/respon') ?>">
                📋 Respon
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= (uri_string() == 'admin/respon/ami') ? 'active' : '' ?>"
                href="<?= base_url('admin/respon/ami') ?>">
                🧾 AMI
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= (uri_string() == 'admin/respon/akreditasi') ? 'active' : '' ?>"
                href="<?= base_url('admin/respon/akreditasi') ?>">
                📊 Akreditasi
            </a>
        </li>
    </ul>
</div>
<div class="respon-container">
    <div class="respon-header">
        <h2 class="respon-title">Data Respon Alumni</h2>
    </div>



    <!-- Filter Form -->
    <div class="filter-card">
        <form method="get" action="<?= base_url('admin/respon') ?>" class="filter-form">
            <div class="filter-grid">
                <!-- Row 1 -->
                <div class="filter-group">
                    <input type="text" name="nim" class="filter-input" placeholder="NIM" value="<?= esc($selectedNim) ?>">
                </div>
                <div class="filter-group">
                    <input type="text" name="nama" class="filter-input" placeholder="Nama Alumni" value="<?= esc($selectedNama) ?>">
                </div>
                <div class="filter-group">
                    <select name="jurusan" class="filter-select">
                        <option value="">-- Semua Jurusan --</option>
                        <?php foreach ($allJurusan as $j): ?>
                            <option value="<?= $j['id'] ?>" <?= $selectedJurusan == $j['id'] ? 'selected' : '' ?>><?= esc($j['nama_jurusan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <select name="prodi" class="filter-select">
                        <option value="">-- Semua Prodi --</option>
                        <?php foreach ($allProdi as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $selectedProdi == $p['id'] ? 'selected' : '' ?>>
                                <?= esc($p['nama_prodi']) ?> (<?= esc($p['nama_jurusan'] ?? '-') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Row 2 -->
                <div class="filter-group">
                    <select name="angkatan" class="filter-select">
                        <option value="">-- Semua Angkatan --</option>
                        <?php foreach ($allAngkatan as $a): ?>
                            <option value="<?= $a['angkatan'] ?>" <?= $selectedAngkatan == $a['angkatan'] ? 'selected' : '' ?>><?= esc($a['angkatan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <select name="year" class="filter-select">
                        <option value="">-- Semua Tahun Kelulusan --</option>
                        <?php foreach ($allYears as $y): ?>
                            <option value="<?= $y['tahun_kelulusan'] ?>" <?= $selectedYear == $y['tahun_kelulusan'] ? 'selected' : '' ?>><?= esc($y['tahun_kelulusan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <select name="status" class="filter-select">
                        <option value="">-- Semua Status --</option>
                        <option value="completed" <?= $selectedStatus == 'completed' ? 'selected' : '' ?>>Sudah</option>
                        <option value="ongoing" <?= $selectedStatus == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                        <option value="Belum" <?= $selectedStatus == 'Belum' ? 'selected' : '' ?>>Belum Mengisi</option>
                    </select>
                </div>

                <!-- Row 3 -->
                <div class="filter-group">
                    <select name="sort_by" class="filter-select">
                        <option value="">-- Urutkan Berdasarkan --</option>
                        <option value="nim" <?= ($filters['sort_by'] ?? '') == 'nim' ? 'selected' : '' ?>>NIM</option>
                        <option value="nama_lengkap" <?= ($filters['sort_by'] ?? '') == 'nama_lengkap' ? 'selected' : '' ?>>Nama</option>
                        <option value="angkatan" <?= ($filters['sort_by'] ?? '') == 'angkatan' ? 'selected' : '' ?>>Angkatan</option>
                        <option value="tahun_kelulusan" <?= ($filters['sort_by'] ?? '') == 'tahun_kelulusan' ? 'selected' : '' ?>>Tahun Kelulusan</option>
                        <option value="status" <?= ($filters['sort_by'] ?? '') == 'status' ? 'selected' : '' ?>>Status</option>
                    </select>
                </div>
                <div class="filter-group">
                    <select name="sort_order" class="filter-select">
                        <option value="asc" <?= ($filters['sort_order'] ?? '') == 'asc' ? 'selected' : '' ?>>Ascending (A–Z / 0–9)</option>
                        <option value="desc" <?= ($filters['sort_order'] ?? '') == 'desc' ? 'selected' : '' ?>>Descending (Z–A / 9–0)</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-primary">Search</button>
                <a href="<?= base_url('admin/respon') ?>" class="btn-secondary">Clear</a>
                <a href="<?= base_url('admin/respon/grafik') ?>" class="btn-info">Grafik</a>
                <a href="<?= base_url('admin/respon/export?' . http_build_query($_GET)) ?>" class="btn-success">Export Excel</a>
            </div>
        </form>
    </div>

    <!-- Summary Counter -->
    <div class="summary-counter">
        <div class="counter-item counter-success">
            <span class="counter-label">Sudah</span>
            <span class="counter-value"><?= $totalCompleted ?? 0 ?></span>
        </div>
        <div class="counter-item counter-primary">
            <span class="counter-label">Ongoing</span>
            <span class="counter-value"><?= $totalOngoing ?? 0 ?></span>
        </div>
        <div class="counter-item counter-danger">
            <span class="counter-label">Belum Mengisi</span>
            <span class="counter-value"><?= $totalBelum ?? 0 ?></span>
        </div>
    </div>

    <!-- Table -->
    <div class="respon-table-card">
        <div class="table-container">
            <table class="respon-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Alumni</th>
                        <th>Jurusan</th>
                        <th>Prodi</th>
                        <th>Angkatan</th>
                        <th>Tahun Lulusan</th>
                        <th>Judul Kuesioner</th>
                        <th>Status</th>
                        <th>Tanggal Submit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($responses)): ?>
                        <?php $no = ($currentPage - 1) * $perPage + 1; ?>
                        <?php foreach ($responses as $res): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="nim-cell"><?= esc($res['nim'] ?? '-') ?></td>
                                <td class="nama-cell"><?= esc($res['nama_lengkap'] ?? '-') ?></td>
                                <td><?= esc($res['nama_jurusan'] ?? '-') ?></td>
                                <td><?= esc($res['nama_prodi'] ?? '-') ?></td>
                                <td class="text-center"><?= esc($res['angkatan'] ?? '-') ?></td>
                                <td class="text-center"><?= esc($res['tahun_kelulusan'] ?? '-') ?></td>
                                <td class="kuesioner-cell"><?= esc($res['judul_kuesioner'] ?? '-') ?></td>
                                <td class="status-cell">
                                    <?php if (($res['status'] ?? '') === 'completed'): ?>
                                        <span class="status-badge status-success">Sudah</span>
                                    <?php elseif (($res['status'] ?? '') === 'draft'): ?>
                                        <span class="status-badge status-primary">Ongoing</span>
                                    <?php else: ?>
                                        <span class="status-badge status-danger">Belum Mengisi</span>
                                    <?php endif; ?>
                                </td>

                                <td class="date-cell"><?= esc($res['submitted_at'] ?? '-') ?></td>
                                <td class="action-cell">
                                    <?php if (!empty($res['response_id']) && ($res['status'] ?? '') === 'completed'): ?>
                                        <!-- Debug: Output $res to check available keys -->
                                        <?php // Remove this after debugging 
                                        ?>
                                        <?php // echo '<pre>'; var_dump($res); echo '</pre>'; 
                                        ?>
                                        <a href="<?= base_url('admin/respon/allow_edit/' . $res['questionnaire_id'] . '/' . $res['id_account']) ?>"
                                            class="action-btn"
                                            onclick="return confirm('Izinkan alumni mengedit jawaban ini?')">Edit Jawaban</a>
                                        <a href="<?= base_url('admin/respon/detail/' . $res['response_id']) ?>"
                                            class="action-btn">Jawaban</a>
                                    <?php else: ?>
                                        <span class="no-action">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="no-data">Tidak ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <<?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('info')): ?>
    <div class="alert alert-info">
        <?= session()->getFlashdata('info') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('warning')): ?>
    <div class="alert alert-warning">
        <?= session()->getFlashdata('warning') ?>
    </div>
<?php endif; ?>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <?php
    $queryParams = $_GET;
    unset($queryParams['page']); // hapus page lama
    ?>
    <div class="d-flex justify-content-end mt-3">
        <ul class="pagination">
            <!-- Previous -->
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $currentPage > 1 ? base_url('admin/respon?page=' . ($currentPage - 1) . '&' . http_build_query($queryParams)) : '#' ?>">Previous</a>
            </li>

            <!-- Page numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= base_url('admin/respon?page=' . $i . '&' . http_build_query($queryParams)) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <!-- Next -->
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $currentPage < $totalPages ? base_url('admin/respon?page=' . ($currentPage + 1) . '&' . http_build_query($queryParams)) : '#' ?>">Next</a>
            </li>
        </ul>
    </div>
<?php endif; ?>

</div>

<?= $this->endSection() ?>
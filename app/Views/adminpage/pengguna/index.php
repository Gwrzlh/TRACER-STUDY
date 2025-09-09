<?php $this->extend('layout/sidebar'); ?>
<?php $this->section('content'); ?>

<!-- External CSS and JS -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="<?= base_url('css/pengguna.css') ?>" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('js/pengguna.js') ?>"></script>

<!-- Main Content -->
<div class="pengguna-page">
    <div class="page-wrapper">
        <div class="page-container">
            <h2 class="page-title">Daftar Pengguna</h2>

            <!-- Alert pesan sukses atau error -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errorLogs')): ?>
                <div class="alert alert-danger">
                    <strong>Data Gagal Import:</strong>
                    <ul>
                        <?php foreach(session()->getFlashdata('errorLogs') as $log): ?>
                            <li><i class="fas fa-times-circle text-danger"></i> <?= esc($log) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Button Container -->
            <div class="button-container">
                <a href="<?= base_url('admin/pengguna/tambahPengguna') ?>"
                   style="background-color: <?= get_setting('pengguna_button_color', '#007bff') ?>;
                          color: <?= get_setting('pengguna_button_text_color', '#ffffff') ?>;"
                   onmouseover="this.style.backgroundColor='<?= get_setting('pengguna_button_hover_color', '#0056b3') ?>'"
                   onmouseout="this.style.backgroundColor='<?= get_setting('pengguna_button_color', '#007bff') ?>'"
                   class="px-4 py-2 rounded-md shadow-sm fw-bold">
                     <?= get_setting('pengguna_button_text', 'Tambah Pengguna') ?>
                </a>

                <a href="<?= base_url('admin/pengguna/import') ?>" class="btn btn-success">
                    <i class="fas fa-file-import"></i> Import Akun
                </a>
            </div>
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errorLogs')): ?>
                <div class="alert alert-danger">
                    <strong>Data Gagal Import:</strong>
                    <ul>
                        <?php foreach(session()->getFlashdata('errorLogs') as $log): ?>
                            <li><i class="fas fa-times-circle text-danger"></i> <?= esc($log) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- Search Form -->
            <div class="controls-container">
                <form method="get" action="<?= base_url('admin/pengguna') ?>">
                    <?php if (isset($roleId) && $roleId): ?>
                        <input type="hidden" name="role" value="<?= esc($roleId) ?>">
                    <?php endif; ?>
                    <div class="search-wrapper">
                        <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" 
                               placeholder="Cari nama pengguna..." class="search-input">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Main Table -->
            <?php if (isset($accounts) && !empty($accounts)): ?>
                <div class="table-container">
                    <div style="overflow-x: auto;">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengguna</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Group</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1 + (($perPage ?? 10) * (($currentPage ?? 1) - 1));
                                foreach ($accounts as $acc): ?>
                                    <tr>
                                        <td data-label="No"><?= $no++ ?></td>
                                        <td data-label="Nama Pengguna" title="<?= esc($acc['username']) ?>">
                                            <?= esc($acc['username']) ?>
                                        </td>
                                        <td data-label="Email" title="<?= esc($acc['email']) ?>">
                                            <?= esc($acc['email']) ?>
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge-status <?= (strtolower($acc['status']) == 'active' || strtolower($acc['status']) == 'aktif' || $acc['status'] == '1') ? 'badge-active' : 'badge-inactive' ?>">
                                                <?= (strtolower($acc['status']) == 'active' || strtolower($acc['status']) == 'aktif' || $acc['status'] == '1') ? 'AKTIF' : 'TIDAK AKTIF' ?>
                                            </span>
                                        </td>
                                        <td data-label="Group">
                                            <span class="badge-role">
                                                <?= esc($acc['nama_role'] ?? 'Tidak ada role') ?>
                                            </span>
                                        </td>
                                        <td data-label="Aksi" style="text-align: center;">
                                            <a href="<?= base_url('/admin/pengguna/editPengguna/' . $acc['id']) ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="<?= base_url('/admin/pengguna/delete/' . $acc['id']) ?>" method="post" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn-delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users" style="font-size: 48px; color: #cbd5e1; margin-bottom: 20px;"></i>
                    <p>Belum ada data pengguna, silakan tambah pengguna baru.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

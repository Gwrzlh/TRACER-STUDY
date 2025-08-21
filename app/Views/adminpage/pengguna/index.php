<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/pengguna/index.css') ?>">

<div class="page-wrapper">
    <h2 class="page-title">Daftar Pengguna</h2>

    <!-- Tombol Tambah Pengguna -->
    <a href="<?= base_url('/admin/pengguna/tambahPengguna') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Pengguna
    </a>

    <!-- Tombol Import -->
    <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fas fa-file-import"></i> Import Akun
    </button>

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Akun dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('admin/pengguna/import') ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File (xls, xlsx, csv)</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_role" class="form-label">Pilih Role</label>
                            <select name="id_role" id="id_role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="1">Alumni</option>
                                <option value="2">Admin</option>
                                <option value="6">Kaprodi</option>
                                <option value="7">Perusahaan</option>
                                <option value="8">Atasan</option>
                                <option value="9">Jabatan lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Flashdata -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success mt-3"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mt-3"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Table -->
    <div class="table-container">
        <div class="tab-content" id="userTabContent">
            <!-- Search -->
            <form method="get" action="<?= base_url('admin/pengguna') ?>" style="margin-bottom:15px;">
                <?php if ($roleId): ?>
                    <input type="hidden" name="role" value="<?= esc($roleId) ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>
  
    <div class="search-wrapper">
        <input type="text" 
               name="keyword" 
               value="<?= esc($keyword ?? '') ?>" 
               placeholder="Cari nama..." 
               class="search-input">
        
        <button type="submit" class="search-btn">Search</button>
    </div>
</form>

<form method="get" action="<?= base_url('admin/pengguna') ?>" class="mb-3">
    <?php if ($roleId): ?>
        <input type="hidden" name="role" value="<?= esc($roleId) ?>">
    <?php endif; ?>
    <?php if ($keyword): ?>
        <input type="hidden" name="keyword" value="<?= esc($keyword) ?>">
    <?php endif; ?>
    
    <label for="perpage">Tampilkan per halaman:</label>
    <input type="number" 
           name="perpage" 
           id="perpage" 
           min="1" 
           value="<?= esc($perPage) ?>" 
           style="width: 80px;"
           onchange="this.form.submit()">
</form>

<!-- Main Table -->
<?php if (isset($accounts) && !empty($accounts)): ?>
    <div style="overflow-x:auto;">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Group</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1 + ($perPage * ($currentPage - 1)); foreach ($accounts as $acc): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($acc['username']) ?></td>
                        <td><?= esc($acc['email']) ?></td>
                        <td>
                            <?php 
                            $isActive = (strtolower($acc['status']) == 'active' || strtolower($acc['status']) == 'aktif' || $acc['status'] == '1');
                            ?>
                            <span class="badge-status <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
                                <?= $isActive ? 'AKTIF' : 'TIDAK AKTIF' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-role">
                                <?= esc($acc['nama_role'] ?? 'Tidak ada role') ?>
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <a href="<?= base_url('/admin/pengguna/editPengguna/'. $acc['id'] ) ?>" class="btn-edit">
                                Edit
                            </a>
                            <form action="<?= base_url('/admin/pengguna/delete/' . $acc['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div style="margin-top:20px; padding:20px;">
            <?php if (isset($pager)): ?>
                <?= $pager->links('accounts', 'paginations') ?>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div style="padding:40px; text-align:center; color:#6c757d;">
        <p style="font-size:16px; margin:0;">Tidak ada data pengguna.</p>
        <?php if (isset($accounts)): ?>
            <p style="font-size:14px; color:#999;">Variable accounts ditemukan tapi kosong.</p>
        <?php else: ?>
            <p style="font-size:14px; color:#999;">Variable accounts tidak ditemukan.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->endSection(); ?>

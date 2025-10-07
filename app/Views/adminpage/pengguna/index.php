<!-- DESAIN DAFTAR PENGGUNA -->
<?php $this->extend('layout/sidebar'); ?>
<?php $this->section('content'); ?>

<!-- ====== External CSS ====== -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="<?= base_url('css/pengguna/index.css') ?>" rel="stylesheet">

<!-- ====== External JS ====== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('js/pengguna.js') ?>"></script>

<!-- ====== MAIN CONTENT ====== -->
<div class="pengguna-page">
    <div class="page-wrapper">
        <div class="page-container">
            
            <h2 class="page-title mb-4">Daftar Pengguna</h2>

            <!-- 🔔 ALERT MESSAGES -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errorLogs')): ?>
                <div class="alert alert-danger shadow-sm">
                    <strong><i class="fas fa-times-circle me-2"></i> Data Gagal Import:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach (session()->getFlashdata('errorLogs') as $log): ?>
                            <li><i class="fas fa-times text-danger me-1"></i> <?= esc($log) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- 🔔 END ALERT -->

            <!-- ====== TOP CONTROLS ====== -->
            <div class="top-controls d-flex justify-content-between align-items-center mb-3">
                
                <!-- 🔍 SEARCH & FILTER -->
                <div class="controls-container">
                    <form method="get" action="<?= base_url('admin/pengguna') ?>" class="d-flex align-items-center gap-2 mb-3">
                        <select name="role" id="roleSelect" class="form-select" style="width: 200px;">
                            <option value="">-- Semua Role --</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= esc($r['id']) ?>" <?= isset($roleId) && $roleId == $r['id'] ? 'selected' : '' ?>>
                                    <?= esc($r['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <input type="text" name="keyword" id="keywordInput"
                            value="<?= esc($keyword ?? '') ?>"
                            placeholder="Cari pengguna..."
                            class="form-control"
                            style="width: 250px;">

                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>

                <!-- 🎛️ BUTTONS (TAMBAH & IMPORT) -->
                <div class="button-container">
                    <a href="<?= base_url('admin/pengguna/tambahPengguna') ?>"
                       class="btn btn-primary btn-add"
                       style="
                           background-color: <?= get_setting('pengguna_button_color', '#3b82f6') ?>;
                           color: <?= get_setting('pengguna_button_text_color', '#ffffff') ?>;
                       "
                       onmouseover="this.style.backgroundColor='<?= get_setting('pengguna_button_hover_color', '#2563eb') ?>'"
                       onmouseout="this.style.backgroundColor='<?= get_setting('pengguna_button_color', '#3b82f6') ?>'">
                        <i class="fas fa-user-plus"></i>
                        <?= get_setting('pengguna_button_text', 'Tambah Pengguna') ?>
                    </a>

                    <a href="<?= base_url('admin/pengguna/import') ?>"
                       class="btn btn-success btn-import"
                       style="
                           background-color: <?= get_setting('import_button_color', '#22c55e') ?>;
                           color: <?= get_setting('import_button_text_color', '#ffffff') ?>;
                       "
                       onmouseover="this.style.backgroundColor='<?= get_setting('import_button_hover_color', '#16a34a') ?>'"
                       onmouseout="this.style.backgroundColor='<?= get_setting('import_button_color', '#22c55e') ?>'">
                        <i class="fas fa-file-import"></i>
                        <?= get_setting('import_button_text', 'Import Akun') ?>
                    </a>
                </div>
            </div>

      <form id="bulkDeleteForm"
      action="<?= base_url('admin/pengguna/deleteMultiple') ?>"
      method="post"
      onsubmit="return confirm('Yakin ingin menghapus akun yang dipilih?')">

                <?= csrf_field() ?>

                <div class="mb-3">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt"></i> Hapus Terpilih
                    </button>
                </div>

                <!-- ====== TABLE ====== -->
                <?php if (!empty($accounts) && count($accounts) > 0): ?>
                    <div class="table-container">
                        <div class="table-wrapper">
                            <table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>Pengguna</th>
            <th>Status</th>
            <th>Email</th>
            <th>Group</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($accounts as $acc): ?>
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?= esc($acc['id']) ?>" class="row-checkbox"></td>
                <td>
                    <strong><?= esc($acc['username']) ?></strong><br>
                    <small><?= esc($acc['email']) ?></small>
                </td>
                <td>
                    <span class="badge <?= (strtolower($acc['status']) == 'active' || $acc['status'] == '1') ? 'bg-success' : 'bg-secondary' ?>">
                        <?= (strtolower($acc['status']) == 'active' || $acc['status'] == '1') ? 'Active' : 'Inactive' ?>
                    </span>
                </td>
                <td><?= esc($acc['email']) ?></td>
                <td><?= esc($acc['nama_role'] ?? 'No Role') ?></td>
                <td>
                    <a href="<?= base_url('pengguna/editPengguna/' . $acc['id']) ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="<?= base_url('pengguna/delete/' . $acc['id']) ?>" 
                          method="post" style="display:inline;" 
                          onsubmit="return confirm('Hapus pengguna ini?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
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
                    <!-- ====== EMPTY STATE ====== -->
                    <div class="empty-state text-center">
                        <i class="fas fa-users mb-3" style="font-size:48px;color:#cbd5e1;"></i>
                        <h3>Belum ada data pengguna</h3>
                        <p>Silakan tambah pengguna baru untuk memulai.</p>
                        <a href="<?= base_url('admin/pengguna/tambahPengguna') ?>" class="btn btn-primary mt-2">
                            <i class="fas fa-user-plus"></i> Tambah Pengguna
                        </a>
                    </div>
                <?php endif; ?>
            </form>

        </div>
    </div>
</div>

<?php $this->endSection(); ?>


<?php $this->extend('layout/sidebar'); ?>
<?php $this->section('content'); ?>

<!-- ====== External CSS ====== -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="<?= base_url('css/pengguna.css') ?>" rel="stylesheet">

<!-- ====== SweetAlert2 ====== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ====== MAIN CONTENT ====== -->
<div class="pengguna-page">
    <div class="page-wrapper">
        <div class="page-container">
            <h2 class="page-title mb-4">Daftar Pengguna</h2>

            <!-- ====== TOP CONTROLS ====== -->
            <div class="controls-section d-flex flex-column gap-3 mb-4">
                <!-- 🔍 SEARCH & FILTER -->
                <div class="controls-container">
                    <form method="get" action="<?= base_url('admin/pengguna') ?>" class="d-flex align-items-center gap-2 flex-wrap">

                        <!-- Role -->
                        <select name="role" id="roleSelect" class="form-select">
                            <option value=""> Semua Role </option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= esc($r['id']) ?>" <?= $roleId == $r['id'] ? 'selected' : '' ?>>
                                    <?= esc($r['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Tahun Masuk -->
                        <select name="angkatan" id="angkatanSelect" class="form-select">
                            <option value=""> Tahun Masuk </option>
                            <?php foreach ($angkatanList as $a): ?>
                                <?php if (!empty($a['angkatan'])): ?>
                                    <option value="<?= esc($a['angkatan']) ?>" <?= $angkatan == $a['angkatan'] ? 'selected' : '' ?>>
                                        <?= esc($a['angkatan']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>

                        <!-- Tahun Lulus -->
                        <select name="tahun_lulus" id="tahunLulusSelect" class="form-select">
                            <option value=""> Tahun Lulus</option>
                            <?php foreach ($tahunLulusList as $t): ?>
                                <?php if (!empty($t['tahun_kelulusan'])): ?>
                                    <option value="<?= esc($t['tahun_kelulusan']) ?>" <?= $tahunLulus == $t['tahun_kelulusan'] ? 'selected' : '' ?>>
                                        <?= esc($t['tahun_kelulusan']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>

                        <!-- Keyword -->
                        <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari pengguna..." class="form-control search-input">

                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                    </form>
                </div>

                <!-- 🎛️ BUTTONS -->
                <div class="button-container d-flex gap-2 flex-wrap">
                    <a href="<?= base_url('admin/pengguna/errorLogs') ?>" class="btn btn-outline-danger">
                        <i class="fas fa-bug"></i> Riwayat Error
                    </a>

                    <a href="<?= base_url('admin/pengguna/tambahPengguna') ?>" class="btn btn-primary btn-add">
                        <i class="fas fa-user-plus"></i> <?= get_setting('pengguna_button_text', 'Tambah Pengguna') ?>
                    </a>

                    <a href="<?= base_url('admin/pengguna/import') ?>" class="btn btn-success btn-import">
                        <i class="fas fa-file-import"></i> <?= get_setting('import_button_text', 'Import Akun') ?>
                    </a>

                    <form id="exportForm" action="<?= base_url('admin/pengguna/exportSelected') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="button" id="btnExportSelected" class="btn btn-outline-info btn-export">
                            <i class="fas fa-file-export"></i> Export Terpilih
                        </button>
                    </form>

                    <a href="<?= base_url('admin/pengguna/export?role=' . ($roleId ?? '') . '&keyword=' . ($keyword ?? '')) ?>" class="btn btn-outline-primary">
                        <i class="fas fa-file-export"></i> Export Semua
                    </a>

                    <button type="submit" form="bulkDeleteForm" class="btn btn-delete-multiple">
                        <i class="fas fa-trash-alt"></i> Hapus Terpilih
                    </button>
                </div>
            </div>

            <!-- ====== TABLE ====== -->
            <form id="bulkDeleteForm" action="<?= base_url('admin/pengguna/deleteMultiple') ?>" method="post">
                <?= csrf_field() ?>
                <?php if (!empty($accounts) && count($accounts) > 0): ?>
                    <div class="table-container">
                        <div class="table-wrapper">
                            <table class="table table-bordered align-middle modern-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Pengguna</th>
                                        <th>Status</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Tahun Masuk</th>
                                        <th>Tahun Lulus</th>
                                        <th>Aksi</th>
                                        <th><input type="checkbox" id="selectAll" aria-label="Pilih semua pengguna"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($accounts as $index => $acc): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <strong><?= esc($acc['username']) ?></strong><br>
                                                <small><?= esc($acc['email']) ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                    $status = strtolower(trim((string)$acc['status']));
                                                    $isActive = ($status === '1' || $status === 'active' || $status === 'aktif');
                                                ?>
                                                <span class="badge badge-status <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
                                                    <?= $isActive ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td><?= esc($acc['email']) ?></td>
                                            <td><span class="badge badge-role"><?= esc($acc['nama_role'] ?? 'No Role') ?></span></td>
                                            <td><?= esc($acc['angkatan'] ?? '-') ?></td>
                                            <td><?= esc($acc['tahun_kelulusan'] ?? '-') ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/pengguna/editPengguna/' . $acc['id']) ?>" class="btn btn-sm btn-edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-delete btn-delete-single"
                                                    data-id="<?= esc($acc['id']) ?>">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="ids[]" value="<?= esc($acc['id']) ?>" class="row-checkbox" aria-label="Pilih pengguna <?= esc($acc['username']) ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
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

<!-- ====== SWEETALERT2 HANDLER ====== -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === FLASH MESSAGES ===

    <?php if (session()->getFlashdata('errorWajib')): ?>
    Swal.fire({
        icon: 'warning',
        title: 'Data Wajib!',
        text: '<?= esc(session()->getFlashdata('errorWajib')) ?>',
        confirmButtonText: 'OK'
    });
<?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= esc(session()->getFlashdata('success')) ?>',
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            html: `<ul style="text-align:left;"> 
                <?php foreach(session()->getFlashdata('errors') as $field => $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>`
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('errorLogs')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Data Gagal Diimport!',
            html: `<ul style="text-align:left;"> 
                <?php foreach(session()->getFlashdata('errorLogs') as $log): ?>
                    <li><?= esc($log) ?></li>
                <?php endforeach; ?>
            </ul>`
        });
    <?php endif; ?>

    // === KONFIRMASI HAPUS SATUAN ===
    document.querySelectorAll('.btn-delete-single').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data pengguna ini akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `<?= base_url('admin/pengguna/delete/') ?>${id}`;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '<?= csrf_token() ?>';
                    csrf.value = '<?= csrf_hash() ?>';
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // === KONFIRMASI HAPUS TERPILIH ===
    const bulkForm = document.getElementById('bulkDeleteForm');
    const bulkButton = document.querySelector('.btn-delete-multiple');
    if (bulkButton) {
        bulkButton.addEventListener('click', (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus akun terpilih?',
                text: 'Semua pengguna yang dipilih akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkForm.submit();
                }
            });
        });
    }

    // === PILIH SEMUA CHECKBOX ===
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    if (selectAll) {
        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    }
});
</script>

<?php $this->endSection(); ?>

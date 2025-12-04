<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/organisasi/tipeorganisasi.css') ?>">
<?php
$tambah_text = get_setting('tambah_button_text', 'Tambah Data');
$tambah_color = get_setting('tambah_button_color', '#0d6efd');
$tambah_text_color = get_setting('tambah_button_text_color', '#ffffff');
$tambah_hover = get_setting('tambah_button_hover_color', '#0b5ed7');
?>
<!-- Main Container -->
<div class="main-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Tipe Organisasi</h1>
        <div class="header-actions">
          
<a href="<?= base_url('admin/tipeorganisasi/form') ?>"
   id="btnTambah"
   class="btn"
   style="background-color: <?= esc($tambah_color) ?>;
          color: <?= esc($tambah_text_color) ?>;
          border: none; padding: 8px 16px; border-radius: 6px;
          transition: all 0.3s ease;">
    <?= esc($tambah_text) ?>
</a>

        </div>
    </div>

        <!-- Table -->
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Tipe</th>
                <th>Level</th>
                <th>Deskripsi</th>
                <th>Group</th>
                <th class="action-column">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($Tipeorganisasi)): ?>
                <?php foreach ($Tipeorganisasi as $tipe): ?>
                <tr>
                    <td class="name-cell"><?= esc($tipe['nama_tipe']) ?></td>
                    <td>
                        <span class="level-badge"><?= esc($tipe['level']) ?></span>
                    </td>
                    <td class="description-cell" title="<?= esc($tipe['deskripsi']) ?>">
                        <?= esc($tipe['deskripsi']) ?>
                    </td>
                    <td>
                        <span class="group-badge"><?= esc($tipe['nama_role']) ?></span>
                    </td>
                    <td class="action-cell">
                        <div class="action-buttons">
                            <!-- Edit Button -->
                            <a href="<?= base_url('/admin/tipeorganisasi/edit/' . $tipe['id']) ?>" 
                               class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Delete Button -->
                            <form action="<?= base_url('/admin/tipeorganisasi/delete/' . $tipe['id']) ?>" 
                                  method="post" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus tipe organisasi ini?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn-action btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            <!-- More Button (opsional, biar konsisten dengan pengguna) -->
                            <button class="btn-action btn-more" title="More options">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        <div class="empty-content">
                            <i class="fas fa-sitemap"></i>
                            <p>Belum ada data tipe organisasi</p>
                            <small>Klik tombol "+ Tambah" untuk menambah data baru</small>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="mt-3">
    <?= $pager->links('default', 'bootstrap5') ?>
</div>
</div>

<!-- Flashdata -->
<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if(session()->getFlashdata('success')): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '<?= session()->getFlashdata('success') ?>',
    confirmButtonColor: '#198754'
});
<?php endif; ?>

function confirmDelete(button) {
    const form = button.closest("form");

    Swal.fire({
        title: 'Yakin hapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}
document.getElementById('btnTambah').addEventListener('mouseover', function() {
    this.style.backgroundColor = '<?= esc($tambah_hover) ?>';
});
document.getElementById('btnTambah').addEventListener('mouseout', function() {
    this.style.backgroundColor = '<?= esc($tambah_color) ?>';
});
</script>

<?= $this->endSection() ?>

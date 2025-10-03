<?php
$orgText = get_setting('org_button_text', 'Tambah Satuan Organisasi');
$orgColor = get_setting('org_button_color', '#28a745');
$orgTextColor = get_setting('org_button_text_color', '#ffffff');
$orgHover = get_setting('org_button_hover_color', '#218838');
?>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<?= $pager->links() ?>
<link rel="stylesheet" href="<?= base_url('css/organisasi/satuanorganisasi.css') ?>">

<!-- Main Container -->
<div class="main-container">
    <div class="page-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Satuan Organisasi</h1>
            <div class="header-actions">
              <a href="<?= base_url('satuanorganisasi/create') ?>" 
   class="btn-org"
   style="background-color: <?= esc($orgColor) ?>;
          color: <?= esc($orgTextColor) ?>;">
   <span class="btn-icon">+</span> <?= esc($orgText) ?>
</a>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tab-container">
            <a href="<?= base_url('satuanorganisasi') ?>" class="tab-link active">
                Satuan Organisasi (<?= esc($count_satuan) ?>)
            </a>
            <a href="<?= base_url('satuanorganisasi/jurusan') ?>" class="tab-link">
                Jurusan (<?= esc($count_jurusan) ?>)
            </a>
            <a href="<?= base_url('satuanorganisasi/prodi') ?>" class="tab-link">
                Prodi (<?= esc($count_prodi) ?>)
            </a>
        </div>

        <!-- Search -->
        <form method="get" action="<?= base_url('satuanorganisasi') ?>" class="search-form">
            <div class="search-box">
                <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>"
                    placeholder="Cari nama, singkatan, tipe, jurusan..." class="search-input">
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>

        <!-- Content Card -->
        <div class="content-card">
            <!-- Table -->
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Satuan</th>
                            <th>Singkatan</th>
                            <th>Slug</th>
                            <th>Tipe Organisasi</th>
                            <th class="action-column">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($satuan)): ?>
                            <?php foreach ($satuan as $row): ?>
                                <!-- Baris utama -->
                                <tr>
                                    <td class="name-cell" onclick="toggleDetail(<?= $row['id'] ?>)" 
                                        style="cursor:pointer; color:#3b82f6; font-weight:600;">
                                        <?= esc($row['nama_satuan']) ?>
                                    </td>
                                    <td><?= esc($row['nama_singkatan']) ?></td>
                                    <td><?= esc($row['nama_slug']) ?></td>
                                    <td>
                                        <span class="group-badge"><?= esc($row['nama_tipe'] ?? '-') ?></span>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <!-- Edit Button -->
                                            <a href="<?= base_url('satuanorganisasi/edit/' . $row['id']) ?>" 
                                               class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="<?= base_url('satuanorganisasi/delete/' . $row['id']) ?>" 
                                                  method="post" 
                                                  style="display: inline;" 
                                                  class="delete-form">
                                                <?= csrf_field() ?>
                                                <button type="button" class="btn-delete" 
                                                        onclick="confirmDelete(this)" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Baris detail hanya Prodi -->
                                <tr id="detail-<?= $row['id'] ?>" class="detail-row" style="display:none; background:#f9fafb;">
                                    <td colspan="5" style="padding:15px;">
                                        <div class="detail-box">
                                            <b>Prodi:</b><br>
                                            <?php if (!empty($row['prodi_list'])): ?>
                                                <?php foreach ($row['prodi_list'] as $p): ?>
                                                    <span class="prodi-badge">
                                                        <?= esc($p['nama_prodi']) ?>
                                                    </span>
                                                <?php endforeach ?>
                                            <?php else: ?>
                                                <span style="color:#6c757d;">Tidak ada Prodi</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <div class="empty-content">
                                        <i class="fas fa-sitemap"></i>
                                        <p>Belum ada data satuan organisasi</p>
                                        <small>Klik tombol "+ Tambah" untuk menambah data baru</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Flashdata -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<style>
.btn-org {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-org:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}
.btn-org:active {
    transform: translateY(0);
}
.btn-org .btn-icon {
    font-size: 16px;
    font-weight: bold;
}
</style>
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

// Toggle detail baris
function toggleDetail(id) {
    const row = document.getElementById("detail-" + id);

    document.querySelectorAll(".detail-row").forEach(el => {
        if (el.id !== "detail-" + id) el.style.display = "none";
    });

    if (row.style.display === "none" || row.style.display === "") {
        row.style.display = "table-row";
    } else {
        row.style.display = "none";
    }
}
</script>

<?= $this->endSection() ?>
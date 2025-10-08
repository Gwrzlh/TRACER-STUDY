<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/jurusan.css') ?>">

<!-- Main Container -->
<div class="main-container">
    <div class="page-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Jurusan</h1>
            <div class="header-actions">
                <a href="<?= base_url('satuanorganisasi/jurusan/create') ?>" class="btn-primary">
                    <span class="btn-icon">+</span> Tambah
                </a>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tab-container">
            <a href="<?= base_url('satuanorganisasi') ?>" 
               class="tab-link <?= (uri_string() == 'satuanorganisasi') ? 'active' : '' ?>">
                Satuan Organisasi (<?= esc($count_satuan) ?>)
            </a>
            <a href="<?= base_url('satuanorganisasi/jurusan') ?>" 
               class="tab-link <?= (uri_string() == 'satuanorganisasi/jurusan') ? 'active' : '' ?>">
                Jurusan (<?= esc($count_jurusan) ?>)
            </a>
            <a href="<?= base_url('satuanorganisasi/prodi') ?>" 
               class="tab-link <?= (uri_string() == 'satuanorganisasi/prodi') ? 'active' : '' ?>">
                Prodi (<?= esc($count_prodi) ?>)
            </a>
        </div>

        <!-- Search -->
        <form method="get" action="<?= base_url('satuanorganisasi/jurusan') ?>" class="search-form">
            <div class="search-box">
                <input type="text" 
                       name="keyword"
                       value="<?= esc($keyword ?? '') ?>"
                       placeholder="Cari nama atau singkatan..." 
                       class="search-input">
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
                            <th>ID</th>
                            <th>Nama Jurusan</th>
                            <th class="action-column">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($jurusan)): ?>
                            <?php foreach ($jurusan as $row): ?>
                                <tr>
                                    <td><?= esc($row['id']) ?></td>
                                    <td class="name-cell"><?= esc($row['nama_jurusan']) ?></td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <!-- Edit Button -->
                                            <a href="<?= base_url('satuanorganisasi/jurusan/edit/' . $row['id']) ?>" 
                                               class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <form id="deleteForm-<?= $row['id'] ?>" 
                                                  action="<?= base_url('satuanorganisasi/jurusan/delete/' . $row['id']) ?>" 
                                                  method="post" 
                                                  style="display:inline;"
                                                  class="delete-form">
                                                <?= csrf_field() ?>
                                                <button type="button" class="btn-delete" 
                                                        onclick="confirmDelete(<?= $row['id'] ?>)" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="empty-state">
                                    <div class="empty-content">
                                        <i class="fas fa-graduation-cap"></i>
                                        <p>Belum ada data jurusan</p>
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

<!-- SweetAlert2 -->
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

function confirmDelete(id) {
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
            document.getElementById('deleteForm-' + id).submit();
        }
    });
}
</script>

<?= $this->endSection() ?>
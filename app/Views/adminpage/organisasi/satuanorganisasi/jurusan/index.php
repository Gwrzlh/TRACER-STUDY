<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/jurusan.css') ?>">

<div class="page-container">

    <!-- Judul -->
    <h2 class="page-title">Jurusan</h2>

    <!-- Tombol Tambah -->
    <div class="btn-tambah-wrapper">
        <a href="<?= base_url('satuanorganisasi/jurusan/create') ?>" class="btn-tambah">
            Tambah
        </a>
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
            <button type="submit" class="search-button">
                Search
            </button>
        </div>
    </form>

    <!-- Tabel -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Jurusan</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jurusan)): ?>
                    <?php foreach ($jurusan as $row): ?>
                        <tr>
                            <td><?= esc($row['id']) ?></td>
                            <td><?= esc($row['nama_jurusan']) ?></td>
                            <td style="text-align:center;">
                                <a href="<?= base_url('satuanorganisasi/jurusan/edit/' . $row['id']) ?>" 
                                   class="btn-edit">
                                    Edit
                                </a>
                                <form id="deleteForm-<?= $row['id'] ?>" 
                                      action="<?= base_url('satuanorganisasi/jurusan/delete/' . $row['id']) ?>" 
                                      method="post" 
                                      style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn-delete" onclick="confirmDelete(<?= $row['id'] ?>)">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center; color:#6c757d;">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Flashdata -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success mt-3">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

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

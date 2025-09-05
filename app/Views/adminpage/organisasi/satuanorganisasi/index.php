<?php
$orgText = get_setting('org_button_text', 'Tambah Satuan Organisasi');
$orgColor = get_setting('org_button_color', '#28a745');
$orgTextColor = get_setting('org_button_text_color', '#ffffff');
$orgHover = get_setting('org_button_hover_color', '#218838');
?>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/satuanorganisasi.css') ?>">

<div class="page-container">

    <!-- Judul -->
    <h2 class="page-title">Satuan Organisasi</h2>

    <!-- Tombol Tambah -->
<div class="btn-tambah-wrapper">
 <a href="<?= base_url('satuan-organisasi/tambah') ?>"
   class="btn"
   style="background-color: <?= $orgColor ?>; 
          color: <?= $orgTextColor ?>; 
          padding:10px 20px; 
          font-weight:600; 
          border-radius: 8px;" 
   onmouseover="this.style.backgroundColor='<?= $orgHover ?>';"
   onmouseout="this.style.backgroundColor='<?= $orgColor ?>';">
   <?= esc($orgText) ?>
</a>
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
                placeholder="Cari nama, singkatan, tipe, jurusan, atau prodi..." class="search-input">
            <button type="submit" class="search-button">Search</button>
        </div>
    </form>

    <!-- Tabel -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nama Satuan</th>
                    <th>Singkatan</th>
                    <th>Slug</th>
                    <th>Tipe Organisasi</th>
                    <th>Prodi</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($satuan)): ?>
                    <?php foreach ($satuan as $row): ?>
                        <tr>
                            <td><?= esc($row['nama_satuan']) ?></td>
                            <td><?= esc($row['nama_singkatan']) ?></td>
                            <td><?= esc($row['nama_slug']) ?></td>
                            <td>
                                  <span class="badge tipe-organisasi"><?= esc($row['nama_tipe'] ?? '-') ?></span>

                            </td>
                            <td>
                                <span class="badge" style="background-color:#20c997; color:white; font-size:0.8rem; padding:2px 6px; border-radius:4px;">
                                    <?= esc($row['nama_prodi'] ?? '-') ?>
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <a href="<?= base_url('satuanorganisasi/edit/' . $row['id']) ?>" class="btn-edit">Edit</a>
                                <form action="<?= base_url('satuanorganisasi/delete/' . $row['id']) ?>" 
                                    method="post" 
                                    class="d-inline delete-form">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn-delete" onclick="confirmDelete(this)">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center; color:#6c757d;">Tidak ada data</td>
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
</script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
<?= $this->endSection() ?>
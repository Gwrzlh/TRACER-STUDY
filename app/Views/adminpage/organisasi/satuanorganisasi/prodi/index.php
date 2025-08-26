<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/prodi.css') ?>">

<div class="page-container">

    <!-- Judul -->
    <h2 class="page-title">Prodi</h2>

    <!-- Tombol Tambah -->
    <div class="btn-tambah-wrapper">
        <a href="<?= base_url('satuanorganisasi/prodi/create') ?>" class="btn-tambah">
            + Tambah
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
    <form method="get" action="<?= base_url('satuanorganisasi/prodi') ?>" class="search-form">
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
                    <th>Nama Prodi</th>
                    <th>Nama Jurusan</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($prodi)): ?>
                    <?php foreach ($prodi as $row): ?>
                        <tr>
                            <td><?= esc($row['nama_prodi']) ?></td>
                            <td><?= esc($row['nama_jurusan']) ?></td>
                            <td style="text-align:center;">
                                <a href="<?= base_url('satuanorganisasi/prodi/edit/' . $row['id']) ?>" 
                                   class="btn-edit">
                                    Edit
                                </a>
                                <form action="<?= base_url('satuanorganisasi/prodi/delete/' . $row['id']) ?>" 
                                      method="post" 
                                      style="display:inline;" 
                                      onsubmit="return confirm('Yakin hapus?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-delete">
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

</div>

<?= $this->endSection() ?>

<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/organisasi/tipeorganisasi.css') ?>">

<div class="table-container">

    <!-- Header -->
    <div class="table-header">
        <h2 class="table-title">Tipe Organisasi</h2>
        <a href="<?= base_url('/admin/tipeorganisasi/form') ?>" class="btn-add">
            <span>+</span> Tambah
        </a>
    </div>

    <!-- Filter & Per Page -->
    <div class="table-controls">
        <form method="get" class="form-per-page">
            <label for="per_page">Tampilkan:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()">
                <option value="5" <?= $perPage == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
            </select>
        </form>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Tipe</th>
                    <th>Level</th>
                    <th>Deskripsi</th>
                    <th>Group</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($Tipeorganisasi)): ?>
                    <?php foreach ($Tipeorganisasi as $tipe): ?>
                    <tr>
                        <td class="customer-name"><?= esc($tipe['nama_tipe']) ?></td>
                        <td><span class="level-badge"><?= esc($tipe['level']) ?></span></td>
                        <td class="description-text" title="<?= esc($tipe['deskripsi']) ?>">
                            <?= esc($tipe['deskripsi']) ?>
                        </td>
                        <td><?= esc($tipe['nama_role']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?= base_url('/admin/tipeorganisasi/edit/' . $tipe['id']) ?>" class="btn-edit">Edit</a>
                                <form action="<?= base_url('/admin/tipeorganisasi/delete/' . $tipe['id']) ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <p>Belum ada data tipe organisasi</p>
                            <small>Klik tombol "+ Tambah" untuk menambah data baru</small>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        <?= $pager->links('default', 'pagination2') ?>
    </div>
</div>

<?= $this->endSection() ?>

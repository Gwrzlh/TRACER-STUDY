<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
.table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    padding: 20px;
}

/* Header */
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.table-title {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.btn-add {
    background: #2563eb;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-add:hover {
    background: #1d4ed8;
}

/* Per page dropdown */
.table-controls {
    margin-bottom: 15px;
}

.form-per-page label {
    margin-right: 5px;
    font-size: 14px;
    color: #444;
}

.form-per-page select {
    padding: 5px 8px;
    font-size: 14px;
}

/* Table */
.table-wrapper {
    overflow-x: auto;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.custom-table thead {
    background: #f8f9fc;
}

.custom-table th {
    padding: 12px;
    font-weight: 600;
    color: #6c757d;
    text-align: left;
    font-size: 12px;
    border-bottom: 2px solid #e9ecef;
}

.custom-table td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

.custom-table tbody tr:hover {
    background-color: #f8f9fc;
}

.customer-name {
    font-weight: 500;
}

.description-text {
    color: #6c757d;
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.level-badge {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
}

/* Buttons */
.action-buttons {
    display: flex;
    gap: 6px;
}

.btn-edit {
    background: #001BB7;
    color: white;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 12px;
    text-decoration: none;
}

.btn-edit:hover {
    background: #000f75;
}

.btn-delete {
    background: #dc3545;
    color: white;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 12px;
    border: none;
}

.btn-delete:hover {
    background: #b02a37;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 15px;
}
</style>

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

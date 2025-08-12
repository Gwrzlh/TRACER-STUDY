<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<style>
.table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin: 20px 0;
}

.table-header {
    padding: 24px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.btn-add {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.btn-add:hover {
    background: #1d4ed8;
    color: white;
    text-decoration: none;
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
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e9ecef;
}

.custom-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
    color: #495057;
    vertical-align: middle;
}

.custom-table tbody tr {
    transition: all 0.2s ease;
}

.custom-table tbody tr:hover {
    background-color: #f8f9fc;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.level-badge {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.action-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-edit {
    background:#001BB7;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-edit:hover {
    background: #001BB7;
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.btn-delete {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-delete:hover {
    background: #c82333;
    transform: translateY(-1px);
}

.inline {
    display: inline;
}

.customer-name {
    font-weight: 500;
    color: #1a1a1a;
}

.description-text {
    color: #6c757d;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state img {
    max-width: 120px;
    margin-bottom: 16px;
    opacity: 0.5;
}
</style>

<div class="table-container">
    <div class="table-header" style="flex-direction: column; align-items: flex-start; gap: 10px;">
        <h2 class="table-title">Tipe Organisasi</h2>
        <a href="<?= base_url('/admin/tipeorganisasi/form') ?>" class="btn-add">
            <span>+</span>
            Tambah
        </a>
    </div>
    <!-- Dropdown jumlah data per halaman -->
    <form method="get" style="margin: 10px 0;">
        <label for="per_page">Tampilkan data:</label>
        <select name="per_page" id="per_page" onchange="this.form.submit()">
            <option value="5" <?= $perPage == 5 ? 'selected' : '' ?>>5</option>
            <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
        </select>   
    <div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Tipe</th>
                    <th>Level</th>
                    <th>Deskripsi</th>
                    <th>Group</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($Tipeorganisasi)): ?>
                    <?php foreach ($Tipeorganisasi as $tipe): ?>
                    <tr>
                        <td>
                            <span class="customer-name"><?= htmlspecialchars($tipe['nama_tipe']) ?></span>
                        </td>
                        <td>
                            <span class="level-badge"><?= htmlspecialchars($tipe['level']) ?></span>
                        </td>
                        <td>
                            <span class="description-text" title="<?= htmlspecialchars($tipe['deskripsi']) ?>">
                                <?= htmlspecialchars($tipe['deskripsi']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($tipe['nama_role']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?= base_url('/admin/tipeorganisasi/edit/'. $tipe['id'] ) ?>" class="btn-edit">Edit</a>
                                <form action="<?= base_url('/admin/tipeorganisasi/delete/' . $tipe['id']) ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <div>
                                <p>Belum ada data tipe organisasi</p>
                                <small>Klik tombol "+ Tambah" untuk menambah data baru</small>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
       <?= $pager->links('default', 'pagination2') ?>

    </div>
</div>


<?= $this->endSection() ?>
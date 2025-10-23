<!-- app/Views/atasan/perusahaan/index.php -->
<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>📦 Daftar Perusahaan</h2>
        <a href="<?= base_url('atasan/perusahaan/tambah') ?>" class="btn btn-primary">
            ➕ Tambah Perusahaan
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-primary text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Alamat</th>
                    <th>Kota</th>
                    <th>Provinsi</th>
                    <th>Telepon</th>
                    <th style="width: 180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($perusahaan)): ?>
                    <?php foreach ($perusahaan as $i => $p): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td><?= esc($p['nama_perusahaan']) ?></td>
                            <td><?= esc($p['alamat1'] ?? '-') ?></td>
                            <td><?= esc($p['kota'] ?? '-') ?></td>
                            <td><?= esc($p['provinsi'] ?? '-') ?></td>
                            <td class="text-center"><?= esc($p['noTlp'] ?? '-') ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('atasan/perusahaan/detail/' . $p['id']) ?>" 
                                   class="btn btn-info btn-sm">👁️ Lihat</a>
                                <a href="<?= base_url('atasan/perusahaan/edit/' . $p['id']) ?>" 
                                   class="btn btn-warning btn-sm">✏️ Edit</a>
                                <a href="<?= base_url('atasan/perusahaan/delete/' . $p['id']) ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus perusahaan ini?')">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            Belum ada data perusahaan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

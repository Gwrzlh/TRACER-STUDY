<?= $this->extend('layout/sidebar'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>🏅 Detail Alumni - <span class="text-primary">Akreditasi</span></h4>
        <a href="<?= base_url('admin/respon/akreditasi'); ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (empty($alumni)) : ?>
        <div class="alert alert-warning">Tidak ada alumni yang termasuk dalam data Akreditasi.</div>
    <?php else : ?>
        <form method="get" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="jurusan" class="form-select">
                    <option value="">Semua Jurusan</option>
                    <?php foreach ($jurusanList as $j): ?>
                        <option value="<?= esc($j['id']); ?>" <?= ($filterJurusan == $j['id']) ? 'selected' : ''; ?>>
                            <?= esc($j['nama_jurusan']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="prodi" class="form-select">
                    <option value="">Semua Prodi</option>
                    <?php foreach ($prodiList as $p): ?>
                        <option value="<?= esc($p['id']); ?>" <?= ($filterProdi == $p['id']) ? 'selected' : ''; ?>>
                            <?= esc($p['nama_prodi']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" name="angkatan" class="form-control" placeholder="Angkatan"
                    value="<?= esc($filterAngkatan); ?>">
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </form>

        <div class="mb-3 text-end">
            <a href="<?= base_url('admin/respon/akreditasi/pdf/' . urlencode($opsi)); ?>" target="_blank" class="btn btn-danger btn-sm">
                <i class="bi bi-filetype-pdf"></i> Export PDF
            </a>
        </div>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Angkatan</th>
                    <th>Tahun Kelulusan</th>
                    <th>IPK</th>
                    <th>Alamat</th>
                    <th>Jenis Kelamin</th>
                    <th>No. Telp</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($alumni as $a): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= esc($a['nama_lengkap']); ?></td>
                        <td><?= esc($a['nim']); ?></td>
                        <td><?= esc($a['nama_jurusan']); ?></td>
                        <td><?= esc($a['nama_prodi']); ?></td>
                        <td><?= esc($a['angkatan']); ?></td>
                        <td><?= esc($a['tahun_kelulusan']); ?></td>
                        <td><?= esc($a['ipk']); ?></td>
                        <td><?= esc($a['alamat']); ?></td>
                        <td><?= esc($a['jenisKelamin']); ?></td>
                        <td><?= esc($a['notlp']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>
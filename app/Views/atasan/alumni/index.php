<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<style>
.table-wrapper {
    background: #fff;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
}
th {
    background: #0d47a1 !important;
    color: #fff !important;
}
.badge-info {
    background: #0d47a1;
}
</style>

<div class="container mt-4">

    <h3 class="fw-bold text-primary mb-4">👥 Data Alumni Binaan Anda</h3>

    <div class="table-wrapper">
        <form method="get" class="mb-3">
    <div class="input-group">
        <input type="text" name="keyword" value="<?= esc($_GET['keyword'] ?? '') ?>"
               class="form-control" placeholder="Cari nama, jurusan, NIM, prodi, angkatan, tahun lulus, IPK, provinsi, kota...">
        
        <button class="btn btn-primary">
            <i class="fa fa-search"></i>
        </button>

        <?php if (!empty($_GET['keyword'])): ?>
        <a href="<?= base_url('atasan/alumni') ?>" class="btn btn-secondary">
            Clear
        </a>
        <?php endif; ?>
    </div>
</form>

        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Angkatan</th>
                    <th>Tahun Lulus</th>
                    <th>IPK</th>
                    <th>Alamat</th>
                    <th>Kode Pos</th>
                    <th>Jenis Kelamin</th>
                    <th>No. Telepon</th>
                    <th>Provinsi</th>
                    <th>Kota</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($alumni as $a): ?>
                <tr>
                    <td><?= esc($a['nama_lengkap']) ?></td>
                    <td><?= esc($a['nim']) ?></td>
                    <td><?= esc($a['nama_jurusan']) ?></td>
                    <td><?= esc($a['nama_prodi']) ?></td>
                    <td><?= esc($a['angkatan']) ?></td>
                    <td><?= esc($a['tahun_kelulusan']) ?></td>
                    <td><?= esc($a['ipk']) ?></td>
                    <td><?= esc($a['alamat']) ?><br><?= esc($a['alamat2']) ?></td>
                    <td><?= esc($a['kodepos']) ?></td>
                    <td><?= esc($a['jenisKelamin']) ?></td>
                    <td><?= esc($a['notlp']) ?></td>
                    <td><?= esc($a['nama_provinsi']) ?></td>
                    <td><?= esc($a['nama_kota']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($alumni)): ?>
            <div class="alert alert-info text-center mt-3">
                Belum ada alumni yang terdata.
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3 class="mb-3">Teman Satu Jurusan & Prodi</h3>
    <p>Jurusan: <b><?= esc($jurusan) ?></b> | Prodi: <b><?= esc($prodi) ?></b></p>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($teman) > 0): ?>
                        <?php $no = 1;
                        foreach ($teman as $t): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($t['nama_lengkap']) ?></td>
                                <td><?= esc($t['nim']) ?></td>
                                <td>
                                    <?php if ($t['status'] === 'Aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><?= esc($t['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('alumni/pesan/' . $t['id_account']) ?>" class="btn btn-sm btn-primary">
                                        Kirim Pesan
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Belum ada teman dengan jurusan & prodi sama.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
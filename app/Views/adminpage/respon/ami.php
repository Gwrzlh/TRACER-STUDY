<?= $this->extend('layout/sidebar'); ?>
<?= $this->section('content'); ?>

<div class="respon-navbar mb-4">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link <?= (uri_string() == 'admin/respon') ? '' : '' ?>" href="<?= base_url('admin/respon') ?>">📋 Respon</a></li>
        <li class="nav-item"><a class="nav-link <?= (uri_string() == 'admin/respon/ami') ? 'active' : '' ?>" href="<?= base_url('admin/respon/ami') ?>">🧾 AMI</a></li>
        <li class="nav-item"><a class="nav-link <?= (uri_string() == 'admin/respon/akreditasi') ? '' : '' ?>" href="<?= base_url('admin/respon/akreditasi') ?>">📊 Akreditasi</a></li>
    </ul>
</div>

<div class="container-fluid">
    <h4 class="mb-4">🧾 Data AMI</h4>

    <div class="card">
        <div class="card-body">
            <?php if (empty($pertanyaan)) : ?>
                <div class="alert alert-info">Belum ada pertanyaan untuk AMI.</div>
            <?php else : ?>
                <?php foreach ($pertanyaan as $q) : ?>
                    <div class="mb-4">
                        <h6 class="fw-bold">
                            <?= esc($q['teks']); ?>
                            <span class="badge bg-success">AMI</span>
                            <a href="<?= base_url('admin/respon/remove_from_ami/' . $q['id']); ?>" class="btn btn-sm btn-danger float-end" onclick="return confirm('Yakin hapus pertanyaan ini dari AMI?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </h6>

                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Opsi Jawaban</th>
                                    <th>Jumlah</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($q['jawaban'] as $a) : ?>
                                    <tr>
                                        <td><?= esc($a['opsi']); ?></td>
                                        <td><?= esc($a['jumlah']); ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/respon/ami/detail/' . urlencode($a['opsi'])); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Lihat Alumni
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
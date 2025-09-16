<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/lihatteman.css') ?>">

<div class="container mt-4">
    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h3 class="mb-3">Teman Satu Jurusan & Prodi</h3>
    <p>Jurusan: <b><?= esc($jurusan) ?></b> | Prodi: <b><?= esc($prodi) ?></b></p>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 10%;">Foto</th>
                        <th style="width: 25%;">Nama</th>
                        <th style="width: 15%;">NIM</th>
                        <th style="width: 20%;">Status Kuesioner</th>
                        <th style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($teman)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($teman as $t): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <img src="<?= base_url('uploads/foto_alumni/' . (!empty($t['foto']) ? $t['foto'] : 'default.png')) ?>"
                                        alt="Foto <?= esc($t['nama_lengkap']) ?>"
                                        class="rounded-circle border"
                                        style="width:45px; height:45px; object-fit:cover;">
                                </td>
                                <td><?= esc($t['nama_lengkap']) ?></td>
                                <td><?= esc($t['nim']) ?></td>
                                <td>
                                    <?php if ($t['status'] === 'Finish'): ?>
                                        <span class="badge bg-success">Finish</span>
                                    <?php elseif ($t['status'] === 'Ongoing'): ?>
                                        <span class="badge bg-warning text-dark">Ongoing</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum Mengisi</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($t['id_account'] != session('id')): ?>
                                        <a href="<?= base_url('alumni/pesan/' . $t['id_account']) ?>"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-send"></i> Kirim Pesan
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Ini Anda</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada teman dengan jurusan & prodi sama.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
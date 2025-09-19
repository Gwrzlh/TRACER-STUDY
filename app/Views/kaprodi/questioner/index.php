<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <!-- Notifikasi Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-dark d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Daftar Kuesioner Aktif</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:40%;">Judul</th>
                            <th style="width:25%;">Program Studi</th>
                            <th style="width:15%;">Status</th>
                            <th style="width:15%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kuesioner)): ?>
                            <?php $no = 1;
                            foreach ($kuesioner as $k): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= esc($k['title']) ?></strong></td>
                                    <td><?= esc($kaprodi['nama_prodi'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($k['is_active'] === 'active'): ?>
                                            <span class="badge bg-success px-3 py-2">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary px-3 py-2">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('kaprodi/pertanyaan/' . $k['id']) ?>" class="btn btn-info btn-sm">
                                            <i class="bi bi-eye"></i> Lihat Pertanyaan
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada kuesioner aktif untuk prodi Anda.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
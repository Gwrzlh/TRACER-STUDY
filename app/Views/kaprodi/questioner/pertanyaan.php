<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="container mt-5">

    <!-- Flashdata Alert -->
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
    <?php endif ?>

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-info text-dark d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📋 Lihat Pertanyaan - <?= esc($questionnaire['title']) ?></h4>
            <a href="<?= base_url('kaprodi/questioner') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card-body">

            <!-- Tombol Download PDF -->
            <div class="mb-3 text-end">
                <a href="<?= base_url('kaprodi/questioner/' . $idKuesioner . '/download') ?>" class="btn btn-danger btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Download Pertanyaan (PDF)
                </a>
            </div>

            <?php if (empty($pages)): ?>
                <div class="alert alert-warning text-center">
                    Belum ada pertanyaan pada kuesioner ini.
                </div>
            <?php else: ?>
                <form method="post" action="<?= base_url('kaprodi/questioner/save_flags') ?>">
                    <?php $no = 1; ?>
                    <?php foreach ($pages as $page): ?>
                        <div class="mb-4">
                            <h5 class="mb-2"><?= esc($page['title']) ?> (<?= count($page['questions']) ?> pertanyaan)</h5>

                            <?php if (!empty($page['questions'])): ?>
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th style="width:5%;">#</th>
                                            <th style="width:10%;">Akreditasi</th>
                                            <th style="width:10%;">AMI</th>
                                            <th>Pertanyaan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($page['questions'] as $q): ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input" name="akreditasi[]" value="<?= $q['id'] ?>" <?= (!empty($q['is_for_accreditation'])) ?: '' ?>>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input" name="ami[]" value="<?= $q['id'] ?>" <?= (!empty($q['is_for_ami'])) ?: '' ?>>
                                                </td>
                                                <td><?= esc($q['question_text']) ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada pertanyaan di halaman ini.</p>
                            <?php endif ?>
                        </div>
                    <?php endforeach ?>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan
                        </button>
                    </div>
                </form>
            <?php endif ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
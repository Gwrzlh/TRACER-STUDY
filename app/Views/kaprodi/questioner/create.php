<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= isset($questionnaire) ? 'Edit Kuesioner' : 'Tambah Kuesioner' ?></h4>
        </div>
        <div class="card-body">
            <form action="<?= isset($questionnaire)
                                ? base_url("kaprodi/questioner/update/{$questionnaire['id']}")
                                : base_url('kaprodi/questioner/store') ?>" method="post">
                <?php if (isset($questionnaire)): ?>
                    <input type="hidden" name="id" value="<?= $questionnaire['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Kuesioner</label>
                    <input type="text" name="judul" id="judul" class="form-control"
                        value="<?= isset($questionnaire) ? esc($questionnaire['title']) : '' ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <input type="text" class="form-control" value="<?= esc($kaprodi['nama_prodi']) ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="aktif" <?= isset($questionnaire) && $questionnaire['is_active'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= isset($questionnaire) && $questionnaire['is_active'] !== 'active' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('kaprodi/questioner/list') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> <?= isset($questionnaire) ? 'Update' : 'Simpan' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
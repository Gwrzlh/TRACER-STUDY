<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Edit Halaman Kuesioner</h2>

    <form action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page['id']}/update") ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Judul Halaman</label>
            <input type="text" name="title" class="form-control" value="<?= esc($page['page_title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control"><?= esc($page['page_description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="order_no" class="form-control" value="<?= esc($page['order_no']) ?>" min="1" required>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages") ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?= $this->endSection() ?>

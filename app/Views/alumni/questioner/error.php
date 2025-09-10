<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="alert alert-warning"><?= esc($message) ?></div>
    <a href="<?= base_url('alumni/questionnaires') ?>" class="btn btn-primary">Kembali ke Daftar Kuesioner</a>
</div>
<?= $this->endSection() ?>
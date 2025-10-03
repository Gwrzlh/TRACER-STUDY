<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="alert alert-warning"><?= esc($message) ?></div>
    <a href="<?= base_url('atasan/kuesioner') ?>" class="btn btn-primary">Kembali ke Daftar Kuesioner</a>
</div>

<?= $this->endSection() ?>

<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="p-6">
    <h1 class="text-2xl font-bold">Dashboard Kaprodi</h1>
    <p class="mt-2">Halo <?= session()->get('username') ?> (Kaprodi)</p>
</div>

<?= $this->endSection() ?>

<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<h2 class="text-2xl font-bold mb-2">Selamat datang di Dashboard</h2>
<p class="text-gray-700">Halo, <?= session()->get('username') ?> (<?= session()->get('email') ?>)</p>
<p class="text-gray-500 mb-4">Role ID: <?= session()->get('role_id') ?></p>



<?= $this->endSection() ?>
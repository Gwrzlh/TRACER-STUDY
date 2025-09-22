<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="p-6">
    <!-- Header -->
    <h1 class="text-2xl font-bold">Dashboard Kaprodi</h1>
    <p class="mt-2">Halo <?= esc(session()->get('username')) ?> (Kaprodi)</p>

    <!-- Card Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-sm font-semibold text-gray-500">Jumlah Kuesioner Aktif</h2>
            <p class="text-2xl font-bold mt-2"><?= esc($kuesionerCount ?? 0) ?></p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-sm font-semibold text-gray-500">Jumlah Alumni <?= esc($kaprodi['nama_prodi']) ?></h2>
            <p class="text-2xl font-bold mt-2"><?= esc($alumniCount ?? 0) ?></p>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-sm font-semibold text-gray-500">Akreditasi</h2>
            <p class="text-2xl font-bold mt-2"><?= esc($akreditasiAlumni ?? 0) ?></p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-sm font-semibold text-gray-500">AMI</h2>
            <p class="text-2xl font-bold mt-2"><?= esc($amiAlumni ?? 0) ?></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
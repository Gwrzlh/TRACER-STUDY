<?= $this->extend('layout/sidebar_alumni2') ?>

<?= $this->section('content') ?>
<div class="bg-white p-6 rounded-2xl shadow-md">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Selamat Datang di Dasboard</h1>
    <p class="text-gray-600">Dashboard, <span class="font-semibold"><?= session()->get('username') ?></span>!</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <!-- Card Profil -->
        <div class="p-4 bg-blue-100 rounded-xl shadow-sm">
            <h2 class="text-lg font-semibold text-blue-700">Profil</h2>
            <p class="text-sm text-gray-600">Lihat dan perbarui data pribadi Anda.</p>
            <a href="<?= base_url('alumni/profil') ?>" class="inline-block mt-3 text-blue-700 font-medium hover:underline">
                Lihat Profil →
            </a>
        </div>

        <!-- Card Kuesioner -->
        <div class="p-4 bg-green-100 rounded-xl shadow-sm">
            <h2 class="text-lg font-semibold text-green-700">Kuesioner</h2>
            <p class="text-sm text-gray-600">Isi kuesioner tracer study untuk mendukung pengembangan alumni.</p>
            <a href="<?= base_url('alumni/questioner') ?>" class="inline-block mt-3 text-green-700 font-medium hover:underline">
                Isi Kuesioner →
            </a>
        </div>
        <div class="p-4 bg-green-100 rounded-xl shadow-sm">
            <h2 class="text-lg font-semibold text-green-700">Lihat Teman</h2>
            <p class="text-sm text-gray-600">Anda bisa melihat teman anda</p>
            <a href="<?= base_url('alumni/lihat_teman') ?>"
                class="inline-block mt-3 text-green-700 font-medium hover:underline">
                Lihat Teman
            </a>

        </div>
    </div>
</div>
<?= $this->endSection() ?>
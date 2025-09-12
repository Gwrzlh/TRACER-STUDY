<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<div class="bg-white p-6 rounded-2xl shadow-md">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <img src="/images/logo.png" alt="Polban Logo" class="w-16 h-16 object-contain bg-gray-50 rounded-full p-2 border-2 border-gray-200 mr-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang di Dashboard</h1>
            <p class="text-gray-600">Dashboard, <span class="font-semibold text-blue-600"><?= session()->get('username') ?></span>!</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Card Profil -->
        <div class="p-6 bg-blue-50 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fa-solid fa-user text-blue-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-blue-700">Profil</h2>
            </div>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Lihat dan perbarui data pribadi, informasi kontak, serta riwayat pendidikan Anda.</p>
            <a href="<?= base_url($showLihatTeman ? 'alumni/surveyor/profilSurveyor' : 'alumni/profil') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Lihat Profil
                <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Card Kuesioner -->
        <div class="p-6 bg-green-50 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fa-solid fa-list text-green-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-green-700">Kuesioner</h2>
            </div>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Isi kuesioner tracer study untuk evaluasi dan pengembangan program studi alumni.</p>
            <a href="<?= base_url('alumni/questionnaires') ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                Kuesioner
                <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
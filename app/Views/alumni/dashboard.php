<?= $this->extend('layout/sidebar_alumni') ?>

<?= $this->section('content') ?>
<div class="bg-white p-6 rounded-2xl shadow-md">
    <!-- Header with Logo -->
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
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-blue-700">Profil</h2>
            </div>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Lihat dan perbarui data pribadi, informasi kontak, serta riwayat pendidikan Anda.</p>
            <a href="<?= base_url('alumni/profil') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Lihat Profil
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <!-- Card Kuesioner -->
        <div class="p-6 bg-green-50 rounded-xl shadow-sm border border-green-100 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-green-700">Kuesioner</h2>
            </div>
            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Isi kuesioner tracer study untuk membantu evaluasi dan pengembangan program studi alumni.</p>
            <a href="<?= base_url('alumni/questioner') ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                Kuesioner
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
        <div class="bg-gray-50 p-4 rounded-lg border">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $totalKuesioner ?></p>
                    <p class="text-sm text-gray-600">Total Kuesioner</p>

                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $selesai ?></p>
                    <p class="text-sm text-gray-600">Selesai</p>

                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $sedangBerjalan ?></p>
                    <p class="text-sm text-gray-600">Sedang Berjalan</p>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
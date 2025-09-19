<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/dashboard.css') ?>">

<div class="dashboard-container">
    <!-- Header -->
    <div class="header-dashboard">
        <img src="<?= base_url('images/logo.png') ?>" alt="Polban Logo" class="logo-dashboard">
        <div>
            <h1 class="title-dashboard">Selamat Datang di Dashboard Alumni</h1>
            <p class="subtitle-dashboard">
                Halo, <span class="username"><?= session()->get('username') ?></span>!
            </p>
        </div>
    </div>

    <!-- Grid Menu -->
    <div class="grid-menu">
        <!-- Card Profil -->
        <div class="card card-blue">
            <div class="card-icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <h2 class="card-title">Profil</h2>
            <p class="card-text">Lihat & perbarui data pribadi, kontak, dan riwayat pendidikan.</p>
            <a href="<?= base_url('alumni/profil') ?>" class="card-btn">
                Lihat Profil <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Card Kuesioner -->
        <div class="card card-green">
            <div class="card-icon">
                <i class="fa-solid fa-list"></i>
            </div>
            <h2 class="card-title">Kuesioner</h2>
            <p class="card-text">Isi tracer study untuk evaluasi & pengembangan prodi.</p>
            <a href="<?= base_url('alumni/questionnaires') ?>" class="card-btn">
                Isi Kuesioner <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Card Lowongan (tanpa button) -->
        <div class="card card-orange">
            <div class="card-icon">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <h2 class="card-title">Lowongan Kerja</h2>
            <p class="card-text">Akses informasi lowongan kerja terbaru dari mitra kampus.</p>
        </div>

        <!-- Card Berita (tanpa button) -->
        <div class="card card-purple">
            <div class="card-icon">
                <i class="fa-solid fa-newspaper"></i>
            </div>
            <h2 class="card-title">Berita & Event</h2>
            <p class="card-text">Ikuti berita, seminar, & kegiatan alumni terbaru dari kampus.</p>
        </div>

        <!-- Card Statistik (tanpa button) -->
        <div class="card card-teal">
            <div class="card-icon">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <h2 class="card-title">Statistik</h2>
            <p class="card-text">Lihat progres pengisian kuesioner & data alumni terkini.</p>
        </div>

        <!-- Card Interaksi (tanpa button) -->
        <div class="card card-red">
            <div class="card-icon">
                <i class="fa-solid fa-comments"></i>
            </div>
            <h2 class="card-title">Interaksi</h2>
            <p class="card-text">Diskusi & terhubung dengan alumni lain dan career center.</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/dashboard.css') ?>">

<?php
use App\Models\SiteSettingModel;
use App\Models\PengaturanDashboardModel;

// Ambil pengaturan warna/tombol
$siteSettingModel = new SiteSettingModel();
$settings = $siteSettingModel->getSettings();

// Ambil teks dashboard dari database
$dashboardModel = new PengaturanDashboardModel();
$dashboard = $dashboardModel->first();

// Default jika belum ada di database
$judul_dashboard = $dashboard['judul'] ?? 'Selamat Datang di Dashboard Alumni';
$deskripsi_dashboard = $dashboard['deskripsi'] ?? 'Halo, alumni!';
$judul_profil = $dashboard['judul_profil'] ?? 'Profil';
$deskripsi_profil = $dashboard['deskripsi_profil'] ?? 'Lihat & perbarui data pribadi, kontak, dan riwayat pendidikan.';
$judul_kuesioner = $dashboard['judul_kuesioner'] ?? 'Kuesioner';
$deskripsi_kuesioner = $dashboard['deskripsi_kuesioner'] ?? 'Isi tracer study untuk evaluasi & pengembangan prodi.';
?>

<div class="dashboard-container">
    <!-- Header -->
    <div class="header-dashboard">
        <img src="<?= base_url('images/logo.png') ?>" alt="Polban Logo" class="logo-dashboard">
        <div>
            <h1 class="title-dashboard"><?= esc($judul_dashboard) ?></h1>
            <div class="subtitle-dashboard">
                <?= html_entity_decode($deskripsi_dashboard) ?>
                <span class="username"><?= session()->get('username') ?></span>!
            </div>
        </div>
    </div>

    <!-- Grid Menu -->
    <div class="grid-menu">
        <!-- Card Profil -->
        <div class="card card-blue">
            <div class="card-icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <h2 class="card-title"><?= esc($judul_profil) ?></h2>
            <div class="card-text"><?= html_entity_decode($deskripsi_profil) ?></div>

            <a href="<?= base_url('alumni/profil') ?>"
               class="btn-dashboard"
               style="background-color: <?= esc($settings['dashboard_profil_button_color'] ?? '#0d6efd') ?>;
                      color: <?= esc($settings['dashboard_profil_button_text_color'] ?? '#ffffff') ?>;"
               onmouseover="this.style.backgroundColor='<?= esc($settings['dashboard_profil_button_hover_color'] ?? '#0b5ed7') ?>'"
               onmouseout="this.style.backgroundColor='<?= esc($settings['dashboard_profil_button_color'] ?? '#0d6efd') ?>'">
                <?= esc($settings['dashboard_profil_button_text'] ?? 'Lihat Profil') ?> 
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Card Kuesioner -->
        <div class="card card-green">
            <div class="card-icon">
                <i class="fa-solid fa-list"></i>
            </div>
            <h2 class="card-title"><?= esc($judul_kuesioner) ?></h2>
            <div class="card-text"><?= html_entity_decode($deskripsi_kuesioner) ?></div>

            <a href="<?= base_url('alumni/questionnaires') ?>"
               class="btn-dashboard"
               style="background-color: <?= esc($settings['dashboard_kuesioner_button_color'] ?? '#198754') ?>;
                      color: <?= esc($settings['dashboard_kuesioner_button_text_color'] ?? '#ffffff') ?>;"
               onmouseover="this.style.backgroundColor='<?= esc($settings['dashboard_kuesioner_button_hover_color'] ?? '#157347') ?>'"
               onmouseout="this.style.backgroundColor='<?= esc($settings['dashboard_kuesioner_button_color'] ?? '#198754') ?>'">
                <?= esc($settings['dashboard_kuesioner_button_text'] ?? 'Isi Kuesioner') ?> 
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

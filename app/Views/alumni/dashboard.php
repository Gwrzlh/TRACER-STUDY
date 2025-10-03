<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/dashboard.css') ?>">
<?php
$siteSettingModel = new \App\Models\SiteSettingModel();
$settings = $siteSettingModel->getSettings();
?>

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
            <a href="<?= base_url('alumni/profil') ?>"
   class="btn-dashboard"
   style="background-color: <?= esc($settings['dashboard_profil_button_color'] ?? '#0d6efd') ?>;
          color: <?= esc($settings['dashboard_profil_button_text_color'] ?? '#ffffff') ?>;"
   onmouseover="this.style.backgroundColor='<?= esc($settings['dashboard_profil_button_hover_color'] ?? '#0b5ed7') ?>'"
   onmouseout="this.style.backgroundColor='<?= esc($settings['dashboard_profil_button_color'] ?? '#0d6efd') ?>'">
    <?= esc($settings['dashboard_profil_button_text'] ?? 'Lihat Profil') ?> <i class="fa-solid fa-arrow-right"></i>
</a>

        </div>

        <!-- Card Kuesioner -->
        <div class="card card-green">
            <div class="card-icon">
                <i class="fa-solid fa-list"></i>
            </div>
            <h2 class="card-title">Kuesioner</h2>
            <p class="card-text">Isi tracer study untuk evaluasi & pengembangan prodi.</p>
         <a href="<?= base_url('alumni/questionnaires') ?>"
   class="btn-dashboard"
   style="background-color: <?= esc($settings['dashboard_kuesioner_button_color'] ?? '#198754') ?>;
          color: <?= esc($settings['dashboard_kuesioner_button_text_color'] ?? '#ffffff') ?>;"
   onmouseover="this.style.backgroundColor='<?= esc($settings['dashboard_kuesioner_button_hover_color'] ?? '#157347') ?>'"
   onmouseout="this.style.backgroundColor='<?= esc($settings['dashboard_kuesioner_button_color'] ?? '#198754') ?>'">
    <?= esc($settings['dashboard_kuesioner_button_text'] ?? 'Isi Kuesioner') ?> <i class="fa-solid fa-arrow-right"></i>
</a>
        </div>

        
<?= $this->endSection() ?>
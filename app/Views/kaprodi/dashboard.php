<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/kaprodi/dashboard.css') ?>" rel="stylesheet">

<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="dashboard-logo">
                <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2" style="height: 60px;">
            </div>
            <div class="header-text">
                <!-- Ambil dari pengaturan dashboard -->
                <h1 class="dashboard-title">
                    <?= esc($dashboard['judul'] ?? 'Dashboard Kaprodi') ?>
                </h1>
                <p class="dashboard-subtitle">
                    <?= !empty($dashboard['deskripsi']) 
                        ? $dashboard['deskripsi'] 
                        : 'Halo ' . esc(session()->get('username')) . ' (Kaprodi)' ?>
                </p>
            </div>
        </div>
        <div class="header-decoration"></div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <!-- Kuesioner Aktif Card -->
        <div class="stat-card kuesioner-card">
            <div class="card-header">
                <div class="card-icon kuesioner-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="card-trend positive">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="card-content">
                <h3 class="card-title">
                    <?= esc($dashboard['judul_kuesioner'] ?? 'Jumlah Kuesioner Aktif') ?>
                </h3>
                <p class="card-value"><?= esc($kuesionerCount ?? 0) ?></p>
                <div class="card-progress">
                    <div class="progress-bar kuesioner-progress"></div>
                </div>
            </div>
        </div>

        <!-- Alumni Card -->
        <div class="stat-card alumni-card">
            <div class="card-header">
                <div class="card-icon alumni-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="card-trend positive">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="card-content">
                <h3 class="card-title">
                    <?= esc($dashboard['judul_data_alumni'] ?? 'Jumlah Alumni') ?>
                    <?= esc($kaprodi->nama_prodi ?? '') ?>
                </h3>
                <p class="card-value"><?= esc($alumniCount ?? 0) ?></p>
                <div class="card-progress">
                    <div class="progress-bar alumni-progress"></div>
                </div>
            </div>
        </div>

        <!-- Akreditasi Card -->
        <div class="stat-card akreditasi-card">
            <div class="card-header">
                <div class="card-icon akreditasi-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="card-trend stable">
                    <i class="fas fa-minus"></i>
                </div>
            </div>
            <div class="card-content">
                <h3 class="card-title">
                    <?= esc($dashboard['judul_profil'] ?? 'Akreditasi') ?>
                </h3>
                <p class="card-value"><?= esc($akreditasiAlumni ?? 0) ?></p>
                <div class="card-progress">
                    <div class="progress-bar akreditasi-progress"></div>
                </div>
            </div>
        </div>

        <!-- AMI Card -->
        <div class="stat-card ami-card">
            <div class="card-header">
                <div class="card-icon ami-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-trend stable">
                    <i class="fas fa-minus"></i>
                </div>
            </div>
            <div class="card-content">
                <h3 class="card-title">
                    <?= esc($dashboard['judul_ami'] ?? 'AMI') ?>
                </h3>
                <p class="card-value"><?= esc($amiAlumni ?? 0) ?></p>
                <div class="card-progress">
                    <div class="progress-bar ami-progress"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?= $this->endSection() ?>

<?= $this->extend('layout/sidebar_jabatan') ?>
<?= $this->section('content') ?>

<link href="<?= base_url('css/jabatan/dashboard.css') ?>" rel="stylesheet">

<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="dashboard-logo">
                <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2" style="height: 60px;">
            </div>
            <div class="header-text">
                <h1 class="dashboard-title">Dashboard Jabatan Lainnya</h1>
                <p class="dashboard-subtitle">Halo <?= esc(session()->get('username')) ?> 👋</p>
            </div>
        </div>
        <div class="header-decoration"></div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <!-- AMI Card -->
        <div class="stat-card ami-card">
            <div class="card-header">
                <div class="card-icon ami-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="card-trend stable">
                    <i class="fas fa-minus"></i>
                </div>
            </div>
            <div class="card-content">
                <h3 class="card-title">AMI</h3>
                <p>Total Pertanyaan: <span class="font-bold"><?= $totalPertanyaanAmi ?></span></p>
                <p>Total Jawaban: <span class="font-bold"><?= $totalJawabanAmi ?></span></p>
                <div class="card-progress">
                    <div class="progress-bar ami-progress"></div>
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
                <h3 class="card-title">Akreditasi</h3>
                <p>Total Pertanyaan: <span class="font-bold"><?= $totalPertanyaanAkreditasi ?></span></p>
                <p>Total Jawaban: <span class="font-bold"><?= $totalJawabanAkreditasi ?></span></p>
                <div class="card-progress">
                    <div class="progress-bar akreditasi-progress"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Card (Tanpa Ikon) -->
    <div class="stat-card grafik-card">
        <div class="card-header">
            <!-- Menghapus ikon grafik -->
            <div class="card-trend stable">
                <i class="fas fa-minus"></i>
            </div>
        </div>
        <div class="card-content">
            <div class="flex justify-between items-center mb-4">
                <h3 id="grafik-title" class="card-title">Grafik AMI</h3>
                <div class="space-x-2">
                    <button id="btnAmi" onclick="showGrafik('ami')"
                        class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">AMI</button>
                    <button id="btnAkreditasi" onclick="showGrafik('akreditasi')"
                        class="px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700">Akreditasi</button>
                </div>
            </div>
            <div class="h-96">
                <canvas id="grafikCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Ringkasan Jurusan & Prodi Card -->
    <div class="stat-card data-card">
        <div class="card-header">
            <div class="card-icon data-icon">
                <i class="fas fa-university"></i>
            </div>
            <div class="card-trend stable">
                <i class="fas fa-minus"></i>
            </div>
        </div>
        <div class="card-content">
            <h3 class="card-title">Data Jurusan & Prodi</h3>

            <?php foreach ($dashboardData as $jurusanData): ?>
                <div class="jurusan-section mb-6">
                    <h4 class="jurusan-name mb-3"><?= esc($jurusanData['jurusan']['nama_jurusan']) ?></h4>
                    <div class="data-prodi-container">
                        <?php foreach ($jurusanData['prodis'] as $prodiData): ?>
                            <div class="prodi-card">
                                <h5 class="prodi-name"><?= esc($prodiData['prodi']['nama_prodi']) ?></h5>
                                <p class="prodi-alumni">Alumni: <?= count($prodiData['alumni']) ?></p>
                                <p class="prodi-kaprodi">Kaprodi: <?= count($prodiData['kaprodi']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>


    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const grafikAmi = <?= json_encode($grafikAmi) ?>;
        const grafikAkreditasi = <?= json_encode($grafikAkreditasi) ?>;
        let chart;

        function renderChart(dataset, label, color) {
            const ctx = document.getElementById('grafikCanvas').getContext('2d');
            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: dataset.map(d => d.question_text),
                    datasets: [{
                        label: label,
                        data: dataset.map(d => d.total),
                        backgroundColor: color.replace('1)', '0.2)'),
                        borderColor: color,
                        borderWidth: 2,
                        pointBackgroundColor: color
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        }

        function showGrafik(type) {
            if (type === 'ami') {
                renderChart(grafikAmi, 'Jawaban AMI', 'rgba(37,99,235,1)');
                document.getElementById('grafik-title').innerText = "Grafik AMI";
            } else {
                renderChart(grafikAkreditasi, 'Jawaban Akreditasi', 'rgba(16,185,129,1)');
                document.getElementById('grafik-title').innerText = "Grafik Akreditasi";
            }
        }

        showGrafik('ami');
    </script>

    <!-- Add FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?= $this->endSection() ?>
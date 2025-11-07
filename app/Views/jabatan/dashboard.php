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
                <!-- 🔹 Judul dan deskripsi dari database -->
                <h1 class="dashboard-title"><?= ($judul) ?></h1>
                <p class="dashboard-subtitle"><?= ($deskripsi) ?></p>
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
                <!-- 🔹 Judul Card AMI dinamis -->
                <h3 class="card-title"><?= esc($judul_ami) ?></h3>
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
                <!-- 🔹 Judul Card Akreditasi dinamis -->
                <h3 class="card-title"><?= esc($judul_profil) ?></h3>
                <p>Total Pertanyaan: <span class="font-bold"><?= $totalPertanyaanAkreditasi ?></span></p>
                <p>Total Jawaban: <span class="font-bold"><?= $totalJawabanAkreditasi ?></span></p>
                <div class="card-progress">
                    <div class="progress-bar akreditasi-progress"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Card -->
    <div class="stat-card grafik-card">
        <div class="card-header">
            <div class="card-trend stable">
                <i class="fas fa-minus"></i>
            </div>
        </div>
        <div class="card-content">
            <div class="flex justify-between items-center mb-4">
                <h3 id="grafik-title" class="card-title">Grafik <?= esc($judul_ami) ?></h3>
                <div class="space-x-2">
                    <!-- Tombol AMI -->
                    <button id="btnAmi"
                        onclick="showGrafik('ami')"
                        style="
                            background-color: <?= get_setting('jabatanlainnya_ami_button_color', '#2563eb') ?>;
                            color: <?= get_setting('jabatanlainnya_ami_button_text_color', '#ffffff') ?>;
                            border: none;
                            padding: 8px 16px;
                            border-radius: 8px;
                            font-weight: 600;
                            transition: 0.2s;
                        "
                        onmouseover="this.style.backgroundColor='<?= get_setting('jabatanlainnya_ami_button_hover_color', '#1d4ed8') ?>'"
                        onmouseout="this.style.backgroundColor='<?= get_setting('jabatanlainnya_ami_button_color', '#2563eb') ?>'">
                        <?= esc(get_setting('jabatanlainnya_ami_button_text', $judul_ami)) ?>
                    </button>

                    <!-- Tombol Akreditasi -->
                    <button id="btnAkreditasi"
                        onclick="showGrafik('akreditasi')"
                        style="
                            background-color: <?= get_setting('jabatanlainnya_akreditasi_button_color', '#059669') ?>;
                            color: <?= get_setting('jabatanlainnya_akreditasi_button_text_color', '#ffffff') ?>;
                            border: none;
                            padding: 8px 16px;
                            border-radius: 8px;
                            font-weight: 600;
                            transition: 0.2s;
                        "
                        onmouseover="this.style.backgroundColor='<?= get_setting('jabatanlainnya_akreditasi_button_hover_color', '#047857') ?>'"
                        onmouseout="this.style.backgroundColor='<?= get_setting('jabatanlainnya_akreditasi_button_color', '#059669') ?>'">
                        <?= esc(get_setting('jabatanlainnya_akreditasi_button_text', $judul_profil)) ?>
                    </button>
                </div>
            </div>
            <div class="h-96">
                <canvas id="grafikCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Ringkasan Jurusan & Prodi -->
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
                    r: { beginAtZero: true, ticks: { stepSize: 1 } }
                },
                plugins: {
                    legend: { display: true, position: 'top' }
                }
            }
        });
    }

    function showGrafik(type) {
        if (type === 'ami') {
            renderChart(grafikAmi, 'Jawaban <?= esc($judul_ami) ?>', 'rgba(37,99,235,1)');
            document.getElementById('grafik-title').innerText = "Grafik <?= esc($judul_ami) ?>";
        } else {
            renderChart(grafikAkreditasi, 'Jawaban <?= esc($judul_profil) ?>', 'rgba(16,185,129,1)');
            document.getElementById('grafik-title').innerText = "Grafik <?= esc($judul_profil) ?>";
        }
    }

    showGrafik('ami');
</script>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?= $this->endSection() ?>

<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<style>
:root {
    --primary-color: #0d47a1; /* Biru Polban */
    --primary-dark: #1e40af;
    --primary-light: #dbeafe;
    --success-color: #10b981;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    --border-radius: 12px;
    --transition: all 0.2s ease-in-out;
}

.dashboard-wrapper {
    padding: 24px;
    background: linear-gradient(135deg, var(--gray-50) 0%, #ffffff 100%);
    min-height: calc(100vh - 60px);
    font-family: 'Inter', sans-serif;
}

.dashboard-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 32px;
    margin-bottom: 32px;
    box-shadow: var(--shadow-lg);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 24px;
}

.dashboard-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    transition: var(--transition);
}
.stat-card:hover { transform: translateY(-2px); }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-bottom: 12px;
}

.stat-value { font-size: 32px; font-weight: 700; color: var(--gray-800); }
.stat-title { font-size: 14px; color: var(--gray-600); font-weight: 500; }

.card-table, .card-chart {
    background: white;
    border-radius: var(--border-radius);
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    margin-bottom: 32px;
}

.card-table h2, .card-chart h2 {
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--gray-800);
}

table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 10px 14px;
    border-bottom: 1px solid var(--gray-200);
    font-size: 14px;
}
th { background: var(--gray-100); font-weight: 600; }
tr:hover td { background: var(--gray-50); }

.chart-wrapper {
    width: 100%;
    max-width: 600px;
    height: 300px;
    margin: 0 auto;
}
.star {
    font-size: 1.3rem;
    color: #ddd;
}
.star.active {
    color: #ffc107;
}
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-header">
        <div class="header-content">
            <div><img src="/images/logo.png" alt="Tracer Study" style="height: 64px;"></div>
            <div>
                <h1 class="dashboard-title"><?= esc($judul_dashboard) ?></h1>
                <p style="color: var(--gray-600);"><?= esc($deskripsi) ?></p>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
            <div class="stat-title"><?= esc($judul_kuesioner) ?></div>
            <div class="stat-value"><?= $totalPerusahaan ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-title">Total Alumni Direlasikan</div>
            <div class="stat-value"><?= count($alumni) ?></div>
        </div>
    </div>

    <!-- Grafik Alumni per Tahun Kelulusan -->
    <div class="card-chart">
        <h2><?= esc($judul_profil) ?></h2>
        <div class="chart-wrapper">
            <canvas id="alumniChart"></canvas>
        </div>
    </div>

    <!-- Tabel Daftar Alumni -->
    <div class="card-table">
        <h2><?= esc($judul_data_alumni) ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Tahun Lulus</th>
                    <th>IPK</th>
                    <th>Kota</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($alumni)): ?>
                    <?php foreach ($alumni as $row): ?>
                        <tr>
                            <td><?= esc($row['nama_lengkap']) ?></td>
                            <td><?= esc($row['nim']) ?></td>
                            <td><?= esc($row['nama_jurusan'] ?? '-') ?></td>
                            <td><?= esc($row['nama_prodi'] ?? '-') ?></td>
                            <td><?= esc($row['tahun_kelulusan'] ?? '-') ?></td>
                            <td><?= esc($row['ipk'] ?? '-') ?></td>
                            <td><?= esc($row['kota'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada alumni yang direlasikan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Rekap Penilaian Alumni -->
    <div class="card-table">
        <h2>🧾 Rekap Penilaian Alumni</h2>
        <?php if (empty($penilaian)): ?>
            <div class="alert alert-warning text-center">Belum ada penilaian yang tersimpan.</div>
        <?php else: ?>
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Alumni</th>
                        <th>NIM</th>
                        <th>Prodi</th>
                        <th>Nilai Rata-rata</th>
                        <th>Catatan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($penilaian as $p): ?>
                        <?php 
                            $rata2 = round(($p['kelengkapan'] + $p['kejelasan'] + $p['konsistensi'] + $p['refleksi']) / 4, 1);
                            $stars = floor($rata2);
                            $half = ($rata2 - $stars >= 0.5);
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($p['nama_lengkap']) ?></td>
                            <td><?= esc($p['nim']) ?></td>
                            <td><?= esc($p['nama_prodi'] ?? '-') ?></td>
                            <td>
                                <div class="text-warning fs-5">
                                    <?= str_repeat('⭐', $stars) ?>
                                    <?= $half ? '⭐' : '' ?>
                                    <?= str_repeat('☆', 5 - $stars - ($half ? 1 : 0)) ?>
                                    <div class="small text-muted"><?= $rata2 ?>/5</div>
                                </div>
                            </td>
                            <td><?= esc($p['catatan']) ?></td>
                            <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartData = <?= json_encode($chartData) ?>;

const labels = chartData.map(item => item.tahun_kelulusan);
const data = chartData.map(item => item.total);

new Chart(document.getElementById('alumniChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Jumlah Alumni per Tahun Kelulusan',
            data: data,
            backgroundColor: '#0d47a1',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

<?= $this->endSection() ?>

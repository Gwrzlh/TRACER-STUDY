<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/respon/grafik.css') ?>" rel="stylesheet">

<div class="grafik-container">
    <div class="grafik-header">
        <div class="header-actions">
            <a href="<?= base_url('admin/respon?' . http_build_query($_GET)) ?>" class="btn-back">
                <svg class="back-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            <button id="exportChart" class="btn-export">
                <svg class="export-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
                Export Grafik (PNG)
            </button>
        </div>
        <h2 class="grafik-title">Grafik Respon Alumni</h2>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form id="filterForm" class="filter-form" method="get">
            <div class="filter-grid">
                <div class="filter-group">
                    <select name="tahun" class="filter-select">
                        <option value="">-- Tahun Kelulusan --</option>
                        <?php foreach ($allYears ?? [] as $y): ?>
                            <option value="<?= $y['tahun_kelulusan'] ?>" <?= ($filters['tahun'] ?? '') == $y['tahun_kelulusan'] ? 'selected' : '' ?>>
                                <?= $y['tahun_kelulusan'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="angkatan" class="filter-select">
                        <option value="">-- Angkatan --</option>
                        <?php foreach ($allAngkatan ?? [] as $a): ?>
                            <option value="<?= $a['angkatan'] ?>" <?= ($filters['angkatan'] ?? '') == $a['angkatan'] ? 'selected' : '' ?>><?= $a['angkatan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="jurusan" class="filter-select">
                        <option value="">-- Jurusan --</option>
                        <?php foreach ($allJurusan ?? [] as $j): ?>
                            <option value="<?= $j['id'] ?>" <?= ($filters['jurusan'] ?? '') == $j['id'] ? 'selected' : '' ?>><?= $j['nama_jurusan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="prodi" class="filter-select">
                        <option value="">-- Prodi --</option>
                        <?php foreach ($allProdi ?? [] as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($filters['prodi'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= $p['nama_prodi'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="status" class="filter-select">
                        <option value="">-- Status --</option>
                        <option value="completed" <?= ($filters['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Sudah</option>
                        <option value="draft" <?= ($filters['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="Belum" <?= ($filters['status'] ?? '') == 'Belum' ? 'selected' : '' ?>>Belum Mengisi</option>
                    </select>
                </div>

                <div class="filter-group">
                    <button type="submit" class="btn-filter">
                        <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Chart Card -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Statistik Respon Alumni</h3>
            <div class="chart-legend">
                <div class="legend-item">
                    <span class="legend-color legend-success"></span>
                    <span class="legend-label">Sudah</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color legend-warning"></span>
                    <span class="legend-label">Draft</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color legend-danger"></span>
                    <span class="legend-label">Belum Mengisi</span>
                </div>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="responChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('responChart').getContext('2d');

    const chartData = {
        labels: <?= json_encode(array_column($summary ?? [], 'nama_prodi')) ?>,
        datasets: [{
                label: 'Sudah',
                data: <?= json_encode(array_column($summary ?? [], 'total_completed')) ?>,
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1
            },
            {
                label: 'Draft',
                data: <?= json_encode(array_column($summary ?? [], 'total_draft')) ?>,
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                borderColor: 'rgba(245, 158, 11, 1)',
                borderWidth: 1
            },
            {
                label: 'Belum Mengisi',
                data: <?= json_encode(array_column($summary ?? [], 'total_belum')) ?>,
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgba(239, 68, 68, 1)',
                borderWidth: 1
            }
        ]
    };

    const responChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                x: {
                    stacked: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(229, 231, 235, 0.5)'
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12
                        }
                    }
                }
            },
            elements: {
                bar: {
                    borderRadius: 4,
                    borderSkipped: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Tombol export PNG
    document.getElementById('exportChart').addEventListener('click', function() {
        const link = document.createElement('a');
        link.href = responChart.toBase64Image('image/png', 1.0);
        link.download = `grafik_respon_${new Date().toISOString().split('T')[0]}.png`;
        link.click();
    });
</script>

<?= $this->endSection() ?>
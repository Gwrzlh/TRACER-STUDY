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
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --transition: all 0.2s ease-in-out;
    }

    .dashboard-wrapper {
        padding: 24px;
        background: linear-gradient(135deg, var(--gray-50) 0%, #ffffff 100%);
        min-height: calc(100vh - 60px);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-header {
        background: #ffffff;
        border-radius: var(--border-radius);
        padding: 32px;
        margin-bottom: 32px;
        color: var(--gray-900);
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .dashboard-logo {
        height: 64px;
        width: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dashboard-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    /* Statistik */
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
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        font-size: 20px;
        color: white;
        background: var(--primary-color);
    }

    .stat-title {
        font-size: 14px;
        color: var(--gray-600);
        font-weight: 500;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--gray-800);
    }

    .stat-percentage {
        font-size: 14px;
        font-weight: 600;
        color: var(--success-color);
        margin-top: 4px;
        margin-bottom: 8px;
    }

    /* User Info Card */
    .user-info-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 24px;
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        text-align: center;
        margin-bottom: 32px;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px auto;
        font-size: 24px;
        color: white;
        font-weight: 700;
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
    }

    .user-name {
        font-size: 20px;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 4px;
    }

    .user-email {
        font-size: 14px;
        color: var(--gray-600);
        margin-bottom: 12px;
    }

    .user-role {
        display: inline-block;
        background: var(--primary-light);
        color: var(--primary-dark);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Tabel & Chart */
    .card-table, .card-chart {
        background: white;
        border-radius: var(--border-radius);
        padding: 24px;
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        margin-bottom: 32px;
    }

    .card-table h2, .card-chart h2 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
        color: var(--gray-800);
        text-align: center;
    }

    .card-chart {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .chart-wrapper {
        max-width: 400px;
        margin: 0 auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid var(--gray-200);
        font-size: 14px;
    }

    th {
        background: var(--gray-100);
        font-weight: 600;
        color: var(--gray-700);
    }

    tr:hover td {
        background: var(--gray-50);
    }
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-container">

        <!-- HEADER -->
        <div class="dashboard-header">
            <div class="header-content">
                <div class="dashboard-logo">
                    <img src="/images/logo.png" alt="Tracer Study" style="height: 60px;">
                </div>
                <div>
                   <h1 class="dashboard-title"><?= esc($judul_dashboard) ?></h1>
                    <p style="color: var(--gray-600);"><?= ($deskripsi) ?></p>
                </div>
            </div>
        </div>

        <!-- User Info Card -->
        <div class="user-info-card">
            <div class="user-avatar">
                <?= strtoupper(substr(session()->get('username'), 0, 2)) ?>
            </div>
            <div class="user-name"><?= session()->get('username') ?></div>
            <div class="user-email"><?= session()->get('email') ?></div>
            <div class="user-role">Role ID: <?= session()->get('role_id') ?></div>
        </div>

        <!-- Statistik Perusahaan -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-building"></i></div>
               <div class="stat-title"><?= esc($judul_kuesioner) ?></div>

                <div class="stat-value"><?= $totalPerusahaan ?></div>
                <div class="stat-percentage">+1.8% dari bulan lalu</div>
            </div>
        </div>

        <!-- Chart -->
        <div class="card-chart">
            <h2><?= esc($judul_profil) ?></h2>
            <div class="chart-wrapper">
                <canvas id="companyChart"></canvas>
            </div>
        </div>

      <!-- Tabel Ringkasan -->
<div class="card-table">
  <h2><?= esc($judul_data_alumni) ?></h2>
    <table id="companyTable">
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
                        <td><?= esc($row['id_jurusan']) ?></td>
                        <td><?= esc($row['id_prodi']) ?></td>
                        <td><?= esc($row['tahun_kelulusan']) ?></td>
                        <td><?= esc($row['ipk']) ?></td>
                        <td><?= esc($row['id_cities']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Belum ada data alumni</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ambil data dari tabel ringkasan
    const bidangCount = {};
    document.querySelectorAll("#companyTable tbody tr").forEach(row => {
        const bidang = row.cells[1].innerText.trim();
        bidangCount[bidang] = (bidangCount[bidang] || 0) + 1;
    });

    const labels = Object.keys(bidangCount);
    const data = Object.values(bidangCount);

    const ctx = document.getElementById('companyChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: ['#0d47a1', '#1e88e5', '#64b5f6', '#bbdefb'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#374151' }
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>

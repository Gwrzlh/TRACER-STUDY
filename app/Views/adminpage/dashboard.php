<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
    .dashboard-wrapper {
        padding: 30px;
        background: #f8fafc;
        min-height: calc(100vh - 50px);
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .welcome-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 35px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        text-align: center;
        margin-bottom: 25px;
    }

    .dashboard-logo {
        display: block;
        margin: 0 auto 20px auto;
        height: 70px;
        width: auto;
    }

    .dashboard-title {
        font-size: 26px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .welcome-text {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 25px;
    }

    .user-info-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        max-width: 400px;
        margin: 0 auto;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        background: #3b82f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
        font-size: 24px;
        color: white;
        font-weight: 600;
    }

    .user-details {
        text-align: center;
    }

    .user-name {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .user-email {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 10px;
    }

    .user-role {
        display: inline-block;
        background: #e0f2fe;
        color: #0369a1;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .stat-indicator {
        width: 4px;
        height: 40px;
        border-radius: 2px;
        margin-bottom: 15px;
    }

    .stat-indicator.blue {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-indicator.green {
        background: linear-gradient(135deg, #10b981, #16a34a);
    }

    .stat-indicator.purple {
        background: linear-gradient(135deg, #8b5cf6, #9333ea);
    }

    .stat-indicator.orange {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-indicator.red {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .stat-indicator.indigo {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
    }

    .stat-indicator.teal {
        background: linear-gradient(135deg, #14b8a6, #0d9488);
    }

    .stat-title {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
    }

    /* Chart Section Styles */
    .charts-section {
        margin-top: 30px;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .chart-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 20px;
        text-align: center;
    }

    .chart-container {
        position: relative;
        height: 350px;
    }

    .chart-container.small {
        height: 280px;
    }

    .bottom-charts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .activity-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 16px;
        color: white;
        font-weight: 600;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .activity-desc {
        font-size: 12px;
        color: #64748b;
    }

    @media (max-width: 768px) {
        .dashboard-wrapper {
            padding: 20px 15px;
        }

        .welcome-card {
            padding: 30px 20px;
        }

        .dashboard-title {
            font-size: 24px;
        }

        .welcome-text {
            font-size: 16px;
        }

        .user-info-card {
            padding: 20px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .bottom-charts {
            grid-template-columns: 1fr;
        }

        .chart-container {
            height: 250px;
        }
    }
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <!-- Logo Polban -->
            <img src="/images/logo.png" alt="Tracer Study" class="dashboard-logo">

            <!-- Welcome Message -->
            <h1 class="dashboard-title">Selamat Datang di Dashboard</h1>
            <p class="welcome-text">Sistem Tracer Study - Monitoring Alumni</p>

            <!-- User Info Card -->
            <div class="user-info-card">
                <div class="user-avatar">
                    <?= strtoupper(substr(session()->get('username'), 0, 2)) ?>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= session()->get('username') ?></div>
                    <div class="user-email"><?= session()->get('email') ?></div>
                    <div class="user-role">Role ID: <?= session()->get('role_id') ?></div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-indicator blue"></div>
                <div class="stat-title">Total Survei</div>
                <div class="stat-value" id="totalSurvei" data-target="<?= $totalSurvei ?>"><?= $totalSurvei ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator purple"></div>
                <div class="stat-title">Response Rate</div>
                <div class="stat-value" id="responseRate" data-target="<?= $responseRate ?>"><?= $responseRate ?>%</div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator green"></div>
                <div class="stat-title">Alumni</div>
                <div class="stat-value" id="totalAlumni" data-target="<?= $totalAlumni ?>"><?= $totalAlumni ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator orange"></div>
                <div class="stat-title">Admin</div>
                <div class="stat-value" id="totalAdmin" data-target="<?= $totalAdmin ?>"><?= $totalAdmin ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator indigo"></div>
                <div class="stat-title">Kaprodi</div>
                <div class="stat-value" id="totalKaprodi" data-target="<?= $totalKaprodi ?>"><?= $totalKaprodi ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator teal"></div>
                <div class="stat-title">Perusahaan</div>
                <div class="stat-value" id="totalPerusahaan" data-target="<?= $totalPerusahaan ?>"><?= $totalPerusahaan ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator red"></div>
                <div class="stat-title">Atasan</div>
                <div class="stat-value" id="totalAtasan" data-target="<?= $totalAtasan ?>"><?= $totalAtasan ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator red"></div>
                <div class="stat-title">Jabatan Lainya</div>
                <div class="stat-value" id="totalAtasan" data-target="<?= $totalAtasan ?>"><?= $totalAtasan ?></div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="charts-grid">
                <!-- Distribusi Pengguna per Role -->
                <div class="chart-card">
                    <h3 class="chart-title">Distribusi Pengguna per Role</h3>
                    <div class="chart-container">
                        <canvas id="userRoleChart"></canvas>
                    </div>
                </div>

                <!-- Status Pekerjaan Alumni -->
                <div class="chart-card">
                    <h3 class="chart-title">Status Pekerjaan Alumni</h3>
                    <div class="chart-container small">
                        <canvas id="statusPekerjaanChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bottom-charts">
                <!-- Tren Response Alumni -->
                <div class="chart-card">
                    <h3 class="chart-title">Tren Response Alumni (6 Bulan)</h3>
                    <div class="chart-container small">
                        <canvas id="responseTrendChart"></canvas>
                    </div>
                </div>

                <!-- Aktivitas Terbaru -->
                <div class="activity-card">
                    <h3 class="chart-title">Aktivitas Terbaru</h3>
                    <div class="activity-list">
                        <?php foreach($recentActivities as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon" style="background: <?= $activity['color'] ?>;"><?= $activity['icon'] ?></div>
                            <div class="activity-content">
                                <div class="activity-title"><?= $activity['title'] ?></div>
                                <div class="activity-desc"><?= $activity['description'] ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari controller
    const userRoleData = <?= json_encode($userRoleData) ?>;
    const statusPekerjaanData = <?= json_encode($statusPekerjaanData) ?>;
    const responseTrendData = <?= json_encode($responseTrendData) ?>;

    // Color palette
    const colors = {
        primary: '#3b82f6',
        secondary: '#10b981', 
        accent: '#8b5cf6',
        warning: '#f59e0b',
        danger: '#ef4444',
        info: '#06b6d4',
        success: '#22c55e',
        indigo: '#6366f1',
        teal: '#14b8a6'
    };

    // Chart.js default configuration
    Chart.defaults.font.family = 'Inter, system-ui, -apple-system, sans-serif';
    Chart.defaults.color = '#64748b';

    // 1. User Role Distribution Chart (Doughnut Chart)
    const userRoleCtx = document.getElementById('userRoleChart').getContext('2d');
    new Chart(userRoleCtx, {
        type: 'doughnut',
        data: {
            labels: userRoleData.labels,
            datasets: [{
                data: userRoleData.data,
                backgroundColor: [
                    colors.secondary,  // Alumni - Green
                    colors.teal,       // Perusahaan - Teal
                    colors.danger,     // Atasan - Red
                    colors.indigo,     // Kaprodi - Indigo
                    colors.warning,    // Admin - Orange
                    colors.accent      // Jabatan Lainnya - Purple
                ],
                borderWidth: 0,
                hoverBorderWidth: 3,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 13
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // 2. Status Pekerjaan Alumni Chart (Doughnut Chart)
    const statusPekerjaanCtx = document.getElementById('statusPekerjaanChart').getContext('2d');
    new Chart(statusPekerjaanCtx, {
        type: 'doughnut',
        data: {
            labels: statusPekerjaanData.labels,
            datasets: [{
                data: statusPekerjaanData.data,
                backgroundColor: [
                    colors.success,    // Bekerja - Green
                    colors.warning,    // Wirausaha - Orange
                    colors.info,       // Melanjutkan Studi - Blue
                    colors.danger      // Mencari Kerja - Red
                ],
                borderWidth: 0,
                hoverBorderWidth: 3,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            }
        }
    });

    // 3. Response Trend Chart (Line Chart)
    const responseTrendCtx = document.getElementById('responseTrendChart').getContext('2d');
    new Chart(responseTrendCtx, {
        type: 'line',
        data: {
            labels: responseTrendData.labels,
            datasets: [{
                label: 'Response Rate',
                data: responseTrendData.data,
                borderColor: colors.primary,
                backgroundColor: colors.primary + '20',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }, {
                label: 'Target',
                data: [75, 75, 75, 75, 75, 75],
                borderColor: colors.warning,
                borderWidth: 2,
                borderDash: [5, 5],
                fill: false,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        },
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: '#e2e8f0'
                    }
                },
                x: {
                    grid: {
                        color: '#e2e8f0'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 8
                }
            }
        }
    });

    // Animation for statistics cards
    function animateValue(elementId, start, end, duration) {
        const obj = document.getElementById(elementId);
        if (!obj) return;
        
        const range = end - start;
        const minTimer = 50;
        let stepTime = Math.abs(Math.floor(duration / range));
        stepTime = Math.max(stepTime, minTimer);
        const startTime = new Date().getTime();
        const endTime = startTime + duration;
        let timer;

        function run() {
            const now = new Date().getTime();
            const remaining = Math.max((endTime - now) / duration, 0);
            const value = Math.round(end - (remaining * range));
            
            if (elementId === 'responseRate') {
                obj.innerHTML = value + '%';
            } else if (elementId === 'totalAlumni' || elementId === 'totalPerusahaan') {
                obj.innerHTML = value.toLocaleString();
            } else {
                obj.innerHTML = value;
            }
            
            if (value == end) {
                clearInterval(timer);
            }
        }

        timer = setInterval(run, stepTime);
        run();
    }

    // Animate statistics on page load - menggunakan data dari controller
    setTimeout(() => {
        const totalSurvei = parseInt(document.getElementById('totalSurvei').dataset.target);
        const responseRate = parseInt(document.getElementById('responseRate').dataset.target);
        const totalAlumni = parseInt(document.getElementById('totalAlumni').dataset.target);
        const totalAdmin = parseInt(document.getElementById('totalAdmin').dataset.target);
        const totalKaprodi = parseInt(document.getElementById('totalKaprodi').dataset.target);
        const totalPerusahaan = parseInt(document.getElementById('totalPerusahaan').dataset.target);
        const totalAtasan = parseInt(document.getElementById('totalAtasan').dataset.target);

        animateValue('totalSurvei', 0, totalSurvei, 2000);
        animateValue('responseRate', 0, responseRate, 2000);
        animateValue('totalAlumni', 0, totalAlumni, 2500);
        animateValue('totalAdmin', 0, totalAdmin, 1500);
        animateValue('totalKaprodi', 0, totalKaprodi, 1800);
        animateValue('totalPerusahaan', 0, totalPerusahaan, 2200);
        animateValue('totalAtasan', 0, totalAtasan, 1800);
    }, 500);
});
</script>

<?= $this->endSection() ?>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
    .dashboard-wrapper {
        padding: 30px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: calc(100vh - 50px);
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .welcome-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        text-align: center;
        margin-bottom: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .welcome-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    }

    .dashboard-logo {
        display: block;
        margin: 0 auto 25px auto;
        height: 80px;
        width: auto;
        transition: transform 0.3s ease;
    }

    .dashboard-logo:hover {
        transform: scale(1.05);
    }

    .dashboard-title {
        font-size: 28px;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 12px;
        text-shadow: 0 2px 4px rgba(30, 64, 175, 0.2);
        position: relative;
    }

    .dashboard-title::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
    }

    /* Fallback untuk browser yang tidak support background-clip */
    @supports not (-webkit-background-clip: text) {
        .dashboard-title::before {
            display: none;
        }
        .dashboard-title {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: #1e40af;
        }
    }

    .welcome-text {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .user-info-card {
        background: rgba(248, 250, 252, 0.9);
        border-radius: 16px;
        padding: 25px;
        max-width: 420px;
        margin: 0 auto;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .user-avatar {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px auto;
        font-size: 28px;
        color: white;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        transition: all 0.3s ease;
    }

    .user-avatar:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4);
    }

    .user-details {
        text-align: center;
    }

    .user-name {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .user-email {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 12px;
    }

    .user-role {
        display: inline-block;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid rgba(30, 64, 175, 0.2);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
    }

    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--indicator-color);
        transition: height 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover::before {
        height: 6px;
    }

    .stat-indicator {
        width: 6px;
        height: 45px;
        border-radius: 3px;
        margin-bottom: 18px;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-indicator {
        width: 8px;
        transform: scale(1.1);
    }

    .stat-indicator.blue {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        --indicator-color: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-card:has(.stat-indicator.blue) {
        --indicator-color: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-indicator.green {
        background: linear-gradient(135deg, #10b981, #16a34a);
    }

    .stat-card:has(.stat-indicator.green) {
        --indicator-color: linear-gradient(135deg, #10b981, #16a34a);
    }

    .stat-indicator.purple {
        background: linear-gradient(135deg, #8b5cf6, #9333ea);
    }

    .stat-card:has(.stat-indicator.purple) {
        --indicator-color: linear-gradient(135deg, #8b5cf6, #9333ea);
    }

    .stat-indicator.orange {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-card:has(.stat-indicator.orange) {
        --indicator-color: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-indicator.red {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .stat-card:has(.stat-indicator.red) {
        --indicator-color: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .stat-indicator.indigo {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
    }

    .stat-card:has(.stat-indicator.indigo) {
        --indicator-color: linear-gradient(135deg, #6366f1, #4f46e5);
    }

    .stat-indicator.teal {
        background: linear-gradient(135deg, #14b8a6, #0d9488);
    }

    .stat-card:has(.stat-indicator.teal) {
        --indicator-color: linear-gradient(135deg, #14b8a6, #0d9488);
    }

    .stat-title {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #1e293b;
    }

    /* Enhanced Chart Section */
    .charts-section {
        margin-top: 35px;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    .chart-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    }

    .chart-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 25px;
        text-align: center;
        position: relative;
        padding-bottom: 15px;
    }

    .chart-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        border-radius: 2px;
    }

    .chart-container {
        position: relative;
        height: 370px;
    }

    .chart-container.small {
        height: 300px;
    }

    .bottom-charts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .activity-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .activity-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid rgba(241, 245, 249, 0.8);
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        padding-left: 10px;
        background: rgba(248, 250, 252, 0.5);
        border-radius: 12px;
        margin: 0 -10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 18px;
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .activity-item:hover .activity-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .activity-desc {
        font-size: 13px;
        color: #64748b;
    }

    /* Enhanced Responsive Design */
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

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .bottom-charts {
            grid-template-columns: 1fr;
        }

        .chart-container {
            height: 280px;
        }

        .chart-container.small {
            height: 250px;
        }
    }

    /* Loading animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .loading {
        animation: pulse 1.5s ease-in-out infinite;
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
                <div class="stat-value">
                    <span id="totalSurvei" data-target="<?= $totalSurvei ?>"><?= $totalSurvei ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator purple"></div>
                <div class="stat-title">Response Rate</div>
                <div class="stat-value">
                    <span id="responseRate" data-target="<?= $responseRate ?>"><?= $responseRate ?>%</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator green"></div>
                <div class="stat-title">Alumni</div>
                <div class="stat-value">
                    <span id="totalAlumni" data-target="<?= $totalAlumni ?>"><?= $totalAlumni ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator orange"></div>
                <div class="stat-title">Admin</div>
                <div class="stat-value">
                    <span id="totalAdmin" data-target="<?= $totalAdmin ?>"><?= $totalAdmin ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator indigo"></div>
                <div class="stat-title">Kaprodi</div>
                <div class="stat-value">
                    <span id="totalKaprodi" data-target="<?= $totalKaprodi ?>"><?= $totalKaprodi ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator teal"></div>
                <div class="stat-title">Perusahaan</div>
                <div class="stat-value">
                    <span id="totalPerusahaan" data-target="<?= $totalPerusahaan ?>"><?= $totalPerusahaan ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator red"></div>
                <div class="stat-title">Atasan</div>
                <div class="stat-value">
                    <span id="totalAtasan" data-target="<?= $totalAtasan ?>"><?= $totalAtasan ?></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-indicator red"></div>
                <div class="stat-title">Jabatan Lainnya</div>
                <div class="stat-value">
                    <span id="totalJabatanLain" data-target="<?= $totalAtasan ?>"><?= $totalAtasan ?></span>
                </div>
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

    // Enhanced color palette
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

    // Enhanced Chart.js configuration
    Chart.defaults.font.family = 'Inter, system-ui, -apple-system, sans-serif';
    Chart.defaults.color = '#64748b';
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(30, 41, 59, 0.95)';
    Chart.defaults.plugins.tooltip.titleColor = '#f8fafc';
    Chart.defaults.plugins.tooltip.bodyColor = '#f8fafc';
    Chart.defaults.plugins.tooltip.cornerRadius = 12;
    Chart.defaults.plugins.tooltip.padding = 12;

    // Create gradient function
    function createGradient(ctx, color1, color2) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    }

    // 1. Enhanced User Role Distribution Chart
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
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 5,
                hoverBorderColor: '#ffffff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            animation: {
                duration: 2000,
                easing: 'easeInOutCubic',
                animateRotate: true,
                animateScale: true
            },
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 13,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed.toLocaleString()} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // 2. Enhanced Status Pekerjaan Alumni Chart
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
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 5,
                hoverBorderColor: '#ffffff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            animation: {
                duration: 2000,
                easing: 'easeInOutCubic',
                delay: 300
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed}%`;
                        }
                    }
                }
            }
        }
    });

    // 3. Enhanced Response Trend Chart
    const responseTrendCtx = document.getElementById('responseTrendChart').getContext('2d');
    const gradient = createGradient(responseTrendCtx, colors.primary + '40', colors.primary + '10');
    
    new Chart(responseTrendCtx, {
        type: 'line',
        data: {
            labels: responseTrendData.labels,
            datasets: [{
                label: 'Response Rate',
                data: responseTrendData.data,
                borderColor: colors.primary,
                backgroundColor: gradient,
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 8,
                pointHoverRadius: 12,
                pointHoverBorderWidth: 4
            }, {
                label: 'Target (75%)',
                data: [75, 75, 75, 75, 75, 75],
                borderColor: colors.warning,
                backgroundColor: 'transparent',
                borderWidth: 3,
                borderDash: [8, 6],
                fill: false,
                pointRadius: 0,
                pointHoverRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2500,
                easing: 'easeInOutCubic',
                delay: 600
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'line',
                        font: {
                            size: 12,
                            weight: '600'
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
                            size: 11,
                            weight: '500'
                        }
                    },
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)',
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(226, 232, 240, 0.3)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        }
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 12,
                    hoverBorderWidth: 4
                }
            }
        }
    });

    // Enhanced animation for statistics cards
    function animateValue(elementId, start, end, duration, suffix = '') {
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
            
            if (suffix === '%') {
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

    // Enhanced card entrance animation
    function animateCards() {
        const cards = document.querySelectorAll('.stat-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // Initialize animations
    setTimeout(() => {
        animateCards();
        
        // Animate statistics with staggered timing
        const totalSurvei = parseInt(document.getElementById('totalSurvei').dataset.target);
        const responseRate = parseInt(document.getElementById('responseRate').dataset.target);
        const totalAlumni = parseInt(document.getElementById('totalAlumni').dataset.target);
        const totalAdmin = parseInt(document.getElementById('totalAdmin').dataset.target);
        const totalKaprodi = parseInt(document.getElementById('totalKaprodi').dataset.target);
        const totalPerusahaan = parseInt(document.getElementById('totalPerusahaan').dataset.target);
        const totalAtasan = parseInt(document.getElementById('totalAtasan').dataset.target);

        // Staggered animation timing
        setTimeout(() => animateValue('totalSurvei', 0, totalSurvei, 2000), 200);
        setTimeout(() => animateValue('responseRate', 0, responseRate, 2000, '%'), 400);
        setTimeout(() => animateValue('totalAlumni', 0, totalAlumni, 2500), 600);
        setTimeout(() => animateValue('totalAdmin', 0, totalAdmin, 1500), 800);
        setTimeout(() => animateValue('totalKaprodi', 0, totalKaprodi, 1800), 1000);
        setTimeout(() => animateValue('totalPerusahaan', 0, totalPerusahaan, 2200), 1200);
        setTimeout(() => animateValue('totalAtasan', 0, totalAtasan, 1800), 1400);
        setTimeout(() => animateValue('totalJabatanLain', 0, totalAtasan, 1800), 1600);
        
    }, 300);

    // Chart hover effects enhancement
    function enhanceChartInteractions() {
        // Add subtle animations when hovering over chart areas
        const chartCards = document.querySelectorAll('.chart-card');
        chartCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px) scale(1.01)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    // Activity items animation
    function animateActivityItems() {
        const activityItems = document.querySelectorAll('.activity-item');
        activityItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                item.style.transition = 'all 0.5s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, (index * 150) + 1000);
        });
    }

    // Initialize all enhancements
    setTimeout(() => {
        enhanceChartInteractions();
        animateActivityItems();
    }, 2000);

    // Add loading shimmer effect for charts
    function addLoadingEffect() {
        const chartContainers = document.querySelectorAll('.chart-container');
        chartContainers.forEach(container => {
            container.classList.add('loading');
            setTimeout(() => {
                container.classList.remove('loading');
            }, 2000);
        });
    }

    addLoadingEffect();
});
</script>

<?= $this->endSection() ?>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-color: #0d47a1; /* Biru Polban */
        --primary-dark: #1e40af;
        --primary-light: #dbeafe;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #06b6d4;
        --purple-color: #8b5cf6;
        --orange-color: #f97316;
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

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .dashboard-wrapper {
        padding: 24px;
        background: linear-gradient(135deg, var(--gray-50) 0%, #ffffff 100%);
        min-height: calc(100vh - 60px);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ====== HEADER SECTION ====== */
    .dashboard-header {
    background: #ffffff; /* Putih polos */
    border-radius: var(--border-radius);
    padding: 32px;
    margin-bottom: 32px;
    color: var(--gray-900); /* Judul teks tetap terlihat */
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
    
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
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
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        backdrop-filter: blur(10px);
    }

    .header-text {
        flex: 1;
    }

    .dashboard-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .dashboard-subtitle {
        font-size: 16px;
        opacity: 0.9;
        font-weight: 400;
    }

    /* ====== TOP CARDS SECTION ====== */
    .top-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    .card {
        background: white;
        border-radius: var(--border-radius);
        padding: 24px;
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .user-info-card {
        text-align: center;
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

    /* Response Rate Card */
    .response-card {
        text-align: center;
        position: relative;
    }

    .response-header {
        margin-bottom: 20px;
    }

    .response-label {
        font-size: 14px;
        color: var(--gray-600);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .response-value {
        font-size: 48px;
        font-weight: 800;
        background: linear-gradient(135deg, var(--success-color), #059669);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
    }

    .chart-container {
        height: 200px;
        position: relative;
    }

    /* ====== STATISTICS GRID ====== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

    .stat-card:nth-child(2)::before { background: var(--success-color); }
    .stat-card:nth-child(3)::before { background: var(--danger-color); }
    .stat-card:nth-child(4)::before { background: var(--purple-color); }
    .stat-card:nth-child(5)::before { background: var(--warning-color); }
    .stat-card:nth-child(6)::before { background: var(--info-color); }
    .stat-card:nth-child(7)::before { background: var(--orange-color); }

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

    .stat-card:nth-child(2) .stat-icon { background: var(--success-color); }
    .stat-card:nth-child(3) .stat-icon { background: var(--danger-color); }
    .stat-card:nth-child(4) .stat-icon { background: var(--purple-color); }
    .stat-card:nth-child(5) .stat-icon { background: var(--warning-color); }
    .stat-card:nth-child(6) .stat-icon { background: var(--info-color); }
    .stat-card:nth-child(7) .stat-icon { background: var(--orange-color); }

    .stat-title {
        font-size: 14px;
        color: var(--gray-600);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--gray-800);
        line-height: 1;
    }

    .stat-trend {
        margin-top: 8px;
        font-size: 12px;
        color: var(--success-color);
        font-weight: 500;
    }

    /* ====== RESPONSIVE DESIGN ====== */
    @media (max-width: 768px) {
        .dashboard-wrapper {
            padding: 16px;
        }

        .top-cards {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }

        .dashboard-title {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }

        .stat-value {
            font-size: 24px;
        }

        .response-value {
            font-size: 36px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .card {
            padding: 20px;
        }
        
        .dashboard-header {
            padding: 24px;
        }
    }

    /* ====== LOADING ANIMATION ====== */
    .loading {
        opacity: 0;
        animation: fadeIn 0.6s ease-in-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Stagger animation for cards */
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
    .stat-card:nth-child(5) { animation-delay: 0.5s; }
    .stat-card:nth-child(6) { animation-delay: 0.6s; }
    .stat-card:nth-child(7) { animation-delay: 0.7s; }
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-container">

        <!-- HEADER SECTION -->
        <div class="dashboard-header loading">
            <div class="header-content">
                <div class="dashboard-logo">
                   <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2" style="height: 60px;">
                </div>
                <div class="header-text">
                    <h1 class="dashboard-title">Dashboard Admin Tracer Study</h1>
                </div>
            </div>
        </div>

        <!-- TOP CARDS -->
        <div class="top-cards">
            <!-- User Info Card -->
            <div class="card user-info-card loading">
                <div class="user-avatar">
                    <?= strtoupper(substr(session()->get('username'), 0, 2)) ?>
                </div>
                <div class="user-name"><?= session()->get('username') ?></div>
                <div class="user-email"><?= session()->get('email') ?></div>
                <div class="user-role">Role ID: <?= session()->get('role_id') ?></div>
            </div>

            <!-- Response Rate Card -->
            <div class="card response-card loading">
                <div class="response-header">
                    <div class="response-label">Response Rate</div>
                    <div class="response-value"><?= $responseRate ?>%</div>
                </div>
                <div class="chart-container">
                    <canvas id="userRoleChart"></canvas>
                </div>
            </div>
        </div>

        <!-- STATISTICS GRID -->
<div class="stats-grid">
    <div class="stat-card loading">
        <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
        <div class="stat-title">Total Survei</div>
        <div class="stat-value"><?= $totalSurvei ?></div>
        <div class="stat-trend">+2.5% dari bulan lalu</div>
    </div>

    <div class="stat-card loading">
        <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-title">Alumni</div>
        <div class="stat-value"><?= $totalAlumni ?></div>
        <div class="stat-trend">+5.2% dari bulan lalu</div>
    </div>

    <div class="stat-card loading">
        <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
        <div class="stat-title">Admin</div>
        <div class="stat-value"><?= $totalAdmin ?></div>
        <div class="stat-trend">Stabil</div>
    </div>

    <div class="stat-card loading">
        <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-title">Kaprodi</div>
        <div class="stat-value"><?= $totalKaprodi ?></div>
        <div class="stat-trend">Stabil</div>
    </div>

    <div class="stat-card loading">
        <div class="stat-icon"><i class="fas fa-building"></i></div>
        <div class="stat-title">Perusahaan</div>
        <div class="stat-value"><?= $totalPerusahaan ?></div>
        <div class="stat-trend">+1.8% dari bulan lalu</div>
    </div>

    <div class="stat-card loading">
        <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
        <div class="stat-title">Atasan</div>
        <div class="stat-value"><?= $totalAtasan ?></div>
        <div class="stat-trend">+3.1% dari bulan lalu</div>
    </div>

    <div class="stat-card loading">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-title">Jabatan Lainnya</div>
        <div class="stat-value"><?= $totalAtasan ?></div>
        <div class="stat-trend">+0.9% dari bulan lalu</div>
    </div>
</div>


<!-- Chart.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation to all cards
        const cards = document.querySelectorAll('.loading');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Chart configuration
        const userRoleData = <?= json_encode($userRoleData) ?>;
        const ctx = document.getElementById('userRoleChart').getContext('2d');

        const gradient1 = ctx.createLinearGradient(0, 0, 0, 200);
        gradient1.addColorStop(0, '#10b981');
        gradient1.addColorStop(1, '#059669');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: userRoleData.labels,
                datasets: [{
                    data: userRoleData.data,
                    backgroundColor: [
                        '#3b82f6',  // Blue
                        '#10b981',  // Green
                        '#ef4444',  // Red
                        '#8b5cf6',  // Purple
                        '#f59e0b',  // Amber
                        '#06b6d4',  // Cyan
                        '#f97316'   // Orange
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 12,
                                family: 'Inter'
                            },
                            padding: 20,
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        cornerRadius: 8,
                        displayColors: true,
                        boxPadding: 3
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Add hover effects to stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>

<?= $this->endSection() ?>

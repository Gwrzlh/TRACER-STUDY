<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="/css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        <?php if (session()->get('foto')): ?>
            <img src="<?= base_url('uploads/foto_admin/' . session()->get('foto')) ?>"
                 alt="Foto Profil"
                 style="width:60px; height:60px; border-radius:50%;">
        <?php else: ?>
            <?= strtoupper(substr(session()->get('username'), 0, 2)) ?>
        <?php endif; ?>
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
                <div class="stat-title">Account</div>
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-title">Alumni</div>
                <div class="stat-value"><?= $totalAlumni ?></div>
            </div>

            <div class="stat-card loading">
                <div class="stat-title">Account</div>
                <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
                <div class="stat-title">Admin</div>
                <div class="stat-value"><?= $totalAdmin ?></div>
            </div>

            <div class="stat-card loading">
                <div class="stat-title">Account</div>
                <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-title">Kaprodi</div>
                <div class="stat-value"><?= $totalKaprodi ?></div>
            </div>

            <div class="stat-card loading">
                <div class="stat-title">Account</div>
                <div class="stat-icon"><i class="fas fa-building"></i></div>
                <div class="stat-title">Perusahaan</div>
                <div class="stat-value"><?= $totalPerusahaan ?></div>
            </div>

            <div class="stat-card loading">
                <div class="stat-title">Account</div>
                <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                <div class="stat-title">Atasan</div>
                <div class="stat-value"><?= $totalAtasan ?></div>
            </div>

            <div class="stat-card loading">
                <div class="stat-title">Account</div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-title">Jabatan Lainnya</div>
                <div class="stat-value"><?= $totalJabatanLainnya ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userRoleData = <?= json_encode($userRoleData) ?>;
    const ctx = document.getElementById('userRoleChart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: userRoleData.labels,
            datasets: [{
                data: userRoleData.data,
                backgroundColor: [
                    '#3b82f6','#10b981','#ef4444',
                    '#8b5cf6','#f59e0b','#06b6d4','#f97316'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true }
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
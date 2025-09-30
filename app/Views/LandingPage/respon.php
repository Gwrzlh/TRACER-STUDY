<?php
$selectedYear = $selectedYear ?? date('Y');
$data         = $data ?? [];
$allYears     = $allYears ?? [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Respon Tracer Study <?= esc($selectedYear) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
        }
        h1, h2, h3 { font-weight: 700; }

        /* Hero persis seperti dummy */
        .hero {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
            padding: 100px 20px 70px;
            text-align: center;
            border-radius: 0 0 40px 40px;
            margin-bottom: 40px;
        }
        .hero h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .hero p {
            font-size: 1.2rem;
            color: #e5e7eb;
            max-width: 700px;
            margin: 0 auto;
        }

        .card-custom {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 30px;
        }
        table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }
        th {
            background: #1e40af;
            color: #fff;
            text-align: center;
        }
        td {
            text-align: center;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?= view('layout/navbar') ?>

<!-- Hero Section -->
<section class="hero animate__animated animate__fadeIn">
    <h1 class="animate__animated animate__fadeInDown">Respon Tracer Study <?= esc($selectedYear) ?></h1>
    <p class="animate__animated animate__fadeInUp animate__delay-1s">Update per <?= date("d F Y"); ?></p>
</section>

<div class="container">

    <!-- Dropdown Tahun -->
    <form method="get" class="mb-4 animate__animated animate__fadeInDown animate__delay-1s">
        <label for="tahun" class="form-label fw-semibold">Pilih Tahun:</label>
        <select id="tahun" name="tahun" class="form-select shadow-sm" onchange="this.form.submit()">
           <?php if (!empty($allYears)): ?>
    <?php foreach ($allYears as $tahun): ?>
        <option value="<?= esc($tahun) ?>" <?= ($tahun == $selectedYear) ? 'selected' : ''; ?>>
            <?= esc($tahun) ?>
        </option>
    <?php endforeach; ?>
<?php else: ?>
    <option value="">(Belum ada data tahun)</option>
<?php endif; ?>

            </select>
    </form>

    <!-- Chart -->
    <div class="card-custom animate__animated animate__fadeInUp animate__delay-2s">
        <h3 class="mb-3">Grafik Respon</h3>
        <canvas id="myChart" style="max-height: 400px;"></canvas>
    </div>

    <script>
        const chartData = <?= json_encode($data); ?>;

        const labels = chartData.map(d => d.prodi);
        const finish = chartData.map(d => d.finish);
        const ongoing = chartData.map(d => d.ongoing);
        const belum = chartData.map(d => d.belum);

        new Chart(document.getElementById('myChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Finish', data: finish, backgroundColor: '#16a34a' },
                    { label: 'Ongoing', data: ongoing, backgroundColor: '#facc15' },
                    { label: 'Belum', data: belum, backgroundColor: '#dc2626' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { stacked: true, ticks: { maxRotation: 45, minRotation: 45 }},
                    y: { stacked: true, beginAtZero: true }
                }
            }
        });
    </script>

    <!-- Tabel -->
    <div class="card-custom animate__animated animate__fadeInUp animate__delay-3s">
        <h3 class="mb-3">Detail Progress Per Prodi</h3>
        <div class="table-responsive">
            <?php if (!empty($data)): ?>
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>PRODI</th>
                            <th>FINISH</th>
                            <th>ONGOING</th>
                            <th>BELUM</th>
                            <th>JUMLAH</th>
                            <th>PERSENTASE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $d): ?>
                            <tr>
                                <td><?= esc($d['prodi']); ?></td>
                                <td><?= esc($d['finish']); ?></td>
                                <td><?= esc($d['ongoing']); ?></td>
                                <td><?= esc($d['belum']); ?></td>
                                <td><?= esc($d['jumlah']); ?></td>
                                <td><?= esc($d['persentase']); ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning">Belum ada data respon untuk tahun ini.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<?= view('layout/footer') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
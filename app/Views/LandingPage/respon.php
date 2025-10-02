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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
        }

        /* Hero */
        .hero {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
            padding: 70px 20px 60px;
            text-align: center;
            border-radius: 0 0 40px 40px;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.1rem;
            color: #e5e7eb;
        }

        /* Konten */
        main {
            padding: 60px 20px;
        }

        .chart-container {
            height: 400px;
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .table-wrapper {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        /* Warna untuk persentase */
        .persentase-low {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .persentase-medium {
            background-color: #fef3c7;
            color: #92400e;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .persentase-high {
            background-color: #d1fae5;
            color: #065f46;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <!-- Hero -->
    <section class="hero animate__animated animate__fadeIn">
        <h1 class="animate__animated animate__fadeInDown">Respon Tracer Study <?= esc($selectedYear) ?></h1>
        <p class="animate__animated animate__fadeInUp animate__delay-1s"><?= date("d F Y"); ?></p>
    </section>

    <!-- Konten -->
    <main>
        <div class="container animate__animated animate__fadeInUp animate__delay-1s">

            <!-- Dropdown Tahun -->
            <form method="get" class="mb-4">
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
            <div class="chart-container mb-5">
                <canvas id="myChart"></canvas>
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
                        datasets: [{
                                label: 'Jumlah Finish',
                                data: finish,
                                backgroundColor: '#22c55e'
                            },
                            {
                                label: 'Jumlah Ongoing',
                                data: ongoing,
                                backgroundColor: '#facc15'
                            },
                            {
                                label: 'Jumlah Belum Memulai',
                                data: belum,
                                backgroundColor: '#ef4444'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>

            <!-- Tabel -->
            <div class="table-wrapper mt-4">
                <h3 class="mb-3">Detail Progress Per Prodi</h3>
                <?php if (!empty($data)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
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
                                        <td><?= esc($d['persentase']) ?>%</td>


                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Belum ada data respon untuk tahun ini.</div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$selectedYear = $selectedYear ?? date('Y');
$data = $data ?? [];
$allYears = $allYears ?? [];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Respon Tracer Study <?= esc($selectedYear) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?= view('layout/navbar') ?>

    <div class="container mt-4">
        <h2>Progress Pengisian Tracer Study Lulusan Tahun <?= esc($selectedYear) ?></h2>
        <p><?= date("d F Y"); ?></p>

        <!-- Dropdown Tahun -->
        <?php if (!empty($allYears)): ?>
            <form method="get" class="mb-3">
                <label for="tahun" class="form-label">Pilih Tahun:</label>
                <select id="tahun" name="tahun" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($allYears as $tahun): ?>
                        <option value="<?= esc($tahun) ?>" <?= ($tahun == $selectedYear) ? 'selected' : ''; ?>>
                            <?= esc($tahun) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php else: ?>
            <p class="text-warning">Belum ada data tahun kelulusan tersedia.</p>
        <?php endif; ?>

        <!-- Chart -->
        <div class="mb-4 bg-white p-3 rounded shadow" style="height:450px;">
            <canvas id="myChart"></canvas>
        </div>

        <script>
            const chartData = <?= json_encode($data); ?>;

            const labels = chartData.map(d => d.prodi);
            const finish = chartData.map(d => d.finish);
            const ongoing = chartData.map(d => d.ongoing);
            const belum = chartData.map(d => d.belum);

            const ctx = document.getElementById('myChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Selesai (Finish)',
                            data: finish,
                            backgroundColor: 'rgba(46, 204, 113, 0.8)',
                            borderRadius: 8
                        },
                        {
                            label: 'Sedang Mengisi (Ongoing)',
                            data: ongoing,
                            backgroundColor: 'rgba(241, 196, 15, 0.8)',
                            borderRadius: 8
                        },
                        {
                            label: 'Belum Memulai',
                            data: belum,
                            backgroundColor: 'rgba(231, 76, 60, 0.8)',
                            borderRadius: 8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Statistik Respon Tracer Study per Prodi',
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            color: '#2c3e50',
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14
                                },
                                color: '#34495e'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#2c3e50',
                            titleColor: '#ecf0f1',
                            bodyColor: '#ecf0f1',
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + ' orang';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        </script>

        <!-- Tabel -->
        <h3 class="mt-4 mb-3">Detail Progress Per Prodi</h3>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
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
                            <td><?= esc($d['prodi']) ?></td>
                            <td><span class="badge bg-success"><?= esc($d['finish']) ?></span></td>
                            <td><span class="badge bg-warning text-dark"><?= esc($d['ongoing']) ?></span></td>
                            <td><span class="badge bg-danger"><?= esc($d['belum']) ?></span></td>
                            <td><?= esc($d['jumlah']) ?></td>
                            <td style="width: 200px;">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" style="width: <?= esc($d['persentase']) ?>%;"
                                        aria-valuenow="<?= esc($d['persentase']) ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= esc($d['persentase']) ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <?= view('layout/footer') ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
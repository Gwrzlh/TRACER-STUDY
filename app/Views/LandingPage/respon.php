<?php
$selectedYear = $selectedYear ?? date('Y');
$data         = $data ?? [];
$allYears     = $allYears ?? [];
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
        <form method="get" class="mb-3">
            <label for="tahun" class="form-label">Pilih Tahun:</label>
            <select id="tahun" name="tahun" class="form-select" onchange="this.form.submit()">
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
        <div class="mb-4" style="height:400px;">
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
                            backgroundColor: 'green'
                        },
                        {
                            label: 'Jumlah Ongoing',
                            data: ongoing,
                            backgroundColor: 'gold'
                        },
                        {
                            label: 'Jumlah Belum Memulai',
                            data: belum,
                            backgroundColor: 'red'
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
        <h3 class="mt-4">Detail Progress Per Prodi</h3>
        <?php if (!empty($data)): ?>
            <table class="table table-bordered table-striped">
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
                            <td><?= esc($d['persentase']); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Belum ada data respon untuk tahun ini.</div>
        <?php endif; ?>
    </div>

    <?= view('layout/footer') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
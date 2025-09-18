<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<link href="<?= base_url('css/kaprodi/akreditasi.css') ?>" rel="stylesheet">

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Hasil AMI</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($pertanyaan)): ?>
                <?php $colors = ['#4CAF50', '#FFC107', '#2196F3', '#FF5722', '#9C27B0', '#00BCD4', '#8BC34A', '#FF9800']; ?>

                <?php foreach ($pertanyaan as $p): ?>
                    <h5 class="fw-bold mb-4"><?= esc($p['teks'] ?? '-') ?></h5>
                    <hr>

                    <div class="row">
                        <div class="col-lg-7 mb-4 mb-lg-0">
                            <table class="table table-bordered align-middle text-center shadow-sm">
                                <thead class="table-warning">
                                    <tr>
                                        <th style="width:5%;">#</th>
                                        <th>Jawaban</th>
                                        <th style="width:15%;">Jumlah</th>
                                        <th style="width:15%;">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php if (!empty($p['jawaban'])): ?>
                                        <?php foreach ($p['jawaban'] as $i => $j): ?>
                                            <tr>
                                                <?php $color = $colors[$i % count($colors)]; ?>
                                                <td><?= $no++ ?></td>
                                                <td class="text-start">
                                                    <span class="legend-box me-2" style="background-color: <?= $color ?>"></span>
                                                    <?= esc($j['opsi'] ?? '-') ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary"><?= $j['jumlah'] ?? 0 ?></span>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('kaprodi/ami/detail/' . urlencode($j['opsi'] ?? '')) ?>"
                                                        class="btn btn-sm btn-detail">Detail</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada jawaban untuk pertanyaan ini.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-5 d-flex justify-content-center align-items-center">
                            <div style="max-width:350px; width:100%;">
                                <canvas id="amiChart<?= $p['id'] ?>"></canvas>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="alert alert-warning text-center">Belum ada pertanyaan untuk AMI.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    <?php if (!empty($pertanyaan)): ?>
        <?php foreach ($pertanyaan as $p): ?>
            const ctx<?= $p['id'] ?> = document.getElementById('amiChart<?= $p['id'] ?>').getContext('2d');
            new Chart(ctx<?= $p['id'] ?>, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode(array_map(fn($j) => $j['opsi'] ?? '-', $p['jawaban'] ?? [])) ?>,
                    datasets: [{
                        data: <?= json_encode(array_map(fn($j) => $j['jumlah'] ?? 0, $p['jawaban'] ?? [])) ?>,
                        backgroundColor: <?= json_encode($colors) ?>,
                        borderColor: "#fff",
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let value = context.raw;
                                    let percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${value} (${percent}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        <?php endforeach; ?>
    <?php endif; ?>
</script>

<?= $this->endSection() ?>
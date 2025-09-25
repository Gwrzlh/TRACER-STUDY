<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<link href="<?= base_url('css/kaprodi/akreditasi/index.css') ?>" rel="stylesheet">

<div class="questionnaire-container">
    <div class="page-wrapper">
        <div class="page-container">
            <h2 class="page-title">Hasil Akreditasi</h2>
            
            <?php if (!empty($pertanyaan)): ?>
                <?php $colors = ['#4CAF50', '#FFC107', '#2196F3', '#FF5722', '#9C27B0', '#00BCD4', '#8BC34A', '#FF9800']; ?>

                <?php foreach ($pertanyaan as $p): ?>
                    <div class="content-card mb-5">
                        <div class="card-header-custom">
                            <h5 class="question-title"><?= esc($p['teks'] ?? '-') ?></h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-lg-7 mb-4 mb-lg-0">
                                    <div class="table-container">
                                        <div class="table-wrapper">
                                            <table class="data-table">
                                                <thead>
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
                                                                <td>
                                                                    <span class="row-number"><?= $no++ ?></span>
                                                                </td>
                                                                <td>
                                                                    <div class="answer-content">
                                                                        <span class="legend-box" style="background-color: <?= $color ?>"></span>
                                                                        <span class="answer-text"><?= esc($j['opsi'] ?? '-') ?></span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span class="count-badge"><?= $j['jumlah'] ?? 0 ?></span>
                                                                </td>
                                                                <td class="action-cell">
                                                                    <a href="<?= base_url('kaprodi/akreditasi/detail/' . urlencode($j['opsi'] ?? '')) ?>"
                                                                        class="action-btn view-btn">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="4" class="empty-row">
                                                                <div class="empty-content">
                                                                    <i class="fas fa-inbox"></i>
                                                                    <p>Belum ada jawaban untuk pertanyaan ini.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5 d-flex justify-content-center align-items-center">
                                    <div class="chart-container">
                                        <canvas id="akreditasiChart<?= $p['id'] ?>"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-content">
                        <i class="fas fa-clipboard-list empty-state-icon"></i>
                        <h3 class="empty-state-title">Belum Ada Pertanyaan</h3>
                        <p class="empty-state-description">Belum ada pertanyaan untuk Akreditasi.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    <?php if (!empty($pertanyaan)): ?>
        <?php foreach ($pertanyaan as $p): ?>
            const ctx<?= $p['id'] ?> = document.getElementById('akreditasiChart<?= $p['id'] ?>').getContext('2d');
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
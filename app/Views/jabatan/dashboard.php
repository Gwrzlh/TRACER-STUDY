<?= $this->extend('layout/sidebar_jabatan') ?>
<?= $this->section('content') ?>

<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-2">📊 Dashboard Jabatan Lainnya</h2>
    <p class="text-gray-600 mb-6">Halo, <span class="font-semibold"><?= esc(session('username')) ?></span> 👋</p>

    <!-- Ringkasan -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- AMI -->
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-2">📑 AMI</h3>
            <p>Total Pertanyaan: <span class="font-bold"><?= $totalPertanyaanAmi ?></span></p>
            <p>Total Jawaban: <span class="font-bold"><?= $totalJawabanAmi ?></span></p>
        </div>

        <!-- Akreditasi -->
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-2">📑 Akreditasi</h3>
            <p>Total Pertanyaan: <span class="font-bold"><?= $totalPertanyaanAkreditasi ?></span></p>
            <p>Total Jawaban: <span class="font-bold"><?= $totalJawabanAkreditasi ?></span></p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="grafik-title" class="text-lg font-semibold">Grafik AMI</h3>
            <div class="space-x-2">
                <button id="btnAmi" onclick="showGrafik('ami')"
                    class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">AMI</button>
                <button id="btnAkreditasi" onclick="showGrafik('akreditasi')"
                    class="px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700">Akreditasi</button>
            </div>
        </div>
        <div class="h-96">
            <canvas id="grafikCanvas"></canvas>
        </div>
    </div>

    <!-- Ringkasan Jurusan & Prodi (Jumlah Alumni & Kaprodi) -->
    <div class="bg-white shadow rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">🏫 Data Jurusan & Prodi (Jumlah)</h3>

        <?php foreach ($dashboardData as $jurusanData): ?>
            <div class="mb-4">
                <h4 class="font-bold text-blue-600 mb-2"><?= esc($jurusanData['jurusan']['nama_jurusan']) ?></h4>

                <?php foreach ($jurusanData['prodis'] as $prodiData): ?>
                    <div class="mb-2 pl-4 border-l-2 border-gray-300 flex items-center justify-between">
                        <span class="font-semibold text-gray-700"><?= esc($prodiData['prodi']['nama_prodi']) ?></span>
                        <div class="flex space-x-4">
                            <span class="bg-green-200 text-green-800 px-2 py-1 rounded">
                                Alumni: <?= count($prodiData['alumni']) ?>
                            </span>
                            <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded">
                                Kaprodi: <?= count($prodiData['kaprodi']) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const grafikAmi = <?= json_encode($grafikAmi) ?>;
    const grafikAkreditasi = <?= json_encode($grafikAkreditasi) ?>;
    let chart;

    function renderChart(dataset, label, color) {
        const ctx = document.getElementById('grafikCanvas').getContext('2d');
        if (chart) chart.destroy();

        chart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: dataset.map(d => d.question_text),
                datasets: [{
                    label: label,
                    data: dataset.map(d => d.total),
                    backgroundColor: color.replace('1)', '0.2)'),
                    borderColor: color,
                    borderWidth: 2,
                    pointBackgroundColor: color
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }

    function showGrafik(type) {
        if (type === 'ami') {
            renderChart(grafikAmi, 'Jawaban AMI', 'rgba(37,99,235,1)');
            document.getElementById('grafik-title').innerText = "Grafik AMI";
        } else {
            renderChart(grafikAkreditasi, 'Jawaban Akreditasi', 'rgba(16,185,129,1)');
            document.getElementById('grafik-title').innerText = "Grafik Akreditasi";
        }
    }

    showGrafik('ami');
</script>

<?= $this->endSection() ?>
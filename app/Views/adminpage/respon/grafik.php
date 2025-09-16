<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?= base_url('admin/respon?' . http_build_query($_GET)) ?>" class="btn btn-secondary">
            &larr; Kembali
        </a>
        <button id="exportChart" class="btn btn-success">Export Grafik (PNG)</button>
    </div>

    <h2>Grafik Respon Alumni</h2>

    <!-- ▼ Filter -->
    <form id="filterForm" class="mb-3 d-flex gap-2" method="get">
        <select name="tahun" class="form-select">
            <option value="">-- Tahun Kelulusan --</option>
            <?php foreach ($allYears ?? [] as $y): ?>
                <option value="<?= $y['tahun_kelulusan'] ?>" <?= ($filters['tahun'] ?? '') == $y['tahun_kelulusan'] ? 'selected' : '' ?>>
                    <?= $y['tahun_kelulusan'] ?>
                </option>
            <?php endforeach; ?>
        </select>


        <select name="angkatan" class="form-select">
            <option value="">-- Angkatan --</option>
            <?php foreach ($allAngkatan ?? [] as $a): ?>
                <option value="<?= $a['angkatan'] ?>" <?= ($filters['angkatan'] ?? '') == $a['angkatan'] ? 'selected' : '' ?>><?= $a['angkatan'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="jurusan" class="form-select">
            <option value="">-- Jurusan --</option>
            <?php foreach ($allJurusan ?? [] as $j): ?>
                <option value="<?= $j['id'] ?>" <?= ($filters['jurusan'] ?? '') == $j['id'] ? 'selected' : '' ?>><?= $j['nama_jurusan'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="prodi" class="form-select">
            <option value="">-- Prodi --</option>
            <?php foreach ($allProdi ?? [] as $p): ?>
                <option value="<?= $p['id'] ?>" <?= ($filters['prodi'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= $p['nama_prodi'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="status" class="form-select">
            <option value="">-- Status --</option>
            <option value="completed" <?= ($filters['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Sudah</option>
            <option value="draft" <?= ($filters['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="Belum" <?= ($filters['status'] ?? '') == 'Belum' ? 'selected' : '' ?>>Belum Mengisi</option>
        </select>

        <button type="submit" class="btn btn-primary">Tampilkan</button>
    </form>

    <!-- 📊 Grafik -->
    <div class="card p-3 mb-4">
        <canvas id="responChart" height="120"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('responChart').getContext('2d');

    const chartData = {
        labels: <?= json_encode(array_column($summary ?? [], 'nama_prodi')) ?>,
        datasets: [{
                label: 'Sudah',
                data: <?= json_encode(array_column($summary ?? [], 'total_completed')) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.7)'
            },
            {
                label: 'Draft',
                data: <?= json_encode(array_column($summary ?? [], 'total_draft')) ?>,
                backgroundColor: 'rgba(255, 205, 86, 0.7)'
            },
            {
                label: 'Belum Mengisi',
                data: <?= json_encode(array_column($summary ?? [], 'total_belum')) ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.7)'
            }
        ]
    };

    const responChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Grafik Respon Alumni'
                }
            },
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });

    // Tombol export PNG
    document.getElementById('exportChart').addEventListener('click', function() {
        const link = document.createElement('a');
        link.href = responChart.toBase64Image();
        link.download = 'grafik_respon.png';
        link.click();
    });
</script>

<?= $this->endSection() ?>
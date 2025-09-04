<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>

<div class="p-6">
    <!-- Header -->
    <h1 class="text-2xl font-bold">Dashboard Kaprodi</h1>
    <p class="mt-2">Halo <?= session()->get('username') ?> (Kaprodi)</p>

    <!-- Card Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-sm font-semibold text-gray-500">Jumlah Alumni</h2>
            <p class="text-2xl font-bold mt-2">1200</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-sm font-semibold text-gray-500">Sudah Isi Tracer</h2>
            <p class="text-2xl font-bold mt-2">800</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-sm font-semibold text-gray-500">Bekerja</h2>
            <p class="text-2xl font-bold mt-2">65%</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-sm font-semibold text-gray-500">Kuliah Lanjut</h2>
            <p class="text-2xl font-bold mt-2">15%</p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-lg font-semibold mb-4">Status Pekerjaan Alumni</h2>
            <canvas id="statusChart"></canvas>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-lg font-semibold mb-4">Kesesuaian Bidang Kerja</h2>
            <canvas id="kesesuaianChart"></canvas>
        </div>

        <!-- Rate Gaji Alumni -->
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-lg font-semibold mb-4">Rate Gaji Alumni</h2>
            <canvas id="gajiChart"></canvas>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-xl shadow p-4 mt-6">
        <h2 class="text-lg font-semibold mb-4">Feedback Alumni</h2>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Aspek</th>
                    <th class="p-2 border">Rating</th>
                    <th class="p-2 border">Komentar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2 border">Kurikulum</td>
                    <td class="p-2 border">4.5</td>
                    <td class="p-2 border">Perlu update teknologi terbaru</td>
                </tr>
                <tr>
                    <td class="p-2 border">Dosen</td>
                    <td class="p-2 border">4.2</td>
                    <td class="p-2 border">Sangat membantu</td>
                </tr>
                <tr>
                    <td class="p-2 border">Fasilitas</td>
                    <td class="p-2 border">3.8</td>
                    <td class="p-2 border">Lab komputer kurang</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Pekerjaan Alumni
    const ctx1 = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Bekerja', 'Kuliah Lanjut', 'Wirausaha', 'Belum Bekerja'],
            datasets: [{
                data: [55, 15, 10, 20],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
            }]
        }
    });

    // Kesesuaian Bidang Kerja
    const ctx2 = document.getElementById('kesesuaianChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Sesuai', 'Tidak Sesuai'],
            datasets: [{
                data: [70, 30],
                backgroundColor: ['#10b981', '#ef4444'],
            }]
        },
        options: {
            indexAxis: 'y'
        }
    });

    // Rate Gaji Alumni
    const ctx3 = document.getElementById('gajiChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['< 3 Juta', '3 - 5 Juta', '5 - 10 Juta', '> 10 Juta'],
            datasets: [{
                label: 'Jumlah Alumni',
                data: [200, 350, 180, 70],
                backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<?= $this->endSection() ?>

<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Selamat Datang, <?= session('username') ?> 🎓</h2>

<div class="alert alert-success">
    Anda login sebagai <b>Alumni dengan Hak Supervisi</b>.
    Anda memiliki akses tambahan dibanding alumni biasa.
</div>

<!-- Statistik singkat -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">Jumlah Kuesioner</h5>
                <p class="display-6">12</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">Supervisi Aktif</h5>
                <p class="display-6">4</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">Alumni Dibimbing</h5>
                <p class="display-6">27</p>
            </div>
        </div>
    </div>
</div>

<!-- Daftar supervisi -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Daftar Alumni dalam Supervisi</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Alumni</th>
                    <th>Angkatan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Budi</td>
                    <td>2021</td>
                    <td><span class="badge bg-success">Aktif</span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Siti</td>
                    <td>2021</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
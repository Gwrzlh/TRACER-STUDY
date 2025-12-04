<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/pengaturan_dashboard.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('tinymce/tinymce.min.js') ?>"></script>

<!-- 🔹 Tabs Navigasi -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link <?= (uri_string() === 'pengaturan-dashboard/dashboard-admin') ? 'active' : '' ?>"
           href="<?= base_url('pengaturan-dashboard/dashboard-admin') ?>">
            Dashboard Admin
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (uri_string() === 'pengaturan-dashboard/dashboard-alumni') ? 'active' : '' ?>"
           href="<?= base_url('pengaturan-dashboard/dashboard-alumni') ?>">
            Dashboard Alumni
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (uri_string() === 'pengaturan-dashboard/dashboard-kaprodi') ? 'active' : '' ?>"
           href="<?= base_url('pengaturan-dashboard/dashboard-kaprodi') ?>">
            Dashboard Kaprodi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (uri_string() === 'pengaturan-dashboard/dashboard-jabatanlainnya') ? 'active' : '' ?>"
           href="<?= base_url('pengaturan-dashboard/dashboard-jabatanlainnya') ?>">
            Dashboard Jabatan Lainnya
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (uri_string() === 'pengaturan-dashboard/dashboard-atasan') ? 'active' : '' ?>"
           href="<?= base_url('pengaturan-dashboard/dashboard-atasan') ?>">
            Dashboard Atasan
        </a>
    </li>
</ul>

<div class="container mt-4">
    <h2 class="mb-3">Pengaturan Dashboard Atasan</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= session()->getFlashdata('success') ?>',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    <?php endif; ?>

    <form action="<?= base_url('pengaturan-dashboard/dashboard-atasan/save') ?>" method="post">
        <input type="hidden" name="id" value="<?= esc($dashboard['id'] ?? '') ?>">

        <!-- Judul Dashboard -->
        <div class="mb-3">
            <label for="judul" class="form-label">Judul Dashboard</label>
            <input type="text" name="judul" class="form-control" id="judul"
                value="<?= esc($dashboard['judul'] ?? 'Dashboard Atasan') ?>" required>
        </div>

        <!-- Teks Sapaan -->
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Teks Sapaan / Subjudul</label>
            <textarea id="deskripsi" name="deskripsi"><?= esc($dashboard['deskripsi'] ?? 'Halo atasan 👋') ?></textarea>
        </div>

        <hr>

        <!-- Card 1: Total Perusahaan -->
        <h5 class="mt-4">Card 1: Total Perusahaan</h5>
        <div class="mb-3">
            <label for="card_1" class="form-label">Judul Card</label>
            <input type="text" name="card_1" class="form-control" id="card_1"
                value="<?= esc($dashboard['judul_kuesioner'] ?? 'Total Perusahaan') ?>" required>
        </div>

        <hr>

        <!-- Card 2: Grafik Pertumbuhan Alumni -->
        <h5 class="mt-4">Card 2: Grafik Pertumbuhan Alumni</h5>
        <div class="mb-3">
            <label for="card_2" class="form-label">Judul Card</label>
            <input type="text" name="card_2" class="form-control" id="card_2"
                value="<?= esc($dashboard['judul_data_alumni'] ?? 'Grafik Pertumbuhan Alumni') ?>" required>
        </div>

        <hr>

        <!-- Card 3: Daftar Alumni Terbaru -->
        <h5 class="mt-4">Card 3: Daftar Alumni Terbaru</h5>
        <div class="mb-3">
            <label for="card_3" class="form-label">Judul Card</label>
            <input type="text" name="card_3" class="form-control" id="card_3"
                value="<?= esc($dashboard['judul_profil'] ?? 'Daftar Alumni Terbaru') ?>" required>
        </div>

        <hr>

        <!-- Card 4 (opsional, bisa digunakan nanti) -->
        <h5 class="mt-4">Card 4 (Opsional)</h5>
        <div class="mb-3">
            <label for="card_4" class="form-label">Judul Card</label>
            <input type="text" name="card_4" class="form-control" id="card_4"
                value="<?= esc($dashboard['judul_ami'] ?? '') ?>">
        </div>

        <hr>

        <!-- Card 5–7 (opsional) -->
        <h5 class="mt-4">Card Tambahan (Opsional)</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="card_5" class="form-label">Card 5</label>
                <input type="text" name="card_5" class="form-control"
                    value="<?= esc($dashboard['card_5'] ?? '') ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label for="card_6" class="form-label">Card 6</label>
                <input type="text" name="card_6" class="form-control"
                    value="<?= esc($dashboard['card_6'] ?? '') ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label for="card_7" class="form-label">Card 7</label>
                <input type="text" name="card_7" class="form-control"
                    value="<?= esc($dashboard['card_7'] ?? '') ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>

<script>
tinymce.init({
    selector: '#deskripsi',
    height: 200,
    menubar: false,
    plugins: 'link image code lists',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | code',
    license_key: 'gpl'
});
</script>

<?= $this->endSection() ?>

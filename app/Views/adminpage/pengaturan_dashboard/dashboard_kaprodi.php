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
    <h2 class="mb-3">Pengaturan Dashboard Kaprodi</h2>

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

    <form action="<?= base_url('pengaturan-dashboard/dashboard-kaprodi/save') ?>" method="post">
        <input type="hidden" name="id" value="<?= esc($dashboard['id'] ?? '') ?>">

        <!-- Judul Dashboard -->
        <div class="mb-3">
            <label for="judul" class="form-label">Judul Dashboard</label>
            <input type="text" name="judul" class="form-control" id="judul"
                value="<?= esc($dashboard['judul'] ?? 'Dashboard Kaprodi') ?>" required>
        </div>

        <!-- Teks Sapaan -->
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Teks Sapaan / Subjudul</label>
            <textarea id="deskripsi" name="deskripsi"><?= esc($dashboard['deskripsi'] ?? 'Halo kaprodi1 (Kaprodi)') ?></textarea>
        </div>

        <hr>

        <!-- Card 1: Kuesioner Aktif -->
        <h5 class="mt-4">Card 1: Kuesioner Aktif</h5>
        <div class="mb-3">
            <label for="judul_kuesioner" class="form-label">Judul Card</label>
            <input type="text" name="judul_kuesioner" class="form-control" id="judul_kuesioner"
                value="<?= esc($dashboard['judul_kuesioner'] ?? 'Jumlah Kuesioner Aktif') ?>" required>
        </div>

        <hr>

        <!-- Card 2: Alumni -->
        <h5 class="mt-4">Card 2: Alumni</h5>
        <div class="mb-3">
            <label for="judul_data_alumni" class="form-label">Judul Card</label>
            <input type="text" name="judul_data_alumni" class="form-control" id="judul_data_alumni"
                value="<?= esc($dashboard['judul_data_alumni'] ?? 'Jumlah Alumni') ?>" required>
        </div>

        <hr>

        <!-- Card 3: Akreditasi -->
        <h5 class="mt-4">Card 3: Akreditasi</h5>
        <div class="mb-3">
            <label for="judul_profil" class="form-label">Judul Card</label>
            <input type="text" name="judul_profil" class="form-control" id="judul_profil"
                value="<?= esc($dashboard['judul_profil'] ?? 'Akreditasi') ?>" required>
        </div>

        <hr>

        <!-- Card 4: AMI -->
        <h5 class="mt-4">Card 4: AMI</h5>
        <div class="mb-3">
            <label for="judul_ami" class="form-label">Judul Card</label>
            <input type="text" name="judul_ami" class="form-control" id="judul_ami"
                value="<?= esc($dashboard['judul_ami'] ?? 'AMI') ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
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

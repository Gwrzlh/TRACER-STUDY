<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/pengaturan_situs.css') ?>">
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
</ul>>
</ul>

<div class="container mt-4">
    <h2 class="mb-3">Pengaturan Dashboard Jabatan Lainnya</h2>

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

    <form action="<?= base_url('pengaturan-dashboard/dashboard-jabatanlainnya/save') ?>" method="post">
        <input type="hidden" name="id" value="<?= esc($dashboard['id'] ?? '') ?>">

        <!-- Judul Dashboard -->
        <div class="mb-3">
            <label class="form-label">Judul Dashboard</label>
            <input type="text" name="judul" class="form-control"
                   value="<?= esc($dashboard['judul'] ?? 'Dashboard Jabatan Lainnya') ?>" required>
        </div>

        <!-- Deskripsi / Sapaan -->
        <div class="mb-3">
            <label class="form-label">Deskripsi / Teks Sapaan</label>
            <textarea id="deskripsi" name="deskripsi"><?= esc($dashboard['deskripsi'] ?? 'Halo jabatan 👋') ?></textarea>
        </div>

        <hr>

        <!-- Card 1 -->
        <h5 class="mt-4">Card 1: AMI</h5>
        <div class="mb-3">
            <label class="form-label">Judul Card</label>
            <input type="text" name="judul_ami" class="form-control"
                   value="<?= esc($dashboard['judul_ami'] ?? 'AMI') ?>">
        </div>

        <hr>

        <!-- Card 2 -->
        <h5 class="mt-4">Card 2: Akreditasi</h5>
        <div class="mb-3">
            <label class="form-label">Judul Card</label>
            <input type="text" name="judul_profil" class="form-control"
                   value="<?= esc($dashboard['judul_profil'] ?? 'Akreditasi') ?>">
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

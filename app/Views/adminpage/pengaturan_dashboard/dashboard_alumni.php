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
    <h2 class="mb-3">Pengaturan Dashboard Alumni</h2>

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

    <form action="<?= base_url('pengaturan-dashboard/dashboard-alumni/save') ?>" method="post">
        <input type="hidden" name="id" value="<?= $dashboard['id'] ?? '' ?>">

        <!-- Judul Utama -->
        <div class="mb-3">
            <label for="judul" class="form-label">Teks Judul Utama</label>
            <input type="text" name="judul" class="form-control" id="judul"
                   value="<?= esc($dashboard['judul'] ?? 'Selamat Datang di Dashboard Alumni') ?>" required>
        </div>

        <!-- Deskripsi / sapaan -->
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Teks Sapaan</label>
            <textarea id="deskripsi" name="deskripsi"><?= $dashboard['deskripsi'] ?? 'Halo, alumni!' ?></textarea>

        </div>

        <hr>

        <!-- Card Profil -->
        <h5 class="mt-4">Teks Card Profil</h5>
        <div class="mb-3">
            <label for="judul_profil" class="form-label">Judul Card</label>
            <input type="text" name="judul_profil" class="form-control" id="judul_profil"
                   value="<?= esc($dashboard['judul_profil'] ?? 'Profil') ?>" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi_profil" class="form-label">Deskripsi Card</label>
            <textarea id="deskripsi_profil" name="deskripsi_profil"><?= $dashboard['deskripsi_profil'] ?? 'Lihat & perbarui data pribadi, kontak, dan riwayat pendidikan.' ?></textarea>

        </div>

        <hr>

        <!-- Card Kuesioner -->
        <h5 class="mt-4">Teks Card Kuesioner</h5>
        <div class="mb-3">
            <label for="judul_kuesioner" class="form-label">Judul Card</label>
            <input type="text" name="judul_kuesioner" class="form-control" id="judul_kuesioner"
                   value="<?= esc($dashboard['judul_kuesioner'] ?? 'Kuesioner') ?>" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi_kuesioner" class="form-label">Deskripsi Card</label>
<textarea id="deskripsi_kuesioner" name="deskripsi_kuesioner"><?= $dashboard['deskripsi_kuesioner'] ?? 'Isi tracer study untuk evaluasi & pengembangan prodi.' ?></textarea>

        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
tinymce.init({
    selector: '#deskripsi, #deskripsi_profil, #deskripsi_kuesioner',
    height: 250,
    menubar: false,
    plugins: 'link image code lists',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | code',
    license_key: 'gpl'
});
</script>

<?= $this->endSection() ?>

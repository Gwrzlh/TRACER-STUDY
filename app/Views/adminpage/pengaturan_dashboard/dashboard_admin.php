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
    <h2 class="mb-3">Pengaturan Dashboard Admin</h2>

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

    <form action="<?= base_url('pengaturan-dashboard/dashboard-admin/save') ?>" method="post">
        <input type="hidden" name="id" value="<?= esc($dashboard['id'] ?? '') ?>">

        <!-- Judul Dashboard -->
        <div class="mb-3">
            <label for="judul" class="form-label">Judul Dashboard</label>
            <input type="text" name="judul" class="form-control" id="judul"
                value="<?= esc($dashboard['judul'] ?? 'Dashboard Admin') ?>" required>
        </div>

        <!-- Teks Sapaan -->
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Teks Sapaan / Subjudul</label>
            <textarea id="deskripsi" name="deskripsi"><?= esc($dashboard['deskripsi'] ?? 'Halo Admin! Selamat datang di sistem tracer study.') ?></textarea>
        </div>

        <hr>

        <!-- CARD 1 -->
        <h5 class="mt-4">Card 1: Total Survei</h5>
        <div class="mb-3">
            <label class="form-label">Judul Card</label>
            <input type="text" name="card_1" class="form-control"
                value="<?= esc($dashboard['judul_kuesioner'] ?? 'Total Survei') ?>">
        </div>

        <hr>

        <!-- CARD 2 -->
        <h5 class="mt-4">Card 2: Alumni</h5>
        <div class="mb-3">
            <label class="form-label">Judul Card</label>
            <input type="text" name="card_2" class="form-control"
                value="<?= esc($dashboard['judul_data_alumni'] ?? 'Alumni') ?>">
        </div>

        <hr>

        <!-- CARD 3 -->
        <h5 class="mt-4">Card 3: Admin</h5>
        <div class="mb-3">
            <label class="form-label">Judul Card</label>
            <input type="text" name="card_3" class="form-control"
                value="<?= esc($dashboard['judul_profil'] ?? 'Admin') ?>">
        </div>

        <hr>

        <!-- CARD 4 -->
        <h5 class="mt-4">Card 4: Kaprodi</h5>
        <div class="mb-3">
            <label class="form-label">Judul Card</label>
            <input type="text" name="card_4" class="form-control"
                value="<?= esc($dashboard['judul_ami'] ?? 'Kaprodi') ?>">
        </div>

        <hr>

        <!-- CARD 5 -->
        <h5 class="mt-4">Card 5: Perusahaan</h5>
        <div class="mb-3">
            <label class="form-label">Judul Card</label>
            <input type="text" name="card_5" class="form-control"
                value="<?= esc($dashboard['card_5'] ?? 'Perusahaan') ?>">
        </div>

        <hr>

        <!-- CARD 6 -->
        <h5 class="mt-4">Card 6: Atasan</h5>
        <div class="mb-3">
            <label class="form-label">Judul Card</label>
            <input type="text" name="card_6" class="form-control"
                value="<?= esc($dashboard['card_6'] ?? 'Atasan') ?>">
        </div>

        <hr>

        <!-- CARD 7 -->
        <h5 class="mt-4">Card 7: Jabatan Lainnya</h5>
        <div class="mb-3">
            <label class="form-label">Judul Card</label>
            <input type="text" name="card_7" class="form-control"
                value="<?= esc($dashboard['card_7'] ?? 'Jabatan Lainnya') ?>">
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

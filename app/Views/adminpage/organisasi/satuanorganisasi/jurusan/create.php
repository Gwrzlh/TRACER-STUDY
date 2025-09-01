<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/tambahjurusan.css') ?>">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <img src="/images/logo.png" alt="Tracer Study" class="logo">
                    <h4 class="mb-0">Tambah Jurusan</h4>
                </div>
                <div class="card-body">
                    <!-- Error Messages -->
                    <?php if (session('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('satuanorganisasi/jurusan/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
                            <input type="text" name="nama_jurusan" id="nama_jurusan" 
                                   class="form-control" required>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-primary-custom">Simpan</button>
                            <a href="<?= base_url('satuanorganisasi') ?>" class="btn-warning-custom">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/editprodi.css') ?>">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <img src="/images/logo.png" alt="Tracer Study" class="logo">
                    <h4 class="mb-0">Edit Jurusan</h4>
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

                    <form action="<?= base_url('satuanorganisasi/jurusan/update/' . $jurusan['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="nama_jurusan" class="form-label">Nama Jurusan:</label>
                            <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan"
                                   value="<?= old('nama_jurusan', $jurusan['nama_jurusan']) ?>" required>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-primary-custom">Update</button>
                            <a href="<?= base_url('satuanorganisasi/jurusan') ?>" class="btn-warning-custom">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

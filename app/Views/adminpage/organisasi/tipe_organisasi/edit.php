<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/edittipe.css') ?>">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <img src="/images/logo.png" alt="Tracer Study" class="logo">
                    <h4 class="mb-0">Edit Tipe Organisasi</h4>
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

                    <form action="<?= base_url('/admin/tipeorganisasi/edit/update/' . $datatpOr['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="nama_tipe" class="form-label">Nama Tipe:</label>
                            <input type="text" class="form-control" id="nama_tipe" name="nama_tipe" 
                                   value="<?= old('nama_tipe', $datatpOr['nama_tipe'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="lavel" class="form-label">Level:</label>
                            <input type="number" class="form-control" id="lavel" name="lavel" 
                                   value="<?= old('lavel', $datatpOr['level'] ?? '') ?>" required>
                            <small class="text-muted">Level organisasi dalam hierarki</small>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi:</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi', $datatpOr['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="group" class="form-label">Group (Role):</label>
                            <select class="form-select" id="group" name="group">
                                <option value="">-- Pilih Role --</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= esc($r['id']) ?>"
                                        <?= old('group', $datatpOr['id_group'] ?? '') == $r['id'] ? 'selected' : '' ?>>
                                        <?= esc($r['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-primary-custom">Simpan</button>
                            <a href="<?= base_url('/admin/tipeorganisasi') ?>" class="btn-warning-custom">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

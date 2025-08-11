<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tipe Organisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-detail {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.2em;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .loading-spinner {
            display: inline-block;
            margin-left: 10px;
        }
        .logo {
            height: 60px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .text-muted {
            font-size: 0.875rem;
        }
        .alert {
            margin-bottom: 20px;
        }
        .alert ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2" style="height: 60px;">
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
                                       value="<?= old('nama_tipe', isset($datatpOr['nama_tipe']) ? $datatpOr['nama_tipe'] : '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="lavel" class="form-label">Level:</label>
                                <input type="number" class="form-control" id="lavel" name="lavel" 
                                       value="<?= old('lavel', isset($datatpOr['level']) ? $datatpOr['level'] : '') ?>" required>
                                <small class="text-muted">Level organisasi dalam hierarki</small>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi:</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi', isset($datatpOr['deskripsi']) ? $datatpOr['deskripsi'] : '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="group" class="form-label">Group (Role):</label>
                                <select class="form-select" id="group" name="group">
                                    <option value="">-- Pilih Role --</option>
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= esc($r['id']) ?>"
                                                <?= old('group', isset($datatpOr['id_group']) ? $datatpOr['id_group'] : '') == $r['id'] ? 'selected' : '' ?>>
                                            <?= esc($r['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn" style="background-color: #001BB7; color: white;">Simpan</button>
                                <a href="<?= base_url('/admin/tipeorganisasi') ?>">
                                    <button type="button" class="btn" style="background-color: orange; color: white;">Batal</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
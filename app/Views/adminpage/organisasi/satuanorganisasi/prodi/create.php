<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Prodi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo {
            height: 60px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
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
                    <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2">
                    <h4 class="mb-0">Tambah Prodi</h4>
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

                    <form action="<?= base_url('satuanorganisasi/prodi/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="nama_prodi" class="form-label">Nama Prodi:</label>
                            <input type="text" class="form-control" id="nama_prodi" name="nama_prodi"
                                   value="<?= old('nama_prodi') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="id_jurusan" class="form-label">Jurusan:</label>
                            <select class="form-select" id="id_jurusan" name="id_jurusan" required>
                                <option value="" disabled selected>-- Pilih Jurusan --</option>
                                <?php foreach ($jurusan as $j): ?>
                                    <option value="<?= $j['id'] ?>" <?= old('id_jurusan') == $j['id'] ? 'selected' : '' ?>>
                                        <?= esc($j['nama_jurusan']) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn" style="background-color: #001BB7; color: white;">Simpan</button>
                            <a href="<?= base_url('satuanorganisasi/prodi') ?>">
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

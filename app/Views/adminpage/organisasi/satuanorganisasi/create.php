<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Satuan Organisasi</title>
    <style>
        /* CSS tetap, tidak diubah */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            height: 50px;
            width: auto;
        }

        .form-title {
            color: #333;
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
        }

        .form-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            font-size: 0.95rem;
        }

        .required::after {
            content: '*';
            color: #dc3545;
            margin-left: 3px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
            transition: border-color 0.2s ease;
            background-color: white;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 10px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 35px;
            appearance: none;
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
            font-family: inherit;
        }

        .form-help {
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-divider {
            border: none;
            border-top: 1px solid #ddd;
            margin: 25px 0;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-start;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: 4px;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 100px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            color: white;
            text-decoration: none;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
            border-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #212529;
            text-decoration: none;
        }

        .btn-icon {
            margin-right: 6px;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .form-container {
                margin: 0;
            }

            .form-header {
                padding: 15px;
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .form-body {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .form-header {
                padding: 12px;
            }

            .form-body {
                padding: 15px;
            }

            .form-title {
                font-size: 1.2rem;
            }
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-valid {
            border-color: #28a745;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="form-header">
            <img src="/images/logo.png" alt="Tracer Study" class="logo">
            <h1 class="form-title">Tambah Satuan Organisasi</h1>
        </div>

        <div class="form-body">
            <form action="/satuanorganisasi/store" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="id_jurusan" class="form-label required">Jurusan</label>
                    <select name="id_jurusan" id="id_jurusan" class="form-control form-select" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <?php foreach ($jurusan as $j): ?>
                            <option value="<?= esc($j['id']) ?>"><?= esc($j['nama_jurusan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_prodi" class="form-label required">Prodi</label>
                    <select name="id_prodi" id="id_prodi" class="form-control form-select" required>
                        <option value="">-- Pilih Prodi --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nama_satuan" class="form-label required">Nama Satuan</label>
                    <input type="text" name="nama_satuan" id="nama_satuan" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="nama_singkatan" class="form-label required">Singkatan</label>
                    <input type="text" name="nama_singkatan" id="nama_singkatan" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="nama_slug" class="form-label required">Slug</label>
                    <input type="text" name="nama_slug" id="nama_slug" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control form-textarea"></textarea>
                </div>

                <div class="form-group">
                    <label for="id_tipe" class="form-label required">Tipe Organisasi</label>
                    <select name="id_tipe" id="id_tipe" class="form-control form-select" required>
                        <option value="">-- Pilih Tipe Organisasi --</option>
                        <?php foreach ($tipe as $t): ?>
                            <option value="<?= esc($t['id']) ?>"><?= esc($t['nama_tipe']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="/satuanorganisasi" class="btn btn-warning">Batal</a>
                </div>
            </form>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $('#nama_satuan').on('input', function() {
                    const nama = $(this).val();
                    const slug = nama.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '');
                    $('#nama_slug').val(slug);
                    let singkatan = '';
                    nama.split(' ').forEach(w => {
                        if (w.length > 0) singkatan += w[0].toUpperCase();
                    });
                    $('#nama_singkatan').val(singkatan);
                });

                $('#id_jurusan').change(function() {
                    const jurusanId = $(this).val();
                    $('#id_prodi').empty().append('<option value="">-- Pilih Prodi --</option>');
                    if (jurusanId) {
                        $.getJSON("<?= base_url('satuanorganisasi/getProdi') ?>/" + jurusanId, function(data) {
                            $.each(data, function(k, v) {
                                $('#id_prodi').append('<option value="' + v.id + '">' + v.nama_prodi + '</option>');
                            });
                        });
                    }
                });
            </script>

</body>

</html>
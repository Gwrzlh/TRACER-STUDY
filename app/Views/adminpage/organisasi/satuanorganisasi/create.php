<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Satuan Organisasi</title>
    <style>
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

        /* Responsive Design */
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

        /* Form validation styles */
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
                <div class="form-group">
                    <label for="nama_satuan" class="form-label required">Nama Satuan</label>
                    <select name="nama_satuan" id="nama_satuan" class="form-control form-select" required>
                        <option value="" disabled selected>-- Pilih Nama Satuan --</option>
                        <option value="Fakultas Teknik">Fakultas Teknik</option>
                        <option value="Fakultas Ekonomi">Fakultas Ekonomi</option>
                        <option value="Fakultas MIPA">Fakultas MIPA</option>
                        <option value="Fakultas Sosial">Fakultas Sosial</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nama_singkatan" class="form-label required">Singkatan</label>
                    <input type="text" name="nama_singkatan" id="nama_singkatan" class="form-control"
                        placeholder="Masukkan singkatan" required>
                </div>

                <div class="form-group">
                    <label for="nama_slug" class="form-label required">Slug</label>
                    <input type="text" name="nama_slug" id="nama_slug" class="form-control"
                        placeholder="Masukkan slug" required>
                    <div class="form-help">Slug digunakan untuk URL (contoh: fakultas-teknik)</div>
                </div>

                <div class="form-group">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control form-textarea" rows="3"
                        placeholder="Masukkan deskripsi satuan organisasi"></textarea>
                </div>

                <div class="form-group">
                    <label for="id_tipe" class="form-label required">Tipe Organisasi</label>
                    <select name="id_tipe" id="id_tipe" class="form-control form-select" required>
                        <option value="" disabled selected>-- Pilih Tipe Organisasi --</option>
                        <?php foreach ($tipe as $t): ?>
                            <option value="<?= esc($t['id']) ?>">
                                <?= esc($t['nama_tipe']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="form-row">
                    <div class="form-group">
                        <label for="urutan" class="form-label">Urutan</label>
                        <input type="number" name="urutan" id="urutan" class="form-control"
                            placeholder="Nomor urutan" min="1">
                        <div class="form-help">Urutan tampilan pada sistem</div>
                    </div>

                    <div class="form-group">
                        <label for="satuan_induk" class="form-label">NIK</label>
                        <input type="text" name="satuan_induk" id="satuan_induk" class="form-control"
                            placeholder="Masukkan NIK">
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <button type="submit" class="btn" style="background-color: #001BB7; border-color: #001BB7; color: white;">Simpan</button>
                    <a href="/satuanorganisasi" class="btn" style="background-color: orange; border-color: orange; color: white;">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto generate slug from nama satuan
        document.getElementById('nama_satuan').addEventListener('change', function() {
            const namaSatuan = this.value;
            const slug = namaSatuan.toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^a-z0-9\-]/g, '');
            document.getElementById('nama_slug').value = slug;
        });

        // Auto generate singkatan
        document.getElementById('nama_satuan').addEventListener('change', function() {
            const namaSatuan = this.value;
            const words = namaSatuan.split(' ');
            let singkatan = '';
            words.forEach(word => {
                if (word.length > 0) {
                    singkatan += word.charAt(0).toUpperCase();
                }
            });
            document.getElementById('nama_singkatan').value = singkatan;
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi!');
            }
        });

        // Clear validation on input
        document.querySelectorAll('.form-control').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid', 'is-valid');
            });
        });
    </script>
</body>

</html>
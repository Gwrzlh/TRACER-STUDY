<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2" style="height: 60px;">
                        <h4 class="mb-0">Edit Pengguna</h4>
                    </div>
                    <div class="card-body">
                        <!-- Display validation errors -->
                        <?php if (session()->has('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Display success/error messages -->
                        <?php if (session()->has('error')): ?>
                            <div class="alert alert-danger"><?= session('error') ?></div>
                        <?php endif; ?>

                        <form action="<?= base_url('/admin/pengguna/update/' . $account['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <!-- Basic User Information -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= old('username', $account['username']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= old('email', $account['email']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" minlength="6">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="" disabled>-- Status --</option>
                                    <option value="Aktif" <?= old('status', $account['status']) == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="Tidak-Aktif" <?= old('status', $account['status']) == 'Tidak-Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="group" class="form-label">Group (Role):</label>
                                <select class="form-select" id="group" name="group" required>
                                    <option value="" disabled>-- Pilih Role --</option>
                                    <?php foreach ($roles as $roleItem): ?>
                                        <option value="<?= esc($roleItem['id']) ?>" 
                                                <?= old('group', $account['id_role']) == $roleItem['id'] ? 'selected' : '' ?>>
                                            <?= esc($roleItem['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <hr>

                            <!-- Form detail untuk ADMIN (Role ID: 2) -->
                            <div id="form-detail-2" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Admin</h5>
                                <div class="mb-3">
                                    <label for="admin_nama_lengkap" class="form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" id="admin_nama_lengkap" name="admin_nama_lengkap"
                                           value="<?= old('admin_nama_lengkap', isset($detail['nama_lengkap']) ? $detail['nama_lengkap'] : '') ?>">
                                </div>
                            </div>

                            <!-- Form detail untuk ALUMNI (Role ID: 1) -->
                            <div id="form-detail-1" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Alumni</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap"
                                               value="<?= old('nama_lengkap', isset($detail['nama_lengkap']) ? $detail['nama_lengkap'] : '') ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="jeniskelamin" class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" name="jeniskelamin" id="jeniskelamin">
                                            <option value="" disabled>-Jenis Kelamin-</option>
                                            <option value="Laki-Laki" <?= old('jeniskelamin', isset($detail['jenisKelamin']) ? $detail['jenisKelamin'] : '') == 'Laki-Laki' ? 'selected' : '' ?>>Laki-Laki</option>
                                            <option value="Perempuan" <?= old('jeniskelamin', isset($detail['jenisKelamin']) ? $detail['jenisKelamin'] : '') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input type="text" class="form-control" name="nim" id="nim"
                                               value="<?= old('nim', isset($detail['nim']) ? $detail['nim'] : '') ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="notlp" class="form-label">No. HP</label>
                                        <input type="text" class="form-control" name="notlp" id="notlp"
                                               value="<?= old('notlp', isset($detail['notlp']) ? $detail['notlp'] : '') ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="ipk" class="form-label">IPK</label>
                                    <input type="number" step="0.01" min="0" max="4" class="form-control" name="ipk" id="ipk"
                                           value="<?= old('ipk', isset($detail['ipk']) ? $detail['ipk'] : '') ?>">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <select class="form-select" name="jurusan" id="jurusan">
                                            <option value="">-- Pilih Jurusan --</option>
                                            <?php foreach ($datajurusan as $jurusan): ?>
                                                <option value="<?= esc($jurusan['id']) ?>"
                                                        <?= old('jurusan', isset($detail['id_jurusan']) ? $detail['id_jurusan'] : '') == $jurusan['id'] ? 'selected' : '' ?>>
                                                    <?= esc($jurusan['nama_jurusan']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="prodi" class="form-label">Program Studi</label>
                                        <select class="form-select" name="prodi" id="prodi">
                                            <option value="">-- Pilih Program Studi --</option>
                                            <?php foreach ($dataProdi as $prodi): ?>
                                                <option value="<?= esc($prodi['id']) ?>"
                                                        <?= old('prodi', isset($detail['id_prodi']) ? $detail['id_prodi'] : '') == $prodi['id'] ? 'selected' : '' ?>>
                                                    <?= esc($prodi['nama_prodi']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="angkatan" class="form-label">Angkatan</label>
                                        <select class="form-select" name="angkatan" id="angkatan">
                                            <option value="">-- Pilih Angkatan --</option>
                                            <?php
                                            $tahunSekarang = date('Y');
                                            $tahunAwal = $tahunSekarang - 10;
                                            $selectedAngkatan = old('angkatan', isset($detail['angkatan']) ? $detail['angkatan'] : '');
                                            for ($tahun = $tahunSekarang; $tahun >= $tahunAwal; $tahun--) {
                                                $selected = ($selectedAngkatan == $tahun) ? 'selected' : '';
                                                echo "<option value=\"$tahun\" $selected>$tahun</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                                        <select class="form-select" name="tahun_lulus" id="tahun_lulus">
                                            <option value="">-- Pilih Tahun Lulus --</option>
                                            <?php
                                            $tahunSekarang = date('Y');
                                            $tahunAwal = $tahunSekarang - 10;
                                            $selectedTahunLulus = old('tahun_lulus', isset($detail['tahun_kelulusan']) ? $detail['tahun_kelulusan'] : '');
                                            for ($tahun = $tahunSekarang; $tahun >= $tahunAwal; $tahun--) {
                                                $selected = ($selectedTahunLulus == $tahun) ? 'selected' : '';
                                                echo "<option value=\"$tahun\" $selected>$tahun</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="province" class="form-label">Provinsi</label>
                                        <select class="form-select" id="province" name="province">
                                            <option value="">-- Pilih Provinsi --</option>
                                            <?php 
                                            $selectedProvince = old('province', isset($detail['id_provinsi']) ? $detail['id_provinsi'] : '');
                                            foreach($provinces as $province): 
                                            ?>
                                                <option value="<?= esc($province['id']) ?>" 
                                                        <?= $selectedProvince == $province['id'] ? 'selected' : '' ?>>
                                                    <?= esc($province['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="kota" class="form-label">Kota/Kabupaten</label>
                                        <select class="form-select" id="kota" name="kota">
                                            <option value="">-- Pilih Kota/Kabupaten --</option>
                                            <?php if (isset($cities) && !empty($cities)): ?>
                                                <?php 
                                                $selectedCity = old('kota', isset($detail['id_cities']) ? $detail['id_cities'] : '');
                                                foreach($cities as $city): 
                                                ?>
                                                    <option value="<?= esc($city['id']) ?>" 
                                                            <?= $selectedCity == $city['id'] ? 'selected' : '' ?>>
                                                        <?= esc($city['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div id="city-loading" class="d-none mt-1">
                                            <small class="text-muted">
                                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                Memuat data kota...
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control" name="kode_pos" id="kode_pos" maxlength="5" 
                                           pattern="\d{5}" placeholder="12345"
                                           value="<?= old('kode_pos', isset($detail['kodepos']) ? $detail['kodepos'] : '') ?>">
                                    <small class="text-muted">5 digit angka</small>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat:</label>
                                    <input type="text" class="form-control" name="alamat" id="alamat"
                                           value="<?= old('alamat', isset($detail['alamat']) ? $detail['alamat'] : '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="alamat2" class="form-label">Alamat 2:</label>
                                    <input type="text" class="form-control" name="alamat2" id="alamat2"
                                           value="<?= old('alamat2', isset($detail['alamat2']) ? $detail['alamat2'] : '') ?>">
                                </div>
                            </div>

                            <!-- Form detail untuk COMPANY (Role ID: 3) -->
                            <div id="form-detail-3" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Company</h5>
                                <div class="mb-3">
                                    <label for="nama_perusahaan" class="form-label">Nama Perusahaan:</label>
                                    <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan"
                                           value="<?= old('nama_perusahaan', isset($detail['nama_perusahaan']) ? $detail['nama_perusahaan'] : '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan:</label>
                                    <textarea class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" rows="3"><?= old('alamat_perusahaan', isset($detail['alamat_perusahaan']) ? $detail['alamat_perusahaan'] : '') ?></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn" style="background-color: #001BB7; color: white;">Update</button>
                                <a href="<?= base_url('/admin/pengguna') ?>">
                                    <button type="button" class="btn" style="background-color: orange; color: white;">Batal</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Current role dari PHP
        var currentRole = '<?= $account['id_role'] ?>';
        
        // Show form detail berdasarkan role saat load
        if (currentRole) {
            showFormDetail(currentRole);
        }

        // Function untuk menampilkan form detail berdasarkan role
        function showFormDetail(roleId) {
            // Sembunyikan semua form detail
            $('.form-detail').hide();
            
            // Reset required attributes
            $('.form-detail input, .form-detail select, .form-detail textarea').prop('required', false);
            
            // Tampilkan form detail yang sesuai
            if (roleId) {
                $('#form-detail-' + roleId).show();
                
                // Set required attributes untuk form yang aktif
                switch(roleId) {
                    case '1': // Alumni
                        $('#nama_lengkap, #nim, #notlp, #jurusan, #prodi').prop('required', true);
                        break;
                    case '2': // Admin
                        $('#admin_nama_lengkap').prop('required', true);
                        break;
                    case '3': // Company
                        $('#nama_perusahaan, #alamat_perusahaan').prop('required', true);
                        break;
                }
            }
        }

        // Handle role change
        $('#group').change(function() {
            var roleId = $(this).val();
            var currentRole = '<?= $account['id_role'] ?>';
            
            showFormDetail(roleId);
            
            // Jika role berubah, bersihkan data detail
            if (roleId != currentRole) {
                $('.form-detail input, .form-detail select, .form-detail textarea').val('');
                
                // Reset dropdown yang dependent
                if (roleId == '1') {
                    $('#kota').html('<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>').prop('disabled', true);
                }
                
                // Show warning
                if (confirm('Mengubah role akan menghapus data detail yang sudah ada. Yakin ingin melanjutkan?')) {
                    // User confirmed, continue
                } else {
                    // User cancelled, revert role selection
                    $(this).val(currentRole);
                    showFormDetail(currentRole);
                }
            }
        });

        // Province change handler (same as create)
        $('#province').change(function() {
            var provinceId = $(this).val();
            var citySelect = $('#kota');
            var cityLoading = $('#city-loading');

            // Reset dropdown kota
            citySelect.html('<option value="">-- Pilih Kota/Kabupaten --</option>');
            citySelect.prop('disabled', true);

            if (provinceId) {
                // Tampilkan loading
                cityLoading.removeClass('d-none');

                // AJAX request untuk mengambil data kota
                $.ajax({
                    url: '<?= base_url("api/cities/province") ?>/' + provinceId,
                    type: 'GET',
                    dataType: 'json',
                    timeout: 10000,
                    success: function(response) {
                        // Sembunyikan loading
                        cityLoading.addClass('d-none');

                        if (response.error) {
                            showAlert('error', response.error);
                            return;
                        }

                        // Populate dropdown kota
                        if (response.length > 0) {
                            var selectedCity = '<?= old("kota", isset($detail["id_cities"]) ? $detail["id_cities"] : "") ?>';
                            $.each(response, function(index, city) {
                                var selected = (selectedCity == city.id) ? 'selected' : '';
                                citySelect.append('<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>');
                            });
                            citySelect.prop('disabled', false);
                        } else {
                            citySelect.html('<option value="">-- Tidak ada kota yang tersedia --</option>');
                            showAlert('warning', 'Tidak ada kota yang tersedia untuk provinsi ini');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Sembunyikan loading
                        cityLoading.addClass('d-none');
                        
                        var errorMsg = 'Terjadi kesalahan saat memuat data kota.';
                        if (status === 'timeout') {
                            errorMsg = 'Koneksi timeout. Silakan coba lagi.';
                        }
                        
                        showAlert('error', errorMsg);
                        console.error('AJAX Error:', error);
                    }
                });
            }
        });

        // Auto-load cities jika ada province yang dipilih saat edit
        var currentProvince = $('#province').val();
        if (currentProvince) {
            $('#province').trigger('change');
        }

        // Form validation (sama seperti create, tapi untuk update)
        $('form').on('submit', function(e) {
            var isValid = true;
            var roleId = $('#group').val();
            
            // Remove previous validation states
            $('.is-invalid').removeClass('is-invalid');
            
            // Validate basic fields
            if (!$('#username').val()) {
                $('#username').addClass('is-invalid');
                isValid = false;
            }
            
            if (!$('#email').val() || !isValidEmail($('#email').val())) {
                $('#email').addClass('is-invalid');
                isValid = false;
            }
            
            // Password validation (optional for edit)
            var password = $('#password').val();
            if (password && password.length < 6) {
                $('#password').addClass('is-invalid');
                isValid = false;
            }
            
            if (!$('#group').val()) {
                $('#group').addClass('is-invalid');
                isValid = false;
            }
            
            // Validate role-specific fields
            if (roleId == '1') { // Alumni
                if (!$('#nama_lengkap').val()) {
                    $('#nama_lengkap').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('#nim').val()) {
                    $('#nim').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('#notlp').val()) {
                    $('#notlp').addClass('is-invalid');
                    isValid = false;
                }
            } else if (roleId == '2') { // Admin
                if (!$('#admin_nama_lengkap').val()) {
                    $('#admin_nama_lengkap').addClass('is-invalid');
                    isValid = false;
                }
            } else if (roleId == '3') { // Company
                if (!$('#nama_perusahaan').val()) {
                    $('#nama_perusahaan').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('#alamat_perusahaan').val()) {
                    $('#alamat_perusahaan').addClass('is-invalid');
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                showAlert('error', 'Harap lengkapi semua field yang wajib diisi dengan benar!');
                $('.is-invalid').first().focus();
            }
        });

        // Helper functions (same as create)
        function isValidEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function showAlert(type, message) {
            $('.alert').not('.alert-danger, .alert-success').remove();
            
            var alertClass = 'alert-' + (type === 'error' ? 'danger' : type);
            var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                           message +
                           '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                           '</div>';
            
            $('.card-body form').prepend(alertHtml);
            
            setTimeout(function() {
                $('.alert').not('.alert-danger, .alert-success').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }

        // Input filters
        $('#kode_pos').on('input', function() {
            $(this).val($(this).val().replace(/[^\d]/g, ''));
        });

        $('#ipk').on('input', function() {
            var val = parseFloat($(this).val());
            if (val < 0) $(this).val(0);
            if (val > 4) $(this).val(4);
        });
    });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
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
                        <h4 class="mb-0">Tambah Pengguna</h4>
                    </div>
                    <div class="card-body">
                        <form action="/admin/pengguna/editPengguna/put/<?= $detail['id'] ?>" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            <?= csrf_field() ?>
                            
                            <!-- Basic User Information -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= $account['username'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $account['email'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" >
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="" disabled <?= ($account['status'] == '') ? 'selected' : '' ?>>-- Status --</option>
                                    <option value="Aktif" <?= ($account['status'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                                    <option value="Tidak-Aktif" <?= ($account['status'] == 'Tidak-Aktif') ? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                            </div>

                           <div class="mb-3">
                            <label for="group" class="form-label">Group (Role):</label>
                            <select class="form-select" id="group" name="group" required>
                                <option value="" disabled>-- Pilih Role --</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= esc($role['id']) ?>"
                                        <?= ($role['id'] == $account['id_role']) ? 'selected' : '' ?>>
                                        <?= esc($role['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                            <hr>

                            <!-- Form detail untuk ADMIN (Role ID: 2) -->
                         
                         <div id="form-detail-2" class="form-detail" style="<?= $account['id_role'] == 2 ? '' : 'display: none;' ?>">
                                <h5 class="mb-3">Detail Admin</h5>
                                <div class="mb-3">
                                    <label for="admin_nama_lengkap" class="form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" id="admin_nama_lengkap" name="admin_nama_lengkap"  value="<?= $detail['nama_lengkap'] ?? '' ?>">
                                </div>
                            </div>
 
                            <!-- Form detail untuk ALUMNI (Role ID: 1) -->
                          
                        <div id="form-detail-2" class="form-detail" style="<?= $account['id_role'] == 1 ? '' : 'display: none;' ?>">
                                <h5 class="mb-3">Detail Alumni</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap"  value="<?= $detail['nama_lengkap'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="jeniskelamin" class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" name="jeniskelamin" id="jeniskelamin">
                                           <option value="" disabled <?= (isset($detail['jenisKelamin']) && $detail['jenisKelamin'] == '') ? 'selected' : '' ?>>-Jenis Kelamin-</option>
                                            <option value="Laki-Laki" <?= (isset($detail['jenisKelamin']) && $detail['jenisKelamin'] == 'Laki-Laki') ? 'selected' : '' ?>>Laki-Laki</option>
                                            <option value="Perempuan" <?= (isset($detail['jenisKelamin']) && $detail['jenisKelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input type="text" class="form-control" name="nim" id="nim"  value="<?= $detail['nim'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="notlp" class="form-label">No. HP</label>
                                        <input type="text" class="form-control" name="notlp" id="notlp"  value="<?= $detail['notlp'] ?? '' ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="ipk" class="form-label">IPK</label>
                                    <input type="number" step="0.01" min="0" max="5" class="form-control" name="ipk" id="ipk"  value="<?= $detail['ipk'] ?? '' ?>">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <select class="form-select" name="jurusan" id="jurusan">
                                            <option value="" disabled>-- Pilih Jurusan --</option>
                                                <?php foreach ($datajurusan as $jurusan): ?>
                                                    <option value="<?= esc($jurusan['id']) ?>"
                                                       <?= (isset($detail['id_jurusan']) && $jurusan['id'] == $detail['id_jurusan']) ? 'selected' : '' ?>>

                                                        <?= esc($jurusan['nama_jurusan']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                     <div class="col-md-6 mb-3">
                                        <label for="prodi" class="form-label">Prodi</label>
                                        <select class="form-select" name="prodi" id="prodi">
                                            <option value="" disabled>-- Pilih Jurusan --</option>
                                                <?php foreach ($dataProdi as $prodi): ?>
                                                    <option value="<?= esc($prodi['id']) ?>"
                                                    <?= (isset($detail['id_prodi']) && $prodi['id'] == $detail['id_prodi']) ? 'selected' : '' ?>>
                                                        <?= esc($prodi['nama_prodi']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                        </select>
                                    </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="angkatan" class="form-label">Angkatan</label>
                                        <select class="form-select" name="angkatan" id="angkatan">
                                            <option value="">-- Pilih Angkatan --</option>
                                            <?php
                                            $tahunSekarang = date('Y');
                                            $tahunAwal = $tahunSekarang - 10;
                                            for ($tahun = $tahunSekarang; $tahun >= $tahunAwal; $tahun--) {
                                                    $selected = (isset($detail['angkatan']) && $detail['angkatan'] == $tahun) ? 'selected' : '';
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
                                           for ($tahun = $tahunSekarang; $tahun >= $tahunAwal; $tahun--) {
                                                    $selected = (isset($detail['tahun_kelulusan']) && $detail['tahun_kelulusan'] == $tahun) ? 'selected' : '';
                                                    echo "<option value=\"$tahun\" $selected>$tahun</option>";
                                                }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="province" class="form-label">Provinsi</label>
                                        <select class="form-select" id="province" name="province">
                                            <option value="">-- Pilih Provinsi --</option>
                                            <?php foreach ($provinces as $pro): ?>
                                                <option value="<?= esc($pro['id']) ?>"
                                                    <?= (isset($detail['id_provinsi']) && $pro['id'] == $detail['id_provinsi']) ? 'selected' : '' ?>>
                                                    <?= esc($pro['name']) ?>
                                                </option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="kota" class="form-label">Kota/Kabupaten</label>
                                        <select class="form-select" id="kota" name="kota" disabled>
                                            <option value="">-- Pilih Provinsi Terlebih Dahulu --</option>
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
                                    <input type="text" class="form-control" name="kode_pos" id="kode_pos" maxlength="5" pattern="\d{5}" placeholder="12345" value="<?= $detail['kodepos'] ?? '' ?>">
                                    <small class="text-muted">5 digit angka</small>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat:</label>
                                    <input type="text" class="form-control" name="alamat" id="alamat" value="<?= $detail['alamat'] ?? '' ?>" >
                                    <!-- <small class="text-muted">Tuliskan Alamat Lengkap</small> -->
                                </div>
                                <div class="mb-3">
                                    <label for="alamat2" class="form-label">Alamat 2:</label>
                                    <input type="text" class="form-control" name="alamat2" id="alamat2" value="<?= $detail['alamat2'] ?? '' ?>"  >
                                    <!-- <small class="text-muted">Tuliskan alamat cadangan anda</small> -->
                                </div>
                              </div>
                            <div class="mt-4">
                                <button type="submit" class="btn" style="background-color: #001BB7; color: white;">Simpan</button>
                               <a href="<?= base_url('/admin/pengguna') ?>"><button type="button" class="btn" style="background-color: orange; color: white;">Batal</button></a>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    var detailProvince = "<?= $detail['id_provinsi'] ?? '' ?>";
    var detailCity = "<?= $detail['id_cities'] ?? '' ?>";
    var detailGroup = "<?= $detail['group'] ?? '' ?>";

    $(document).ready(function() {
        // Function untuk menampilkan form detail berdasarkan role
        $('#group').change(function() {
            var roleId = $(this).val();
            
            // Sembunyikan semua form detail
            $('.form-detail').hide();
            
            // Reset required attributes
            $('.form-detail input, .form-detail select').prop('required', false);
            
            // Tampilkan form detail yang sesuai
            if (roleId) {
                $('#form-detail-' + roleId).show();
                
                // Set required attributes untuk form yang aktif
                if (roleId == '1') { // Alumni
                    $('#nama_lengkap, #nim, #notlp').prop('required', true);
                } else if (roleId == '2') { // Admin
                    $('#admin_nama_lengkap').prop('required', true);
                }
            }
        });

        // Event handler ketika provinsi dipilih
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
                            $.each(response, function(index, city) {
                                citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                            });
                            citySelect.prop('disabled', false);
                            
                            // Restore selected city if exist (untuk old input)
                           var oldCity = '<?= old("kota") ?>';
                            if (oldCity) {
                                citySelect.val(oldCity);
                            } else if (detailCity) {
                                citySelect.val(detailCity);
                            }

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
                        } else if (xhr.status === 404) {
                            errorMsg = 'API endpoint tidak ditemukan. Periksa konfigurasi.';
                        } else if (xhr.status === 500) {
                            errorMsg = 'Terjadi kesalahan server. Silakan hubungi administrator.';
                        }
                        
                        showAlert('error', errorMsg);
                        console.error('AJAX Error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });
                    }
                });
            } else {
                citySelect.html('<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>');
            }
        });

        // Trigger change event jika ada old province value
        var oldProvince = '<?= old("province") ?>';
        if (oldProvince) {
            $('#province').val(oldProvince).trigger('change');
        }if (!oldProvince && detailProvince) {
            $('#province').val(detailProvince).trigger('change');
        }

        // Trigger change event jika ada old group value
        var oldGroup = '<?= old("group") ?>';
        if (oldGroup) {
            $('#group').val(oldGroup).trigger('change');
        }else if(detailGroup){
            $('#group').val(detailGroup).trigger('change');
        }

        // Form validation
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
                
                // Validate IPK if filled
                var ipk = $('#ipk').val();
                if (ipk && (parseFloat(ipk) < 0 || parseFloat(ipk) > 4)) {
                    $('#ipk').addClass('is-invalid');
                    showAlert('error', 'IPK harus antara 0 - 4');
                    isValid = false;
                }
            } else if (roleId == '2') { // Admin
                if (!$('#admin_nama_lengkap').val()) {
                    $('#admin_nama_lengkap').addClass('is-invalid');
                    isValid = false;
                }
            }
            
            // Validate postal code (if filled)
            var postalCode = $('#kode_pos').val().trim();
            if (postalCode && (!/^\d{5}$/.test(postalCode))) {
                $('#kode_pos').addClass('is-invalid');
                showAlert('error', 'Kode pos harus 5 digit angka');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                showAlert('error', 'Harap lengkapi semua field yang wajib diisi dengan benar!');
                
                // Scroll to first invalid field
                $('.is-invalid').first().focus();
                
                // Scroll to top of form
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);
            }
        });

        // Function to validate email
        function isValidEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Function to show alert
        function showAlert(type, message) {
            // Remove existing alerts
            $('.alert').remove();
            
            var alertClass = 'alert-' + (type === 'error' ? 'danger' : type);
            var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                           message +
                           '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                           '</div>';
            
            $('.card-body').prepend(alertHtml);
            
            // Auto dismiss after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }

        // Add input event listeners for real-time validation
        $('#email').on('input', function() {
            if ($(this).val() && !isValidEmail($(this).val())) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        $('#password').on('input', function() {
            if ($(this).val() && $(this).val().length < 6) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        $('#kode_pos').on('input', function() {
            // Only allow numbers
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
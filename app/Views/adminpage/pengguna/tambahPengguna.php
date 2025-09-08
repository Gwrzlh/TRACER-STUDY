<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
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
        /* Base input style */
.form-control, .form-select {
    border-radius: 12px;
    border: 2px solid #ddd;
    padding: 12px 14px;
    font-size: 16px;
    transition: all 0.2s ease-in-out;
    background-color: #fff;
    box-shadow: none;
}

/* Hover state */
.form-control:hover, .form-select:hover {
    border-color: #999;
}

/* Focus state */
.form-control:focus, .form-select:focus {
    border-color: #00b894; /* Green focus */
    box-shadow: 0 0 0 3px rgba(0, 184, 148, 0.15);
}

/* Invalid state */
.is-invalid {
    border-color: #e74c3c !important;
    background-color: #fff0f0;
}

/* Placeholder style */
.form-control::placeholder,
.form-select::placeholder {
    color: #bbb;
}

/* Label style */
.form-label {
    font-weight: 500;
    margin-bottom: 6px;
}

/* Dropdown style */
.form-select option {
    padding: 12px;
}

/* Section headers like "Detail Alumni" */
.form-detail h5 {
    font-size: 18px;
    font-weight: 600;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
    margin-bottom: 20px;
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
                        <form action="<?= base_url('/admin/pengguna/tambahPengguna/post') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <!-- Basic User Information -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="" disabled selected>-- Status --</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak-Aktif">Tidak Aktif</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="group" class="form-label">Group (Role):</label>
                                <select class="form-select" id="group" name="group" required>
                                    <option value="" disabled selected>-- Pilih Role --</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= esc($role['id']) ?>"><?= esc($role['nama']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <hr>

                            <!-- Form detail untuk ADMIN (Role ID: 2) -->

                            <div id="form-detail-2" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Admin</h5>
                                <div class="mb-3">
                                    <label for="admin_nama_lengkap" class="form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" id="admin_nama_lengkap" name="admin_nama_lengkap">
                                </div>
                            </div>

                            <!-- Form detail untuk ALUMNI (Role ID: 1) -->

                            <div id="form-detail-1" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Alumni</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_nama_lengkap" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="alumni_nama_lengkap" id="alumni_nama_lengkap">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_jeniskelamin" class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" name="alumni_jeniskelamin" id="alumni_jeniskelamin">
                                            <option value="" disabled selected>-Jenis Kelamin-</option>
                                            <option value="Laki-Laki">Laki-Laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_nim" class="form-label">NIM</label>
                                        <input type="text" class="form-control" name="alumni_nim" id="alumni_nim">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_notlp" class="form-label">No. HP</label>
                                        <input type="text" class="form-control" name="alumni_notlp" id="alumni_notlp">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="alumni_ipk" class="form-label">IPK</label>
                                    <input type="number" step="0.01" min="0" max="5" class="form-control" name="alumni_ipk" id="alumni_ipk">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_jurusan" class="form-label">Jurusan</label>
                                        <select class="form-select" name="alumni_jurusan" id="alumni_jurusan">
                                            <option value="">-- Pilih Jurusan --</option>
                                            <?php foreach ($datajurusan as $jurusan): ?>
                                                <option value="<?= esc($jurusan['id']) ?>"><?= esc($jurusan['nama_jurusan']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_prodi" class="form-label">Program Studi</label>
                                        <select class="form-select" name="alumni_prodi" id="alumni_prodi">
                                            <option value="">-- Pilih Program Studi --</option>
                                            <?php foreach ($dataProdi as $prodi): ?>
                                                <option value="<?= esc($prodi['id']) ?>"><?= esc($prodi['nama_prodi']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_angkatan" class="form-label">Angkatan</label>
                                        <select class="form-select" name="alumni_angkatan" id="alumni_angkatan">
                                            <option value="">-- Pilih Angkatan --</option>
                                            <?php
                                            $tahunSekarang = date('Y');
                                            $tahunAwal = $tahunSekarang - 10;
                                            for ($tahun = $tahunSekarang; $tahun >= $tahunAwal; $tahun--) {
                                                echo "<option value=\"$tahun\">$tahun</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_tahun_lulus" class="form-label">Tahun Lulus</label>
                                        <select class="form-select" name="alumni_tahun_lulus" id="alumni_tahun_lulus">
                                            <option value="">-- Pilih Tahun Lulus --</option>
                                            <?php
                                            $tahunSekarang = date('Y');
                                            $tahunAwal = $tahunSekarang - 10;
                                            for ($tahun = $tahunSekarang; $tahun >= $tahunAwal; $tahun--) {
                                                echo "<option value=\"$tahun\">$tahun</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_province" class="form-label">Provinsi</label>
                                        <select class="form-select province-select" id="alumni_province" name="alumni_province">
                                            <option value="">-- Pilih Provinsi --</option>
                                            <?php foreach($provinces as $province): ?>
                                                <option value="<?= esc($province['id']) ?>" 
                                                        <?= old('alumni_province') == $province['id'] ? 'selected' : '' ?>>
                                                    <?= esc($province['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_kota" class="form-label">Kota/Kabupaten</label>
                                        <select class="form-select city-select" id="alumni_kota" name="alumni_kota" disabled>
                                            <option value="">-- Pilih Provinsi Terlebih Dahulu --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="alumni_kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control kode-pos-input" name="alumni_kode_pos" id="alumni_kode_pos" maxlength="5" pattern="\d{5}" placeholder="12345">
                                    <small class="text-muted">5 digit angka</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="alumni_alamat" class="form-label">Alamat:</label>
                                    <input type="text" class="form-control" name="alumni_alamat" id="alumni_alamat">
                                    <small class="text-muted">Tuliskan Alamat Lengkap</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="alumni_alamat2" class="form-label">Alamat 2:</label>
                                    <input type="text" class="form-control" name="alumni_alamat2" id="alumni_alamat2">
                                    <small class="text-muted">Tuliskan alamat cadangan anda</small>
                                </div>
                                
                                <div>
                                    <label for="alumni_hak">Hak Supervisi</label>
                                    <input type="checkbox" name="alumni_hak" id="alumni_hak" value="1">
                                </div>
                            </div>

                            <!-- Form detail Kaprodi (Role ID: 6) -->
                            <div id="form-detail-6" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Kaprodi</h5>
                                <div class="mb-3">
                                    <label for="kaprodi_nama_lengkap" class="form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" name="kaprodi_nama_lengkap" id="kaprodi_nama_lengkap">
                                </div>
                                <div class="mb-3">
                                    <label for="kaprodi_jurusan" class="form-label">Jurusan</label>
                                    <select class="form-select" name="kaprodi_jurusan" id="kaprodi_jurusan">
                                        <option value="">-- Pilih Jurusan --</option>
                                        <?php foreach ($datajurusan as $jurusan): ?>
                                            <option value="<?= $jurusan['id'] ?>"><?= $jurusan['nama_jurusan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kaprodi_prodi" class="form-label">Program Studi</label>
                                    <select class="form-select" name="kaprodi_prodi" id="kaprodi_prodi">
                                        <option value="">-- Pilih Program Studi --</option>
                                        <?php foreach ($dataProdi as $prodi): ?>
                                            <option value="<?= $prodi['id'] ?>"><?= $prodi['nama_prodi'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kaprodi_notlp" class="form-label">No.Hp:</label>
                                    <input type="text" class="form-control" name="kaprodi_notlp" id="kaprodi_notlp">
                                </div>
                                <div>
                                    <label for="kaprodi_hak">Hak Supervisi</label>
                                    <input type="checkbox" name="kaprodi_hak" id="kaprodi_hak" value="1">
                                </div>
                            </div>

                            <!-- Form detail Perusahaan (Role ID: 7) -->
                            <div id="form-detail-7" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Perusahaan</h5>
                                <div class="mb-3">
                                    <label for="perusahaan_nama_perusahaan" class="form-label">Nama Perusahaan:</label>
                                    <input type="text" class="form-control" name="perusahaan_nama_perusahaan" id="perusahaan_nama_perusahaan">
                                </div>
                                <div class="row">
                                <div class="col-md-6 mb-3">
                                        <label for="perusahaan_province" class="form-label">Provinsi</label>
                                        <select class="form-select province-select" id="perusahaan_province" name="perusahaan_province">
                                            <option value="">-- Pilih Provinsi --</option>
                                            <?php foreach($provinces as $province): ?>
                                                <option value="<?= esc($province['id']) ?>">
                                                    <?= esc($province['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="perusahaan_kota" class="form-label">Kota/Kabupaten</label>
                                        <select class="form-select city-select" id="perusahaan_kota" name="perusahaan_kota" disabled>
                                            <option value="">-- Pilih Provinsi Terlebih Dahulu --</option>
                                        </select>
                                    </div> 
                                </div>
                                <div class="mb-3">
                                    <label for="perusahaan_alamat1" class="form-label">Alamat Perusahaan 1</label>
                                    <input type="text" class="form-control" name="perusahaan_alamat1" id="perusahaan_alamat1">
                                </div>
                                <div class="mb-3">
                                    <label for="perusahaan_alamat2" class="form-label">Alamat Perusahaan 2</label>
                                    <input type="text" class="form-control" name="perusahaan_alamat2" id="perusahaan_alamat2">
                                </div>
                            <div class="mb-3">
                                    <label for="perusahaan_kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control kode-pos-input" name="perusahaan_kode_pos" id="perusahaan_kode_pos" maxlength="5" pattern="\d{5}" placeholder="12345">
                                    <small class="text-muted">5 digit angka</small>
                                </div>
                                <div class="mb-3">
                                    <label for="perusahaan_notlp" class="form-label">No.Hp</label>
                                    <input type="text" class="form-control" name="perusahaan_notlp" id="perusahaan_notlp">
                                </div>
                            </div>

                            <!-- Form detail Atasan (Role ID: 8) -->
                            <div id="form-detail-8" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Atasan</h5>
                                <div class="mb-3">
                                    <label for="atasan_nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="atasan_nama_lengkap" id="atasan_nama_lengkap">
                                </div>

                                <div class="mb-3">
                                    <label for="atasan_jabatan" class="form-label">Jabatan :</label>
                                    <select class="form-select" name="atasan_jabatan" id="atasan_jabatan">
                                        <option value="">-- Pilih Jabatan --</option>
                                        <?php foreach ($jabatan as $j) : ?>
                                            <option value="<?= esc($j['id']) ?>">
                                                <?= esc($j['jabatan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="atasan_notlp" class="form-label">No.Hp</label>
                                    <input type="text" class="form-control" name="atasan_notlp" id="atasan_notlp">
                                </div>
                            </div>

                            <!-- Form detail Jabatan Lainnya (Role ID: 9) -->
                            <div id="form-detail-9" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Jabatan Lainnya</h5>
                            <div class="mb-3">
                                    <label for="lainnya_nama_lengkap" class="form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" name="lainnya_nama_lengkap" id="lainnya_nama_lengkap">
                                </div>
                                <div class="mb-3">
                                    <label for="lainnya_jabatan" class="form-label">jabatan</label>
                                    <select class="form-select" name="lainnya_jabatan" id="lainnya_jabatan">
                                        <option value="">-- Pilih jabatan --</option>
                                        <?php foreach ($jabatan as $jabatan): ?>
                                            <option value="<?= $jabatan['id'] ?>"><?= $jabatan['jabatan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="lainnya_jurusan" class="form-label">Jurusan</label>
                                    <select class="form-select" name="lainnya_jurusan" id="lainnya_jurusan">
                                        <option value="">-- Pilih Jurusan --</option>
                                        <?php foreach ($datajurusan as $jurusan): ?>
                                            <option value="<?= $jurusan['id'] ?>"><?= $jurusan['nama_jurusan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="lainnya_prodi" class="form-label">Program Studi</label>
                                    <select class="form-select" name="lainnya_prodi" id="lainnya_prodi">
                                        <option value="">-- Pilih Program Studi --</option>
                                        <?php foreach ($dataProdi as $prodi): ?>
                                            <option value="<?= $prodi['id'] ?>"><?= $prodi['nama_prodi'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="lainnya_notlp" class="form-label">No.Hp:</label>
                                    <input type="text" class="form-control" name="lainnya_notlp" id="lainnya_notlp">
                                </div>
                                <div>
                                    <label for="lainnya_hak">Hak Supervisi</label>
                                    <input type="checkbox" name="lainnya_hak" id="lainnya_hak" value="1">
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
    </div>

    <script>
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
                    $('#nama_lengkap, #nim, #alumni_notlp').prop('required', true);
                } else if (roleId == '2') { // Admin
                    $('#admin_nama_lengkap').prop('required', true);
                } else if (roleId == '7') { // Perusahaan
                    $('#nama_perusahaan, #perusahaan_notlp').prop('required', true);
                }
            }
        });

        // Function untuk load data kota berdasarkan provinsi - UNIVERSAL
        function loadCities(provinceId, citySelectId, loadingId) {
            var citySelect = $('#' + citySelectId);
            var cityLoading = $('#' + loadingId);

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
        }

        // Event handler untuk Alumni provinsi
        $('#alumni_province').change(function() {
            var provinceId = $(this).val();
            loadCities(provinceId, 'alumni_kota', 'alumni-city-loading');
        });

        // Event handler untuk Perusahaan provinsi
        $('#perusahaan_province').change(function() {
            var provinceId = $(this).val();
            loadCities(provinceId, 'perusahaan_kota', 'perusahaan-city-loading');
        });

        // Trigger change event jika ada old province value
        var oldProvince = '<?= old("province") ?>';
        if (oldProvince) {
            $('#alumni_province, #perusahaan_province').val(oldProvince).trigger('change');
        }

        // Trigger change event jika ada old group value
        var oldGroup = '<?= old("group") ?>';
        if (oldGroup) {
            $('#group').val(oldGroup).trigger('change');
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
            
            if (!$('#password').val() || $('#password').val().length < 6) {
                $('#password').addClass('is-invalid');
                isValid = false;
            }
            
            if (!$('#group').val()) {
                $('#group').addClass('is-invalid');
                isValid = false;
            }
            
            // Validate role-specific fields
           // GANTI BAGIAN VALIDASI ROLE-SPECIFIC INI:

// Validate role-specific fields
if (roleId == '1') { // Alumni
    if (!$('#alumni_nama_lengkap').val()) {        // ✅ UBAH: dari #nama_lengkap ke #alumni_nama_lengkap
        $('#alumni_nama_lengkap').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#alumni_nim').val()) {                 // ✅ UBAH: dari #nim ke #alumni_nim
        $('#alumni_nim').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#alumni_notlp').val()) {               // ✅ BENAR: sudah sesuai
        $('#alumni_notlp').addClass('is-invalid');
        isValid = false;
    }
    
    // Validate IPK if filled
    var ipk = $('#alumni_ipk').val();              // ✅ UBAH: dari #ipk ke #alumni_ipk
    if (ipk && (parseFloat(ipk) < 0 || parseFloat(ipk) > 4)) {
        $('#alumni_ipk').addClass('is-invalid');   // ✅ UBAH: dari #ipk ke #alumni_ipk
        showAlert('error', 'IPK harus antara 0 - 4');
        isValid = false;
    }
    
    // Validate postal code (if filled)
    var postalCodeAlumni = $('#alumni_kode_pos').val().trim();  // ✅ BENAR: sudah sesuai
    if (postalCodeAlumni && (!/^\d{5}$/.test(postalCodeAlumni))) {
        $('#alumni_kode_pos').addClass('is-invalid');
        showAlert('error', 'Kode pos harus 5 digit angka');
        isValid = false;
    }
} else if (roleId == '2') { // Admin
    if (!$('#admin_nama_lengkap').val()) {         // ✅ BENAR: sudah sesuai
        $('#admin_nama_lengkap').addClass('is-invalid');
        isValid = false;
    }
} else if (roleId == '6') { // Kaprodi - TAMBAH VALIDASI INI
    if (!$('#kaprodi_nama_lengkap').val()) {
        $('#kaprodi_nama_lengkap').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#kaprodi_notlp').val()) {
        $('#kaprodi_notlp').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#kaprodi_jurusan').val()) {
        $('#kaprodi_jurusan').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#kaprodi_prodi').val()) {
        $('#kaprodi_prodi').addClass('is-invalid');
        isValid = false;
    }
} else if (roleId == '7') { // Perusahaan
    if (!$('#perusahaan_nama_perusahaan').val()) { // ✅ UBAH: dari #nama_perusahaan ke #perusahaan_nama_perusahaan
        $('#perusahaan_nama_perusahaan').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#perusahaan_notlp').val()) {           // ✅ BENAR: sudah sesuai
        $('#perusahaan_notlp').addClass('is-invalid');
        isValid = false;
    }
    
    // Validate postal code (if filled)
    var postalCodePerusahaan = $('#perusahaan_kode_pos').val().trim();  // ✅ BENAR: sudah sesuai
    if (postalCodePerusahaan && (!/^\d{5}$/.test(postalCodePerusahaan))) {
        $('#perusahaan_kode_pos').addClass('is-invalid');
        showAlert('error', 'Kode pos harus 5 digit angka');
        isValid = false;
    }
} else if (roleId == '8') { // Atasan - TAMBAH VALIDASI INI
    if (!$('#atasan_nama_lengkap').val()) {
        $('#atasan_nama_lengkap').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#atasan_jabatan').val()) {
        $('#atasan_jabatan').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#atasan_notlp').val()) {
        $('#atasan_notlp').addClass('is-invalid');
        isValid = false;
    }
} else if (roleId == '9') { // Jabatan Lainnya - TAMBAH VALIDASI INI
    if (!$('#lainnya_nama_lengkap').val()) {
        $('#lainnya_nama_lengkap').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#lainnya_jabatan').val()) {
        $('#lainnya_jabatan').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#lainnya_jurusan').val()) {
        $('#lainnya_jurusan').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#lainnya_prodi').val()) {
        $('#lainnya_prodi').addClass('is-invalid');
        isValid = false;
    }
    if (!$('#lainnya_notlp').val()) {
        $('#lainnya_notlp').addClass('is-invalid');
        isValid = false;
    }
}

// JUGA UBAH BAGIAN IPK VALIDATION INI:
$('#alumni_ipk').on('input', function() {         // ✅ UBAH: dari #ipk ke #alumni_ipk
    var val = parseFloat($(this).val());
    if (val < 0) $(this).val(0);
    if (val > 4) $(this).val(4);
});
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

        // Kode pos validation - hanya angka (menggunakan class selector untuk multiple elements)
        $('.kode-pos-input').on('input', function() {
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
<?= $this->endSection() ?>
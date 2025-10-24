<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
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
            <div class="col-md-17">
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
                                        <label for="alumni_nama_lengkap" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="alumni_nama_lengkap" id="alumni_nama_lengkap"
                                               value="<?= old('alumni_nama_lengkap', isset($detail['nama_lengkap']) ? $detail['nama_lengkap'] : '') ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_jeniskelamin" class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" name="alumni_jeniskelamin" id="alumni_jeniskelamin">
                                            <option value="" disabled>-Jenis Kelamin-</option>
                                            <option value="Laki-Laki" <?= old('alumni_jeniskelamin', isset($detail['jenisKelamin']) ? $detail['jenisKelamin'] : '') == 'Laki-Laki' ? 'selected' : '' ?>>Laki-Laki</option>
                                            <option value="Perempuan" <?= old('alumni_jeniskelamin', isset($detail['jenisKelamin']) ? $detail['jenisKelamin'] : '') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_nim" class="form-label">NIM</label>
                                        <input type="text" class="form-control" name="alumni_nim" id="alumni_nim"
                                               value="<?= old('alumni_nim', isset($detail['nim']) ? $detail['nim'] : '') ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_notlp" class="form-label">No. HP</label>
                                        <input type="text" class="form-control" name="alumni_notlp" id="alumni_notlp"
                                               value="<?= old('alumni_notlp', isset($detail['notlp']) ? $detail['notlp'] : '') ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="alumni_ipk" class="form-label">IPK</label>
                                    <input type="number" step="0.01" min="0" max="4" class="form-control" name="alumni_ipk" id="alumni_ipk"
                                           value="<?= old('alumni_ipk', isset($detail['ipk']) ? $detail['ipk'] : '') ?>">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_jurusan" class="form-label">Jurusan</label>
                                        <select class="form-select" name="alumni_jurusan" id="alumni_jurusan">
                                            <option value="">-- Pilih Jurusan --</option>
                                            <?php foreach ($datajurusan as $jurusan): ?>
                                                <option value="<?= esc($jurusan['id']) ?>"
                                                        <?= old('alumni_jurusan', isset($detail['id_jurusan']) ? $detail['id_jurusan'] : '') == $jurusan['id'] ? 'selected' : '' ?>>
                                                    <?= esc($jurusan['nama_jurusan']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="alumni_prodi" class="form-label">Program Studi</label>
                                        <select class="form-select" name="alumni_prodi" id="alumni_prodi">
                                            <option value="">-- Pilih Program Studi --</option>
                                            <?php foreach ($dataProdi as $prodi): ?>
                                                <option value="<?= esc($prodi['id']) ?>"
                                                        <?= old('alumni_prodi', isset($detail['id_prodi']) ? $detail['id_prodi'] : '') == $prodi['id'] ? 'selected' : '' ?>>
                                                    <?= esc($prodi['nama_prodi']) ?>
                                                </option>
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
                                            $selectedAngkatan = old('alumni_angkatan', isset($detail['angkatan']) ? $detail['angkatan'] : '');
                                            for ($tahun = $tahunSekarang; $tahun >= $tahunAwal; $tahun--) {
                                                $selected = ($selectedAngkatan == $tahun) ? 'selected' : '';
                                                echo "<option value=\"$tahun\" $selected>$tahun</option>";
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
                                            $selectedTahunLulus = old('alumni_tahun_lulus', isset($detail['tahun_kelulusan']) ? $detail['tahun_kelulusan'] : '');
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
                                        <label for="alumni_province" class="form-label">Provinsi</label>
                                        <select class="form-select province-select" id="alumni_province" name="alumni_province">
                                            <option value="">-- Pilih Provinsi --</option>
                                            <?php 
                                            $selectedProvince = old('alumni_province', isset($detail['id_provinsi']) ? $detail['id_provinsi'] : '');
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
                                        <label for="alumni_kota" class="form-label">Kota/Kabupaten</label>
                                        <select class="form-select" id="alumni_kota" name="alumni_kota">
                                            <option value="">-- Pilih Kota/Kabupaten --</option>
                                            <?php if (isset($cities) && !empty($cities)): ?>
                                                <?php 
                                                $selectedCity = old('alumni_kota', isset($detail['id_cities']) ? $detail['id_cities'] : '');
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
                                    <label for="alumni_kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control kode-pos-input" name="alumni_kode_pos" id="alumni_kode_pos" maxlength="5" 
                                           pattern="\d{5}" placeholder="12345"
                                           value="<?= old('alumni_kode_pos', isset($detail['kodepos']) ? $detail['kodepos'] : '') ?>">
                                    <small class="text-muted">5 digit angka</small>
                                </div>

                                <div class="mb-3">
                                    <label for="alumni_alamat" class="form-label">Alamat:</label>
                                    <input type="text" class="form-control" name="alumni_alamat" id="alumni_alamat"
                                           value="<?= old('alumni_alamat', isset($detail['alamat']) ? $detail['alamat'] : '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="alumni_alamat2" class="form-label">Alamat 2:</label>
                                    <input type="text" class="form-control" name="alumni_alamat2" id="alumni_alamat2"
                                           value="<?= old('alumni_alamat2', isset($detail['alamat2']) ? $detail['alamat2'] : '') ?>">
                                </div>

                                <div>
                                    <label for="alumni_hak">Hak Supervisi</label>
                                    <input type="checkbox" name="alumni_hak" id="alumni_hak" value="1"
                                        <?= (!empty($account['id_surveyor']) && $account['id_surveyor'] == 1) ? 'checked' : '' ?>>
                                </div>
                            </div>

                            <!-- Form detail Kaprodi (Role ID: 6) -->
                            <div id="form-detail-6" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Kaprodi</h5>
                                <div class="mb-3">
                                    <label for="kaprodi_nama_lengkap" class="form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" name="kaprodi_nama_lengkap" id="kaprodi_nama_lengkap"
                                           value="<?= old('kaprodi_nama_lengkap', isset($detail['nama_lengkap']) ? $detail['nama_lengkap'] : '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="kaprodi_jurusan" class="form-label">Jurusan</label>
                                    <select class="form-select" name="kaprodi_jurusan" id="kaprodi_jurusan">
                                        <option value="">-- Pilih Jurusan --</option>
                                        <?php foreach ($datajurusan as $jurusan): ?>
                                            <option value="<?= $jurusan['id'] ?>"
                                                    <?= old('kaprodi_jurusan', isset($detail['id_jurusan']) ? $detail['id_jurusan'] : '') == $jurusan['id'] ? 'selected' : '' ?>>
                                                <?= $jurusan['nama_jurusan'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kaprodi_prodi" class="form-label">Program Studi</label>
                                    <select class="form-select" name="kaprodi_prodi" id="kaprodi_prodi">
                                        <option value="">-- Pilih Program Studi --</option>
                                        <?php foreach ($dataProdi as $prodi): ?>
                                            <option value="<?= $prodi['id'] ?>"
                                                    <?= old('kaprodi_prodi', isset($detail['id_prodi']) ? $detail['id_prodi'] : '') == $prodi['id'] ? 'selected' : '' ?>>
                                                <?= $prodi['nama_prodi'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kaprodi_notlp" class="form-label">No.Hp:</label>
                                    <input type="text" class="form-control" name="kaprodi_notlp" id="kaprodi_notlp"
                                           value="<?= old('kaprodi_notlp', isset($detail['notlp']) ? $detail['notlp'] : '') ?>">
                                </div>
                                <div>
                                    <label for="kaprodi_hak">Hak Supervisi</label>
                                    <input type="checkbox" name="kaprodi_hak" id="kaprodi_hak" value="1"
                                        <?= (!empty($account['id_surveyor']) && $account['id_surveyor'] == 1) ? 'checked' : '' ?>>
                                </div>
                            </div>

                            <!-- Form detail Perusahaan (Role ID: 7) -->
                            <div id="form-detail-7" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Perusahaan</h5>
                                <div class="mb-3">
                                    <label for="perusahaan_nama_perusahaan" class="form-label">Nama Perusahaan:</label>
                                    <input type="text" class="form-control" name="perusahaan_nama_perusahaan" id="perusahaan_nama_perusahaan"
                                           value="<?= old('perusahaan_nama_perusahaan', isset($detail['nama_perusahaan']) ? $detail['nama_perusahaan'] : '') ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="perusahaan_province" class="form-label">Provinsi</label>
                                        <select class="form-select province-select" id="perusahaan_province" name="perusahaan_province">
                                            <option value="">-- Pilih Provinsi --</option>
                                            <?php foreach($provinces as $province): ?>
                                                <option value="<?= esc($province['id']) ?>"
                                                        <?= old('perusahaan_province', isset($detail['id_provinsi']) ? $detail['id_provinsi'] : '') == $province['id'] ? 'selected' : '' ?>>
                                                    <?= esc($province['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                                <!-- DEBUG: Tambahkan ini sementara -->
<?php 
echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc;'>";
echo "<strong>DEBUG INFO:</strong><br>";
echo "Detail id_kota: " . (isset($detail['id_kota']) ? $detail['id_kota'] : 'NULL') . " (type: " . gettype($detail['id_kota'] ?? null) . ")<br>";
echo "Old value: " . old('perusahaan_kota', isset($detail['id_kota']) ? $detail['id_kota'] : '') . " (type: " . gettype(old('perusahaan_kota', isset($detail['id_kota']) ? $detail['id_kota'] : '')) . ")<br>";

if (isset($kotaPerusahaan) && !empty($kotaPerusahaan)) {
    echo "Sample city ID: " . $kotaPerusahaan[0]['id'] . " (type: " . gettype($kotaPerusahaan[0]['id']) . ")<br>";
    echo "Comparison test: ";
    $selectedCity = old('perusahaan_kota', isset($detail['id_kota']) ? $detail['id_kota'] : '');
    foreach($kotaPerusahaan as $city) {
        if ($city['id'] == '3204') {
            echo "City 3204: " . $city['id'] . " == " . $selectedCity . " ? ";
            echo ($selectedCity == $city['id']) ? 'TRUE' : 'FALSE';
            echo " | strict: " . (($selectedCity === $city['id']) ? 'TRUE' : 'FALSE');
            break;
        }
    }
}
echo "</div>";
?>

                                  <div class="col-md-6 mb-3">
                                        <label for="perusahaan_kota" class="form-label">Kota/Kabupaten</label>
                                        <select class="form-select" id="perusahaan_kota" name="perusahaan_kota">
                                            <option value="">-- Pilih Kota/Kabupaten --</option>
                                            <?php if (isset($kotaPerusahaan) && !empty($kotaPerusahaan)): ?>
                                                <?php 
                                                $selectedCity = old('perusahaan_kota', isset($detail['id_kota']) ? $detail['id_kota'] : '');
                                                foreach($kotaPerusahaan as $city): 
                                                ?>
                                                    <option value="<?= esc($city['id']) ?>" 
                                                            <?= $selectedCity == $city['id'] ? 'selected' : '' ?>>
                                                        <?= esc($city['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                                
                                            <?php endif; ?>
                                        </select>
                                        <div id="kota-loading" class="d-none mt-1">
                                            <small class="text-muted">
                                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                Memuat data kota...
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="perusahaan_alamat1" class="form-label">Alamat Perusahaan 1</label>
                                    <input type="text" class="form-control" name="perusahaan_alamat1" id="perusahaan_alamat1"
                                           value="<?= old('perusahaan_alamat1', isset($detail['alamat1']) ? $detail['alamat1'] : '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="perusahaan_alamat2" class="form-label">Alamat Perusahaan 2</label>
                                    <input type="text" class="form-control" name="perusahaan_alamat2" id="perusahaan_alamat2"
                                           value="<?= old('perusahaan_alamat2', isset($detail['alamat2']) ? $detail['alamat2'] : '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="perusahaan_kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control kode-pos-input" name="perusahaan_kode_pos" id="perusahaan_kode_pos" maxlength="5" pattern="\d{5}" placeholder="12345"
                                           value="<?= old('perusahaan_kode_pos', isset($detail['kodepos']) ? $detail['kodepos'] : '') ?>">
                                    <small class="text-muted">5 digit angka</small>
                                </div>
                                <div class="mb-3">
                                    <label for="perusahaan_notlp" class="form-label">No.Hp</label>
                                    <input type="text" class="form-control" name="perusahaan_notlp" id="perusahaan_notlp"
                                           value="<?= old('perusahaan_notlp', isset($detail['noTlp']) ? $detail['noTlp'] : '') ?>">
                                </div>
                            </div>

                            <!-- Form detail Atasan (Role ID: 8) -->
                            <div id="form-detail-8" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Atasan</h5>
                                <div class="mb-3">
                                    <label for="atasan_nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="atasan_nama_lengkap" id="atasan_nama_lengkap"
                                           value="<?= old('atasan_nama_lengkap', isset($detail['nama_lengkap']) ? $detail['nama_lengkap'] : '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="atasan_jabatan" class="form-label">Jabatan :</label>
                                    <select class="form-select" name="atasan_jabatan" id="atasan_jabatan">
                                        <option value="">-- Pilih Jabatan --</option>
                                        <?php foreach ($jabatan as $j): ?>
                                            <option value="<?= $j['id'] ?>"
                                                    <?= old('atasan_jabatan', isset($detail['id_jabatan']) ? $detail['id_jabatan'] : '') == $j['id'] ? 'selected' : '' ?>>
                                                <?= $j['jabatan'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="atasan_notlp" class="form-label">No.Hp</label>
                                    <input type="text" class="form-control" name="atasan_notlp" id="atasan_notlp"
                                           value="<?= old('atasan_notlp', isset($detail['notlp']) ? $detail['notlp'] : '') ?>">
                                </div>
                                <div>
                                    <label for="perusahaan_atasan" class="form-label">Perusahaan:</label>
                                    <select name="perusahaan_atasan" id="perusahaan_atasan" class="form-select">
                                        <option value="">-- Pilih Perusahaan --</option>
                                        <?php foreach ($perusahaanList as $perusahaan): ?>
                                            <option value="<?= $perusahaan['id'] ?>"
                                                    <?= old('perusahaan_atasan', isset($detail['id_perusahaan']) ? $detail['id_perusahaan'] : '') == $perusahaan['id'] ? 'selected' : '' ?>>
                                                <?= $perusahaan['nama_perusahaan'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>    
                                </div>
                            </div>

                            <!-- Form detail Jabatan Lainnya (Role ID: 9) -->
                            <div id="form-detail-9" class="form-detail" style="display: none;">
                                <h5 class="mb-3">Detail Jabatan Lainnya</h5>
                                <div class="mb-3">
                                    <label for="lainnya_nama_lengkap" class="form-label">Nama Lengkap:</label>
                                    <input type="text" class="form-control" name="lainnya_nama_lengkap" id="lainnya_nama_lengkap"
                                           value="<?= old('lainnya_nama_lengkap', isset($detail['nama_lengkap']) ? $detail['nama_lengkap'] : '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="lainnya_jabatan" class="form-label">jabatan</label>
                                    <select class="form-select" name="lainnya_jabatan" id="lainnya_jabatan">
                                        <option value="">-- Pilih jabatan --</option>
                                        <?php if(isset($jabatan)): ?>
                                            <?php foreach ($jabatan as $jabatan_item): ?>
                                                <option value="<?= $jabatan_item['id'] ?>"
                                                        <?= old('lainnya_jabatan', isset($detail['id_jabatan']) ? $detail['id_jabatan'] : '') == $jabatan_item['id'] ? 'selected' : '' ?>>
                                                    <?= $jabatan_item['jabatan'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="lainnya_jurusan" class="form-label">Jurusan</label>
                                    <select class="form-select" name="lainnya_jurusan" id="lainnya_jurusan">
                                        <option value="">-- Pilih Jurusan --</option>
                                        <?php foreach ($datajurusan as $jurusan): ?>
                                            <option value="<?= $jurusan['id'] ?>"
                                                    <?= old('lainnya_jurusan', isset($detail['id_jurusan']) ? $detail['id_jurusan'] : '') == $jurusan['id'] ? 'selected' : '' ?>>
                                                <?= $jurusan['nama_jurusan'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="lainnya_prodi" class="form-label">Program Studi</label>
                                    <select class="form-select" name="lainnya_prodi" id="lainnya_prodi">
                                        <option value="">-- Pilih Program Studi --</option>
                                        <?php foreach ($dataProdi as $prodi): ?>
                                            <option value="<?= $prodi['id'] ?>"
                                                    <?= old('lainnya_prodi', isset($detail['id_prodi']) ? $detail['id_prodi'] : '') == $prodi['id'] ? 'selected' : '' ?>>
                                                <?= $prodi['nama_prodi'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> -->
                                <div class="mb-3">
                                    <label for="lainnya_notlp" class="form-label">No.Hp:</label>
                                    <input type="text" class="form-control" name="lainnya_notlp" id="lainnya_notlp"
                                           value="<?= old('lainnya_notlp', isset($detail['notlp']) ? $detail['notlp'] : '') ?>">
                                </div>
                                <div>
                                    <label for="lainnya_hak">Hak Supervisi</label>
                                    <input type="checkbox" name="lainnya_hak" id="lainnya_hak" value="1"
                                        <?= (!empty($account['id_surveyor']) && $account['id_surveyor'] == 1) ? 'checked' : '' ?>>
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
                        $('#alumni_nama_lengkap, #alumni_nim, #alumni_notlp').prop('required', true);
                        break;
                    case '2': // Admin
                        $('#admin_nama_lengkap').prop('required', true);
                        break;
                    case '6': // Kaprodi
                        $('#kaprodi_nama_lengkap, #kaprodi_jurusan, #kaprodi_prodi, #kaprodi_notlp').prop('required', true);
                        break;
                    case '7': // Perusahaan
                        $('#perusahaan_nama_perusahaan, #perusahaan_notlp').prop('required', true);
                        break;
                    case '8': // Atasan
                        $('#atasan_nama_lengkap, #atasan_jabatan, #atasan_notlp').prop('required', true);
                        break;
                    case '9': // Jabatan Lainnya
                        $('#lainnya_nama_lengkap, #lainnya_jabatan, #lainnya_jurusan, #lainnya_prodi, #lainnya_notlp').prop('required', true);
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
                $('.form-detail input[type="checkbox"]').prop('checked', false);
                
                // Reset dropdown yang dependent
                if (roleId == '1' || roleId == '7') {
                    $('#alumni_kota, #perusahaan_kota').html('<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>').prop('disabled', true);
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

        // Function untuk load data kota berdasarkan provinsi - UNIVERSAL
       
            function loadCities(provinceId, citySelectId, loadingId, selectedCityId = null) {
                var citySelect = $('#' + citySelectId);
                var cityLoading = $('#' + loadingId);

                // Simpan selected value yang ada jika tidak diberikan parameter
                if (!selectedCityId) {
                    selectedCityId = citySelect.val();
                }

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
                                    var selected = (selectedCityId && city.id == selectedCityId) ? ' selected' : '';
                                    citySelect.append('<option value="' + city.id + '"' + selected + '>' + city.name + '</option>');
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
                } else {
                    citySelect.html('<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>');
                }
            }

        // Event handler untuk Alumni provinsi

        $('#alumni_province').change(function() {
            var provinceId = $(this).val();
            loadCities(provinceId, 'alumni_kota', 'city-loading');
        });

        // Event handler untuk Perusahaan provinsi
        $('#perusahaan_province').change(function() {
            var provinceId = $(this).val();
            loadCities(provinceId, 'perusahaan_kota', 'kota-loading');
        });

        // Auto-load cities jika ada province yang dipilih saat edit
            var currentAlumniProvince = $('#alumni_province').val();
            var currentAlumniCity = $('#alumni_kota').val();
            if (currentAlumniProvince) {
                loadCities(currentAlumniProvince, 'alumni_kota', 'city-loading', currentAlumniCity);
            }

            var currentPerusahaanProvince = $('#perusahaan_province').val();
            var currentPerusahaanCity = $('#perusahaan_kota').val();
            if (currentPerusahaanProvince) {
                loadCities(currentPerusahaanProvince, 'perusahaan_kota', 'kota-loading', currentPerusahaanCity);
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
                if (!$('#alumni_nama_lengkap').val()) {
                    $('#alumni_nama_lengkap').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('#alumni_nim').val()) {
                    $('#alumni_nim').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('#alumni_notlp').val()) {
                    $('#alumni_notlp').addClass('is-invalid');
                    isValid = false;
                }
                
                // Validate IPK if filled
                var ipk = $('#alumni_ipk').val();
                if (ipk && (parseFloat(ipk) < 0 || parseFloat(ipk) > 4)) {
                    $('#alumni_ipk').addClass('is-invalid');
                    showAlert('error', 'IPK harus antara 0 - 4');
                    isValid = false;
                }
                
                // Validate postal code (if filled)
                var postalCode = $('#alumni_kode_pos').val().trim();
                if (postalCode && (!/^\d{5}$/.test(postalCode))) {
                    $('#alumni_kode_pos').addClass('is-invalid');
                    showAlert('error', 'Kode pos harus 5 digit angka');
                    isValid = false;
                }
            } else if (roleId == '2') { // Admin
                if (!$('#admin_nama_lengkap').val()) {
                    $('#admin_nama_lengkap').addClass('is-invalid');
                    isValid = false;
                }
            } else if (roleId == '6') { // Kaprodi
                if (!$('#kaprodi_nama_lengkap').val()) {
                    $('#kaprodi_nama_lengkap').addClass('is-invalid');
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
                if (!$('#kaprodi_notlp').val()) {
                    $('#kaprodi_notlp').addClass('is-invalid');
                    isValid = false;
                }
            } else if (roleId == '7') { // Perusahaan
                if (!$('#perusahaan_nama_perusahaan').val()) {
                    $('#perusahaan_nama_perusahaan').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('#perusahaan_notlp').val()) {
                    $('#perusahaan_notlp').addClass('is-invalid');
                    isValid = false;
                }
                
                // Validate postal code (if filled)
                var postalCodePerusahaan = $('#perusahaan_kode_pos').val().trim();
                if (postalCodePerusahaan && (!/^\d{5}$/.test(postalCodePerusahaan))) {
                    $('#perusahaan_kode_pos').addClass('is-invalid');
                    showAlert('error', 'Kode pos harus 5 digit angka');
                    isValid = false;
                }
            } else if (roleId == '8') { // Atasan
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
            } else if (roleId == '9') { // Jabatan Lainnya
                if (!$('#lainnya_nama_lengkap').val()) {
                    $('#lainnya_nama_lengkap').addClass('is-invalid');
                    isValid = false;
                }
                if (!$('#lainnya_jabatan').val()) {
                    $('#lainnya_jabatan').addClass('is-invalid');
                    isValid = false;
                }
                // if (!$('#lainnya_jurusan').val()) {
                //     $('#lainnya_jurusan').addClass('is-invalid');
                //     isValid = false;
                // }
                // if (!$('#lainnya_prodi').val()) {
                //     $('#lainnya_prodi').addClass('is-invalid');
                //     isValid = false;
                // }
                if (!$('#lainnya_notlp').val()) {
                    $('#lainnya_notlp').addClass('is-invalid');
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                showAlert('error', 'Harap lengkapi semua field yang wajib diisi dengan benar!');
                $('.is-invalid').first().focus();
            }
        });

        // Helper functions
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
        $('.kode-pos-input').on('input', function() {
            $(this).val($(this).val().replace(/[^\d]/g, ''));
        });

        $('#alumni_ipk').on('input', function() {
            var val = parseFloat($(this).val());
            if (val < 0) $(this).val(0);
            if (val > 4) $(this).val(4);
        });
    });
    </script>
<?= $this->endSection() ?>
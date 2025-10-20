<?= $this->extend('layout/sidebar_jabatan') ?>
<?= $this->section('content') ?>

<link href="<?= base_url('css/jabatan/control_panel.css') ?>" rel="stylesheet">

<div class="control-panel-container">
    <div class="control-panel-card">
        <div class="card-header">
            <h2 class="card-title">📊 Control Panel Jurusan & Prodi</h2>
        </div>

        <form id="filterForm" action="<?= site_url('jabatan/filter-control-panel') ?>" method="post" class="filter-form">
            <!-- Dropdown Jurusan -->
            <div class="form-group">
                <label class="form-label">Jurusan</label>
                <select name="jurusan_id" id="jurusan_id" class="form-select">
                    <option value="" disabled <?= !$selectedJurusan ? 'selected' : '' ?>>-- Jurusan --</option>
                    <option value="all" <?= $selectedJurusan == 'all' ? 'selected' : '' ?>>Semua Jurusan</option>
                    <?php $jurusanSeen = []; ?>
                    <?php foreach ($prodiList as $prodi): ?>
                        <?php if (!in_array($prodi['id_jurusan'], $jurusanSeen)): ?>
                            <?php $jurusanSeen[] = $prodi['id_jurusan']; ?>
                            <option value="<?= $prodi['id_jurusan'] ?>" <?= ($selectedJurusan == $prodi['id_jurusan']) ? 'selected' : '' ?>>
                                <?= esc($prodi['nama_jurusan']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Dropdown Prodi -->
            <div class="form-group">
                <label class="form-label">Prodi</label>
                <select name="prodi_id" id="prodi_id" class="form-select">
                    <option value="" disabled <?= !$selectedProdi ? 'selected' : '' ?>>-- Prodi --</option>
                    <option value="all" <?= $selectedProdi == 'all' ? 'selected' : '' ?>>Semua Prodi</option>
                    <?php foreach ($prodiList as $prodi): ?>
                        <?php if (!$selectedJurusan || $selectedJurusan == 'all' || $prodi['id_jurusan'] == $selectedJurusan): ?>
                            <option value="<?= $prodi['id'] ?>" <?= ($selectedProdi == $prodi['id']) ? 'selected' : '' ?>>
                                <?= esc($prodi['nama_prodi']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Dropdown Role -->
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" id="role" class="form-select">
                    <option value="" disabled <?= !$selectedRole ? 'selected' : '' ?>>-- Role --</option>
                    <option value="all" <?= $selectedRole == 'all' ? 'selected' : '' ?>>Semua Role</option>
                    <?php foreach ($roles as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($selectedRole == $key) ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="form-group-button">
                <button type="submit" class="btn-filter">Filter</button>
                <a href="<?= base_url('jabatan/control-panel') ?>" class="bg-gray-400 text-white px-4 py-1 rounded-lg hover:bg-gray-500">
                    Clear
                </a>
            </div>
        </form>

        <!-- === TABEL ALUMNI === -->
        <?php if (!empty($alumniData)): ?>
            <h3 class="text-xl font-bold mt-6 mb-2">Alumni</h3>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>NIM</th>
                            <th>Tahun Masuk</th>
                            <th>Tahun Lulus</th>
                            <th>IPK</th>
                            <th>Alamat</th>
                            <th>Alamat 2</th>
                            <th>Jenis Kelamin</th>
                            <th>Provinsi</th>
                            <th>Kota</th>
                            <th>Username</th>
                            <th>Jurusan</th>
                            <th>Prodi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumniData as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($row['nama_lengkap']) ?></td>
                                <td><?= esc($row['nim']) ?></td>
                                <td><?= esc($row['angkatan']) ?></td>
                                <td><?= esc($row['tahun_kelulusan']) ?></td>
                                <td><?= esc($row['ipk']) ?></td>
                                <td><?= esc($row['alamat']) ?></td>
                                <td><?= esc($row['alamat2']) ?></td>
                                <td><?= esc($row['jenisKelamin']) ?></td>
                                <td><?= esc($row['nama_provinsi']) ?></td>
                                <td><?= esc($row['nama_cities']) ?></td>
                                <td><?= esc($row['username']) ?></td>
                                <td><?= esc($row['nama_jurusan']) ?></td>
                                <td><?= esc($row['nama_prodi']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- === TABEL KAPRODI === -->
        <?php if (!empty($kaprodiData)): ?>
            <h3 class="text-xl font-bold mt-8 mb-2">Kaprodi</h3>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Jurusan</th>
                            <th>Prodi</th>
                            <th>Username</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kaprodiData as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($row['nama_lengkap']) ?></td>
                                <td><?= esc($row['nama_jurusan']) ?></td>
                                <td><?= esc($row['nama_prodi']) ?></td>
                                <td><?= esc($row['username']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (empty($alumniData) && empty($kaprodiData)): ?>
            <div class="empty-state mt-8">
                <p class="text-gray-600">Tidak ada data ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('jurusan_id').addEventListener('change', function() {
        const jurusanId = this.value;
        const prodiSelect = document.getElementById('prodi_id');
        prodiSelect.innerHTML = '<option value="">-- Semua Prodi --</option>';

        if (jurusanId) {
            fetch('<?= site_url('jabatan/get-prodi-by-jurusan') ?>?jurusan_id=' + jurusanId)
                .then(res => res.json())
                .then(data => {
                    data.forEach(prodi => {
                        const option = document.createElement('option');
                        option.value = prodi.id;
                        option.text = prodi.nama_prodi;
                        prodiSelect.appendChild(option);
                    });
                });
        }
    });
</script>

<?= $this->endSection() ?>
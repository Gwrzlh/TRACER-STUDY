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
                    <option value="">-- Semua Jurusan --</option>
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
                    <option value="">-- Semua Prodi --</option>
                    <?php foreach ($prodiList as $prodi): ?>
                        <?php if (!$selectedJurusan || $prodi['id_jurusan'] == $selectedJurusan): ?>
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
                    <option value="">-- Semua Role --</option>
                    <?php foreach ($roles as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($selectedRole == $key) ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group-button">
                <button type="submit" class="btn-filter">Filter</button>
            </div>
        </form>

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="th-no">No</th>
                            <?php if ($selectedRole == 'alumni'): ?>
                                <th class="th-nama">Nama Lengkap</th>
                                <th class="th-nim">NIM</th>
                                <th class="th-angkatan">Angkatan</th>
                                <th class="th-tahun">Tahun Kelulusan</th>
                                <th class="th-ipk">IPK</th>
                                <th class="th-alamat">Alamat</th>
                                <th class="th-alamat2">Alamat2</th>
                                <th class="th-jk">Jenis Kelamin</th>
                                <th class="th-provinsi">Nama Provinsi</th>
                                <th class="th-kota">Nama Kota</th>
                                <th class="th-username">Username</th>
                                <th class="th-jurusan">Nama Jurusan</th>
                                <th class="th-prodi">Nama Prodi</th>
                                <th class="th-role">Role</th>
                            <?php elseif ($selectedRole == 'kaprodi'): ?>
                                <th class="th-nama">Nama Lengkap</th>
                                <th class="th-jurusan">Jurusan</th>
                                <th class="th-prodi">Nama Prodi</th>
                                <th class="th-role">Role</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dataResult)): ?>
                            <?php foreach ($dataResult as $i => $row): ?>
                                <tr>
                                    <td class="td-center"><?= $i + 1 ?></td>
                                    <?php if ($selectedRole == 'alumni'): ?>
                                        <td><?= esc($row['nama_lengkap']) ?></td>
                                        <td><?= esc($row['nim']) ?></td>
                                        <td class="td-center"><?= esc($row['angkatan']) ?></td>
                                        <td class="td-center"><?= esc($row['tahun_kelulusan']) ?></td>
                                        <td class="td-center"><?= esc($row['ipk']) ?></td>
                                        <td><?= esc($row['alamat']) ?></td>
                                        <td><?= esc($row['alamat2']) ?></td>
                                        <td class="td-center"><?= esc($row['jenisKelamin']) ?></td>
                                        <td><?= esc($row['nama_provinsi']) ?></td>
                                        <td><?= esc($row['nama_cities']) ?></td>
                                        <td><?= esc($row['username']) ?></td>
                                        <td><?= esc($row['nama_jurusan']) ?></td>
                                        <td><?= esc($row['nama_prodi']) ?></td>
                                        <td class="td-center"><span class="badge-alumni">Alumni</span></td>
                                    <?php elseif ($selectedRole == 'kaprodi'): ?>
                                        <td><?= esc($row['nama_lengkap']) ?></td>
                                        <td><?= esc($row['nama_jurusan']) ?></td>
                                        <td><?= esc($row['nama_prodi']) ?></td>
                                        <td class="td-center"><span class="badge-kaprodi">Kaprodi</span></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= ($selectedRole == 'alumni') ? 15 : 5 ?>" class="td-empty">
                                    <div class="empty-state">
                                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="empty-text">Tidak ada data</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Update Prodi sesuai Jurusan
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
<?= $this->extend('layout/sidebar_jabatan') ?>
<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">📊 Control Panel Jurusan & Prodi</h2>

    <form id="filterForm" action="<?= site_url('jabatan/filter-control-panel') ?>" method="post" class="flex space-x-4 mb-6">
        <!-- Dropdown Jurusan -->
        <div>
            <label class="block mb-1 font-semibold">Jurusan</label>
            <select name="jurusan_id" id="jurusan_id" class="border rounded px-3 py-2 w-64">
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
        <div>
            <label class="block mb-1 font-semibold">Prodi</label>
            <select name="prodi_id" id="prodi_id" class="border rounded px-3 py-2 w-64">
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
        <div>
            <label class="block mb-1 font-semibold">Role</label>
            <select name="role" id="role" class="border rounded px-3 py-2 w-40">
                <option value="">-- Semua Role --</option>
                <?php foreach ($roles as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($selectedRole == $key) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th>No</th>
                    <?php if ($selectedRole == 'alumni'): ?>
                        <th>Nama Lengkap</th>
                        <th>NIM</th>
                        <th>Angkatan</th>
                        <th>Tahun Kelulusan</th>
                        <th>IPK</th>
                        <th>Alamat</th>
                        <th>Alamat2</th>
                        <th>Jenis Kelamin</th>
                        <th>Nama Provinsi</th>
                        <th>Nama Kota</th>
                        <th>Username</th>
                        <th>Nama Jurusan</th>
                        <th>Nama Prodi</th>
                        <th>Role</th>
                    <?php elseif ($selectedRole == 'kaprodi'): ?>
                        <th>Nama Lengkap</th>
                        <th>Jurusan</th>
                        <th>Nama Prodi</th>
                        <th>Role</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataResult)): ?>
                    <?php foreach ($dataResult as $i => $row): ?>
                        <tr class="hover:bg-gray-50">
                            <td><?= $i + 1 ?></td>
                            <?php if ($selectedRole == 'alumni'): ?>
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
                                <td>Alumni</td>
                            <?php elseif ($selectedRole == 'kaprodi'): ?>
                                <td><?= esc($row['nama_lengkap']) ?></td>
                                <td><?= esc($row['nama_jurusan']) ?></td>
                                <td><?= esc($row['nama_prodi']) ?></td>
                                <td>Kaprodi</td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= ($selectedRole == 'alumni') ? 15 : 5 ?>" class="text-center py-4 text-gray-500">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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

    // document.getElementById('prodi_id').addEventListener('change', () => document.getElementById('filterForm').submit());
    // document.getElementById('role').addEventListener('change', () => document.getElementById('filterForm').submit());
</script>

<?= $this->endSection() ?>
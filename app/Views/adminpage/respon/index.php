<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Data Respon Alumni</h2>

    <!-- Filter Form -->
    <form method="get" action="<?= base_url('admin/respon') ?>" class="mb-3 d-flex gap-2 flex-wrap align-items-end">

        <!-- NIM -->
        <input type="text" name="nim" class="form-control w-auto" placeholder="NIM"
            value="<?= esc($selectedNim) ?>">

        <!-- Nama -->
        <input type="text" name="nama" class="form-control w-auto" placeholder="Nama Alumni"
            value="<?= esc($selectedNama) ?>">

        <!-- Jurusan -->
        <select name="jurusan" class="form-select w-auto">
            <option value="">-- Semua Jurusan --</option>
            <?php foreach ($allJurusan as $j): ?>
                <option value="<?= $j['id'] ?>" <?= $selectedJurusan == $j['id'] ? 'selected' : '' ?>>
                    <?= esc($j['nama_jurusan']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Prodi -->
        <select name="prodi" class="form-select w-auto">
            <option value="">-- Semua Prodi --</option>
            <?php foreach ($allProdi as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $selectedProdi == $p['id'] ? 'selected' : '' ?>>
                    <?= esc($p['nama_prodi']) ?> (<?= esc($p['nama_jurusan'] ?? '-') ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Angkatan -->
        <select name="angkatan" class="form-select w-auto">
            <option value="">-- Semua Angkatan --</option>
            <?php foreach ($allAngkatan as $a): ?>
                <option value="<?= $a['angkatan'] ?>" <?= $selectedAngkatan == $a['angkatan'] ? 'selected' : '' ?>>
                    <?= esc($a['angkatan']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Tahun Lulusan -->
        <select name="year" class="form-select w-auto">
            <option value="">-- Semua Tahun Kelulusan --</option>
            <?php foreach ($allYears as $y): ?>
                <option value="<?= $y['tahun_kelulusan'] ?>" <?= $selectedYear == $y['tahun_kelulusan'] ? 'selected' : '' ?>>
                    <?= esc($y['tahun_kelulusan']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Status -->
        <select name="status" class="form-select w-auto">
            <option value="">-- Semua Status --</option>
            <option value="completed" <?= $selectedStatus == 'completed' ? 'selected' : '' ?>>Sudah</option>
            <option value="ongoing" <?= $selectedStatus == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
            <option value="draft" <?= $selectedStatus == 'draft' ? 'selected' : '' ?>>Belum</option>
            <option value="Belum" <?= $selectedStatus == 'Belum' ? 'selected' : '' ?>>Belum Mengisi</option>
        </select>

        <!-- Judul Kuesioner -->
        <select name="questionnaire_id" class="form-select w-auto">
            <option value="">-- Semua Kuesioner --</option>
            <?php foreach ($allQuestionnaires as $q): ?>
                <option value="<?= $q['id'] ?>" <?= $selectedQuestionnaire == $q['id'] ? 'selected' : '' ?>>
                    <?= esc($q['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Urutkan Berdasarkan -->
        <select name="sort_by" class="form-select w-auto">
            <option value="">-- Urutkan Berdasarkan --</option>
            <option value="nim" <?= ($filters['sort_by'] ?? '') == 'nim' ? 'selected' : '' ?>>NIM</option>
            <option value="nama_lengkap" <?= ($filters['sort_by'] ?? '') == 'nama_lengkap' ? 'selected' : '' ?>>Nama</option>
            <option value="angkatan" <?= ($filters['sort_by'] ?? '') == 'angkatan' ? 'selected' : '' ?>>Angkatan</option>
            <option value="tahun_kelulusan" <?= ($filters['sort_by'] ?? '') == 'tahun_kelulusan' ? 'selected' : '' ?>>Tahun Kelulusan</option>
            <option value="status" <?= ($filters['sort_by'] ?? '') == 'status' ? 'selected' : '' ?>>Status</option>
        </select>

        <!-- Arah Urutan -->
        <select name="sort_order" class="form-select w-auto">
            <option value="asc" <?= ($filters['sort_order'] ?? '') == 'asc' ? 'selected' : '' ?>>Ascending (A–Z / 0–9)</option>
            <option value="desc" <?= ($filters['sort_order'] ?? '') == 'desc' ? 'selected' : '' ?>>Descending (Z–A / 9–0)</option>
        </select>

        <!-- Tombol Filter -->
        <button type="submit" class="btn btn-primary">Filter</button>

        <!-- Tombol Clear -->
        <a href="<?= base_url('admin/respon') ?>" class="btn btn-secondary">Clear</a>

        <!-- Tombol Grafik -->
        <a href="<?= base_url('admin/respon/grafik') ?>" class="btn btn-info text-white">Grafik</a>

        <!-- Export Excel -->
        <a href="<?= base_url('admin/respon/export?' . http_build_query($_GET)) ?>" class="btn btn-success">
            Export Excel
        </a>
    </form>

    <!-- Summary Counter -->
    <div class="mb-3 d-flex gap-3 flex-wrap">
        <span class="badge bg-success p-2">Sudah: <?= $totalCompleted ?? 0 ?></span>
        <span class="badge bg-primary p-2">Ongoing: <?= $totalOngoing ?? 0 ?></span>
        <span class="badge bg-warning text-dark p-2">Belum: <?= $totalDraft ?? 0 ?></span>
        <span class="badge bg-danger p-2">Belum Mengisi: <?= $totalBelum ?? 0 ?></span>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Alumni</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Angkatan</th>
                    <th>Tahun Lulusan</th>
                    <th>Judul Kuesioner</th>
                    <th>Status</th>
                    <th>Tanggal Submit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($responses)): ?>
                    <?php $no = 1;
                    foreach ($responses as $res): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($res['nim'] ?? '-') ?></td>
                            <td><?= esc($res['nama_lengkap'] ?? '-') ?></td>
                            <td><?= esc($res['nama_jurusan'] ?? '-') ?></td>
                            <td><?= esc($res['nama_prodi'] ?? '-') ?></td>
                            <td><?= esc($res['angkatan'] ?? '-') ?></td>
                            <td><?= esc($res['tahun_kelulusan'] ?? '-') ?></td>
                            <td><?= esc($res['judul_kuesioner'] ?? '-') ?></td>
                            <td>
                                <?php if ($res['status'] == 'completed'): ?>
                                    <span class="badge bg-success">Sudah</span>
                                <?php elseif ($res['status'] == 'ongoing'): ?>
                                    <span class="badge bg-primary">Ongoing</span>
                                <?php elseif ($res['status'] == 'draft'): ?>
                                    <span class="badge bg-warning text-dark">Belum</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Belum Mengisi</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($res['submitted_at'] ?? '-') ?></td>
                            <td>
                                <?php if (!empty($res['response_id'])): ?>
                                    <a href="<?= base_url('admin/respon/detail/' . $res['response_id']) ?>"
                                        class="btn btn-sm btn-primary">Jawaban</a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
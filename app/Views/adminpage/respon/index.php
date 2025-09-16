<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Data Respon Alumni</h2>

    <!-- Filter Form -->
    <form method="get" action="<?= base_url('admin/respon') ?>" class="mb-3 d-flex gap-2 flex-wrap align-items-end">
        <input type="text" name="nim" class="form-control w-auto" placeholder="NIM" value="<?= esc($selectedNim ?? '') ?>">
        <input type="text" name="nama" class="form-control w-auto" placeholder="Nama Alumni" value="<?= esc($selectedNama ?? '') ?>">

        <select name="jurusan" class="form-select w-auto">
            <option value="">-- Semua Jurusan --</option>
            <?php foreach ($allJurusan as $j): ?>
                <option value="<?= $j['id'] ?>" <?= ($selectedJurusan == $j['id']) ? 'selected' : '' ?>><?= esc($j['nama_jurusan']) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="prodi" class="form-select w-auto">
            <option value="">-- Semua Prodi --</option>
            <?php foreach ($allProdi as $p): ?>
                <option value="<?= $p['id'] ?>" <?= ($selectedProdi == $p['id']) ? 'selected' : '' ?>>
                    <?= esc($p['nama_prodi']) ?> (<?= esc($p['nama_jurusan'] ?? '-') ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <select name="angkatan" class="form-select w-auto">
            <option value="">-- Semua Angkatan --</option>
            <?php foreach ($allAngkatan as $a): ?>
                <option value="<?= $a['angkatan'] ?>" <?= ($selectedAngkatan == $a['angkatan']) ? 'selected' : '' ?>><?= esc($a['angkatan']) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="tahun" class="form-select w-auto">
            <option value="">-- Semua Tahun Kelulusan --</option>
            <?php foreach ($allYears as $y): ?>
                <option value="<?= $y['tahun_kelulusan'] ?>" <?= ($selectedYear == $y['tahun_kelulusan']) ? 'selected' : '' ?>><?= esc($y['tahun_kelulusan']) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="status" class="form-select w-auto">
            <option value="">-- Semua Status --</option>
            <option value="completed" <?= ($selectedStatus == 'completed') ? 'selected' : '' ?>>Sudah</option>
            <option value="ongoing" <?= ($selectedStatus == 'ongoing') ? 'selected' : '' ?>>Ongoing</option>
            <option value="Belum" <?= ($selectedStatus == 'Belum') ? 'selected' : '' ?>>Belum Mengisi</option>
        </select>

        <select name="questionnaire" class="form-select w-auto">
            <option value="">-- Semua Kuesioner --</option>
            <?php foreach ($allQuestionnaires as $q): ?>
                <option value="<?= $q['id'] ?>" <?= ($selectedQuestionnaire == $q['id']) ? 'selected' : '' ?>><?= esc($q['title']) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="sort_by" class="form-select w-auto">
            <option value="">-- Urutkan Berdasarkan --</option>
            <?php
            $sortFields = ['nim' => 'NIM', 'nama_lengkap' => 'Nama', 'angkatan' => 'Angkatan', 'tahun_kelulusan' => 'Tahun Lulusan', 'status' => 'Status'];
            foreach ($sortFields as $key => $label): ?>
                <option value="<?= $key ?>" <?= (($filters['sort_by'] ?? '') == $key) ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>

        <select name="sort_order" class="form-select w-auto">
            <option value="asc" <?= (($filters['sort_order'] ?? '') == 'asc') ? 'selected' : '' ?>>Urutkan A–Z/0-9</option>
            <option value="desc" <?= (($filters['sort_order'] ?? '') == 'desc') ? 'selected' : '' ?>>Urutkan Z–A/9-0</option>
        </select>


        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="<?= base_url('admin/respon') ?>" class="btn btn-secondary">Clear</a>
        <a href="<?= base_url('admin/respon/grafik') ?>" class="btn btn-info text-white">Grafik</a>
        <a href="<?= base_url('admin/respon/export?' . http_build_query($_GET)) ?>" class="btn btn-success">Export Excel</a>
    </form>

    <!-- Summary -->
    <div class="mb-3 d-flex gap-3 flex-wrap">
        <span class="badge bg-success p-2">Sudah: <?= $totalCompleted ?? 0 ?></span>
        <span class="badge bg-primary p-2">Ongoing: <?= $totalOngoing ?? 0 ?></span>
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
                    <?php $no = ($currentPage - 1) * $perPage + 1; ?>
                    <?php foreach ($responses as $res): ?>
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
                                <?php
                                $status = $res['status'] ?? '';
                                switch ($status) {
                                    case 'completed':
                                        echo '<span class="badge bg-success">Sudah</span>';
                                        break;
                                    case 'ongoing':
                                        echo '<span class="badge bg-primary">Ongoing</span>';
                                        break;
                                    default:
                                        echo '<span class="badge bg-danger">Belum Mengisi</span>';
                                }
                                ?>
                            </td>
                            <td><?= esc($res['submitted_at'] ?? '-') ?></td>
                            <td>
                                <?php if (!empty($res['response_id'])): ?>
                                    <a href="<?= base_url('admin/respon/detail/' . $res['response_id']) ?>" class="btn btn-sm btn-primary">Jawaban</a>
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

    <!-- Pagination Manual -->
    <?php if ($totalPages > 1): ?>
        <div class="d-flex justify-content-end">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= base_url('admin/respon?page=' . $i . '&' . http_build_query($_GET)) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
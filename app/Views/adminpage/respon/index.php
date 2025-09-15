<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Data Respon Alumni</h2>

    <!-- Filter Form -->
    <form method="get" action="<?= base_url('admin/respon') ?>" class="mb-3 d-flex gap-2 flex-wrap">
        <!-- Tahun -->
        <select name="year" class="form-select w-auto">
            <option value="">-- Semua Tahun Kelulusan --</option>
            <?php foreach ($allYears as $y): ?>
                <option value="<?= $y['year'] ?>" <?= $selectedYear == $y['year'] ? 'selected' : '' ?>>
                    <?= $y['year'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Status -->
        <select name="status" class="form-select w-auto">
            <option value="">-- Semua Status --</option>
            <option value="completed" <?= $selectedStatus == 'completed' ? 'selected' : '' ?>>Sudah</option>
            <option value="draft" <?= $selectedStatus == 'draft' ? 'selected' : '' ?>>Belum</option>
        </select>

        <!-- Judul Kuesioner -->
        <select name="questionnaire_id" class="form-select w-auto">
            <option value="">-- Semua Kuesioner --</option>
            <?php foreach ($allQuestionnaires as $q): ?>
                <option value="<?= $q['id'] ?>" <?= $selectedQuestionnaire == $q['id'] ? 'selected' : '' ?>>
                    <?= $q['title'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>

        <a href="<?= base_url(
                        'admin/respon/export?year=' . $selectedYear .
                            '&status=' . $selectedStatus .
                            '&questionnaire_id=' . $selectedQuestionnaire
                    ) ?>" class="btn btn-success">
            Export Excel
        </a>
    </form>

    <!-- Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>Nama Alumni</th>
                <th>Tahun Kelulusan</th>
                <th>Judul Kuesioner</th>
                <th>Status</th>
                <th>Tanggal Submit</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($responses)): ?>
                <?php foreach ($responses as $res): ?>
                    <tr>
                        <td><?= $res['nama_lengkap'] ?: ($res['username'] ?? '-') ?></td>
                        <td><?= $res['tahun_kelulusan'] ?? '-' ?></td>
                        <td><?= $res['judul_kuesioner'] ?? '-' ?></td>
                        <td>
                            <?php if ($res['status'] == 'completed'): ?>
                                <span class="badge bg-success">Sudah</span>
                            <?php elseif ($res['status'] == 'draft'): ?>
                                <span class="badge bg-warning">Belum</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= $res['status'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $res['submitted_at'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
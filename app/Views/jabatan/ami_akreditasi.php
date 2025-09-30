<?= $this->extend('layout/sidebar_jabatan') ?>
<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">📑 AMI dan Akreditasi Kaprodi</h2>

    <!-- Form Filter -->
    <form action="<?= site_url('jabatan/filter-ami-akreditasi') ?>" method="post" id="filterForm" class="flex space-x-4 mb-6">
        <!-- Dropdown Prodi -->
        <div>
            <label for="prodi_id" class="block mb-1 font-semibold">Pilih Prodi</label>
            <select name="prodi_id" id="prodi_id" class="border rounded px-3 py-2 w-64" onchange="document.getElementById('filterForm').submit()">
                <option value="">-- Semua Prodi --</option>
                <?php foreach ($prodiList as $prodi): ?>
                    <option value="<?= $prodi['id'] ?>"
                        <?= ($selectedProdi == $prodi['id']) ? 'selected' : '' ?>>
                        <?= esc($prodi['nama_jurusan']) ?> - <?= esc($prodi['nama_prodi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Dropdown Jenis -->
        <div>
            <label for="jenis" class="block mb-1 font-semibold">Jenis</label>
            <select name="jenis" id="jenis" class="border rounded px-3 py-2 w-40" onchange="document.getElementById('filterForm').submit()">
                <option value="ami" <?= ($selectedJenis == 'ami') ? 'selected' : '' ?>>AMI</option>
                <option value="akreditasi" <?= ($selectedJenis == 'akreditasi') ? 'selected' : '' ?>>Akreditasi</option>
            </select>
        </div>
    </form>

    <!-- Tabel Data -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Pertanyaan</th>
                    <th class="px-4 py-2 border">Jawaban</th>
                    <th class="px-4 py-2 border">Nama Alumni</th>
                    <th class="px-4 py-2 border">Jurusan Alumni</th>
                    <th class="px-4 py-2 border">Prodi Alumni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($answers)): ?>
                    <?php foreach ($answers as $i => $row): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border"><?= $i + 1 ?></td>
                            <td class="px-4 py-2 border"><?= esc($row['question_text'] ?? '-') ?></td>
                            <td class="px-4 py-2 border"><?= esc($row['answer_text'] ?? '-') ?></td>
                            <td class="px-4 py-2 border"><?= esc($row['alumni_name'] ?? '-') ?></td>
                            <td class="px-4 py-2 border"><?= esc($row['jurusan_name'] ?? '-') ?></td>
                            <td class="px-4 py-2 border"><?= esc($row['prodi_name'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
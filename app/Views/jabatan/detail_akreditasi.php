<?= $this->extend('layout/sidebar_jabatan') ?>
<?= $this->section('content') ?>

<h2 class="text-2xl font-bold mb-4">Detail Akreditasi</h2>

<!-- Dropdown Pertanyaan -->
<div class="mb-4 flex items-center space-x-2">
    <label for="question_id" class="font-semibold">Pilih Pertanyaan:</label>
    <select id="question_id" class="border rounded px-2 py-1">
        <option value="">-- Pilih Pertanyaan --</option>
        <?php foreach ($questions as $q): ?>
            <option value="<?= $q['id'] ?>" <?= (isset($selectedQuestion) && $selectedQuestion == $q['id']) ? 'selected' : '' ?>>
                <?= esc($q['question_text']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<?php if (!empty($selectedQuestion)): ?>
    <!-- Tabel Jawaban Alumni -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="px-4 py-2 border">NIM</th>
                <th class="px-4 py-2 border">Nama Lengkap</th>
                <th class="px-4 py-2 border">Prodi</th>
                <th class="px-4 py-2 border">Jurusan</th>
                <th class="px-4 py-2 border">Tahun Lulus</th>
                <th class="px-4 py-2 border">Status</th>
                <th class="px-4 py-2 border">Jawaban</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($answers)): ?>
                <?php foreach ($answers as $ans): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?= esc($ans['nim'] ?? '-') ?></td>
                        <td class="px-4 py-2 border"><?= esc($ans['alumni_name'] ?? '-') ?></td>
                        <td class="px-4 py-2 border"><?= esc($ans['prodi_name'] ?? '-') ?></td>
                        <td class="px-4 py-2 border"><?= esc($ans['jurusan_name'] ?? '-') ?></td>
                        <td class="px-4 py-2 border"><?= esc($ans['tahun_lulus'] ?? '-') ?></td>
                        <td class="px-4 py-2 border"><?= esc($ans['STATUS'] ?? '-') ?></td>
                        <td class="px-4 py-2 border"><?= esc($ans['answer_text'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="px-4 py-2 border text-center">Belum ada jawaban</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- Script untuk auto-load jawaban saat pilih pertanyaan -->
<script>
    const questionDropdown = document.getElementById('question_id');
    questionDropdown.addEventListener('change', function() {
        const questionId = this.value;
        const url = new URL(window.location.href);
        if (questionId) {
            url.searchParams.set('question_id', questionId);
        } else {
            url.searchParams.delete('question_id');
        }
        window.location.href = url.toString();
    });
</script>

<?= $this->endSection() ?>
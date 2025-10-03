<?= $this->extend('layout/sidebar_jabatan') ?>
<?= $this->section('content') ?>

<link href="<?= base_url('css/jabatan/detail_akreditasi.css') ?>" rel="stylesheet">

<div class="container mx-auto px-6 py-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header mirip dengan Tipe Organisasi -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">Detail Akreditasi</h2>
            <!-- Jika perlu tombol tambah seperti di foto, bisa ditambahkan di sini -->
        </div>

        <!-- Dropdown Pertanyaan dalam card-like -->
        <div class="mb-8">
            <label for="question_id" class="font-semibold block mb-3 text-lg">Pilih Pertanyaan:</label>
            <select id="question_id" class="border rounded-lg px-6 py-3 w-full md:w-auto focus:outline-none focus:ring-2 focus:ring-blue-500 text-base">
                <option value="">-- Pilih Pertanyaan --</option>
                <?php foreach ($questions as $q): ?>
                    <option value="<?= $q['id'] ?>" <?= (isset($selectedQuestion) && $selectedQuestion == $q['id']) ? 'selected' : '' ?>>
                        <?= esc($q['question_text']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($selectedQuestion)): ?>
            <!-- Tabel Jawaban Alumni dalam card-like -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-left text-base uppercase font-semibold">
                            <th class="px-8 py-4 border-b">NIM</th>
                            <th class="px-8 py-4 border-b">Nama Lengkap</th>
                            <th class="px-8 py-4 border-b">Prodi</th>
                            <th class="px-8 py-4 border-b">Jurusan</th>
                            <th class="px-8 py-4 border-b">Tahun Lulus</th>
                            <th class="px-8 py-4 border-b">Status</th>
                            <th class="px-8 py-4 border-b">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($answers)): ?>
                            <?php foreach ($answers as $ans): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-8 py-4 border-b"><?= esc($ans['nim'] ?? '-') ?></td>
                                    <td class="px-8 py-4 border-b"><?= esc($ans['alumni_name'] ?? '-') ?></td>
                                    <td class="px-8 py-4 border-b"><?= esc($ans['prodi_name'] ?? '-') ?></td>
                                    <td class="px-8 py-4 border-b"><?= esc($ans['jurusan_name'] ?? '-') ?></td>
                                    <td class="px-8 py-4 border-b"><?= esc($ans['tahun_lulus'] ?? '-') ?></td>
                                    <td class="px-8 py-4 border-b"><?= esc($ans['STATUS'] ?? '-') ?></td>
                                    <td class="px-8 py-4 border-b"><?= esc($ans['answer_text'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-8 py-4 border-b text-center text-gray-500 text-lg">Belum ada jawaban</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

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
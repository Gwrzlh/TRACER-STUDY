<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">📄 Detail Respon Kuesioner Atasan</h2>

    <!-- Informasi Atasan -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold mb-3 border-b pb-2">👤 Informasi Atasan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <p><strong>Nama Lengkap:</strong> <?= esc($response['nama_lengkap'] ?? '-') ?></p>
            <p><strong>Username:</strong> <?= esc($response['username'] ?? '-') ?></p>
            <p><strong>Email:</strong> <?= esc($response['email'] ?? '-') ?></p>
            <p><strong>No. Telepon:</strong> <?= esc($response['notlp'] ?? '-') ?></p>
            <p><strong>Jabatan:</strong> <?= esc($response['jabatan'] ?? '-') ?></p>
            <p><strong>Kuesioner:</strong> <?= esc($response['nama_kuesioner'] ?? '-') ?></p>
            <p><strong>Tanggal Update:</strong> <?= esc(date('d M Y, H:i', strtotime($response['updated_at'] ?? 'now'))) ?></p>
            <p><strong>Status:</strong> 
                <?php if ($response['status'] == 'finish'): ?>
                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Selesai</span>
                <?php elseif ($response['status'] == 'pending'): ?>
                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Belum Selesai</span>
                <?php else: ?>
                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Tidak Valid</span>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Jawaban Kuesioner -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-3 border-b pb-2">📝 Jawaban Kuesioner</h3>

        <?php if (empty($answers)): ?>
            <p class="text-gray-500 italic">Belum ada jawaban yang diisi oleh atasan.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($answers as $index => $ans): ?>
                    <div class="border-l-4 border-blue-500 pl-4 py-2 bg-gray-50 rounded">
                        <p class="font-medium text-gray-700 mb-1">
                            <?= esc(($index + 1) . '. ' . ($ans['question'] ?? 'Pertanyaan tidak diketahui')) ?>
                        </p>
                        <p class="text-gray-800 leading-snug">
                            <?php
                                $answerValue = $ans['answer'] ?? '-';
                                if (is_array($answerValue)) {
                                    echo esc(implode(', ', $answerValue));
                                } else {
                                    echo esc($answerValue);
                                }
                            ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-6">
        <a href="<?= base_url('admin/respon/atasan') ?>" 
           class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
           ⬅ Kembali ke Daftar Respon
        </a>
    </div>
</div>

<?= $this->endSection() ?>

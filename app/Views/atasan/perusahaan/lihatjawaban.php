<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <a href="<?= base_url('atasan/perusahaan/response-alumni') ?>" class="btn btn-secondary mb-3">⬅️ Kembali</a>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h4 class="fw-bold text-primary mb-3">
                📋 Kuesioner: <?= esc($response['nama_kuesioner'] ?? 'Tidak diketahui') ?>
            </h4>

            <p><strong>Nama Alumni:</strong> <?= esc($response['nama_lengkap']) ?></p>
            <p><strong>Status:</strong>
                <span class="badge <?= $response['status'] === 'completed' ? 'bg-success' : 'bg-warning text-dark' ?>">
                    <?= esc(ucfirst($response['status'])) ?>
                </span>
            </p>
            <p><strong>Tanggal Submit:</strong>
                <?= date('d M Y, H:i', strtotime($response['submitted_at'])) ?>
            </p>
            <hr>

            <?php if (!empty($answers)): ?>
                <div class="mt-3">
                    <?php foreach ($answers as $a): ?>
                        <div class="mb-3">
                            <p class="fw-semibold mb-1">🟢 <?= esc($a['pertanyaan']) ?></p>
                            <p class="border rounded-3 p-2 bg-light"><?= esc($a['answer_text'] ?? '-') ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    Alumni ini belum mengisi jawaban apapun untuk kuesioner ini.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

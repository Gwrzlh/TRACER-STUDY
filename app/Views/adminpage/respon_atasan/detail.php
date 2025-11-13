<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/respon/detail_atasan.css') ?>">

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <h2 class="header-title">Detail Respon Kuesioner Atasan</h2>
    </div>

    <!-- Informasi Atasan -->
    <div class="card info-card">
        <div class="card-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h3 class="card-title">Informasi Atasan</h3>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Lengkap:</span>
                    <span class="info-value"><?= esc($response['nama_lengkap'] ?? '-') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Username:</span>
                    <span class="info-value"><?= esc($response['username'] ?? '-') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= esc($response['email'] ?? '-') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">No. Telepon:</span>
                    <span class="info-value"><?= esc($response['notlp'] ?? '-') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jabatan:</span>
                    <span class="info-value"><?= esc($response['jabatan'] ?? '-') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kuesioner:</span>
                    <span class="info-value"><?= esc($response['nama_kuesioner'] ?? '-') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Update:</span>
                    <span class="info-value"><?= esc(date('d M Y, H:i', strtotime($response['updated_at'] ?? 'now'))) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <?php if ($response['status'] == 'finish'): ?>
                            <span class="badge badge-success">Selesai</span>
                        <?php elseif ($response['status'] == 'pending'): ?>
                            <span class="badge badge-warning">Belum Selesai</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Tidak Valid</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Jawaban Kuesioner -->
    <div class="card answer-card">
        <div class="card-header">
            <div class="section-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </div>
            <h3 class="card-title">Jawaban Kuesioner</h3>
        </div>
        <div class="card-body">
            <?php if (empty($answers)): ?>
                <p class="empty-state">Belum ada jawaban yang diisi oleh atasan.</p>
            <?php else: ?>
                <div class="answer-list">
                    <?php foreach ($answers as $index => $ans): ?>
                        <div class="answer-item">
                            <p class="question-text">
                                <?= esc(($index + 1) . '. ' . ($ans['question'] ?? 'Pertanyaan tidak diketahui')) ?>
                            </p>
                            <p class="answer-text">
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
    </div>

    <!-- Tombol Kembali -->
    <div class="action-section">
        <a href="<?= base_url('admin/respon/atasan') ?>" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Daftar Respon
        </a>
    </div>
</div>

<?= $this->endSection() ?>
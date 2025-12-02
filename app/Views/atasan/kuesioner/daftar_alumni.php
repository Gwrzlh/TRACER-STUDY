<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<?php
$answerModel = new \App\Models\AnswerModel();
$atasanId = session()->get('id_account');
$q_id = $q_id; 
?>

<link rel="stylesheet" href="<?= base_url('css/atasan/kuesioner/daftar_alumni.css') ?>">

<div class="container mt-4">
    <h3 class="fw-bold text-primary mb-4">
        Data Alumni Binaan Anda untuk Kuesioner: <?= esc($questionnaire['title']) ?>
    </h3>

    <?php if (!empty($pesan_kosong)): ?>
        <div class="alert alert-info text-center">
            <?= $pesan_kosong ?>
        </div>
    <?php elseif (empty($alumni)): ?>
        <div class="alert alert-light text-center border">
            Tidak ada alumni binaan yang tersedia saat ini.<br>
            <small class="text-muted">Hubungi admin untuk menambahkan alumni binaan Anda.</small>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Lengkap</th>
                        <th>NIM</th>
                        <th>Prodi</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($alumni as $a): ?>
                            <tr>
                                <td><?= esc($no++) ?></td>
                                <td><?= esc($a['nama_lengkap']) ?></td>
                                <td><?= esc($a['nim']) ?></td>
                                <td><?= esc($a['nama_prodi']) ?></td>
                                <td><?= esc($a['nama_jurusan']) ?></td>
                                <!-- KOLOM STATUS & TOMBOL -->
                                <td>
                                    <?php
                                    // Cek apakah sudah ada jawaban di tabel answers (draft atau completed)
                                    $hasAnswer = $answerModel->where([
                                        'questionnaire_id' => $q_id,
                                        'user_id'          => $atasanId,
                                        'alumni_id'        => $a['id_account']  // ini penting: id_account alumni!
                                    ])->countAllResults() > 0;

                                    // Cek apakah sudah completed
                                    $isCompleted = $answerModel->where([
                                        'questionnaire_id' => $q_id,
                                        'user_id'          => $atasanId,
                                        'alumni_id'        => $a['id_account'],
                                        'STATUS'           => 'completed'
                                    ])->countAllResults() > 0;

                                    if ($isCompleted) {
                                        $badge = '<span class="badge bg-success">Selesai</span>';
                                        $button = '<a href="' . base_url("atasan/kuesioner/lihat/{$q_id}/{$a['id']}") . '" class="btn btn-sm btn-success">Lihat</a>';
                                    } elseif ($hasAnswer) {
                                        $badge = '<span class="badge bg-warning text-dark">Sedang Diisi</span>';
                                        $button = '<a href="' . base_url("atasan/kuesioner/lanjutkan/{$q_id}/{$a['id']}") . '" class="btn btn-sm btn-warning">Lanjutkan</a>';
                                    } else {
                                        $badge = '<span class="badge bg-secondary">Belum Diisi</span>';
                                        $button = '<a href="' . base_url("atasan/kuesioner/isi/{$q_id}/{$a['id']}") . '" class="btn btn-sm btn-primary">Mulai Penilaian</a>';
                                    }
                                    ?>

                                    <?= $badge ?>
                                </td>
                                <td>
                                    <?= $button ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="<?= base_url('atasan/kuesioner') ?>" class="btn btn-secondary">
            Kembali ke Daftar Kuesioner
        </a>
    </div>
</div>

<?= $this->endSection() ?>
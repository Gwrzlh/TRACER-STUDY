<div class="container my-3 p-4 border rounded bg-light shadow-sm">
    <h3 class="mb-3">
        <?= esc($pesan['subject'] ?: 'Pesan dari ' . ($pesan['nama_pengirim'] ?? 'Alumni')) ?>
    </h3>

    <div class="mb-4">
        <p class="mb-0"><?= nl2br(esc($pesan['pesan'] ?? '-')) ?></p>
    </div>

    <small class="text-muted d-block mb-3">
        Dari: <strong><?= esc($pesan['nama_pengirim'] ?? 'Alumni') ?></strong><br>
        Dikirim pada: <?= !empty($pesan['created_at']) ? date('d M Y H:i', strtotime($pesan['created_at'])) : '-' ?>
    </small>

    <a href="<?= base_url('alumni/notifikasi') ?>" class="btn btn-secondary">
        &larr; Kembali
    </a>
</div>
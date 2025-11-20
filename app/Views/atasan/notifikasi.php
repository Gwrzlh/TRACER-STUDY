<?php $layout = 'layout/sidebar_atasan'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<div class="container-fluid">
    <!-- Header -->
    <div class="notification-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="header-text">
                <h2>Notifikasi Pesan</h2>
                <p>Pesan yang masuk dari Admin atau sistem</p>
            </div>
        </div>
    </div>

    <!-- 🧾 Daftar Pesan -->
    <div class="messages-container">
        <?php if (empty($pesan)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                <h3>Belum ada pesan masuk</h3>
                <p>Pesan dari admin akan muncul di sini</p>
            </div>
        <?php else: ?>
            <?php foreach ($pesan as $p): ?>
                <div class="message-card <?= $p['status'] === 'terkirim' ? 'unread' : 'read' ?>">
                    <div class="status-indicator <?= $p['status'] === 'terkirim' ? 'unread' : 'read' ?>"></div>

                    <div class="message-content">
                        <div class="message-header">
                            <h4 class="message-subject">
                                Pesan dari <?= esc($p['nama_pengirim']) ?>
                            </h4>
                            <div class="message-meta">
                                <span class="sender"><?= esc($p['nama_pengirim']) ?></span>
                                <span class="date">
                                    <?php
                                    if (!empty($p['created_at'])) {
                                        try {
                                            $dt = new DateTime($p['created_at'], new DateTimeZone('UTC'));
                                            $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                            echo $dt->format('d M Y H:i');
                                        } catch (Exception $e) {
                                            echo 'Tanggal tidak tersedia';
                                        }
                                    } else {
                                        echo 'Tanggal tidak tersedia';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                        <div class="message-preview">
                            <?= esc(substr(strip_tags($p['pesan']), 0, 90)) ?>...
                        </div>
                    </div>

                    <div class="message-actions">
                        <a href="<?= base_url('atasan/viewPesan/' . $p['id_pesan']) ?>" class="btn btn-view">
                            <i class="fas fa-eye"></i> <span>Lihat</span>
                        </a>

                        <?php if ($p['status'] === 'terkirim'): ?>
                            <a href="<?= base_url('atasan/notifikasi/tandai/' . $p['id_pesan']) ?>" class="btn btn-mark-read">
                                <i class="fas fa-check"></i> <span>Tandai dibaca</span>
                            </a>
                        <?php else: ?>
                            <span class="status-badge read"><i class="fas fa-check-double"></i> Sudah dibaca</span>
                        <?php endif; ?>

                        <a href="<?= base_url('atasan/hapusNotifikasi/' . $p['id_pesan']) ?>" class="btn btn-delete">
                            <i class="fas fa-trash"></i> <span>Hapus</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
    .container-fluid { background: #f8fafc; min-height: 100vh; padding: 2rem; }
    .notification-header { background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 1.5rem; padding: 1.5rem; border-left: 4px solid #0d6efd; }
    .header-content { display: flex; align-items: center; gap: 1rem; }
    .header-icon { background: #0d6efd; color: white; padding: 10px; border-radius: 8px; font-size: 1.5rem; }
    .header-text h2 { font-weight: 700; color: #0d1b2a; }
    .header-text p { color: #6c757d; }
    .messages-container { background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .empty-state { text-align: center; padding: 4rem; color: #6c757d; }
    .empty-icon { font-size: 3rem; color: #d0d0d0; margin-bottom: 1rem; }
    .message-card { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding: 1rem 1.5rem; transition: 0.3s; }
    .message-card.unread { background: #eaf4ff; border-left: 4px solid #0d6efd; }
    .message-card:hover { background: #f9f9f9; }
    .message-content { flex: 1; margin-right: 1rem; }
    .message-subject { font-weight: 600; color: #0d1b2a; }
    .message-preview { font-size: 0.9rem; color: #6c757d; }
    .message-actions { display: flex; gap: 0.5rem; align-items: center; }
    .btn { padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 500; text-decoration: none; }
    .btn-view { background: #f8f9fa; color: #333; border: 1px solid #ddd; }
    .btn-mark-read { background: #0d6efd; color: white; }
    .btn-delete { background: #dc3545; color: white; }
    .status-badge.read { background: #d1e7dd; color: #0f5132; padding: 6px 10px; border-radius: 6px; }
</style>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// 🔹 Konfirmasi hapus
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const url = btn.getAttribute('href');
        Swal.fire({
            title: 'Hapus Pesan Ini?',
            text: 'Pesan yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545'
        }).then(result => {
            if (result.isConfirmed) window.location.href = url;
        });
    });
});

// 🔹 Konfirmasi tandai dibaca
document.querySelectorAll('.btn-mark-read').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const url = btn.getAttribute('href');
        Swal.fire({
            title: 'Tandai pesan sudah dibaca?',
            text: 'Status pesan akan berubah menjadi dibaca.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, tandai',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0d6efd'
        }).then(result => {
            if (result.isConfirmed) window.location.href = url;
        });
    });
});

// 🔹 Flash message SweetAlert (tanpa alert hijau)
<?php if (session()->getFlashdata('success')): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '<?= esc(session()->getFlashdata('success')) ?>',
    timer: 2000,
    showConfirmButton: false
});
<?php elseif (session()->getFlashdata('error')): ?>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '<?= esc(session()->getFlashdata('error')) ?>'
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>

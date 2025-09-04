<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/notification/index.css') ?>">

<div class="container-fluid">
    <!-- Header Section -->
    <div class="notification-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="header-text">
                <h2>Notifikasi Pesan</h2>
                <p>Kelola dan baca semua pesan masuk Anda</p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">
                <?= session()->getFlashdata('success') ?>
            </div>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                <?= session()->getFlashdata('error') ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Messages Container -->
    <div class="messages-container">
        <?php if (empty($pesan)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3>Belum ada pesan masuk</h3>
                <p>Pesan yang masuk akan ditampilkan di sini</p>
            </div>
        <?php else: ?>
            <div class="messages-list">
                <?php foreach ($pesan as $p): ?>
                    <div class="message-card <?= $p['status'] === 'terkirim' ? 'unread' : 'read' ?>">
                        <!-- Message Status Indicator -->
                        <div class="status-indicator <?= $p['status'] === 'terkirim' ? 'unread' : 'read' ?>"></div>
                        
                        <!-- Message Content -->
                        <div class="message-content">
                            <div class="message-header">
                                <h4 class="message-subject">
                                    <?= esc($p['subject'] ?? 'Tanpa Subjek') ?>
                                </h4>
                                <div class="message-meta">
                                    <span class="sender">Alumni Surveyor #<?= esc($p['id_pengirim']) ?></span>
                                    <span class="date"><?= date('d M Y H:i', strtotime($p['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="message-preview">
                                <?= esc(substr($p['pesan'], 0, 80)) ?>...
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="message-actions">
                            <!-- View Button -->
                            <a href="<?= base_url('alumni/viewpesan/' . $p['id_pesan']) ?>" 
                               class="btn btn-view" title="Lihat pesan">
                                <i class="fas fa-eye"></i>
                                <span>Lihat</span>
                            </a>

                            <!-- Read/Unread Toggle -->
                            <?php if ($p['status'] === 'terkirim'): ?>
                                <a href="<?= base_url('alumni/notifikasi/tandai/' . $p['id_pesan']) ?>" 
                                   class="btn btn-mark-read" title="Tandai sudah dibaca">
                                    <i class="fas fa-check"></i>
                                    <span>Tandai dibaca</span>
                                </a>
                            <?php else: ?>
                                <span class="status-badge read">
                                    <i class="fas fa-check-double"></i>
                                    Sudah dibaca
                                </span>
                            <?php endif; ?>

                            <!-- Delete Button -->
                            <a href="<?= base_url('alumni/notifikasi/hapus/' . $p['id_pesan']) ?>" 
                               onclick="return confirm('Yakin ingin menghapus pesan ini?')"
                               class="btn btn-delete" title="Hapus pesan">
                                <i class="fas fa-trash"></i>
                                <span>Hapus</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Container */
.container-fluid {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 2rem;
}

/* Header Section */
.notification-header {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    padding: 2rem;
    border-left: 4px solid #007bff;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: #007bff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.header-text h2 {
    margin: 0 0 0.25rem 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.header-text p {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
}

/* Flash Messages */
.alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.alert-icon {
    font-size: 1.25rem;
}

/* Messages Container */
.messages-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.empty-icon {
    font-size: 3rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.empty-state h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.empty-state p {
    margin: 0;
    font-size: 0.9rem;
}

/* Messages List */
.messages-list {
    display: flex;
    flex-direction: column;
}

/* Message Card */
.message-card {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #f1f1f1;
    transition: all 0.3s ease;
    position: relative;
    border-left: 4px solid transparent;
}

.message-card:last-child {
    border-bottom: none;
}

.message-card:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

/* Notifikasi Baru - Warna Terang */
.message-card.unread {
    background: linear-gradient(135deg, #e3f2fd 0%, #f0f8ff 100%);
    border-left: 4px solid #2196f3;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.15);
}

.message-card.unread:hover {
    background: linear-gradient(135deg, #d1e9fc 0%, #e8f4fd 100%);
    transform: translateX(3px);
}

/* Notifikasi Sudah Dibaca - Warna Normal */
.message-card.read {
    background-color: #ffffff;
    border-left: 4px solid #e0e0e0;
}

.message-card.read:hover {
    background-color: #f5f5f5;
}

/* Status Indicator */
.status-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 1rem;
    flex-shrink: 0;
    position: relative;
}

.status-indicator.unread {
    background-color: #2196f3;
    box-shadow: 0 0 10px rgba(33, 150, 243, 0.5);
    animation: pulse 2s infinite;
}

.status-indicator.read {
    background-color: #9e9e9e;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(33, 150, 243, 0.7);
    }
    70% {
        box-shadow: 0 0 0 8px rgba(33, 150, 243, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(33, 150, 243, 0);
    }
}

/* Message Content */
.message-content {
    flex: 1;
    min-width: 0;
}

.message-header {
    margin-bottom: 0.5rem;
}

.message-subject {
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
    position: relative;
}

/* Subject untuk notifikasi baru */
.message-card.unread .message-subject {
    color: #1565c0;
    font-weight: 700;
}

.message-card.unread .message-subject::after {
    content: "BARU";
    position: absolute;
    top: -2px;
    right: -10px;
    background: #ff5722;
    color: white;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    letter-spacing: 0.5px;
    animation: bounce 1s infinite alternate;
}

@keyframes bounce {
    from { transform: translateY(0px); }
    to { transform: translateY(-2px); }
}

/* Preview untuk notifikasi baru */
.message-card.unread .message-preview {
    color: #1976d2;
    font-weight: 500;
}

/* Meta untuk notifikasi baru */
.message-card.unread .sender {
    color: #1565c0;
    font-weight: 600;
}

.message-card.unread .date {
    color: #1976d2;
    font-weight: 500;
}

.message-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.8rem;
    color: #666;
}

.sender {
    font-weight: 500;
}

.date {
    color: #999;
}

.message-preview {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.4;
}

/* Action Buttons */
.message-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-view {
    background: #f8f9fa;
    color: #495057;
    border: 1px solid #dee2e6;
}

.btn-view:hover {
    background: #e9ecef;
    color: #495057;
    text-decoration: none;
}

.btn-mark-read {
    background: #007bff;
    color: white;
}

.btn-mark-read:hover {
    background: #0056b3;
    color: white;
    text-decoration: none;
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-delete:hover {
    background: #c82333;
    color: white;
    text-decoration: none;
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    font-weight: 500;
    border-radius: 6px;
}

.status-badge.read {
    background: #d4edda;
    color: #155724;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }

    .notification-header {
        padding: 1.5rem;
    }

    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }

    .message-card {
        flex-direction: column;
        align-items: stretch;
        padding: 1.25rem;
        gap: 1rem;
    }

    .message-actions {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .btn {
        flex: 1;
        justify-content: center;
        min-width: auto;
    }

    .btn span {
        display: none;
    }

    .status-badge span {
        display: inline;
    }

    .message-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}

@media (max-width: 480px) {
    .header-text h2 {
        font-size: 1.25rem;
    }

    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 2rem;
    }
}
</style>

<?= $this->endSection() ?>
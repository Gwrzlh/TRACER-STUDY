<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lihat Pesan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 2rem;
        }

        .message-container {
            max-width: 800px;
            margin: auto;
        }

        .message-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            overflow: hidden;
            animation: fadeIn 0.5s ease-in;
        }

        .message-header {
            background: linear-gradient(135deg, #0d6efd, #00b4d8);
            color: white;
            padding: 1.5rem;
            position: relative;
        }

        .message-header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .status-badge {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            background: rgba(255,255,255,0.2);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            backdrop-filter: blur(8px);
        }

        .sender-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .sender-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd, #00b4d8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .sender-details h5 {
            margin: 0;
            font-weight: 600;
        }

        .message-body {
            padding: 1.5rem 2rem;
        }

        .message-text {
            font-size: 1rem;
            color: #333;
            background: #f1f5f9;
            border-left: 4px solid #0d6efd;
            padding: 1.5rem;
            border-radius: 6px;
            line-height: 1.6;
        }

        .message-actions {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
<div class="message-container">
    <div class="message-card">
        <div class="message-header">
            <h1><i class="bi bi-chat-dots"></i> Pesan dari <?= esc($pesan['nama_pengirim']) ?></h1>
            <div class="status-badge">
                <?= $pesan['status'] === 'dibaca' ? '<i class="bi bi-envelope-open"></i> Dibaca' : '<i class="bi bi-envelope"></i> Belum Dibaca' ?>
            </div>
        </div>

        <div class="sender-info">
            <div class="sender-left">
                <div class="sender-avatar"><?= strtoupper(substr($pesan['nama_pengirim'] ?? 'A', 0, 1)) ?></div>
            </div>
            <div class="sender-details">
                <h5><?= esc($pesan['nama_pengirim']) ?></h5>
                <small><?= esc($pesan['email_pengirim'] ?? '-') ?></small>
            </div>
            <div class="message-date">
                <i class="bi bi-calendar-event"></i>
                <?= date('d M Y H:i', strtotime($pesan['created_at'])) ?>
            </div>
        </div>

        <div class="message-body">
            <div class="message-text">
                <?= nl2br(esc($pesan['pesan'] ?? 'Tidak ada isi pesan.')) ?>
            </div>
        </div>

        <div class="message-actions">
            <a href="<?= base_url('atasan/notifikasi') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
</body>
</html>

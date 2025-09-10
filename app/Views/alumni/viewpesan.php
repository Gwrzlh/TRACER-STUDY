<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Pesan</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f7fa;
            color: #2d3748;
            line-height: 1.6;
            padding: 2rem 0;
        }

        /* Warna Utama */
        :root {
            --blue: #1E90FF;
            /* Dodger Blue */
            --orange: #FF7F50;
            /* Coral Orange */
        }

        /* Message Container */
        .message-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .message-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            position: relative;
        }

        /* Header Section */
        .message-header {
            background: linear-gradient(135deg, var(--blue) 0%, var(--orange) 100%);
            color: white;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .message-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            border-radius: 50%;
        }

        .message-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
        }

        .message-title i {
            margin-right: 0.75rem;
            font-size: 1.75rem;
            opacity: 0.9;
        }

        /* Sender Info */
        .sender-info {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sender-details {
            display: flex;
            align-items: center;
        }

        .sender-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--blue) 0%, var(--orange) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.3rem;
            margin-right: 1rem;
            box-shadow: 0 2px 10px rgba(30, 144, 255, 0.3);
        }

        .sender-text h5 {
            margin: 0;
            font-weight: 600;
            color: #2d3748;
            font-size: 1.1rem;
        }

        .sender-text .email {
            color: #718096;
            font-size: 0.9rem;
            margin: 0.25rem 0 0 0;
        }

        .message-date {
            background: #edf2f7;
            color: #4a5568;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .message-date i {
            margin-right: 0.5rem;
            color: var(--blue);
        }

        /* Message Body */
        .message-body {
            padding: 2.5rem;
        }

        .message-content {
            background: #f7fafc;
            border-left: 4px solid var(--blue);
            border-radius: 8px;
            padding: 2rem;
            margin: 1rem 0 2rem 0;
            position: relative;
        }

        .message-content::before {
            content: '"';
            font-size: 4rem;
            color: var(--blue);
            opacity: 0.2;
            position: absolute;
            top: -0.5rem;
            left: 1rem;
            font-family: serif;
        }

        .message-text {
            font-size: 1.1rem;
            line-height: 1.7;
            color: #2d3748;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .message-text p {
            margin-bottom: 1rem;
        }

        .message-text p:last-child {
            margin-bottom: 0;
        }

        /* Action Buttons */
        .message-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-start;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            padding: 0.875rem 1.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 10px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn i {
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .btn-secondary {
            background: #718096;
            color: white;
            border-color: #718096;
        }

        .btn-secondary:hover {
            background: #4a5568;
            border-color: #4a5568;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(113, 128, 150, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--blue) 0%, var(--orange) 100%);
            color: white;
            border-color: var(--blue);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(30, 144, 255, 0.4);
            filter: brightness(1.1);
        }

        .btn-outline {
            background: transparent;
            color: var(--blue);
            border-color: var(--blue);
        }

        .btn-outline:hover {
            background: var(--blue);
            color: white;
            transform: translateY(-2px);
        }

        /* Status Badge */
        .status-badge {
            position: absolute;
            top: 1.5rem;
            right: 2rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        /* Message Priority Indicator */
        .priority-indicator {
            width: 4px;
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            background: linear-gradient(to bottom, var(--blue), var(--orange));
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0;
            }

            .message-container {
                padding: 0 0.5rem;
            }

            .message-header,
            .sender-info,
            .message-body {
                padding: 1.5rem;
            }

            .message-title {
                font-size: 1.25rem;
            }

            .sender-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .message-date {
                align-self: flex-start;
            }

            .message-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        /* Animation */
        .message-card {
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .message-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .message-actions {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="message-container">
        <div class="message-card">
            <!-- Priority Indicator -->
            <div class="priority-indicator"></div>

            <!-- Status Badge -->
            <div class="status-badge">
                <i class="bi bi-envelope-open"></i>
                Dibaca
            </div>

            <!-- Header Section -->
            <div class="message-header">
                <h1 class="message-title">
                    <i class="bi bi-chat-quote"></i>
                    <?= esc('Pesan dari ' . ($pesan['nama_pengirim'] ?? 'Alumni')) ?>

                </h1>
            </div>

            <!-- Sender Info -->
            <div class="sender-info">
                <div class="sender-details">
                    <div class="sender-avatar">
                        <?= strtoupper(substr($pesan['nama_pengirim'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="sender-text">
                        <h5><?= esc($pesan['nama_pengirim'] ?? 'Alumni') ?></h5>
                        <?php if (!empty($pesan['email_pengirim'])): ?>
                            <p class="email"><?= esc($pesan['email_pengirim']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="message-date">
                    <i class="bi bi-calendar-event"></i>
                    <?= !empty($pesan['created_at']) ? date('d M Y, H:i', strtotime($pesan['created_at'])) : 'Tanggal tidak tersedia' ?>
                </div>
            </div>

            <!-- Message Body -->
            <div class="message-body">
                <div class="message-content">
                    <div class="message-text">
                        <?= nl2br(esc($pesan['pesan'] ?? 'Pesan tidak tersedia')) ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="message-actions">
                    <a href="<?= base_url('alumni/notifikasi') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Kembali
                    </a>


                </div>
            </div>
        </div>
    </div>

    <script>
        // Reply Message Function
        function replyMessage() {
            const senderId = <?= json_encode($pesan['id_pengirim'] ?? '') ?>;
            if (senderId) {
                window.location.href = `<?= base_url('alumni/kirimPesanManual') ?>?reply_to=${senderId}`;
            } else {
                alert('Tidak dapat membalas pesan ini.');
            }
        }

        // Print Message Function
        function printMessage() {
            window.print();
        }

        // Mark message as read (optional AJAX)
        document.addEventListener('DOMContentLoaded', function() {
            const messageId = <?= json_encode($pesan['id'] ?? '') ?>;
            if (messageId) {
                console.log('Message opened:', messageId);
            }
        });

        // Add smooth scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.message-card');
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }
        });
    </script>
</body>

</html>
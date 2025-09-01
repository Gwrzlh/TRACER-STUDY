<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Pesan</title>
    
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
        }

        :root {
            --blue: #1E90FF;
            --orange: #FF7F50;
        }

        /* Message Form Styles */
        .message-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .message-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .message-card-header {
            background: linear-gradient(135deg, var(--blue) 0%, var(--orange) 100%);
            color: white;
            padding: 1.75rem 2rem;
        }

        .message-card-title {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .message-card-title i {
            margin-right: 0.75rem;
            font-size: 1.5rem;
            opacity: 0.9;
        }

        .message-card-body {
            padding: 2rem;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .alert-danger {
            background-color: #fff5f5;
            color: #c53030;
            border-left: 4px solid #e53e3e;
        }

        .alert-success {
            background-color: #f0fff4;
            color: #22543d;
            border-left: 4px solid #38a169;
        }

        .alert i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        /* Form Styles */
        .message-form {
            margin-top: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-label.required::after {
            content: " *";
            color: #e53e3e;
        }

        /* Recipient Info */
        .recipient-info {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
        }

        .recipient-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--blue) 0%, var(--orange) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 1rem;
            box-shadow: 0 2px 10px rgba(30, 144, 255, 0.3);
            flex-shrink: 0;
        }

        .recipient-details h6 {
            margin: 0;
            font-weight: 600;
            color: #2d3748;
            font-size: 1rem;
        }

        .recipient-details .email {
            color: #718096;
            font-size: 0.875rem;
            margin: 0.25rem 0 0 0;
        }

        /* Input Styles */
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.15);
            background-color: #fff;
        }

        .form-control::placeholder {
            color: #a0aec0;
            font-style: italic;
        }

        /* Textarea Styles */
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        /* Button Styles */
        .btn-group {
            display: flex;
            gap: 0.75rem;
            margin-top: 2rem;
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

        .btn-secondary {
            background-color: #718096;
            color: white;
            border-color: #718096;
        }

        .btn-secondary:hover {
            background-color: #4a5568;
            border-color: #4a5568;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(113, 128, 150, 0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Loading State */
        .btn.loading {
            position: relative;
            color: transparent;
        }

        .btn.loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Character Counter */
        .char-counter {
            text-align: right;
            font-size: 0.875rem;
            color: #718096;
            margin-top: 0.5rem;
        }

        .char-counter.over-limit {
            color: #e53e3e;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .message-container {
                margin: 1rem;
                padding: 0;
            }
            
            .message-card-header,
            .message-card-body {
                padding: 1.25rem;
            }
            
            .recipient-info {
                flex-direction: column;
                text-align: center;
            }
            
            .recipient-avatar {
                margin-right: 0;
                margin-bottom: 0.75rem;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }

        /* Validation Styles */
        .form-control.is-invalid {
            border-color: #e53e3e;
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.15);
        }

        .invalid-feedback {
            display: block;
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <div class="message-card">
            <div class="message-card-header">
                <h4 class="message-card-title">
                    <i class="bi bi-envelope"></i>
                    Kirim Pesan ke <?= esc($penerima['nama'] ?? 'Alumni') ?>
                </h4>
            </div>
            
            <div class="message-card-body">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('alumni/kirimPesanManual') ?>" class="message-form" id="messageForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_penerima" value="<?= $penerima['id'] ?>">

                    <!-- Penerima Info -->
                    <div class="form-group">
                        <label class="form-label">Penerima</label>
                        <div class="recipient-info">
                            <div class="recipient-avatar">
                                <?= strtoupper(substr($penerima['nama'] ?? 'A', 0, 1)) ?>
                            </div>
                            <div class="recipient-details">
                                <h6><?= esc($penerima['nama'] ?? 'Alumni') ?></h6>
                                <?php if (!empty($penerima['email'])): ?>
                                    <p class="email"><?= esc($penerima['email']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Message Field -->
                    <div class="form-group">
                        <label for="message" class="form-label required">Pesan</label>
                        <textarea id="message" 
                                  name="message" 
                                  class="form-control" 
                                  rows="6" 
                                  placeholder="Tulis pesan Anda di sini..."
                                  maxlength="1000"
                                  required></textarea>
                        <div id="charCounter" class="char-counter"></div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-send"></i>
                            Kirim Pesan
                        </button>
                        
                        <a href="<?= base_url('alumni/lihat_teman') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('messageForm');
            const submitBtn = document.getElementById('submitBtn');
            const messageField = document.getElementById('message');
            const charCounter = document.getElementById('charCounter');
            const maxLength = 1000;

            // Character counter
            function updateCharCounter() {
                const length = messageField.value.length;
                charCounter.textContent = `${length}/${maxLength} karakter`;
                
                if (length > maxLength) {
                    charCounter.classList.add('over-limit');
                    messageField.classList.add('is-invalid');
                } else {
                    charCounter.classList.remove('over-limit');
                    messageField.classList.remove('is-invalid');
                }
            }

            messageField.addEventListener('input', updateCharCounter);
            updateCharCounter(); // Initial call

            // Auto-resize textarea
            messageField.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Form submission handling
            form.addEventListener('submit', function(e) {
                if (messageField.value.trim() === '') {
                    e.preventDefault();
                    messageField.classList.add('is-invalid');
                    
                    let errorMsg = messageField.parentNode.querySelector('.invalid-feedback');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'invalid-feedback';
                        messageField.parentNode.appendChild(errorMsg);
                    }
                    errorMsg.textContent = 'Pesan tidak boleh kosong!';
                    
                    messageField.focus();
                    return;
                }

                if (messageField.value.length > maxLength) {
                    e.preventDefault();
                    alert(`Pesan terlalu panjang! Maksimal ${maxLength} karakter.`);
                    messageField.focus();
                    return;
                }
                
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                messageField.classList.remove('is-invalid');
                const errorMsg = messageField.parentNode.querySelector('.invalid-feedback');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });

            messageField.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('is-invalid');
                    const errorMsg = this.parentNode.querySelector('.invalid-feedback');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
        });
    </script>
</body>
</html>

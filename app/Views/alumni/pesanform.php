<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Pesan</title>
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="<?= base_url('css/alumni/pesanform.css') ?>">
</head>
<body>
    <div class="message-container">
        <div class="message-card">
            <div class="message-card-header">
                <h4 class="message-card-title">
                    <i class="bi bi-envelope"></i>
                    Kirim Pesan ke <?= esc($penerima['nama_lengkap'] ?? 'Alumni') ?>
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
                                <?= strtoupper(substr($penerima['nama_lengkap'] ?? 'A', 0, 1)) ?>
                            </div>
                            <div class="recipient-details">
                                <h6><?= esc($penerima['nama_lengkap'] ?? 'Alumni') ?></h6>
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
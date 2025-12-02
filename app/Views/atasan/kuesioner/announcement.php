<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - <?= esc($questionnaire_title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('css/alumni/kuesioner/announcement.css') ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <div class="announcement-container">
        <div class="announcement-card">
            <div class="announcement-header">
                <div class="announcement-icon">
                    ✓
                </div>
                <div class="announcement-title">Kuesioner Diselesaikan</div>
                <div class="announcement-subtitle">Terima kasih atas partisipasi Anda</div>
            </div>
            
            <div class="announcement-content">
                <div class="questionnaire-info">
                    <h6>Kuesioner</h6>
                    <p><?= esc($questionnaire_title) ?></p>
                </div>
                
                <div class="announcement-message">
                    <?= $announcement_content ?>
                </div>
            </div>
            
            <div class="announcement-actions">
                <a href="<?= base_url('atasan/kuesioner/daftar-alumni/' . $q_id) ?>" class="btn-continue">
                    Kembali ke Daftar Kuesioner
                </a>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Simple fade-in animation
            $('.announcement-card').css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            }).animate({
                'opacity': '1'
            }, 600).css({
                'transform': 'translateY(0)',
                'transition': 'transform 0.6s ease'
            });
            
            // Focus the continue button for accessibility
            setTimeout(function() {
                $('.btn-continue').focus();
            }, 800);
        });
    </script>
</body>

</html>
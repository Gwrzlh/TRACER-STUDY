<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengumuman - <?= esc($questionnaire_title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .announcement-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .announcement-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-width: 700px;
            width: 100%;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        
        .announcement-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .announcement-icon {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }
        
        .announcement-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            color: white;
        }
        
        .announcement-subtitle {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
            margin: 0;
        }
        
        .announcement-content {
            padding: 40px;
            color: #374151;
            line-height: 1.6;
        }
        
        .announcement-content h1,
        .announcement-content h2,
        .announcement-content h3,
        .announcement-content h4,
        .announcement-content h5,
        .announcement-content h6 {
            color: #111827;
            margin-top: 0;
            margin-bottom: 16px;
            font-weight: 600;
        }
        
        .announcement-content p {
            margin-bottom: 16px;
            font-size: 16px;
        }
        
        .announcement-content p:last-child {
            margin-bottom: 0;
        }
        
        .announcement-content ul,
        .announcement-content ol {
            margin-bottom: 16px;
            padding-left: 20px;
        }
        
        .announcement-content li {
            margin-bottom: 4px;
        }
        
        .announcement-content strong {
            font-weight: 600;
            color: #111827;
        }
        
        .announcement-content em {
            font-style: italic;
        }
        
        .announcement-actions {
            padding: 30px;
            background: #f9fafb;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .btn-continue {
            background: #2563eb;
            border: 1px solid #2563eb;
            padding: 12px 32px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
        }
        
        .btn-continue:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }
        
        .btn-continue:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 2px;
        }
        
        .questionnaire-info {
            background: #f3f4f6;
            border-left: 4px solid #2563eb;
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 0 4px 4px 0;
        }
        
        .questionnaire-info h6 {
            color: #2563eb;
            font-weight: 600;
            margin: 0 0 4px 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .questionnaire-info p {
            color: #6b7280;
            margin: 0;
            font-size: 16px;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .announcement-container {
                padding: 15px;
            }
            
            .announcement-title {
                font-size: 20px;
            }
            
            .announcement-content {
                padding: 30px 25px;
            }
            
            .announcement-actions {
                padding: 25px;
            }
            
            .btn-continue {
                width: 100%;
                padding: 14px 20px;
            }
        }
    </style>
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
                <a href="<?= base_url('alumni/questionnaires') ?>" class="btn-continue">
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
<?php
use App\Models\WelcomePageModel;
$model = new WelcomePageModel();
$data = $model->first();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Landing Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .section {
            padding: 80px 0;
        }

        .section h2 {
             margin-top: 0;
             margin-bottom: 20px;
        }


        .hero-image {
            max-width: 100%;
            height: auto;
            border-radius: 30px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        h2 {
            font-weight: 700;
            font-size: 2.5rem;
            color: #212529;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.7;
            color: #555;
        }

        .btn-primary {
            padding: 12px 30px;
            font-size: 1rem;
            border-radius: 8px; 
        }

        .video-custom iframe {
            border-radius: 30px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        @media (max-width: 767px) {
            h2 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<?= view('layout/navbar') ?>

<!-- Section 1: Gambar + Teks -->
<section class="section bg-white">
    <div class="container">
        <div class="row align-items-top g-5">
            <div class="col-md-6">
                <img src="<?= base_url($data['image_path']) ?>" alt="Tracer Study" class="hero-image">
            </div>
            <div class="col-md-6">
                <h2><?= esc($data['title_1']) ?></h2>
                <p><?= esc($data['desc_1']) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Section 2: Teks + Video -->
<section class="section bg-light">
    <div class="container">
        <div class="row align-items-top g-5">
            <div class="col-md-6">
                <h2><?= esc($data['title_2']) ?></h2>
                <p><?= esc($data['desc_2']) ?></p>
                <a href="<?= base_url('/login') ?>" class="btn btn-primary mt-3">Login</a>
            </div>
            <div class="col-md-6">
                <div class="ratio ratio-16x9 video-custom">
                    <iframe 
                        src="<?= esc($data['youtube_url']) ?>" 
                        title="YouTube video"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?= view('layout/footer') ?>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

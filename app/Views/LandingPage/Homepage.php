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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
      body {
        font-family: 'Inter', sans-serif;
        background-color: #f3f4f6;
        color: #1f2937;
      }

      section {
        padding: 80px 20px;
      }

      h2 {
        font-weight: 700;
        font-size: 2rem;
        color: #111827;
        margin-bottom: 20px;
        border-left: 5px solid #2563eb;
        padding-left: 12px;
      }

      p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #374151;
      }

      .hero-image {
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        width: 100%;
        height: auto;
        transition: all .3s ease;
      }

      .hero-image:hover {
        transform: scale(1.02);
      }

      .video-custom iframe {
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      }

      .btn-primary {
        background-color: #2563eb;
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        transition: background .3s ease;
      }

      .btn-primary:hover {
        background-color: #1d4ed8;
      }

      @media (max-width: 768px) {
        h2 {
          font-size: 1.5rem;
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
<section class="bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <img src="<?= base_url($data['image_path']) ?>" alt="Tracer Study" class="hero-image">
            </div>
            <div class="col-md-6">
                <h2><?= esc($data['title_1']) ?></h2>
                <p><?= $data['desc_1'] ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Section 2: Teks + Video -->
<section class="bg-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <h2><?= esc($data['title_2']) ?></h2>
                <p><?= $data['desc_2'] ?></p>
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

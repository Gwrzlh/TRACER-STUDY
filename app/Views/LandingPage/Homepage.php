<?php

use App\Models\WelcomePageModel;
use App\Models\SiteSettingModel;

// Ambil konten welcome page
$model = new WelcomePageModel();
$data = $model->first();

// Ambil setting tombol dari DB
$settingModel = new SiteSettingModel();
$settings = [
    'survey_button_text'        => get_setting('survey_button_text', 'Mulai Survey'),
    'survey_button_color'       => get_setting('survey_button_color', '#ef4444'),
    'survey_button_text_color'  => get_setting('survey_button_text_color', '#ffffff'),
    'survey_button_hover_color' => get_setting('survey_button_hover_color', '#dc2626'),
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Landing Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/landingpage/Homepage.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
      body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background-color: #f9fafb;
        color: #111827;
      }

      /* Hero */
      .hero-carousel .carousel-item {
        height: 100vh;
        min-height: 500px;
        background: no-repeat center center scroll;
        background-size: cover;
        position: relative;
      }

      .hero-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
        padding: 20px;
      }

      /* Teks lebih kecil */
      .hero-overlay h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 12px;
      }

      .hero-overlay p {
        font-size: 1rem;
        margin-bottom: 20px;
        color: #e5e7eb;
      }

      /* Section */
      section {
        padding: 80px 20px;
      }

      h2.section-title {
        font-weight: 700;
        font-size: 1.6rem;
        color: #111827;
        margin-bottom: 20px;
        border-left: 5px solid #2563eb;
        padding-left: 12px;
      }

      p.section-desc {
        font-size: 1rem;
        color: #374151;
        line-height: 1.8;
      }

      /* Carousel Controls & Indicators */
      .carousel-control-prev-icon,
      .carousel-control-next-icon {
        background-color: rgba(0,0,0,0.6);
        border-radius: 50%;
        padding: 15px;
      }

      .carousel-indicators [data-bs-target] {
        background-color: #fff;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        opacity: 0.7;
      }

      .carousel-indicators .active {
        background-color: #2563eb;
        opacity: 1;
      }

      /* Responsive */
      @media (max-width: 768px) {
        .hero-overlay h1 {
          font-size: 1.5rem;
        }
        .hero-overlay p {
          font-size: 0.9rem;
        }
      }
    </style>
</head>

<body>


<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
  <div class="carousel-inner">
    <!-- Slide 1 -->
    <div class="carousel-item active">
      <div class="w-100 h-100 animate__animated animate__fadeIn animate__slow" 
           style="background-image: url('<?= base_url($data['image_path']) ?>'); background-size: cover; background-position: center;">
        <div class="hero-overlay">
          <div>
            <h1 class="animate__animated animate__fadeInDown animate__slow"><?= esc($data['title_1']) ?></h1>
            <p class="animate__animated animate__fadeInLeft animate__delay-1s animate__slow"><?= $data['desc_1'] ?></p>
            <a href="<?= base_url('/login') ?>"
               class="animate__animated animate__bounceIn animate__delay-2s animate__slow"
               style="background-color: <?= esc($settings['survey_button_color']) ?>;
                      color: <?= esc($settings['survey_button_text_color']) ?>;
                      padding: 10px 26px;
                      border-radius: 30px;
                      font-weight: 600;
                      font-size: 1rem;
                      text-decoration: none;
                      display: inline-block;"
               onmouseover="this.style.backgroundColor='<?= esc($settings['survey_button_hover_color']) ?>'"
               onmouseout="this.style.backgroundColor='<?= esc($settings['survey_button_color']) ?>'">
               <?= esc($settings['survey_button_text']) ?>
            </a>
          </div>
        </div>
    </section>

    <!-- Section 2: Teks + Video -->
    <section class="section bg-light">
        <div class="container">
            <div class="row align-items-top g-5">
                <div class="col-md-6">
                    <h2><?= esc($data['title_2']) ?></h2>
                    <p><?= $data['desc_2'] ?></p>
                    <a href="<?= base_url('/login') ?>"
                        class="btn mt-3"
                        style="background-color: <?= $loginColor ?>; color: <?= $loginTextColor ?>;"
                        onmouseover="this.style.backgroundColor='<?= $loginHover ?>';"
                        onmouseout="this.style.backgroundColor='<?= $loginColor ?>';">
                        <?= esc($loginText) ?>
                    </a>
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
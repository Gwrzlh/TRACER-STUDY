<?php
use App\Models\WelcomePageModel;
use App\Models\SiteSettingModel;

// Ambil konten welcome page
$model = new WelcomePageModel();
$data = $model->first();

// Ambil setting tombol dari DB
$settingModel = new SiteSettingModel();
$settings = [
    'survey_button_text'        => get_setting('survey_button_text', 'Mulai Login'),
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

    <!-- Bootstrap -->
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

      /* 🎬 Modern Video Showcase */
      .video-section {
        margin-top: 60px;
        background: linear-gradient(135deg, #f9fafb 0%, #e0f2fe 100%);
        border-radius: 25px;
        padding: 60px 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      }

      .video-title {
        font-weight: 800;
        font-size: 1.6rem;
        color: #1e3a8a;
        margin-bottom: 35px;
        position: relative;
        display: inline-block;
      }

      .video-title::after {
        content: "";
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 3px;
        background: linear-gradient(90deg, #2563eb, #06b6d4);
        border-radius: 2px;
      }

      .video-grid {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 30px;
      }

      .video-card {
        position: relative;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 20px;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.3);
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transition: all 0.4s ease;
        max-width: 900px;
        width: 100%;
      }

      .video-card:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        border-color: rgba(37,99,235,0.5);
      }

      .video-card iframe,
      .video-card video {
        width: 100%;
        border: none;
        border-radius: 20px;
      }

      @media (max-width: 768px) {
        .hero-overlay h1 { font-size: 1.5rem; }
        .hero-overlay p { font-size: 0.9rem; }
        .video-title { font-size: 1.3rem; }
      }
    </style>
</head>
<body>

<?= view('layout/navbar') ?>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="w-100 h-100" 
           style="background-image: url('<?= base_url($data['image_path']) ?>'); background-size: cover; background-position: center;">
        <div class="hero-overlay">
          <div>
            <h1 class="animate__animated animate__fadeInDown"><?= esc($data['title_1']) ?></h1>
            <p class="animate__animated animate__fadeInLeft animate__delay-1s"><?= $data['desc_1'] ?></p>
            <a href="<?= base_url('/login') ?>"
               class="animate__animated animate__bounceIn animate__delay-0,8s"
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
      </div>
    </div>

    <div class="carousel-item">
      <div class="w-100 h-100" 
           style="background-image: url('<?= base_url($data['image_path_2']) ?>'); background-size: cover; background-position: center;">
        <div class="hero-overlay">
          <div>
            <h1 class="animate__animated animate__fadeInDown"><?= esc($data['title_2']) ?></h1>
            <p class="animate__animated animate__fadeInRight animate__delay-1s"><?= $data['desc_2'] ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- Section 2 -->
<section class="bg-white">
  <div class="container text-center">
    <h2 class="section-title animate__animated animate__lightSpeedInLeft"><?= esc($data['title_3']) ?></h2>
    <p class="section-desc animate__animated animate__fadeInUp animate__delay-1s"><?= $data['desc_3'] ?></p>

    <!-- ✅ Modern Enhanced Video Section -->
    <?php if (!empty($data['youtube_url']) || !empty($data['video_path'])): ?>
      <div class="video-section animate__animated animate__fadeInUp animate__delay-2s">
        <h3 class="video-title">🎥 Saksikan Video Kami</h3>
        <div class="video-grid">

          <?php if (!empty($data['youtube_url'])): ?>
            <div class="video-card">
              <div class="ratio ratio-16x9">
                <iframe 
                    src="<?= esc($data['youtube_url']) ?>" 
                    title="YouTube video"
                    allowfullscreen>
                </iframe>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($data['video_path'])): ?>
            <div class="video-card">
              <div class="ratio ratio-16x9">
                <video controls>
                  <source src="<?= base_url($data['video_path']) ?>" type="video/mp4">
                  Browser kamu tidak mendukung pemutaran video.
                </video>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view('layout/footer') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

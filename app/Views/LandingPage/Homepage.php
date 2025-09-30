<?php

use App\Models\WelcomePageModel;

$model = new WelcomePageModel();
$data = $model->first();
$loginText = get_setting('login_button_text', 'Login');
$loginColor = get_setting('login_button_color', '#007bff');
$loginTextColor = get_setting('login_button_text_color', '#ffffff');
$loginHover = get_setting('login_button_hover_color', '#0056b3');
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
                    <p><?= $data['desc_1'] ?></p>
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
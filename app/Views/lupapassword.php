<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lupa Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left {
            width: 35%;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            z-index: 10;
            animation: fadeInLeft 1s ease;
        }

        .logo {
            width: 70px;
            position: absolute;
            top: 30px;
            left: 50px;
        }

        h2 {
            font-size: 26px;
            margin-bottom: 12px;
            color: #111827;
        }

        p {
            color: #4B5563;
            margin-bottom: 25px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 14px 12px;
            border: 1.5px solid #D1D5DB;
            border-radius: 8px;
            background: #F9FAFB;
            font-size: 15px;
            margin-bottom: 20px;
        }

        input:focus {
            border-color: #13366eff;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background-color: #3B82F6;
            color: white;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #2563EB;
            transform: translateY(-1px);
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            font-size: 14px;
            color: #3B82F6;
            text-decoration: none;
        }

        .right {
            width: 65%;
            position: relative;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transform: scale(1.02);
            transition: 1s ease-in-out;
            filter: brightness(0.6) contrast(1.1) saturate(1.1);
        }

        .slide.active {
            opacity: 1;
            transform: scale(1);
            z-index: 1;
        }

        .nav {
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            pointer-events: none;
        }

        .nav button {
            width: 60px;
            height: 100%;
            background: transparent;
            border: none;
            pointer-events: auto;
            cursor: pointer;
            font-size: 0;
        }

        .nav button:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        @keyframes fadeInLeft {
            0% {
                opacity: 0;
                transform: translateX(-40px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left,
            .right {
                width: 100%;
                height: 50%;
            }

            .left {
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <img src="images/logo.png" class="logo" alt="Logo POLBAN" />
            <h2>Lupa Password</h2>
            <p>Masukkan email Anda. Kami akan mengirimkan link reset password ke email tersebut.</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <form action="<?= base_url('lupapassword') ?>" method="post">
                <?= csrf_field() ?>
                <input type="email" name="email" placeholder="Alamat Email" required autocomplete="off">
                <button type="submit">Kirim Link Reset</button>
            </form>

            <a href="<?= base_url('login') ?>">&larr; Kembali ke Login</a>
        </div>

        <div class="right">
            <div class="slide active" style="background-image: url('images/polban.jpg');"></div>
            <div class="slide" style="background-image: url('images/polban2.jpeg');"></div>
            <div class="slide" style="background-image: url('images/polban3.jpeg');"></div>

            <div class="nav">
                <button onclick="prevSlide()"></button>
                <button onclick="nextSlide()"></button>
            </div>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll(".slide");

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove("active");
                if (i === index) {
                    slide.classList.add("active");
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        setInterval(nextSlide, 8000);
    </script>
</body>

</html>

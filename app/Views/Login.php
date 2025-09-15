<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Tracer Study</title>
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

        h1 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #111827;
        }

        p {
            color: #4B5563;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            position: relative;
            margin-bottom: 25px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 14px 12px;
            border: 1.5px solid #D1D5DB;
            border-radius: 8px;
            background: #F9FAFB;
            font-size: 15px;
            color: #111827;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #13366eff;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .form-group label {
            position: absolute;
            top: 14px;
            left: 12px;
            font-size: 13px;
            color: #9CA3AF;
            pointer-events: none;
            transition: all 0.2s ease;
            background-color: transparent;
        }

        .form-group input:focus+label,
        .form-group input:not(:placeholder-shown)+label {
            top: -8px;
            font-size: 11px;
            color: #3B82F6;
            background-color: #F9FAFB;
            padding: 0 4px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #374151;
        }

        .checkbox-group input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #3B82F6;
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

        a.back {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            color: #3B82F6;
            font-size: 14px;
            text-decoration: none;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <img src="images/logo.png" class="logo" alt="Logo POLBAN" />
            <h1>Selamat Datang</h1>
            <p>Silakan login menggunakan akun Anda</p>
            <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>


            <?php if (session()->getFlashdata('error')) : ?>
                <div style="margin-bottom: 15px; padding: 12px; background-color: #fee2e2; color: #991b1b; border-radius: 6px; font-size: 14px;">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>


            <form action="<?= base_url('do-login') ?>" method="post">

                <div class="form-group">
                    <input type="text" name="username" placeholder=" " required />
                    <label>Username atau Email</label>
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder=" " required />
                    <label>Password</label>
                </div>

                <!-- <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Tetap login</label>
                </div> -->

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <a href="<?= base_url('lupapassword') ?>" style="font-size: 14px; color: #3B82F6; text-decoration: none;">
                        Lupa password?
                    </a>
                </div>

                <button type="submit">Masuk</button>
                <a href="/" class="back">&larr; Kembali ke Beranda</a>
            </form>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLoggedIn = sessionStorage.getItem('tab_logged_in');
            const serverLoggedIn = <?= $server_logged_in ? 'true' : 'false' ?>;
            const viaCookie = <?= $via_cookie ? 'true' : 'false' ?>;

            if (serverLoggedIn && !viaCookie && !tabLoggedIn) {
                // Tab baru tanpa "remember me" → logout paksa
                window.location.href = '<?= site_url("logout") ?>';
            }

            if (serverLoggedIn && !viaCookie) {
                sessionStorage.setItem('tab_logged_in', 'true');
            }
        });
    </script>


</body>

</html>
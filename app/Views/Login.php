<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Tracer Study</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: url('/images/polban.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        .login-box {
            background: rgba(0, 0, 0, 0.6);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 1;
        }

        .logo {
            max-height: 50px;
        }

        .form-floating input {
            background-color: rgba(255, 255, 255, 0.9);
            padding-right: 2.75rem;
            transition: all 0.3s ease;
        }

        .form-floating input:focus {
            background-color: rgba(255, 255, 255, 1);
            border-color: #0033cc;
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 204, 0.25);
        }

        .form-floating label {
            color: #666;
        }

        .form-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            font-size: 1.1rem;
            color: #333;
            pointer-events: none;
        }

        .btn-biru {
            background-color: #0033cc;
            color: white;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-biru:hover {
            background-color: #0022aa;
        }

        .btn-biru:active {
            opacity: 0.85;
            transform: scale(0.97);
        }

        .text-white-50 a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
        }

        .text-white-50 a:hover {
            color: white;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Alert Styling */
        #custom-alert {
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Tambahan posisi tombol close */
        .custom-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #a70000;
        }

        .custom-close:hover {
            color: black;
        }
    </style>
</head>

<body>

    <!-- Flashdata Alert -->
    <?php if (session()->getFlashdata('error')): ?>
        <div id="custom-alert"
            class="position-absolute top-0 start-50 translate-middle-x mt-3 px-4 py-3 rounded-3 shadow fade show d-flex align-items-center justify-content-between gap-3"
            style="background-color: #ffe0e0; color: #a70000; max-width: 460px; z-index: 1050; border: 1px solid #ffbdbd;">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle fa-lg"></i>
                <div class="me-4"><?= session()->getFlashdata('error') ?></div>
            </div>
            <button type="button" class="custom-close" onclick="closeAlert()" aria-label="Close">
                &times;
            </button>
        </div>
    <?php endif; ?>

    <!-- Login Form -->
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="col-md-5 login-box">
            <div class="text-center mb-4">
                <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2">
                <h3 class="fw-bold">Selamat Datang!</h3>
                <p class="text-white-50">Login dengan masukkan Username atau Email</p>
            </div>

            <form method="post" action="/do-login">
                <!-- Username -->
                <div class="form-group mb-3 position-relative">
                    <div class="form-floating">
                        <input type="text" class="form-control pe-5" name="username" id="floatingUsername"
                            placeholder="Username" required>
                        <label for="floatingUsername">Username atau Email</label>
                        <i class="fas fa-user form-icon"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group mb-3 position-relative">
                    <div class="form-floating">
                        <input type="password" class="form-control pe-5" name="password" id="floatingPassword"
                            placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                        <i class="fas fa-lock form-icon"></i>
                    </div>
                </div>

                <!-- Remember -->
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label text-white-50" for="remember">Stay logged in</label>
                    <a href="#" class="float-end text-white-50">Lupa password?</a>
                </div>

                <!-- Button -->
                <button type="submit" class="btn btn-biru w-100 py-2">Masuk</button>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto close alert after 4 seconds
        setTimeout(() => {
            const alertEl = document.getElementById('custom-alert');
            if (alertEl) {
                alertEl.classList.remove('show');
                alertEl.classList.add('fade');
                setTimeout(() => alertEl.remove(), 300);
            }
        }, 4000);

        // Manual close alert
        function closeAlert() {
            const alertEl = document.getElementById('custom-alert');
            if (alertEl) {
                alertEl.classList.remove('show');
                alertEl.classList.add('fade');
                setTimeout(() => alertEl.remove(), 300);
            }
        }
    </script>

</body>

</html>
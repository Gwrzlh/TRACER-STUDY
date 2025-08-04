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
        }

        .login-box {
            background: rgba(0, 0, 0, 0.6);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }

        .logo {
            max-height: 50px;
        }

        .form-floating {
            position: relative;
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
            right: 20px;
            transform: translateY(-50%);
            font-size: 1.1rem;
            color: #333;
            z-index: 10;
            cursor: pointer;
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

        /* Alert animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                top: 0;
            }

            to {
                opacity: 1;
                top: 20px;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                top: 20px;
            }

            to {
                opacity: 0;
                top: 0;
            }
        }

        .alert.fade-custom {
            animation: fadeIn 0.5s ease-out;
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            width: 90%;
            max-width: 500px;
        }

        .alert.fade-custom.hide {
            animation: fadeOut 0.5s ease-in forwards;
        }

        .alert .btn-close {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
        }
    </style>
</head>

<body>

    <!-- ALERT ERROR -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade-custom show shadow" id="customAlert" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- FORM LOGIN -->
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="col-md-5 login-box position-relative">
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
                        <i class="fas fa-eye form-icon toggle-password" onclick="togglePassword()"></i>
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
        function togglePassword() {
            const input = document.getElementById("floatingPassword");
            const icon = document.querySelector(".toggle-password");

            const isPassword = input.getAttribute("type") === "password";
            input.setAttribute("type", isPassword ? "text" : "password");

            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        }

        // Auto-hide alert after 5 seconds
        setTimeout(() => {
            const alert = document.getElementById('customAlert');
            if (alert) {
                alert.classList.add('hide');
                setTimeout(() => alert.remove(), 600);
            }
        }, 5000);
    </script>

</body>

</html>
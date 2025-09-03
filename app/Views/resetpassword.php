<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #3B82F6;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        button:hover {
            background: #2563EB;
        }

        .alert {
            padding: 10px;
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

        .toggle-password {
            cursor: pointer;
            font-size: 12px;
            color: #3B82F6;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Reset Password</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('resetpassword') ?>" method="post">

            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= $token ?>">

            <input type="password" id="password" name="password" placeholder="Password Baru" minlength="8" required autocomplete="off">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password" minlength="8" required autocomplete="off">

            <span class="toggle-password" onclick="togglePassword()">Tampilkan / Sembunyikan Password</span>

            <button type="submit">Simpan Password</button>
        </form>

        <a href="<?= base_url('login') ?>">&larr; Kembali ke Login</a>
    </div>

    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            const confirm = document.getElementById('confirm_password');
            if (pass.type === "password") {
                pass.type = "text";
                confirm.type = "text";
            } else {
                pass.type = "password";
                confirm.type = "password";
            }
        }
    </script>
</body>

</html>
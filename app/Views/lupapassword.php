<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
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

        p {
            font-size: 14px;
            margin-bottom: 20px;
            color: #555;
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
    </style>
</head>

<body>
    <div class="card">
        <h2>Lupa Password</h2>
        <p>Masukkan email Anda. Kami akan mengirimkan link reset password ke email tersebut.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('lupapassword') ?>" method="post">
            <?= csrf_field() ?>
            <input type="email" name="email" placeholder="Alamat Email" required autocomplete="off">
            <button type="submit">Kirim Link Reset</button>
        </form>

        <a href="<?= base_url('login') ?>">&larr; Kembali ke Login</a>
    </div>
</body>

</html>
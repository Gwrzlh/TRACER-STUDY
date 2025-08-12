<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Alumni</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f9f9f9; }
        header { background: #001BB7; color: white; padding: 15px; }
        nav a { color: white; margin-right: 15px; text-decoration: none; }
        main { padding: 20px; }
        .card {
            background: white; padding: 20px; border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            max-width: 600px; margin: auto;
        }
    </style>
</head>
<body>
<header>
    <h1>Halo, <?= session()->get('alumniName') ?></h1>
    <nav>
        <a href="/alumni">Beranda</a>
        <a href="/alumni/logout">Logout</a>
    </nav>
</header>
<main>
    <div class="card">
        <h2>Selamat Datang di Portal Alumni Polban</h2>
        <p>Anda dapat mengisi tracer study, melihat info kampus, dan terhubung dengan alumni lain.</p>
    </div>
</main>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Alumni</title>
</head>
<body>
    <h1>Selamat Datang Alumni</h1>
    <p>Halo, <?= session('username'); ?>! Anda sedang di dashboard alumni.</p>

    <a href="<?= base_url('logout'); ?>" 
   style="display:inline-block; background-color: #ff6600; color: white; 
          padding: 8px 20px; text-decoration: none; border-radius: 5px; 
          font-size: 14px; font-weight: normal;">
   Logout
</a>
</body>
</html>

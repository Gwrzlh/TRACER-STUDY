<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <h2>Selamat datang di Dashboard</h2>

    <p>Halo, <?= session()->get('username') ?> (<?= session()->get('email') ?>)</p>
    <p>Role ID: <?= session()->get('role_id') ?></p>

    <a href="/logout">Logout</a>
</body>

</html>
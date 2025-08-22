<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Kaprodi Supervisi</title>
</head>

<body>
    <h1>Dashboard Kaprodi dengan Hak Supervisi</h1>

    <p>Halo, <b><?= session('username') ?></b>!<br>
        Anda login sebagai <b>Kaprodi</b> dengan hak <b>Supervisi</b>.</p>

    <h3>Fitur Tambahan:</h3>
    <ul>
        <li>Monitoring aktivitas Surveyor</li>
        <li>Validasi hasil survei alumni</li>
        <li>Manajemen surveyor yang ditugaskan</li>
    </ul>

    <br>
    <a href="<?= site_url('logout') ?>">Logout</a>
</body>

</html>
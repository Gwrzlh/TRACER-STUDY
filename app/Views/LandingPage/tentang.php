<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($tentang['judul']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background-color: #deddddff;
        color: #1f2937;
    }

    main {
        flex: 1;
        padding: 50px 20px;
    }

    .content {
    width: 100%;
    max-width: 100%;
    margin-left: 0;
    margin-right: auto;
    background-color: #ffffff;
    padding: 50px 60px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}


    h2 {
        font-weight: 700;
        font-size: 2.25rem;
        margin-bottom: 25px;
        color: #111827;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 10px;
    }

    p {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 20px;
        color: #374151;
    }

    ul {
        padding-left: 1.5rem;
        margin-bottom: 20px;
    }

    ul li {
        font-size: 1.05rem;
        margin-bottom: 10px;
        line-height: 1.6;
        color: #4b5563;
    }

    @media (max-width: 767px) {
        .content {
            padding: 20px;
        }

        h2 {
            font-size: 1.75rem;
        }

        p, ul li {
            font-size: 1rem;
        }
    }
</style>

    <!-- Optional: Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?= view('layout/navbar') ?>

<!-- Konten utama -->
<main>
    <div class="content">
        <h2><?= esc($tentang['judul']) ?></h2>
<p><?= nl2br(esc($tentang['isi'])) ?></p>

    </div>
</main>

<!-- Footer -->
<?= view('layout/footer') ?>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

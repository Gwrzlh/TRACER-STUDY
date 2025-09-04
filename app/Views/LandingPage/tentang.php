<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= esc($tentang['judul']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f3f4f6;
      color: #1f2937;
    }

    main {
      padding: 60px 20px;
    }

    .section-wrapper {
      max-width: 1100px;
      margin: 0 auto;
    }

    .card-content {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 6px 24px rgba(0, 0, 0, 0.06);
      padding: 50px 60px;
      transition: all 0.3s ease;
    }

    .card-content:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    }

    h2 {
      font-weight: 700;
      font-size: 2.25rem;
      color: #111827;
      margin-bottom: 30px;
      border-bottom: 3px solid #2563eb;
      display: inline-block;
      padding-bottom: 6px;
    }

    p {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #374151;
      margin-bottom: 20px;
    }

    ul {
      padding-left: 1.5rem;
      margin-bottom: 20px;
    }

    ul li {
      font-size: 1.05rem;
      line-height: 1.6;
      margin-bottom: 10px;
      color: #4b5563;
    }

    @media (max-width: 768px) {
      .card-content {
        padding: 25px 20px;
      }

      h2 {
        font-size: 1.75rem;
      }

      p, ul li {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<?= view('layout/navbar') ?>

<!-- Konten -->
<main>
  <div class="section-wrapper">
    <div class="card-content">
      <h2><?= esc($tentang['judul']) ?></h2>
      <?= $tentang['isi'] ?>
    </div>
  </div>
</main>

<!-- Footer -->
<?= view('layout/footer') ?>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

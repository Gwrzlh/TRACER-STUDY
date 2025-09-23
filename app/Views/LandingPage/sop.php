<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Standar Operasional Prosedur</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9fafb;
      color: #1f2937;
    }

    /* Hero */
    .hero {
      background: linear-gradient(135deg, #2563eb, #1e40af);
      color: #fff;
      padding: 100px 20px 70px;
      text-align: center;
      border-radius: 0 0 40px 40px;
    }

    .hero h1 {
      font-size: 2.8rem;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .hero p {
      font-size: 1.2rem;
      color: #e5e7eb;
      max-width: 700px;
      margin: 0 auto;
    }

    /* Dropdown Section */
    .dropdown-section {
      margin-top: 40px;
      margin-bottom: 40px;
      text-align: center;
    }

    /* Konten */
    main {
      padding: 60px 20px;
    }

    .card-content {
      background: #fff;
      border-left: 6px solid #2563eb;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
      padding: 35px 40px;
      margin-bottom: 30px;
      transition: all 0.3s ease;
    }

    .card-content:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    }

    .card-content h4 {
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 20px;
      color: #2563eb;
    }

    .card-content p,
    .card-content ul {
      font-size: 1.05rem;
      line-height: 1.7;
      color: #374151;
    }

    .card-content ul {
      padding-left: 1.5rem;
      margin-bottom: 0;
    }

    .card-content ul li {
      margin-bottom: 10px;
    }

    /* Styling gambar SOP */
    .sop-image img {
      max-width: 70%;
      height: auto;
      border-radius: 12px;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2rem;
      }
      .hero p {
        font-size: 1rem;
      }
      .card-content {
        padding: 25px 20px;
      }
      .card-content h4 {
        font-size: 1.25rem;
      }
      .sop-image img {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<?= view('layout/navbar') ?>

<!-- Hero -->
<section class="hero animate__animated animate__fadeIn">
  <h1 class="animate__animated animate__fadeInDown">Standar Operasional Prosedur</h1>
  <p class="animate__animated animate__fadeInUp animate__delay-1s">Panduan kerja untuk memastikan kualitas layanan kami</p>
</section>

<!-- Dropdown -->
<div class="dropdown-section animate__animated animate__fadeInUp animate__delay-1s">
  <div class="dropdown d-inline-block">
    <button class="btn btn-primary dropdown-toggle px-4 py-2 rounded-pill shadow-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
      Pilih Menu
    </button>
    <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton">
      <li><a class="dropdown-item <?= service('uri')->getSegment(1) === 'tentang' ? 'active' : '' ?>" href="<?= base_url('tentang') ?>">Tentang</a></li>
      <li><a class="dropdown-item active" href="<?= base_url('sop') ?>">SOP</a></li>
      <li><a class="dropdown-item <?= service('uri')->getSegment(1) === 'event' ? 'active' : '' ?>" href="<?= base_url('event') ?>">Event</a></li>
    </ul>
  </div>
</div>

<!-- Konten -->
<main>
  <div class="container">
    <div class="card-content animate__animated animate__fadeInUp animate__delay-1s text-center">

      <?php if (!empty($tentang['gambar'])): ?>
        <div class="sop-image mb-4">
          <img src="<?= base_url('uploads/' . $tentang['gambar']) ?>" alt="Gambar SOP" class="img-fluid shadow">
        </div>
      <?php endif; ?>

      <h4><?= esc($tentang['judul2']) ?></h4>
      <div class="animate__animated animate__fadeIn animate__delay-2s text-start">
        <?= $tentang['isi2'] ?>
      </div>
    </div>
  </div>
</main>

<!-- Footer -->
<?= view('layout/footer') ?>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

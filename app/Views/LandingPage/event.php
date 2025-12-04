<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= esc($tentang['judul3']) ?></title>
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
    .hero {
      background: linear-gradient(135deg, #2563eb, #1e40af);
      color: #fff;
      padding: 80px 20px 60px;
      text-align: center;
      border-radius: 0 0 40px 40px;
    }
    .hero h1 {
      font-size: 2.8rem;
      font-weight: 700;
      margin-bottom: 10px;
    }
    .hero p {
      font-size: 1.2rem;
      color: #e5e7eb;
    }
    .dropdown-section {
      margin-top: 30px;
      margin-bottom: 30px;
      text-align: center;
    }
    main {
      padding: 60px 20px;
    }
    .event-section {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 40px;
    }
    .event-card {
      flex: 1;
      min-width: 280px;
      background: rgba(255, 255, 255, 0.75);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
    }
    .event-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(37, 99, 235, 0.3);
    }
    .event-card h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 20px;
      color: #1e3a8a;
    }
    .event-card p {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #374151;
    }
    .event-image {
      flex: 1;
      min-width: 280px;
      text-align: center;
    }
    .event-image img {
      max-width: 100%;
      border-radius: 20px;
      border: 6px solid #fff;
      box-shadow: 0 10px 35px rgba(0,0,0,0.25);
      transition: transform 0.3s ease;
    }
    .event-image img:hover {
      transform: scale(1.05);
    }
    @media (max-width: 768px) {
      .event-section {
        flex-direction: column;
      }
      .event-card h2 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<?= view('layout/navbar') ?>

<!-- Hero -->
<section class="hero animate_animated animate_fadeIn">
  <h1 class="animate_animated animate_fadeInDown">
      <?= esc($tentang['judul3']) ?>
  </h1>
  <p class="animate_animated animatefadeInUp animate_delay-1s">
      Kegiatan & Event Kami
  </p>
</section>

<!-- Dropdown -->
<div class="dropdown-section animate_animated animatefadeInUp animate_delay-1s">
  <div class="dropdown d-inline-block">
    <button class="btn btn-primary dropdown-toggle px-4 py-2 rounded-pill shadow-sm"
            type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
            aria-expanded="false">
      Pilih Menu
    </button>
    <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton">
      <li><a class="dropdown-item" href="<?= base_url('tentang') ?>">Tentang</a></li>
      <li><a class="dropdown-item" href="<?= base_url('sop') ?>">SOP</a></li>
      <li><a class="dropdown-item active" href="<?= base_url('event') ?>">Event</a></li>
    </ul>
  </div>
</div>

<!-- Konten -->
<main>
  <div class="container">
    <div class="event-section animate_animated animatefadeInUp animate_delay-1s">

      <div class="event-card">
        <h2><?= esc($tentang['judul3']) ?></h2>
        <p><?= $tentang['isi3'] ?></p>
      </div>

      <!-- Aman: cek gambar2 jika ada -->
      <?php if (!empty($tentang['gambar2'])): ?>
        <div class="event-image">
          <img src="<?= base_url('uploads/' . $tentang['gambar2']) ?>"
               alt="Gambar Event">
        </div>
      <?php endif; ?>

    </div>
  </div>
</main>

<!-- Footer -->
<?= view('layout/footer') ?>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
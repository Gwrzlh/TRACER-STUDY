<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Tracer Study</title>
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

    /* Konten */
    main {
      padding: 60px 20px;
    }

    .laporan-item {
      background: #fff;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(0,0,0,0.08);
      margin-bottom: 40px;
      transition: all 0.3s ease;
    }

    .laporan-item:hover {
      transform: translateY(-6px);
      box-shadow: 0 14px 32px rgba(0,0,0,0.12);
    }

    .laporan-header {
      background: #1e40af;
      color: #fff;
      padding: 20px 30px;
    }

    .laporan-header h2 {
      font-weight: 700;
      font-size: 1.6rem;
      margin: 0;
    }

    .laporan-body {
      padding: 30px 35px;
    }

    .laporan-body p,
    .laporan-body ul li {
      font-size: 1.05rem;
      line-height: 1.7;
      color: #374151;
    }

    .laporan-body img {
      border-radius: 12px;
      max-height: 400px;
      object-fit: cover;
      margin-bottom: 20px;
    }

    .pdf-container {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
      margin-top: 20px;
    }

    .btn-download {
      display: inline-block;
      margin-top: 15px;
      font-weight: 600;
      border-radius: 10px;
    }

    @media (max-width: 768px) {
      .hero h1 { font-size: 2rem; }
      .hero p { font-size: 1rem; }
      .laporan-header h2 { font-size: 1.3rem; }
      .laporan-body { padding: 20px; }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<?= view('layout/navbar') ?>

<!-- Hero -->
<section class="hero animate__animated animate__fadeIn">
  <h1 class="animate__animated animate__fadeInDown">Laporan Tracer Study</h1>
  <p class="animate__animated animate__fadeInUp animate__delay-1s">Lihat laporan tracer study berdasarkan tahun</p>
</section>

<!-- Konten -->
<main class="container">
  <!-- Dropdown Tahun -->
  <div class="d-flex justify-content-center mb-5 animate__animated animate__fadeInDown animate__delay-2s">
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown">
        Pilih Tahun
      </button>
      <ul class="dropdown-menu">
        <?php 
          $currentYear = (int) $tahun;
          $range = 5; 
          $half = floor($range / 2);
          $startYear = max(2018, $currentYear - $half);
          $endYear   = min($maxYear, $currentYear + $half);

          if (($endYear - $startYear + 1) < $range) {
              if ($startYear == 2018) {
                  $endYear = min($maxYear, $startYear + $range - 1);
              } elseif ($endYear == $maxYear) {
                  $startYear = max(2018, $endYear - $range + 1);
              }
          }

          for ($y = $endYear; $y >= $startYear; $y--): ?>
            <li><a class="dropdown-item <?= ($y == $tahun) ? 'active' : '' ?>" href="<?= base_url('laporan/'.$y) ?>"><?= $y ?></a></li>
        <?php endfor; ?>
      </ul>
    </div>
  </div>

  <!-- Daftar Laporan -->
  <div class="laporan-list">
    <?php if (!empty($laporan)): ?>
      <?php 
        $delay = 3; // mulai delay dari 3s setelah hero dan dropdown
        foreach ($laporan as $lap): 
      ?>
        <div class="laporan-item animate__animated animate__fadeInUp animate__delay-<?= $delay ?>s">
          <div class="laporan-header">
            <h2><?= esc($lap['judul']) ?></h2>
          </div>
          <div class="laporan-body">
            <?= $lap['isi'] ?>

            <?php if (!empty($lap['file_gambar'])): ?>
              <div class="text-center">
                <img src="<?= base_url('uploads/gambar/' . $lap['file_gambar']) ?>" alt="Gambar Laporan" class="img-fluid shadow-sm">
              </div>
            <?php endif; ?>

            <?php if (!empty($lap['file_pdf'])): ?>
              <a href="<?= base_url('uploads/pdf/'.$lap['file_pdf']) ?>" target="_blank" class="btn btn-outline-primary btn-download">
                📄 Lihat PDF
              </a>
              <div class="pdf-container mt-3">
                <embed src="<?= base_url('uploads/pdf/'.$lap['file_pdf']) ?>#toolbar=1&navpanes=0&scrollbar=1" 
                       type="application/pdf" width="100%" height="600px">
              </div>
            <?php else: ?>
              <p class="text-danger mt-3">Belum ada file laporan PDF yang diupload.</p>
            <?php endif; ?>
          </div>
        </div>
      <?php 
        $delay++; // naikkan delay tiap item biar smooth urut
        endforeach; 
      ?>
    <?php else: ?>
      <p class="text-muted text-center">Belum ada laporan untuk tahun <?= esc($tahun) ?>.</p>
    <?php endif; ?>
  </div>
</main>

<!-- Footer -->
<?= view('layout/footer') ?>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

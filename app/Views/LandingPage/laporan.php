<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Tracer Study</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', Arial, sans-serif; background: #f8fafc; color: #1e293b; }
    h1 { text-align: center; margin-top: 20px; font-weight: 700; color: #0f172a; }
    
    .dropdown button { border-radius: 12px; padding: 10px 18px; font-weight: 600; }

    .laporan-item {
      background: #fff;
      border-radius: 16px;
      padding: 25px 30px;
      margin-bottom: 40px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .laporan-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }

    .laporan-item h2 {
      font-weight: 700;
      color: #1e40af;
      font-size: 1.75rem;
      border-bottom: 2px solid #e2e8f0;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .laporan-item img {
      border-radius: 12px;
      max-height: 400px;
      object-fit: cover;
    }

    .pdf-container {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
    }
    
    footer .license { margin-top: 12px; font-size: 14px; line-height: 1.6; }
    footer img { height: 36px; margin: 10px 0; }
  </style>
</head>
<body>

  <!-- Include Navbar -->
  <?= $this->include('layout/navbar'); ?>

  <div class="container mt-4 mb-5">
    <h1 class="mb-4">Laporan Tracer Study</h1>

  <!-- Dropdown Tahun -->
<div class="d-flex justify-content-center mb-5">
  <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown">
          Pilih Tahun
      </button>
      <ul class="dropdown-menu">
          <?php 
            $currentYear = (int) $tahun;
            $range = 5; // jumlah maksimal tahun ditampilkan

            // hitung batas atas & bawah
            $half = floor($range / 2);
            $startYear = max(2018, $currentYear - $half);
            $endYear   = min($maxYear, $currentYear + $half);

            // koreksi kalau jumlah tahun kurang dari $range
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
            <?php foreach ($laporan as $lap): ?>
                <div class="laporan-item">
                    <!-- Judul -->
                    <h2><?= esc($lap['judul']) ?></h2>

                    <!-- Isi -->
                    <div class="mb-3">
                        <?= $lap['isi'] ?>
                    </div>

                    <!-- Gambar -->
                    <?php if (!empty($lap['file_gambar'])): ?>
                        <div class="mb-4 text-center">
                            <img src="<?= base_url('uploads/gambar/' . $lap['file_gambar']) ?>" 
                                 alt="Gambar Laporan" 
                                 class="img-fluid shadow-sm">
                        </div>
                    <?php endif; ?>

                    <!-- PDF -->
                    <?php if (!empty($lap['file_pdf'])): ?>
                        <div class="pdf-container mb-3">
                            <embed 
                                src="<?= base_url('uploads/pdf/'.$lap['file_pdf']) ?>#toolbar=1&navpanes=0&scrollbar=1" 
                                type="application/pdf" 
                                width="100%" 
                                height="600px">
                        </div>
                    <?php else: ?>
                        <p class="text-danger">Belum ada file laporan PDF yang diupload.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center">Belum ada laporan untuk tahun <?= esc($tahun) ?>.</p>
        <?php endif; ?>
    </div>
  </div>

  <!-- Footer -->
  <?= view('layout/footer') ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

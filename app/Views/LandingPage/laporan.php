<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Tracer Study</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; }
    h1 { text-align: center; margin-top: 20px; }
    .laporan-item { margin-bottom: 60px; }
    .pdf-container {
      width: 100%;
      height: 600px;
      margin: 20px 0;
      border: 1px solid #ddd;
    }
    footer {
      background-color: #a9a9a9ff; /* biru tua */
      color: #fff;
      text-align: center;
      padding: 20px 10px;
      margin-top: 50px;
    }
    footer .powered {
      background-color: #8f8f8fff; /* biru muda */
      padding: 8px;
      font-size: 14px;
    }
    footer .license {
      margin-top: 10px;
      font-size: 14px;
    }
    footer img {
      height: 40px;
      margin: 10px 0;
    }
  </style>
</head>
<body>

  <!-- Include Navbar -->
  <?= $this->include('layout/navbar'); ?>

  <div class="container mt-4">
    <h1 class="mb-5">Laporan Tracer Study</h1>

    <!-- Dropdown Tahun -->
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            Pilih Tahun
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= base_url('laporan/2024') ?>">2024</a></li>
            <li><a class="dropdown-item" href="<?= base_url('laporan/2023') ?>">2023</a></li>
            <li><a class="dropdown-item" href="<?= base_url('laporan/2022') ?>">2022</a></li>
            <li><a class="dropdown-item" href="<?= base_url('laporan/2021') ?>">2021</a></li>
            <li><a class="dropdown-item" href="<?= base_url('laporan/2020') ?>">2020</a></li>
            <li><a class="dropdown-item" href="<?= base_url('laporan/2019') ?>">2019</a></li>
            <li><a class="dropdown-item" href="<?= base_url('laporan/2018') ?>">2018</a></li>
        </ul>
    </div>

    <div class="laporan-list mt-3">
        <?php if (!empty($laporan)): ?>
            <?php foreach ($laporan as $lap): ?>
                <div class="laporan-item mb-4">
                    <!-- Judul -->
                    <h2 class="mb-3"><?= esc($lap['judul']) ?></h2>

                    <!-- Isi -->
                    <div class="mb-3">
                        <?= $lap['isi'] ?>
                        <?php // tidak pakai esc() supaya format HTML dari editor ikut tampil ?>
                    </div>

                    <!-- Gambar -->
                    <?php if (!empty($lap['file_gambar'])): ?>
                        <div class="mb-3 text-center">
                            <img src="<?= base_url('uploads/gambar/' . $lap['file_gambar']) ?>" 
                                 alt="Gambar Laporan" 
                                 class="img-fluid rounded shadow">
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
  <footer>
    <div class="powered">Powered by tracer.id</div>
    <img src="https://licensebuttons.net/l/by-nc-sa/4.0/88x31.png" alt="Creative Commons License">
    <div class="license">
      This work by ITB Career Center & Aosan Technology, Customized by Tracer Study POLBAN Team, 
      is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

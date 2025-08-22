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
    <?php if (!empty($laporan)) : ?>
        <?php foreach ($laporan as $row) : ?>
            <div class="laporan-item">
                <?php if (!empty($row['file_pdf'])) : ?>
                    <a href="<?= base_url('uploads/pdf/' . $row['file_pdf']) ?>" target="_blank">Lihat PDF</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Tidak ada laporan untuk tahun <?= esc($tahun) ?>.</p>
    <?php endif; ?>
</div>



    <?php if (!empty($laporan)): ?>
      <?php foreach ($laporan as $lap): ?>
        <div class="laporan-item">
          <!-- Judul -->
          <h2 class="mb-3"><?= esc($lap['judul']) ?></h2>

          <!-- Isi -->
          <div class="mb-3">
            <?= $lap['isi'] ?> 
            <?php // tidak pakai esc() supaya format HTML dari editor ikut tampil ?>
          </div>

          <!-- PDF -->
          <?php if (!empty($lap['file_pdf'])): ?>
            <div class="pdf-container">
              <embed 
                src="<?= base_url('uploads/pdf/'.$lap['file_pdf']) ?>#toolbar=1&navpanes=0&scrollbar=1" 
                type="application/pdf" 
                width="100%" 
                height="100%">
            </div>
          <?php else: ?>
            <p class="text-danger">Belum ada file laporan PDF yang diupload.</p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted text-center">Belum ada laporan yang tersedia.</p>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

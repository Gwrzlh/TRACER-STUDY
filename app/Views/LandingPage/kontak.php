<link rel="stylesheet" href="/css/landingpage/kontak.css">

<!-- panggil navbar -->
<?= $this->include('layout/navbar') ?>

<!-- Bootstrap CSS -->
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

  .card-content {
    background: #fff;
    border: none;
    border-radius: 20px;
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.06);
    padding: 40px 35px;
    margin-bottom: 30px;
    transition: all 0.3s ease;
  }

  .card-content:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 30px rgba(0, 0, 0, 0.1);
  }

  .card-icon {
    font-size: 2rem;
    color: #2563eb;
    margin-bottom: 15px;
  }

  .card-content h4 {
    font-weight: 700;
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #111827;
  }

  .card-content ul,
  .card-content p {
    font-size: 1.05rem;
    line-height: 1.7;
    color: #374151;
  }

  .card-content ul {
    padding-left: 1.2rem;
    margin-bottom: 0;
  }

  .card-content ul li {
    margin-bottom: 8px;
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
  }
</style>

<!-- Hero -->
<section class="hero animate__animated animate__fadeIn">
  <h1 class="animate__animated animate__fadeInDown">Kontak</h1>
  <p class="animate__animated animate__fadeInUp animate__delay-1s">Informasi resmi Tracer Study POLBAN yang bisa Anda hubungi</p>
</section>

<!-- Konten -->
<main>
  <div class="container">
    <div class="row g-4">

      <!-- ----------------- WAKIL DIREKTUR ----------------- -->
      <div class="col-md-6">
        <div class="card-content animate__animated animate__fadeInUp animate__delay-1s">
          <div class="card-icon">👤</div>
          <h4>Wakil Direktur</h4>
          <ul class="list-unstyled">
            <?php if (!empty($wakilDirektur)): ?>
              <?php foreach ($wakilDirektur as $wd): ?>
                <?php if (!empty($wd['nama_lengkap'])): ?>
                  <li><?= esc($wd['nama_lengkap']) ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <li>-</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>

      <!-- ----------------- TEAM TRACER ----------------- -->
      <div class="col-md-6">
        <div class="card-content animate__animated animate__fadeInUp animate__delay-2s">
          <div class="card-icon">👥</div>
          <h4>Team Tracer Study POLBAN</h4>
          <ul class="list-unstyled">
            <?php if (!empty($teamTracer)): ?>
              <?php foreach ($teamTracer as $tt): ?>
                <?php if (!empty($tt['nama_lengkap'])): ?>
                  <li><?= esc($tt['nama_lengkap']) ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <li>-</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>

      <!-- ----------------- ALAMAT KANTOR ----------------- -->
      <div class="col-md-12">
        <div class="card-content animate__animated animate__fadeInUp animate__delay-3s">
          <div class="card-icon">📍</div>
          <h4>Alamat Kantor</h4>
          <p>
            Gedung Direktorat Lantai Dasar<br>
            JL Gegerkalong Hilir, Ciwaruga, Parongpong,<br>
            Kabupaten Bandung Barat, Jawa Barat 40012
          </p>
          <p>
            ☎️ Telp: 022-2013789<br>
            📠 Fax: 022-2013889<br>
            ✉️ Email: <a href="mailto:tracer.study@polban.ac.id">tracer.study@polban.ac.id</a>
          </p>
        </div>
      </div>

      <!-- ----------------- SURVEYOR ----------------- -->
      <?php $tahunDipilih = $tahun ?? date('Y'); ?>
      <div class="col-md-12">
        <div class="card-content animate__animated animate__fadeInUp animate__delay-4s">
          <div class="card-icon">📊</div>
          <h4>Surveyor Tahun <?= esc($tahunDipilih) ?></h4>
          <p class="text-muted">
            Surveyor diangkat untuk membantu Tracer Study tahun <?= esc($tahunDipilih) ?>.
          </p>

          <form method="get" action="" class="mb-3">
            <label for="tahun" class="form-label fw-semibold">Pilih Tahun:</label>
            <select name="tahun" id="tahun" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
              <option value="">Semua Tahun</option>
              <?php for ($i = 2021; $i <= 2025; $i++): ?>
                <option value="<?= $i ?>" <?= (($tahun ?? '') == $i) ? 'selected' : '' ?>>
                  <?= $i ?>
                </option>
              <?php endfor; ?>
            </select>
          </form>

          <?php if (!empty($surveyors)): ?>
            <div class="table-responsive animate__animated animate__fadeIn animate__delay-5s">
              <table class="table table-bordered align-middle text-center">
                <thead class="table-primary">
                  <tr>
                    <th>No</th>
                    <th>Prodi</th>
                    <th>Nama Surveyor</th>
                    <th>Email / WA</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; ?>
                  <?php foreach ($surveyors as $s): ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= esc($s['nama_prodi'] ?? '-') ?></td>
                      <td><?= esc($s['nama_lengkap'] ?? '-') ?></td>
                      <td>
                        <?= esc($s['email'] ?? '-') ?><br>
                        <span class="text-muted"><?= esc($s['notlp'] ?? '-') ?></span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="alert alert-warning animate__animated animate__fadeIn animate__delay-5s">Tidak ada data surveyor.</div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</main>

<!-- Footer -->
<?= view('layout/footer') ?>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

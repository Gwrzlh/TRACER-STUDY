<link rel="stylesheet" href="/css/landingpage/kontak.css">

<!-- panggil navbar -->
<?= $this->include('layout/navbar') ?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Kontak</h2>
        <p class="text-muted">Informasi kontak resmi Tracer Study POLBAN</p>
    </div>

    <!-- ----------------- WAKIL DIREKTUR ----------------- -->
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body">
            <h4 class="card-title text-primary">Wakil Direktur</h4>
            <ul class="list-unstyled mt-3">
                <?php if (!empty($wakilDirektur)): ?>
                    <?php foreach ($wakilDirektur as $wd): ?>
                        <?php if (!empty($wd['nama_lengkap'])): ?>
                            <li>👤 <?= esc($wd['nama_lengkap']) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>-</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- ----------------- TEAM TRACER ----------------- -->
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body">
            <h4 class="card-title text-success">Team Tracer Study POLBAN</h4>
            <ul class="list-unstyled mt-3">
                <?php if (!empty($teamTracer)): ?>
                    <?php foreach ($teamTracer as $tt): ?>
                        <?php if (!empty($tt['nama_lengkap'])): ?>
                            <li>👥 <?= esc($tt['nama_lengkap']) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>-</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- ----------------- ALAMAT KANTOR ----------------- -->
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body">
            <h4 class="card-title text-danger">Alamat Kantor</h4>
            <p class="mt-3">
                📍 Gedung Direktorat Lantai Dasar<br>
                JL Gegerkalong Hilir, Ciwaruga, Parongpong,<br>
                Kabupaten Bandung Barat, Jawa Barat 40012<br><br>
                ☎️ Telp: 022-2013789<br>
                📠 Fax: 022-2013889<br>
                ✉️ Email: <a href="mailto:tracer.study@polban.ac.id">tracer.study@polban.ac.id</a>
            </p>
        </div>
    </div>

    <!-- ----------------- SURVEYOR ----------------- -->
    <?php 
        $tahunDipilih = $tahun ?? date('Y'); 
    ?>
    <div class="card shadow-sm mb-5 border-0 rounded-3">
        <div class="card-body">
            <h4 class="card-title text-info">Surveyor Tahun <?= esc($tahunDipilih) ?></h4>
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
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-dark">
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
                <div class="alert alert-warning">Tidak ada data surveyor.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<?= view('layout/footer') ?>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

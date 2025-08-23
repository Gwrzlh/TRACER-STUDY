<link rel="stylesheet" href="/css/landingpage/kontak.css">

<!-- panggil navbar -->
<?= $this->include('layout/navbar') ?>
 <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-4">
    <h2 class="mb-4">Kontak</h2>

    <!-- ----------------- WAKIL DIREKTUR ----------------- -->
    <h3>Wakil Direktur</h3>
    <?php if (!empty($wakilDirektur)): ?>
        <?php foreach ($wakilDirektur as $wd): ?>
            <?php if (!empty($wd['nama_lengkap'])): ?>
                <p><?= esc($wd['nama_lengkap']) ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>-</p>
    <?php endif; ?>

    <!-- ----------------- TEAM TRACER ----------------- -->
    <h3 class="mt-3">Team Tracer Study POLBAN</h3>
    <?php if (!empty($teamTracer)): ?>
        <?php foreach ($teamTracer as $tt): ?>
            <?php if (!empty($tt['nama_lengkap'])): ?>
                <p><?= esc($tt['nama_lengkap']) ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>-</p>
    <?php endif; ?>

    <!-- ----------------- ALAMAT KANTOR ----------------- -->
    <div class="mt-4 alamat-box">
        <p>
            Gedung Direktorat Lantai Dasar<br>
            JL Gegerkalong Hilir, Ciwaruga, Parongpong, Kabupaten Bandung Barat, Jawa Barat 40012<br>
            Telp: 022-2013789<br>
            Fax: 022-2013889<br>
            Email: tracer.study@polban.ac.id
        </p>
    </div>

    <!-- ----------------- SURVEYOR ----------------- -->
   

    <?php 
    // ambil tahun dari filter, kalau kosong pakai tahun sekarang
    $tahunDipilih = $tahun ?? date('Y'); 
?>
<h3 class="mt-5">Surveyor Tahun <?= esc($tahunDipilih) ?></h3>
<p>
    Surveyor diangkat untuk membantu Tracer Study tahun <?= esc($tahunDipilih) ?>
</p>
  <form method="get" action="">
    <select name="tahun" id="tahun" onchange="this.form.submit()">
        <option value=""> Semua Tahun </option>
        <option value="2021" <?= (($tahun ?? '') == 2021) ? 'selected' : '' ?>>2021</option>
        <option value="2022" <?= (($tahun ?? '') == 2022) ? 'selected' : '' ?>>2022</option>
        <option value="2023" <?= (($tahun ?? '') == 2023) ? 'selected' : '' ?>>2023</option>
        <option value="2024" <?= (($tahun ?? '') == 2024) ? 'selected' : '' ?>>2024</option>
        <option value="2025" <?= (($tahun ?? '') == 2025) ? 'selected' : '' ?>>2025</option>
    </select>
</form>
    <?php if (!empty($surveyors)): ?>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
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
                                <?= esc($s['notlp'] ?? '-') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>Tidak ada data surveyor.</p>
    <?php endif; ?>
</div>

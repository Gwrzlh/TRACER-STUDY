<div class="container mt-4">
    <h2 class="mb-4">Kontak</h2>

    <!-- ----------------- WAKIL DIREKTUR ----------------- -->
    <h5>Wakil Direktur</h5>
    <?php if (!empty($wakilDirektur)): ?>
        <?php foreach ($wakilDirektur as $wd): ?>
            <?php if (!empty($wd['nama_lengkap'])): ?>
                <p><strong><?= esc($wd['nama_lengkap']) ?></strong></p>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>-</p>
    <?php endif; ?>

    <!-- ----------------- TEAM TRACER ----------------- -->
    <h5 class="mt-3">Team Tracer Study POLBAN</h5>
    <?php if (!empty($teamTracer)): ?>
        <?php foreach ($teamTracer as $tt): ?>
            <?php if (!empty($tt['nama_lengkap'])): ?>
                <p><strong><?= esc($tt['nama_lengkap']) ?></strong></p>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>-</p>
    <?php endif; ?>

    <!-- ----------------- ALAMAT KANTOR ----------------- -->
    <div class="mt-4">
        <p>
            Gedung Direktorat Lantai Dasar<br>
            JL Gegerkalong Hilir, Ciwaruga, Parongpong, Kabupaten Bandung Barat, Jawa Barat 40012<br>
            Telp: 022-2013789<br>
            Fax: 022-2013889<br>
            Email: tracer.study@polban.ac.id
        </p>
    </div>

    <!-- ----------------- SURVEYOR ----------------- -->
    <h5 class="mt-5">Surveyor Tahun <?= date('Y') ?></h5>
    <p>
        Surveyor diangkat untuk membantu Tracer Study tahun <?= date('Y') ?>
        (lulus <?= date('Y') - 1 ?>)
    </p>

    <?php if (!empty($surveyors)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Prodi</th>
                        <th>Nama Surveyor</th>
                        <th>Email / WA</th>
                        <th>Tahun Lulus</th>
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
                            <td><?= esc($s['tahun_kelulusan'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>Tidak ada data surveyor.</p>
    <?php endif; ?>
</div>
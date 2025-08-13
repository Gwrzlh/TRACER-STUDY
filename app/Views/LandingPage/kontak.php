<div class="container mt-4">
    <h2 class="mb-3">Kontak</h2>

    <h5>Wakil Direktur Bidang Kemahasiswaan</h5>
    <?php if (!empty($nonSurveyor)): ?>
        <?php foreach ($nonSurveyor as $ns): ?>
            <p>
                <strong><?= esc($ns['nama_lengkap']) ?></strong><br>
                Email: <?= esc($ns['email']) ?><br>
                Alamat:<br>
                <?= nl2br(esc($ns['alamat'])) ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <h5>Team <em>Tracer Study</em> POLBAN</h5>
    <?php if (!empty($teamTracer)): ?>
        <?php foreach ($teamTracer as $tt): ?>
            <p>
                <strong><?= esc($tt['nama_lengkap']) ?></strong><br>
                Email: <?= esc($tt['email']) ?><br>
                Alamat:<br>
                <?= nl2br(esc($tt['alamat'])) ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <p>
        Gedung Direktorat Lantai Dasar<br>
        JL Gegerkalong Hilir, Ciwaruga, Parongpong, Kabupaten Bandung Barat, Jawa Barat 40012<br>
        Telp: 022-2013789<br>
        Fax: 022-2013889<br>
        Email: tracer.study@polban.ac.id
    </p>

    <h5 class="mt-5">Surveyor Tahun <?= date('Y') ?></h5>
    <p>
        Pada Tahun <?= date('Y') ?> Tracer Study dilakukan kepada Alumni POLBAN yang lulus pada Tahun <?= date('Y') - 1 ?>.
        Adapun nama-nama surveyor yang diangkat untuk membantu meningkatkan jumlah data yang masuk adalah sebagai berikut:
    </p>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Prodi</th>
                <th>Nama Surveyor Lulus Tahun <?= date('Y') - 1 ?></th>
                <th>Email / WA</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($surveyors as $s): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($s['nama_prodi']) ?></td>
                    <td><?= esc($s['nama_lengkap']) ?></td>
                    <td>
                        <?= esc($s['email']) ?><br>
                        <?= esc($s['notlp']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<h2>Daftar Kontak - Wakil Direktur & Tim Tracer</h2>
<a href="/admin/kontak/create">Tambah Kontak</a>
<br><br>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Nama</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php if (!empty($wakilDirektur) || !empty($teamTracer)): ?>
            <?php foreach (array_merge($wakilDirektur, $teamTracer) as $k): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($k['kategori'] ?? '-') ?></td>
                    <td><?= esc($k['nama_lengkap'] ?? '-') ?></td>
                    <td>
                        <a href="/admin/kontak/edit/<?= $k['id'] ?>">Edit</a> |
                        <a href="/admin/kontak/delete/<?= $k['id'] ?>" onclick="return confirm('Hapus kontak ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align:center;">Belum ada data Wakil Direktur atau Tim Tracer</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<br><br>

<h2>Daftar Kontak - Surveyor</h2>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Prodi</th>
            <th>Nama Surveyor</th>
            <th>Tahun Lulus</th>
            <th>Email</th>
            <th>WA</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php if (!empty($surveyors)): ?>
            <?php foreach ($surveyors as $s): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($s['nama_prodi'] ?? '-') ?></td>
                    <td><?= esc($s['nama_lengkap'] ?? '-') ?></td>
                    <td><?= esc($s['tahun_kelulusan'] ?? '-') ?></td>
                    <td><?= esc($s['email'] ?? '-') ?></td>
                    <td><?= esc($s['notlp'] ?? '-') ?></td>
                    <td>
                        <a href="/admin/kontak/edit/<?= $s['id'] ?>">Edit</a> |
                        <a href="/admin/kontak/delete/<?= $s['id'] ?>" onclick="return confirm('Hapus kontak ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center;">Belum ada data Surveyor</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
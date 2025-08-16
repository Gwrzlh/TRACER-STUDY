<!DOCTYPE html>
<html>
<head>
    <title>Data Alumni - Surveyor</title>
</head>
<body>
    <h1>Daftar Alumni (Surveyor)</h1>
    <a href="/alumni/create">Tambah Data</a>
    <table border="1">
        <tr>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Tahun Lulus</th>
            <th>Status Pekerjaan</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($alumni_surveyor as $row): ?>
        <tr>
            <td><?= esc($row['nama']) ?></td>
            <td><?= esc($row['prodi']) ?></td>
            <td><?= esc($row['tahun_lulus']) ?></td>
            <td><?= esc($row['status_pekerjaan']) ?></td>
            <td>
                <a href="/alumni/edit/<?= $row['id'] ?>">Edit</a> | 
                <a href="/alumni/delete/<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

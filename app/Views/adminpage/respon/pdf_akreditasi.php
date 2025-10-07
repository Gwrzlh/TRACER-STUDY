<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Alumni - Akreditasi (<?= esc($opsi) ?>)</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h2,
        h4 {
            text-align: center;
            margin: 0;
        }

        h4 {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: right;
            margin-top: 15px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>

    <h2>Laporan Data Alumni - Akreditasi</h2>
    <h4>Jawaban = <?= esc($opsi) ?></h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NIM</th>
                <th>Jurusan</th>
                <th>Prodi</th>
                <th>Angkatan</th>
                <th>Tahun Lulus</th>
                <th>IPK</th>
                <th>Alamat</th>
                <th>Jenis Kelamin</th>
                <th>No. Telepon</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($alumni)): ?>
                <tr>
                    <td colspan="11" style="text-align:center;">Tidak ada data alumni untuk opsi ini.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1;
                foreach ($alumni as $a): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= esc($a['nama_lengkap']); ?></td>
                        <td><?= esc($a['nim']); ?></td>
                        <td><?= esc($a['nama_jurusan']); ?></td>
                        <td><?= esc($a['nama_prodi']); ?></td>
                        <td><?= esc($a['angkatan']); ?></td>
                        <td><?= esc($a['tahun_kelulusan']); ?></td>
                        <td><?= esc($a['ipk']); ?></td>
                        <td><?= esc(trim($a['alamat'] . ' ' . $a['alamat2'])); ?></td>
                        <td><?= esc($a['jenisKelamin']); ?></td>
                        <td><?= esc($a['notlp']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada <?= date('d M Y, H:i'); ?>
    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kontak Tracer Study</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            padding-bottom: 50px;
        }

        .section {
            margin-top: 40px;
        }

        .section h2 {
            font-weight: 600;
            font-size: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f3f4f6;
        }

        .container {
            padding: 30px 15px;
        }

        p {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <div class="container">

        <!-- DESKRIPSI UMUM -->
        <?php if (!empty($deskripsi)): ?>
            <div class="section">
                <?= nl2br(esc($deskripsi['isi'] ?? '')) ?>
            </div>
        <?php endif ?>

        <!-- Direktorat -->
        <?php if (!empty($directorates)): ?>
            <div class="section">
                <h2><?= esc($directorates[0]['posisi'] ?? 'Direktorat') ?></h2>
                <?php foreach ($directorates as $d): ?>
                    <p><strong><?= esc($d['nama']) ?></strong></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <!-- Tim Tracer Study -->
        <?php if (!empty($teams)): ?>
            <div class="section">
                <h2>Team Tracer Study POLBAN</h2>
                <?php foreach ($teams as $t): ?>
                    <p><?= esc($t['nama']) ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <!-- Alamat -->
        <?php if (!empty($address)): ?>
            <div class="section">
                <h2>Alamat</h2>
                <?= nl2br(esc($address['kontak'] ?? '')) ?>
            </div>
        <?php endif ?>

        <!-- Surveyor -->
        <?php if (!empty($surveyors)): ?>
            <div class="section">
                <h2>Surveyor Tahun 2024</h2>
                <p>Pada Tahun 2024 Tracer Study dilakukan kepada Alumni POLBAN yang lulus pada Tahun 2023.</p>
                <p>Adapun nama-nama surveyor yang diangkat untuk membantu meningkatkan jumlah data yang masuk adalah sebagai berikut:</p>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Program Studi</th>
                            <th>Nama Surveyor</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($surveyors as $i => $s): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($s['nama_prodi'] ?? '-') ?></td>
                                <td><?= esc($s['nama']) ?></td>
                                <td><?= esc($s['kontak']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>

        <!-- Koordinator -->
        <?php if (!empty($coordinators)): ?>
            <div class="section">
                <h2>Koordinator Surveyor Tahun 2024</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jurusan</th>
                            <th>Nama Koordinator</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coordinators as $i => $c): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($c['nama_jurusan'] ?? '-') ?></td>
                                <td><?= esc($c['nama']) ?></td>
                                <td><?= esc($c['kontak']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>

    </div>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
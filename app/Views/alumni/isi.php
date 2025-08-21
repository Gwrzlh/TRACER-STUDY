<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Isi Data Alumni</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            margin: 0;
        }
        
        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .btn-kembali {
            background: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            transition: background 0.2s ease;
            margin-top: 20px;
        }

        .btn-kembali:hover {
            background: #0069d9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        th {
            background: #6c757d;
            color: #fff;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
        }
        
        th:first-child {
            width: 60px;
            text-align: center;
        }
        
        th:nth-child(2) {
            width: 120px;
        }
        
        th:nth-child(4) {
            width: 120px;
        }
        
        th:last-child {
            width: 80px;
            text-align: center;
        }
        
        td {
            padding: 15px 12px;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }
        
        td:first-child {
            text-align: center;
            font-weight: 600;
        }
        
        td:last-child {
            text-align: center;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tbody tr:hover {
            background: #f8f9fa;
        }
        
        .btn-isi {
            background: #6c757d;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            transition: background 0.2s ease;
        }
        
        .btn-isi:hover {
            background: #5a6268;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 15px 10px;
            }
            
            table {
                border-radius: 6px;
            }
            
            th, td {
                padding: 10px 8px;
                font-size: 14px;
            }
            
            .btn-isi, .btn-kembali {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <h2>Isi Data Alumni</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumni as $a): ?>
                <tr>
                    <td><?= $a['no']; ?></td>
                    <td><?= $a['nim']; ?></td>
                    <td><?= $a['nama']; ?></td>
                    <td><?= $a['status']; ?></td>
                    <td>
                        <?php if ($a['status'] === 'Finish'): ?>
                            <span style="color: green; font-weight: bold;">Selesai</span>
                        <?php else: ?>
                            <a href="<?= base_url('alumni/form/'.$a['nim']) ?>" class="btn-isi">
                                Isi
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Tombol Kembali di bawah tabel -->
    <a href="<?= base_url('alumni') ?>" class="btn-kembali"> Kembali</a>

</body>
</html>

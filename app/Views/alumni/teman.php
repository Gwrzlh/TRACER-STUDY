<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lihat Teman</title>
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
            width: 120px;
        }
        
        th:last-child {
            width: 200px;
        }
        
        td {
            padding: 15px 12px;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tbody tr:hover {
            background: #f8f9fa;
        }

        /* Tombol Kembali */
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

            .btn-kembali {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <h2>Daftar Lihat Teman</h2>
    <table>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
        </tr>
        <?php foreach ($teman as $t): ?>
        <tr>
            <td><?= esc($t['nim']) ?></td>
            <td><?= esc($t['nama']) ?></td>
            <td><?= esc($t['prodi']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Tombol Kembali -->
    <a href="<?= base_url('alumni') ?>" class="btn-kembali">Kembali</a>

</body>
</html>

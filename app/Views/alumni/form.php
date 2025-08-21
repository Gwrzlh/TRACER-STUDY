<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Alumni</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            padding: 20px; 
            background: #f8f9fa; 
        }
        .form-container { 
            max-width: 500px; 
            margin: auto; 
            background: #fff; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.1); 
        }
        .form-container h2 { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-top: 10px; 
            font-weight: bold; 
        }
        input, select, textarea { 
            width: 100%; 
            padding: 8px; 
            margin-top: 5px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }
        .btn {
            margin-top: 15px; 
            padding: 10px 15px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-simpan {
            background: #007bff; 
            color: white; 
        }
        .btn-simpan:hover { 
            background: #0056b3; 
        }
        .btn-batal {
            background: #6c757d; 
            color: white; 
            margin-left: 10px;
        }
        .btn-batal:hover { 
            background: #5a6268; 
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Isi Data Alumni</h2>
        <form action="<?= base_url('alumni/saveForm') ?>" method="post">
            <label for="nim">NIM</label>
            <input type="text" name="nim" id="nim" value="<?= $nim ?>" readonly>

            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" value="<?= $nama ?>" readonly>

            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="Belum Mengisi">Belum Mengisi</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Finish">Finish</option>
            </select>

            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="3"></textarea>

            <button type="submit" class="btn btn-simpan">Simpan</button>
            <a href="<?= base_url('alumni/isi') ?>" class="btn btn-batal">Batal</a>
        </form>
    </div>
</body>
</html>

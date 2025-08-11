<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Buat Kuesioner Baru</h2>
<form action="<?= base_url('/admin/questionnaire/store') ?>" method="post">
    <div>
        <label>Judul Kuesioner</label><br>
        <input type="text" name="title" required>
    </div>
    <div>
        <label>Deskripsi</label><br>
        <textarea name="deskripsi"></textarea>
    </div>
    <div>
        <label><input type="checkbox" name="is_active" value="1"> Aktif</label>
    </div>
    <button type="submit">Simpan</button>
</form>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <div>
            <form action="<?= base_url('/admin/tipeorganisasi/insert') ?>" method="post"                                                                    >
                <div>
                    <label for="nama_tipe">Nama</label>
                    <input type="text" name="nama_tipe" id="nama_tipe" required>
                </div>
                <div>
                    <label for="lavel">lavel</label>
                    <input type="number" name="lavel" id="lavel" required>
                </div>
                <div>
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi"></textarea>
                </div>
                <div>
                    <label for="group">Group</label>
                    <select class="form-select" name="group" id="group">
                        <option value="">Pilih-Group</option>
                        <?php foreach ($roles as $d): ?>
                            <option value="<?= esc($d['id']) ?>"><?= esc($d['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit">Simpan</button>
                    <a href="<?= base_url('/admin/tipeorganisasi') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
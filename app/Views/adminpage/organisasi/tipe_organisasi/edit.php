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
            <form action="<?= base_url('/admin/tipeorganisasi/edit/update/' . $datatpOr['id']) ?>" method="post" >        
                  <?= csrf_field() ?>

                  <!-- Di bagian atas form -->
<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (session('error')): ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>
                                                                            
                <div>
                    <label for="nama_tipe">Nama</label>
                    <input type="text" name="nama_tipe" id="nama_tipe" value="<?= old('ipk', isset($datatpOr['nama_tipe']) ? $datatpOr['nama_tipe'] : '') ?>" required>
                </div>
                <div>
                    <label for="lavel">lavel</label>
                    <input type="number" name="lavel" id="lavel" value="<?= old('ipk', isset($datatpOr['level']) ? $datatpOr['level'] : '') ?>" required>
                </div>
               <div>
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi"><?= old('deskripsi', isset($datatpOr['deskripsi']) ? $datatpOr['deskripsi'] : '') ?></textarea>
                </div>
                <div>
                    <label for="group">Group</label>
                    <select class="form-select" name="group" id="group">
                        <option value="">-- Pilih Role--</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= esc($r['id']) ?>"
                                    <?= old('jurusan', isset($datatpOr['id_group']) ? $datatpOr['id_group'] : '') == $r['id'] ? 'selected' : '' ?>>
                                <?= esc($r['nama']) ?>
                            </option>
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
<h2>Edit Satuan Organisasi</h2>

<form action="<?= base_url('satuanorganisasi/update/' . $satuan['id']) ?>" method="post">
    <?= csrf_field() ?>

    <label for="nama_satuan">Nama Satuan (Jurusan)</label>
    <select name="nama_satuan" id="nama_satuan" required>
        <option value="">-- Pilih Jurusan --</option>
        <?php foreach ($jurusan as $j): ?>
            <option value="<?= esc($j['nama_jurusan']) ?>" <?= $satuan['nama_satuan'] == $j['nama_jurusan'] ? 'selected' : '' ?>>
                <?= esc($j['nama_jurusan']) ?>
            </option>
        <?php endforeach ?>
    </select><br><br>

    <label for="nama_singkatan">Singkatan</label>
    <input type="text" name="nama_singkatan" id="nama_singkatan" value="<?= esc($satuan['nama_singkatan']) ?>" required><br><br>

    <label for="nama_slug">Slug</label>
    <input type="text" name="nama_slug" id="nama_slug" value="<?= esc($satuan['nama_slug']) ?>"><br><br>

    <label for="deskripsi">Deskripsi</label>
    <input type="text" name="deskripsi" id="deskripsi" value="<?= esc($satuan['deskripsi']) ?>"><br><br>

    <select name="id_tipe" id="id_tipe" required>
        <option value="">-- Pilih Tipe --</option>
        <?php foreach ($tipe as $t): ?>
            <option value="<?= $t['id'] ?>" <?= $satuan['id_tipe'] == $t['id'] ? 'selected' : '' ?>>
                <?= esc($t['nama_tipe']) ?>
            </option>
        <?php endforeach ?>
    </select>


    <label for="urutan">Urutan</label>
    <input type="number" name="urutan" id="urutan" value="<?= esc($satuan['urutan']) ?>"><br><br>

    <label for="satuan_induk">Satuan Induk (opsional)</label>
    <input type="number" name="satuan_induk" id="satuan_induk" value="<?= esc($satuan['satuan_induk']) ?>"><br><br>

    <button type="submit">Update</button>
    <a href="<?= base_url('satuanorganisasi') ?>">Batal</a>
</form>
<h2>Tambah Satuan Organisasi</h2>
<form action="/satuanorganisasi/store" method="post">
    <?= csrf_field() ?>

    <label>Nama Satuan</label>
    <select name="nama_satuan">
        <?php foreach ($jurusan as $j): ?>
            <option value="<?= $j['nama_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
        <?php endforeach ?>
    </select>

    <label>Singkatan</label>
    <input type="text" name="nama_singkatan">

    <label>Slug</label>
    <input type="text" name="nama_slug">

    <label>Deskripsi</label>
    <input type="text" name="deskripsi">

    <label>Tipe Organisasi</label>
    <select name="id_tipe">
        <?php foreach ($tipe as $t): ?>
            <option value="<?= $t['id'] ?>"><?= $t['nama_tipe'] ?></option>
        <?php endforeach ?>
    </select>

    <label>Urutan</label>
    <input type="number" name="urutan">

    <label>NIK</label>
    <input type="number" name="satuan_induk">

    <button type="submit">Simpan</button>
</form>
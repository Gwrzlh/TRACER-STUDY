<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<h2>Daftar Satuan Organisasi</h2>
<a href="/satuanorganisasi/create">Tambah</a>
<table border="1">
    <tr>
        <th>Nama Satuan</th>
        <th>Singkatan</th>
        <th>slug</th>
        <th>ID Tipe</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($satuan as $row): ?>
        <tr>
            <td><?= esc($row['nama_satuan']) ?></td>
            <td><?= esc($row['nama_singkatan']) ?></td>
            <td><?= esc($row['nama_slug']) ?></td>
            <td><?= esc($row['id_tipe']) ?></td>

            <td>
                <a href="/satuanorganisasi/edit/<?= $row['id'] ?>">Edit</a>
                <form action="/satuanorganisasi/delete/<?= $row['id'] ?>" method="post" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" onclick="return confirm('Yakin?')">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach ?>
</table>
<?= $this->endSection() ?>
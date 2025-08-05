<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<div>
    <div>
        <button><a href="<?= base_url('/admin/tipeorganisasi/form') ?>">+ Tipe Organisasi</a></button>
    </div>
    <div>
        <table>
        <tr>
            <th>Nama Tipe</th>
            <th>lavel</th>
            <th>Deskripsi</th>
            <th>Group</th>
        </tr>
        <tr>
            <?php foreach ($Tipeorganisasi as $tipe): ?>
            <td><?= $tipe['nama_tipe'] ?></td>
            <td><?= $tipe['level'] ?></td>
            <td><?= $tipe['deskripsi'] ?></td>
            <td><?= $tipe['nama_role'] ?></td>
            <?php endforeach; ?>
        </tr>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

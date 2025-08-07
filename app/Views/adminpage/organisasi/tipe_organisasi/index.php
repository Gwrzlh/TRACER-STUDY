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
             <td>                                        
                <a href="<?= base_url('/admin/tipeorganisasi/edit/'. $tipe['id'] ) ?>" class="btn btn-sm btn-primary">Edit</a>
                <<form action="<?= base_url('/admin/tipeorganisasi/delete/' . $tipe['id']) ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            <?= csrf_field() ?>
                        <button type="submit" class="text-red-600 hover:underline">delete</button>
                 </form>
            </td>
            <?php endforeach; ?>
        </tr>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

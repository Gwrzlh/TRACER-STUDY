<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<h2>Daftar Kuesioner</h2>
<a href="<?= base_url('admin/questionnaire/create') ?>">+ Buat Kuesioner Baru</a>
<table border="1" cellpadding="5">
    <tr>
        <th>Judul</th>
        <th>Deskripsi</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php foreach($questionnaires as $q): ?>
    <tr>
        <td><?= esc($q['title']) ?></td>
        <td><?= esc($q['deskripsi']) ?></td>
        <td><?= $q['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
        <td>
            <a href="<?= base_url('admin/questionnaire/' . $q['id'] . '/questions') ?>">Kelola Pertanyaan</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>


<?= $this->endSection() ?>

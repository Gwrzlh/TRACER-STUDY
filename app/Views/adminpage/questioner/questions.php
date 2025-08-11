<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Daftar Pertanyaan</h2>

<a href="<?= base_url('admin/questionnaire/' . $questionnaire_id . '/questions/create') ?>">+ Tambah Pertanyaan</a>

<table>
    <thead>
        <tr>
            <th>Pertanyaan</th>
            <th>Tipe</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($questions)) : ?>
            <tr><td colspan="3">Belum ada pertanyaan</td></tr>
        <?php endif; ?>

        <?php foreach ($questions as $q) : ?>
            <tr>
                <td><?= esc($q['order_no']) ?></td>
                <td><?= esc($q['question_text']) ?></td>
                <td><?= esc($q['question_type']) ?></td>
                <td>
                    <a href="<?= base_url('') ?>">Edit</a> |
                    <a href="#">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
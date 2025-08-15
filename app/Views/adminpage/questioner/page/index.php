<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Halaman Kuesioner: <?= esc($questionnaire['title']) ?></h2>
    <p class="text-muted"><?= esc($questionnaire['deskripsi']) ?></p>
    

    <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/create") ?>" 
       class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Halaman
    </a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (empty($pages)): ?>
        <div class="alert alert-warning">Belum ada halaman kuesioner.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Urutan</th>
                    <th>Judul Halaman</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?= esc($page['order_no']) ?></td>
                        <td><?= esc($page['page_title']) ?></td>
                        <td><?= esc($page['page_description']) ?></td>
                        <td>
                            <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/{$page['id']}/sections") ?>" 
                            class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Atur Pertanyaan
                            </a>
                            <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/{$page['id']}/edit") ?>" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <form action="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/{$page['id']}/delete") ?>" 
                                  method="post" style="display:inline-block;" 
                                  onsubmit="return confirm('Yakin ingin menghapus halaman ini?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

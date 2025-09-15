<!-- desain index.php page -->
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/pengguna/index.css') ?>" rel="stylesheet">

<div class="pengguna-page">
    <div class="page-wrapper">
        <div class="page-container">
            <!-- Judul -->
            <h2 class="page-title"> 📑 Halaman Kuesioner: <?= esc($questionnaire['title']) ?></h2>
            <p class="text-muted"><?= esc($questionnaire['deskripsi']) ?></p>

            <!-- Top Controls -->
            <div class="top-controls">
                <div class="controls-container"></div>
                <div class="button-container">
                    <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/create") ?>" 
                       class="btn-add">
                        <i class="fas fa-plus"></i> Tambah Halaman
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <div class="table-wrapper">
                    <?php if (empty($pages)): ?>
                        <div class="alert alert-warning">Belum ada halaman kuesioner.</div>
                    <?php else: ?>
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>Urutan</th>
                                    <th>Judul Halaman</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pages as $page): ?>
                                <tr>
                                    <td>
                                        <span class="status-badge status-inactive">
                                            <?= esc($page['order_no']) ?>
                                        </span>
                                    </td>
                                    <td class="questionnaire-info">
                                        <div class="questionnaire-title"><?= esc($page['page_title']) ?></div>
                                    </td>
                                    <td class="questionnaire-info">
                                        <div class="questionnaire-description"><?= esc($page['page_description']) ?></div>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <!-- Atur Pertanyaan -->
                                            <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/{$page['id']}/sections") ?>" 
                                               class="btn-action btn-edit" title="Atur Pertanyaan">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <!-- Edit -->
                                            <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/{$page['id']}/edit") ?>" 
                                               class="btn-action btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Hapus -->
                                            <button class="btn-action btn-delete delete-page" 
                                                    data-id="<?= $page['id'] ?>" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert untuk konfirmasi hapus -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".delete-page").forEach(el => {
        el.addEventListener("click", function() {
            const pageId = this.dataset.id;
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Halaman beserta pertanyaan di dalamnya akan terhapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages") ?>/${pageId}/delete`;
                }
            });
        });
    });
});
</script>

<?= $this->endSection() ?> 

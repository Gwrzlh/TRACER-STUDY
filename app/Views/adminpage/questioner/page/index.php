<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Halaman Kuesioner: <?= esc($questionnaire['title']) ?></h2>
    <p class="text-muted"><?= esc($questionnaire['deskripsi']) ?></p>
    
    <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/create") ?>" 
       class="btn btn-primary mb-3">
        <i class="fa-solid fa-plus"></i> Tambah Halaman
    </a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (empty($pages)): ?>
        <div class="alert alert-warning">Belum ada halaman kuesioner.</div>
    <?php else: ?>
        <table class="table table-hover align-middle shadow-sm rounded">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">Urutan</th>
                    <th>Judul Halaman</th>
                    <th>Deskripsi</th>
                    <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?= esc($page['order_no']) ?></span></td>
                        <td class="fw-semibold"><?= esc($page['page_title']) ?></td>
                        <td class="text-muted"><?= esc($page['page_description']) ?></td>
                        <td class="text-center">
                            <!-- Atur Pertanyaan -->
                            <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/{$page['id']}/sections") ?>" 
                               class="text-info me-2" title="Atur Pertanyaan">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            
                            <!-- Edit -->
                            <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/{$page['id']}/edit") ?>" 
                               class="text-warning me-2" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <!-- Hapus -->
                            <a href="javascript:void(0)" 
                               class="text-danger delete-page" 
                               data-id="<?= $page['id'] ?>" 
                               title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
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

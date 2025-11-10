<!-- desain navbar index.php page -->
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/questioner/page/index.css') ?>" rel="stylesheet">

<div class="pengguna-page">
    <div class="page-wrapper" style="padding: 16px;">  <!-- Rapatkan padding -->
        <div class="page-container">
            <!-- Breadcrumb (baru) -->
            <?= $this->include('adminpage/questioner/breadcrumb') ?>

            <!-- Judul (optimasi: tambah flex align) -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <h2 class="page-title"> 📑 Halaman Kuesioner: <?= isset($questionnaire['title']) ? esc($questionnaire['title']) : 'Judul Tidak Tersedia' ?></h2>
                    <p class="text-muted" style="margin: 0; font-size: 13px;"><?= isset($questionnaire['deskripsi']) ? esc($questionnaire['deskripsi']) : 'Deskripsi Tidak Tersedia' ?></p>
                </div>
            </div>

            <!-- Top Controls (optimasi: flex space-between, gap 12px) -->
            <div class="top-controls" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px;">
                <div class="controls-container"></div>
                <div class="button-container" style="display: flex; gap: 12px;">
                    <a href="<?= base_url('admin/questionnaire/' . (isset($questionnaire['id']) ? $questionnaire['id'] : 0) . '/pages/create') ?>"
                        class="btn-add" style="padding: 10px 16px; font-size: 13px;">
                        <i class="fas fa-plus"></i> Tambah Halaman
                    </a>
                </div>
            </div>

            <!-- Table Container (optimasi: card tipis, rapatkan) -->
            <div class="table-container" style="border-radius: 8px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 0;">
                <div class="table-wrapper" style="overflow-x: auto;">
                    <?php if (empty($pages)): ?>
                        <div class="alert alert-warning" style="margin: 0; border-radius: 0;">  <!-- Rapatkan margin -->
                            Belum ada halaman kuesioner.
                        </div>
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
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 12px 16px;">
                                            <span class="status-badge status-inactive"><?= esc($page['order_no']) ?></span>
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <div class="questionnaire-info">
                                                <div class="questionnaire-title"><?= esc($page['page_title']) ?></div>
                                            </div>
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <div class="questionnaire-info">
                                                <div class="questionnaire-description"><?= esc($page['page_description']) ?></div>
                                            </div>
                                        </td>
                                        <td class="action-cell" style="padding: 12px 16px; text-align: center;">
                                            <div class="action-buttons" style="gap: 4px;">
                                                <a href="<?= base_url('admin/questionnaire/' . (isset($questionnaire['id']) ? $questionnaire['id'] : 0) . '/pages/' . $page['id'] . '/sections') ?>"
                                                    class="btn-action btn-edit" title="Atur Pertanyaan" style="margin: 0;">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                                <a href="<?= base_url('admin/questionnaire/' . (isset($questionnaire['id']) ? $questionnaire['id'] : 0) . '/pages/' . $page['id'] . '/edit') ?>"
                                                    class="btn-action btn-edit" title="Edit" style="margin: 0;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn-action btn-delete delete-page"
                                                    data-id="<?= $page['id'] ?>" title="Hapus" style="margin: 0;">
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
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // ✅ Alert hapus (sudah ada, biarkan)
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
                    window.location.href = '<?= base_url('admin/questionnaire/' . (isset($questionnaire['id']) ? $questionnaire['id'] : 0) . '/pages') ?>/' + pageId + '/delete';
                }
            });
        });
    });

    // ✅ Alert sukses setelah redirect (flashdata dari session)
    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success') ?>',
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= session()->getFlashdata('error') ?>',
            showConfirmButton: true
        });
    <?php endif; ?>
});
</script>

            </div>

            <!-- JS (tidak berubah) -->
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
                                    window.location.href = '<?= base_url('admin/questionnaire/' . (isset($questionnaire['id']) ? $questionnaire['id'] : 0) . '/pages') ?>/' + pageId + '/delete';
                                }
                            });
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
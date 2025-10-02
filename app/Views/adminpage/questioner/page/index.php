<!-- desain navbar index.php page -->
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/questioner/page/index.css') ?>" rel="stylesheet">

<!-- Navbar -->
    <nav class="navbar navbar-light bg-white border-bottom shadow-sm mb-3">
        <div class="container-fluid px-3">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/questionnaire') ?>">Daftar Kuesioner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/questionnaire/14/pages') ?>">Halaman Kuesioner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/questionnaire/3/pages/30/sections') ?>">Kuesioner Section</a>
                </li>
            </ul>
        </div>
    </nav>

<div class="pengguna-page">
    <div class="page-wrapper">
        <div class="page-container">
            <!-- Judul -->
            <h2 class="page-title"> 📑 Halaman Kuesioner: <?= isset($questionnaire['title']) ? esc($questionnaire['title']) : 'Judul Tidak Tersedia' ?></h2>
            <p class="text-muted"><?= isset($questionnaire['deskripsi']) ? esc($questionnaire['deskripsi']) : 'Deskripsi Tidak Tersedia' ?></p>

            <!-- Top Controls -->
            <div class="top-controls">
                <div class="controls-container"></div>
                <div class="button-container">
                    <a href="<?= base_url('admin/questionnaire/' . (isset($questionnaire['id']) ? $questionnaire['id'] : 0) . '/pages/create') ?>"
                        class="btn-add">
                        <i class="fas fa-plus"></i> Tambah Halaman
                    </a>
                </div>
            </div>

            <!-- Bagian Tabel -->
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
                                        <td>
                                            <div class="questionnaire-info">
                                                <div class="questionnaire-title"><?= esc($page['page_title']) ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="questionnaire-info">
                                                <div class="questionnaire-description"><?= esc($page['page_description']) ?></div>
                                            </div>
                                        </td>
                                        <td class="action-cell">
                                            <div class="action-buttons">
                                                <!-- Atur Pertanyaan -->
                                                <a href="<?= base_url('admin/questionnaire/' . (isset($questionnaire['id']) ? $questionnaire['id'] : 0) . '/pages/' . $page['id'] . '/sections') ?>"
                                                    class="btn-action btn-edit" title="Atur Pertanyaan">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                                <!-- Edit -->
                                                <a href="<?= base_url('admin/questionnaire/' . (isset($questionnaire['id']) ? $questionnaire['id'] : 0) . '/pages/' . $page['id'] . '/edit') ?>"
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
                        // redirect to the page delete endpoint
                        window.location.href = '<?= base_url('admin/questionnaire/' . (isset($questionnaire['id']) ? $questionnaire['id'] : 0) . '/pages') ?>/' + pageId + '/delete';
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection() ?>
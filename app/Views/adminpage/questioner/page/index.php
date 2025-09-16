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

<link rel="stylesheet" href="<?= base_url('css/questioner/page/index.css') ?>">

<div>
    <!-- Navbar -->
    <nav class="sticky top-0 bg-white navbar-shadow nav-bg border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <a href="<?= base_url('admin/questionnaire') ?>"
                       class="nav-title font-semibold text-xl cursor-pointer hover:text-blue-600 transition">
                        Daftar Kuesioner
                    </a>
                    <span class="font-semibold text-lg text-gray-700">
                        <?= esc($questionnaire['title']) ?>
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Card -->
    <div class="questionnaire-card mt-4">
        <div class="card-header flex justify-between items-center">
            <div>
                <h3 class="card-title text-xl font-semibold">
                    Halaman Kuesioner: <?= esc($questionnaire['title']) ?>
                </h3>
                <p class="text-gray-500 text-sm"><?= esc($questionnaire['deskripsi']) ?></p>
            </div>
            <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/create") ?>"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                <i class="fa-solid fa-plus"></i> Tambah Halaman
            </a>
        </div>

        <div class="overflow-x-auto">
            <?php if (empty($pages)): ?>
                <div class="alert alert-warning m-4">Belum ada halaman kuesioner.</div>
            <?php else: ?>
                <table class="questionnaire-table w-full text-sm text-left text-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 w-24">Urutan</th>
                            <th class="px-6 py-3">Judul Halaman</th>
                            <th class="px-6 py-3">Deskripsi</th>
                            <th class="px-6 py-3 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded-full">
                                    <?= esc($page['order_no']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900"><?= esc($page['page_title']) ?></td>
                            <td class="px-6 py-4 text-gray-600"><?= esc($page['page_description']) ?></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-4">
                                    <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/{$page['id']}/sections") ?>" 
                                       class="text-blue-600" title="Atur Pertanyaan">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url("admin/questionnaire/{$questionnaire['id']}/pages/{$page['id']}/edit") ?>" 
                                       class="text-yellow-600" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button class="delete-page text-red-600" 
                                            data-id="<?= $page['id'] ?>" 
                                            title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
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

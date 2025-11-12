<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/pengguna/index.css') ?>" rel="stylesheet">

<div class="pengguna-page">
    <div class="page-wrapper">
        <div class="page-container">
            <?= $this->include('kaprodi/kuesioner/breadcupp') ?>

            <!-- SweetAlert2 CDN -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <!-- 🔔 Notifikasi flashdata -->
            <?php if (session()->getFlashdata('success')): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '<?= esc(session()->getFlashdata('success')) ?>',
                        showConfirmButton: false,
                        timer: 2000
                    });
                </script>
            <?php elseif (session()->getFlashdata('error')): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '<?= esc(session()->getFlashdata('error')) ?>',
                        showConfirmButton: false,
                        timer: 2000
                    });
                </script>
            <?php endif; ?>

            <!-- Judul -->
            <h2 class="page-title">📑 Halaman Kuesioner: <?= esc($questionnaire['title']) ?></h2>
            <p class="text-muted"><?= esc($questionnaire['deskripsi']) ?></p>

            <!-- Top Controls -->
            <div class="top-controls">
                <div class="controls-container"></div>
                <div class="button-container">
                    <a href="<?= base_url("kaprodi/kuesioner/{$questionnaire['id']}/pages/create") ?>" class="btn-add">
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
                                    <?php
                                    $canEdit = !empty($page['canEdit']);
                                    $canAddChild = !empty($page['canAddChild']);
                                    $sessionId = session()->get('id_account');

                                    // Debug per halaman
                                    echo "<tr style='background:#fff8dc;'><td colspan='4'>
                                        <strong>DEBUG:</strong> Page ID={$page['id']}, created_by={$page['created_by']}, 
                                        session_id={$sessionId}, canEdit=" . ($canEdit ? 'true' : 'false') . ",
                                        canAddChild=" . ($canAddChild ? 'true' : 'false') . "
                                    </td></tr>";
                                    ?>

                                    <tr>
                                        <td><?= esc($page['order_no']) ?></td>
                                        <td><?= esc($page['page_title']) ?></td>
                                        <td><?= esc($page['page_description']) ?></td>
                                        <td class="action-cell">
                                            <div class="action-buttons">
                                                <?php if ($canEdit): ?>
                                                    <!-- Halaman kaprodi sendiri → bisa edit dan hapus -->
                                                    <a href="<?= base_url("kaprodi/kuesioner/{$questionnaire['id']}/pages/{$page['id']}/sections") ?>" class="btn-action btn-edit" title="Atur Pertanyaan">
                                                        <i class="fas fa-file-alt"></i>
                                                    </a>
                                                    <a href="<?= base_url("kaprodi/kuesioner/{$questionnaire['id']}/pages/{$page['id']}/edit") ?>" class="btn-action btn-edit" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn-action btn-delete delete-page" data-id="<?= $page['id'] ?>" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php elseif ($canAddChild): ?>
                                                    <!-- Halaman admin dengan conditional logic prodi kaprodi → bisa tambah child -->
                                                    <a href="<?= base_url("kaprodi/kuesioner/{$questionnaire['id']}/pages/create?parent={$page['id']}") ?>" class="btn-add">
                                                        Tambah Halaman
                                                    </a>
                                                <?php else: ?>
                                                    <!-- Halaman admin lain → tidak bisa diubah -->
                                                    <span class="text-gray-500">Tidak bisa diubah</span>
                                                <?php endif; ?>
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

<!-- SweetAlert konfirmasi hapus -->
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
                        window.location.href = '<?= base_url("kaprodi/kuesioner/{$questionnaire['id']}/pages") ?>/' + pageId + '/delete';
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection() ?>

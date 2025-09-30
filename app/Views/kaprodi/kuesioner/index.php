<!-- desain daftar kuesioner -->
<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/questioner/index.css') ?>" rel="stylesheet">

<div class="pengguna-page">
    <div class="page-wrapper">
        <div class="page-container">
            <h2 class="page-title"> Daftar Kuesioner Kaprodi <?= esc($kaprodi['nama_prodi']) ?> </h2>

            <!-- Top Controls -->
            <div class="top-controls">
                <div class="controls-container"></div>

                <div class="button-container">
                    <a href="<?= base_url('kaprodi/kuesioner/create') ?>" class="btn-add">
                        <i class="fas fa-plus"></i> Buat Kuesioner Baru
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <div class="table-wrapper">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questionnaires as $q): ?>
                                <tr>
                                    <td><?= esc($q['title']) ?></td>
                                    <td><?= esc($q['deskripsi']) ?></td>
                                    <td>
                                        <?php if ($q['is_active'] === 'active'): ?>
                                            <span class="status-badge status-active">Aktif</span>
                                        <?php elseif ($q['is_active'] === 'draft'): ?>
                                            <span class="status-badge" style="background:#fef3c7;color:#b45309;">Draft</span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <!-- Kelola Halaman -->
                                            <a href="<?= base_url('kaprodi/kuesioner/' . $q['id'] . '/pages') ?>"
                                                class="btn-action btn-edit"
                                                title="Kelola Halaman">
                                                <i class="fas fa-file-alt"></i>
                                            </a>

                                            <?php if (!$q['is_admin_created']): ?>
                                                <!-- Edit -->
                                                <a href="<?= base_url('kaprodi/kuesioner/' . $q['id'] . '/edit') ?>"
                                                    class="btn-action btn-edit"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- Hapus -->
                                                <button class="btn-action btn-delete delete-questionnaire"
                                                    data-id="<?= $q['id'] ?>"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const baseUrl = "<?= base_url('kaprodi/kuesioner') ?>";
                    document.querySelectorAll(".delete-questionnaire").forEach(button => {
                        button.addEventListener("click", function() {
                            const id = this.getAttribute("data-id");
                            Swal.fire({
                                title: 'Yakin ingin menghapus?',
                                text: "Data questionnaire beserta halaman & pertanyaan akan terhapus permanen!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Ya, hapus!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = `${baseUrl}/${id}/delete`;
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
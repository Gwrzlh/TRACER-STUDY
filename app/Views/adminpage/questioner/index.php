<!-- desain daftar kuesioner -->
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/questioner/index.css') ?>" rel="stylesheet">

<div class="pengguna-page">
    <div class="page-wrapper" style="padding: 16px;">  <!-- Rapatkan padding dari 24px ke 16px -->
        <div class="page-container">
            <!-- Breadcrumb (baru) -->
            <?= $this->include('adminpage/questioner/breadcrumb') ?>

            <h2 class="page-title"> 📋 Daftar Kuesioner</h2>

            <!-- Top Controls (optimasi: flex space-between, gap 12px, hilangkan empty space) -->
            <div class="top-controls" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px;">
                <div class="controls-container" style="flex: 1; display: flex; gap: 12px;"></div>  <!-- Rapatkan -->
                <div class="button-container" style="display: flex; gap: 12px; white-space: nowrap;">  <!-- Stack buttons horizontal -->
                    <!-- Import -->
                    <form action="<?= base_url('admin/questionnaire/import') ?>" method="post" enctype="multipart/form-data" style="display: inline; margin: 0;">
                        <input type="file" name="excel_file" id="excel_file" style="display: none;" accept=".xlsx" required>
                        <button type="button" class="btn-add" onclick="document.getElementById('excel_file').click();" style="padding: 10px 16px; font-size: 13px;">  <!-- Rapatkan size -->
                            <i class="fas fa-upload"></i> Import
                        </button>
                        <script>
                            document.getElementById('excel_file').addEventListener('change', function() {
                                if (this.files[0]) this.form.submit();
                            });
                        </script>
                    </form>
                    <!-- Create -->
                    <a href="<?= base_url('admin/questionnaire/create') ?>" class="btn-add" style="padding: 10px 16px; font-size: 13px;">
                        <i class="fas fa-plus"></i> Buat Baru
                    </a>
                </div>
            </div>

            <!-- Table Container (optimasi: card tipis, hilangkan shadow berat, rapatkan margin) -->
            <div class="table-container" style="border-radius: 8px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 0;">
                <div class="table-wrapper" style="overflow-x: auto;">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Deskripsi</th>
                                <th>Conditional</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questionnaires as $q): ?>
                                <tr style="border-bottom: 1px solid #f3f4f6;">  <!-- Tambah border tipis untuk flow -->
                                    <td class="questionnaire-info" style="padding: 12px 16px;">  <!-- Rapatkan padding -->
                                        <div class="questionnaire-title"><?= esc($q['title']) ?></div>
                                    </td>
                                    <td class="questionnaire-info" style="padding: 12px 16px;">
                                        <div class="questionnaire-description"><?= esc($q['deskripsi']) ?></div>
                                    </td>
                                    <td style="padding: 12px 16px; text-align: center;">
                                        <?php if ($q['conditional_logic']): ?>
                                            <span class="status-badge status-active">Ya</span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">Tidak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px 16px; text-align: center;">
                                        <?php if ($q['is_active'] === 'active'): ?>
                                            <span class="status-badge status-active">Aktif</span>
                                        <?php elseif ($q['is_active'] === 'draft'): ?>
                                            <span class="status-badge" style="background:#fef3c7;color:#b45309;">Draft</span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-cell" style="padding: 12px 16px; text-align: center;">
                                        <div class="action-buttons" style="gap: 4px;">  <!-- Rapatkan gap -->
                                            <a href="<?= base_url('admin/questionnaire/' . $q['id'] . '/export') ?>"
                                                class="btn-action btn-export"
                                                title="Export Excel" style="margin: 0;">
                                                <i class="fas fa-file-export"></i>
                                            </a>
                                            <a href="<?= base_url('admin/questionnaire/' . $q['id'] . '/download-pdf') ?>"
                                                class="btn-action btn-download"
                                                title="Download PDF" style="margin: 0;">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="<?= base_url('admin/questionnaire/' . $q['id'] . '/pages') ?>"
                                                class="btn-action btn-edit"
                                                title="Kelola Halaman" style="margin: 0;">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <a href="<?= base_url('admin/questionnaire/' . $q['id'] . '/edit') ?>"
                                                class="btn-action btn-edit"
                                                title="Edit" style="margin: 0;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn-action btn-delete delete-questionnaire"
                                                data-id="<?= $q['id'] ?>"
                                                title="Hapus" style="margin: 0;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- JS (tidak berubah) -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const baseUrl = "<?= base_url('admin/questionnaire') ?>";
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
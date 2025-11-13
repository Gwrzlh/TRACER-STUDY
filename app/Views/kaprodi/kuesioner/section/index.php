<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="/css/questioner/section/index.css">

<div class="container mt-4">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Page Detail</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="styles.css">
        <style>
            /* (semua CSS kamu tetap di sini) */
        </style>
    </head>

    <body class="bg-gray-50">
        <div class="pengguna-page">
            <div class="page-wrapper">
                <div class="page-container">
                    <?= $this->include('kaprodi/kuesioner/breadcupp') ?>
                    <h2 class="page-title">📑 Sunting Kuesioner Section</h2>

                    <div class="top-controls">
                        <div class="controls-container">
                            <div class="info-box">
                                <div class="info-value"><?= count($sections) ?></div>
                                <div class="info-label">Total Sections</div>
                            </div>
                        </div>

                        <div class="button-container">
                            <a href="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/create") ?>"
                                class="btn-add">
                                <i class="fas fa-plus"></i> Tambah Section
                            </a>
                        </div>
                    </div>

                    <!-- ✅ SweetAlert2 Flash Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: '<?= esc(session()->getFlashdata('success')) ?>',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });
                        </script>
                    <?php elseif (session()->getFlashdata('error')): ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: '<?= esc(session()->getFlashdata('error')) ?>',
                                showConfirmButton: false,
                                timer: 2500,
                                timerProgressBar: true
                            });
                        </script>
                    <?php endif; ?>

                    <!-- Sections Table -->
                    <?php if (empty($sections)): ?>
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada section</h5>
                                <p class="text-muted">Mulai dengan menambahkan section pertama untuk halaman ini.</p>
                                <a href="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/create") ?>"
                                    class="btn-add">
                                    <i class="fas fa-plus"></i> Tambah Section Pertama
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <div class="table-wrapper">
                                <table class="user-table">
                                    <thead>
                                        <tr>
                                            <th>Section ID</th>
                                            <th>Section Name</th>
                                            <th>Description</th>
                                            <th>Conditional Logic</th>
                                            <th>Num of Question</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sections as $section): ?>
                                            <tr>
                                                <td><span class="badge bg-primary"><?= $section['id'] ?></span></td>
                                                <td>
                                                    <div class="questionnaire-title"><?= esc($section['section_title']) ?></div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-eye me-1"></i>Show Title: <?= $section['show_section_title'] ? 'Yes' : 'No' ?>
                                                        <i class="fas fa-align-left ms-2 me-1"></i>Show Desc: <?= $section['show_section_description'] ? 'Yes' : 'No' ?>
                                                    </small>
                                                </td>
                                                <td><?= esc($section['section_description']) ?></td>
                                                <td>
                                                    <?php if ($section['conditional_logic'] != null): ?>
                                                        <span class="status-badge status-active">Active</span>
                                                    <?php else: ?>
                                                        <span class="status-badge status-inactive">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="status-badge status-active"><?= $section['question_count'] ?? 0 ?></span>
                                                </td>
                                                <td class="action-cell">
                                                    <div class="action-buttons">
                                                        <button class="btn-action btn-edit move-up-btn" title="Move Up" data-section-id="<?= $section['id'] ?>">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </button>
                                                        <button class="btn-action btn-edit move-down-btn" title="Move Down" data-section-id="<?= $section['id'] ?>">
                                                            <i class="fas fa-arrow-down"></i>
                                                        </button>
                                                        <a href="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/questions") ?>"
                                                            class="btn-action btn-edit" title="Manage Questions">
                                                            <i class="fas fa-cogs"></i>
                                                        </a>
                                                        <a href="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/edit") ?>"
                                                            class="btn-action btn-edit" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <!-- ✅ Hapus pakai SweetAlert2 -->
                                                        <button type="button" class="btn-action btn-delete delete-section"
                                                            data-url="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/delete") ?>"
                                                            title="Hapus">
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
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // ✅ Konfirmasi Hapus dengan SweetAlert2
            $(document).on('click', '.delete-section', function(e) {
                e.preventDefault();
                const url = $(this).data('url');
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Section dan semua pertanyaannya akan terhapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });

            // ✅ Move Up dan Down pakai AJAX + SweetAlert error
            $('.move-up-btn, .move-down-btn').on('click', function() {
                const sectionId = $(this).data('section-id');
                const isUp = $(this).hasClass('move-up-btn');
                const action = isUp ? 'moveUp' : 'moveDown';
                $.ajax({
                    url: '<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections") ?>/' + sectionId + '/' + action,
                    type: 'POST',
                    data: {
                        section_id: sectionId,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Gagal memindahkan section.',
                                confirmButtonColor: '#1d4ed8'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan!',
                            text: xhr.responseText || 'Server tidak merespon.',
                            confirmButtonColor: '#1d4ed8'
                        });
                    }
                });
            });
        </script>

<?= $this->endSection() ?>

<!-- desain navbar section -->
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/questioner/section/index.css') ?>">

<div class="pengguna-page">
    <div class="page-wrapper" style="padding: 16px;">  <!-- Rapatkan padding -->
        <div class="page-container">
            <!-- Breadcrumb (baru) -->
            <?= $this->include('adminpage/questioner/breadcrumb') ?>

            <h2 class="page-title">📑 Sunting Kuesioner Section</h2>

            <!-- Top Controls (optimasi: flex, gap 12px, info-box inline) -->
            <div class="top-controls" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px;">
                <div class="controls-container" style="display: flex; align-items: center; gap: 12px;">
                    <div class="info-box" style="min-width: auto; padding: 8px 12px; font-size: 13px;">
                        <div class="info-value"><?= count($sections) ?></div>
                        <div class="info-label">Total Sections</div>
                    </div>
                </div>
                <div class="button-container" style="display: flex; gap: 12px;">
                    <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/create") ?>" 
                        class="btn-add" style="padding: 10px 16px; font-size: 13px;">
                        <i class="fas fa-plus"></i> Tambah Section
                    </a>
                </div>
            </div>

            <!-- Flash Messages (rapatkan margin) -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 16px;">
                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 16px;">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Table Container (optimasi: card tipis) -->
            <div class="table-container" style="border-radius: 8px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 0;">
                <div class="table-wrapper" style="overflow-x: auto;">
                    <?php if (empty($sections)): ?>
                        <div class="alert alert-warning" style="margin: 0; border-radius: 0;">  <!-- Rapatkan -->
                            Belum ada section.
                        </div>
                    <?php else: ?>
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
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 12px 16px;">
                                            <span class="badge bg-primary"><?= $section['id'] ?></span>
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <div class="questionnaire-title"><?= esc($section['section_title']) ?></div>
                                            <small class="text-muted" style="font-size: 12px;">
                                                <i class="fas fa-eye me-1"></i>Show Title: <?= $section['show_section_title'] ? 'Yes' : 'No' ?>
                                                <i class="fas fa-align-left ms-2 me-1"></i>Show Desc: <?= $section['show_section_description'] ? 'Yes' : 'No' ?>
                                            </small>
                                        </td>
                                        <td style="padding: 12px 16px;">
                                            <div class="questionnaire-description" title="<?= esc($section['section_description']) ?>" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                <?= esc($section['section_description']) ?>
                                            </div>
                                        </td>
                                        <td style="padding: 12px 16px; text-align: center;">
                                            <?php if ($section['conditional_logic'] != null ): ?>
                                                <span class="status-badge status-active">Active</span>
                                            <?php else: ?>
                                                <span class="status-badge status-inactive">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 12px 16px; text-align: center;">
                                            <span class="status-badge status-active"><?= $section['question_count'] ?? 0 ?></span>
                                        </td>
                                        <td class="action-cell" style="padding: 12px 16px; text-align: center;">
                                            <div class="action-buttons" style="gap: 4px;">
                                                <button class="btn-action btn-edit move-up-btn" title="Move Up" data-section-id="<?= $section['id'] ?>" style="margin: 0;">
                                                    <i class="fas fa-arrow-up"></i>
                                                </button>
                                                <button class="btn-action btn-edit move-down-btn" title="Move Down" data-section-id="<?= $section['id'] ?>" style="margin: 0;">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                                <a href="#" class="btn-action btn-duplicate" data-section-id="<?= $section['id'] ?>" title="Duplicate Section" style="margin: 0;">
                                                    <i class="fas fa-copy"></i>
                                                </a>
                                                <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/questions") ?>" 
                                                    class="btn-action btn-edit" title="Manage Questions" style="margin: 0;">
                                                    <i class="fas fa-cogs"></i>
                                                </a>
                                                <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/edit") ?>" 
                                                    class="btn-action btn-edit" title="Edit" style="margin: 0;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/delete") ?>" 
                                                      method="post" style="display: inline-block; margin: 0;" 
                                                      onsubmit="return confirm('Yakin ingin menghapus section ini dan semua pertanyaannya?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn-action btn-delete" title="Delete" style="margin: 0;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- JS (tidak berubah) -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                // ... kode JS existing untuk move-up, move-down, duplicate (tidak ubah) ...
            </script>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
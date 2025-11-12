<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="/css/questioner/question/index.css">

<div class="question-manager-container">
    <!-- Header -->
    <div class="page-header" style="padding: 16px; border-radius: 8px; margin-bottom: 16px;">
        <?= $this->include('kaprodi/kuesioner/breadcupp') ?>
        <div class="header-content" style="gap: 12px;">
            <img src="/images/logo.png" alt="Tracer Study" class="header-logo" style="height: 48px;">
            <div class="header-text">
                <h2 class="page-title" style="margin: 0; font-size: 24px;">
                    Kelola Pertanyaan - <?= esc($section['section_title'] ?? '-') ?>
                </h2>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <div class="content-main">
            <!-- Form Add/Edit Question -->
            <div class="card add-question-card">
                <div class="card-header card-header-primary">
                    <h3 class="card-title">Tambah Pertanyaan Baru</h3>
                    <button type="button" id="toggleForm" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>

                <div class="card-body form-container hidden" id="formContainer">
                    <form id="questionForm"
                          action="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/store") ?>"
                          method="post">
                        <?= csrf_field() ?>

                        <!-- Question Text -->
                        <div class="form-group">
                            <label for="question_text" class="form-label">Question Text <span class="required-mark">*</span></label>
                            <textarea id="question_text" name="question_text" class="form-control" rows="3"
                                      placeholder="Masukkan teks pertanyaan..." required><?= old('question_text') ?></textarea>
                        </div>

                        <!-- Question Type -->
                        <div class="form-group">
                            <label for="question_type" class="form-label">Question Type <span class="required-mark">*</span></label>
                            <select id="question_type" name="question_type" class="form-control" required>
                                <option value="">-- Pilih Jenis Pertanyaan --</option>
                                <optgroup label="Text Input">
                                    <option value="text">Single Line Text</option>
                                    <option value="textarea">Multi Line Text</option>
                                    <option value="email">Email</option>
                                    <option value="number">Number</option>
                                    <option value="phone">Phone</option>
                                </optgroup>
                                <optgroup label="Selection">
                                    <option value="radio">Radio Buttons</option>
                                    <option value="checkbox">Checkboxes</option>
                                    <option value="dropdown">Dropdown List</option>
                                </optgroup>
                                <optgroup label="Date & Time">
                                    <option value="date">Date</option>
                                    <option value="time">Time</option>
                                    <option value="datetime">Date Time</option>
                                </optgroup>
                                <optgroup label="Advanced">
                                    <option value="scale">Scale/Rating</option>
                                    <option value="matrix">Matrix</option>
                                    <option value="file">File Upload</option>
                                    <option value="user_field">User Field</option>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Options Wrapper -->
                        <div id="options_wrapper" class="form-group options-wrapper hidden">
                            <label class="form-label">Answer Options</label>
                            <div id="option_list" class="option-list">
                                <div class="option-item">
                                    <input type="text" name="options[]" class="form-control" placeholder="Option text...">
                                    <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)">
                                    <button type="button" class="btn btn-danger btn-icon remove-option"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button type="button" id="add_option" class="btn btn-secondary btn-sm">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>

                        <!-- Other Dynamic Wrappers -->
                        <div id="scale_wrapper" class="form-group scale-wrapper hidden">
                            <label class="form-label">Scale Settings</label>
                            <div class="form-row">
                                <div class="form-col"><label>Min</label><input type="number" name="scale_min" class="form-control" value="1"></div>
                                <div class="form-col"><label>Max</label><input type="number" name="scale_max" class="form-control" value="5"></div>
                                <div class="form-col"><label>Step</label><input type="number" name="scale_step" class="form-control" value="1"></div>
                            </div>
                        </div>

                        <div id="file_wrapper" class="form-group file-wrapper hidden">
                            <label>File Upload Settings</label>
                            <div class="form-row">
                                <div class="form-col"><label>Allowed Types</label><input type="text" name="allowed_types" class="form-control" value="pdf,doc,docx"></div>
                                <div class="form-col"><label>Max Size (MB)</label><input type="number" name="max_file_size" class="form-control" value="5"></div>
                            </div>
                        </div>

                        <div id="matrix_wrapper" class="form-group matrix-wrapper hidden">
                            <label>Matrix Settings</label>
                            <div class="form-row">
                                <div class="form-col"><label>Rows</label><input type="text" name="matrix_rows" class="form-control" placeholder="Baris 1, Baris 2"></div>
                                <div class="form-col"><label>Columns</label><input type="text" name="matrix_columns" class="form-control" placeholder="Kolom 1, Kolom 2"></div>
                            </div>
                        </div>

                        <div id="user_field_wrapper" class="form-group user-field-wrapper hidden">
                            <label>User Profile Field</label>
                            <select name="user_field_name" class="form-control">
                                <option value="">-- Pilih Field Profil --</option>
                                <option value="nama_lengkap">Nama Lengkap</option>
                                <option value="nim">NIM</option>
                                <option value="email">Email</option>
                                <option value="id_prodi">ID Prodi</option>
                                <option value="id_jurusan">ID Jurusan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Required</label>
                            <input type="checkbox" name="is_required" value="1">
                        </div>

                        <div class="form-group">
                            <label>Order</label>
                            <input type="number" name="order_no" class="form-control" value="<?= $next_order ?? 1 ?>" min="1" required>
                        </div>

                        <div class="form-actions">
                            <button type="button" id="cancelForm" class="btn btn-neutral">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Questions List -->
            <div class="card questions-list-card">
                <div class="card-header card-header-neutral">
                    <h3 class="card-title">Questions List (<?= count($questions ?? []) ?>)</h3>
                </div>
                <div class="card-body questions-body">
                    <?php if (empty($questions ?? [])): ?>
                        <div class="empty-state">
                            <i class="fas fa-question-circle"></i>
                            <h5>No Questions Yet</h5>
                            <p>Mulai tambahkan pertanyaan menggunakan form di atas.</p>
                        </div>
                    <?php else: ?>
                        <div id="questionsList" class="questions-list">
                            <?php foreach ($questions as $q): ?>
                                <div class="question-item" data-question-id="<?= $q['id'] ?>">
                                    <div class="question-header">
                                        <span class="question-order"><?= $q['order_no'] ?></span>
                                        <div class="question-info">
                                            <h6><?= esc($q['question_text']) ?></h6>
                                            <div class="question-badges">
                                                <span class="badge badge-type"><?= ucfirst($q['question_type']) ?></span>
                                                <?php if ($q['is_required']): ?><span class="badge badge-required">Required</span><?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="question-actions">
                                            <button class="btn btn-blue btn-sm edit-question"
                                                    data-question-id="<?= $q['id'] ?>"
                                                    data-edit-url="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/{$q['id']}") ?>"
                                                    data-update-url="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/{$q['id']}/update") ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm duplicate-question"
                                                    data-question-id="<?= $q['id'] ?>"
                                                    data-duplicate-url="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/{$q['id']}/duplicate") ?>">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-question"
                                                    data-question-id="<?= $q['id'] ?>"
                                                    data-delete-url="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/delete/{$q['id']}") ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/js/questioner/question.js"></script>

<style>
.hidden { display: none; }
.card { border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 24px; }
.card-header-primary { background-color: #f97316; color: white; display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; }
.card-header-neutral { background: #f1f5f9; }
.card-title { margin: 0; font-weight: 600; }
.question-item { border-bottom: 1px solid #e5e7eb; padding: 12px 0; display: flex; justify-content: space-between; align-items: center; }
.question-badges .badge { margin-right: 6px; padding: 4px 8px; border-radius: 6px; font-size: 12px; }
.badge-required { background-color: #dc2626; color: white; }
.badge-type { background-color: #3b82f6; color: white; }
</style>
<?= $this->endSection() ?>

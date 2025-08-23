<div class="container-fluid mt-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <h4>Kelola Pertanyaan - <?= esc($section['section_title']) ?></h4>
            <p class="text-muted"><?= esc($section['section_description'] ?? '') ?></p>

            <!-- Form Tambah Pertanyaan -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Tambah Pertanyaan Baru</span>
                    <button type="button" class="btn btn-sm btn-outline-light" id="toggleForm">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
                <div class="card-body" id="formContainer" style="display: none;">
                    <form id="questionForm" action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/store") ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- Question Title -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Question Title <span class="text-danger">*</span></label>
                            <input type="text" name="question_title" class="form-control" placeholder="Masukkan judul pertanyaan..." required value="<?= old('question_title') ?>">
                            <div class="form-text">Judul singkat untuk pertanyaan ini</div>
                        </div>

                        <!-- Question Text -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Question Text <span class="text-danger">*</span></label>
                            <textarea name="question_text" class="form-control" rows="3" placeholder="Masukkan teks pertanyaan lengkap..." required><?= old('question_text') ?></textarea>
                            <div class="form-text">Teks pertanyaan yang akan ditampilkan kepada responden</div>
                        </div>

                        <!-- Question Type -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Question Type <span class="text-danger">*</span></label>
                            <select name="question_type" id="question_type" class="form-select" required>
                                <option value="">-- Pilih Jenis Pertanyaan --</option>
                                <optgroup label="Text Input">
                                    <option value="text" <?= old('question_type') == 'text' ? 'selected' : '' ?>>Single Line Text</option>
                                    <option value="textarea" <?= old('question_type') == 'textarea' ? 'selected' : '' ?>>Multi Line Text</option>
                                    <option value="email" <?= old('question_type') == 'email' ? 'selected' : '' ?>>Email</option>
                                    <option value="number" <?= old('question_type') == 'number' ? 'selected' : '' ?>>Number</option>
                                    <option value="phone" <?= old('question_type') == 'phone' ? 'selected' : '' ?>>Phone</option>
                                </optgroup>
                                <optgroup label="Selection">
                                    <option value="radio" <?= old('question_type') == 'radio' ? 'selected' : '' ?>>Radio Buttons</option>
                                    <option value="checkbox" <?= old('question_type') == 'checkbox' ? 'selected' : '' ?>>Checkboxes</option>
                                    <option value="dropdown" <?= old('question_type') == 'dropdown' ? 'selected' : '' ?>>Dropdown List</option>
                                </optgroup>
                                <optgroup label="Date & Time">
                                    <option value="date" <?= old('question_type') == 'date' ? 'selected' : '' ?>>Date</option>
                                    <option value="time" <?= old('question_type') == 'time' ? 'selected' : '' ?>>Time</option>
                                    <option value="datetime" <?= old('question_type') == 'datetime' ? 'selected' : '' ?>>Date Time</option>
                                </optgroup>
                                <optgroup label="Advanced">
                                    <option value="scale" <?= old('question_type') == 'scale' ? 'selected' : '' ?>>Scale/Rating</option>
                                    <option value="matrix" <?= old('question_type') == 'matrix' ? 'selected' : '' ?>>Matrix</option>
                                    <option value="file" <?= old('question_type') == 'file' ? 'selected' : '' ?>>File Upload</option>
                                    <option value="user_field" <?= old('question_type') == 'user_field' ? 'selected' : '' ?>>User Field</option>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Options for Selection Types -->
                        <div class="mb-3" id="options_wrapper" style="display: none;">
                            <label class="form-label fw-bold">Answer Options</label>
                            <div id="option_list">
                                <div class="input-group mb-2">
                                    <input type="text" name="options[]" class="form-control" placeholder="Option text...">
                                    <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)">
                                    <select name="next_question_ids[]" class="form-control">
                                        <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                                        <?php foreach ($all_questions as $q): ?>
                                            <option value="<?= $q['id'] ?>"><?= esc($q['question_title'] ?? $q['question_text']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-danger remove-option">&times;</button>
                                </div>
                            </div>
                            <button type="button" id="add_option" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>

                        <!-- Scale Settings -->
                        <div class="mb-3" id="scale_wrapper" style="display: none;">
                            <label class="form-label fw-bold">Scale Settings</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Min Value</label>
                                    <input type="number" name="scale_min" class="form-control" value="1">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Max Value</label>
                                    <input type="number" name="scale_max" class="form-control" value="5">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Step</label>
                                    <input type="number" name="scale_step" class="form-control" value="1" min="1">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Min Label</label>
                                    <input type="text" name="scale_min_label" class="form-control" placeholder="e.g., Sangat Tidak Setuju">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Max Label</label>
                                    <input type="text" name="scale_max_label" class="form-control" placeholder="e.g., Sangat Setuju">
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Settings -->
                        <div class="mb-3" id="file_wrapper" style="display: none;">
                            <label class="form-label fw-bold">File Upload Settings</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Allowed File Types</label>
                                    <input type="text" name="allowed_types" class="form-control" placeholder="pdf,doc,docx,jpg,png" value="pdf,doc,docx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Max File Size (MB)</label>
                                    <input type="number" name="max_file_size" class="form-control" value="5" min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Matrix Settings -->
                        <div class="mb-3" id="matrix_wrapper" style="display: none;">
                            <label class="form-label fw-bold">Matrix Settings</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Rows</label>
                                    <input type="text" name="matrix_rows" class="form-control" placeholder="e.g., Baris 1, Baris 2, Baris 3">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Columns</label>
                                    <input type="text" name="matrix_columns" class="form-control" placeholder="e.g., Kolom 1, Kolom 2, Kolom 3">
                                </div>
                            </div>
                        </div>

                        <!-- Validation Settings -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Validation</label>
                                <div class="form-check">
                                    <input type="checkbox" name="is_required" value="1" id="is_required" class="form-check-input" <?= old('is_required') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_required">Required</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="enable_conditional" value="1" id="enable_conditional" class="form-check-input">
                                    <label class="form-check-label" for="enable_conditional">Enable Conditional Logic</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Order</label>
                                <input type="number" name="order_no" class="form-control" value="<?= old('order_no', $next_order) ?>" min="1" required>
                                <div class="form-text">Position of this question</div>
                            </div>
                        </div>

                        <!-- Conditional Logic -->
                        <div class="mb-3" id="conditional_wrapper" style="display: none;">
                            <label class="form-label fw-bold">Conditional Logic</label>
                            <div class="card border-info">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label">Show this question if:</label>
                                            <select name="parent_question_id" id="parent_question_id" class="form-select" onchange="loadConditionOptions()">
                                                <option value="">-- Select Question --</option>
                                                <?php foreach ($all_questions as $parent): ?>
                                                    <option value="<?= $parent['id'] ?>" <?= old('parent_question_id') == $parent['id'] ? 'selected' : '' ?>>
                                                        <?= esc($parent['question_title'] ?? $parent['question_text']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Condition:</label>
                                            <select name="condition_operator" id="condition_operator" class="form-select">
                                                <option value="is">Is</option>
                                                <option value="is not">Is Not</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Value:</label>
                                            <select name="condition_value" id="condition_value" class="form-select" disabled>
                                                <option value="">-- Pilih Nilai --</option>
                                            </select>
                                            <div class="form-text" id="condition_value_note" style="display: none;">Pilih nilai dari opsi pertanyaan parent.</div>
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle"></i> 
                                        This question will only appear when the selected question meets the specified condition
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" id="cancelForm">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Questions List -->
            <div class="card">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <span>Questions List (<?= count($questions) ?>)</span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-light" id="expandAll">
                            <i class="fas fa-expand-alt"></i> Expand All
                        </button>
                        <button type="button" class="btn btn-outline-light" id="collapseAll">
                            <i class="fas fa-compress-alt"></i> Collapse All
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($questions)): ?>
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-question-circle fa-3x mb-3"></i>
                            <h5>No Questions Yet</h5>
                            <p>Start by adding your first question using the form above.</p>
                        </div>
                    <?php else: ?>
                        <div id="questionsList">
                            <?php foreach ($questions as $index => $q): ?>
                                <div class="question-item border-bottom" data-question-id="<?= $q['id'] ?>">
                                    <div class="question-header p-3 bg-light cursor-pointer" data-bs-toggle="collapse" data-bs-target="#question-<?= $q['id'] ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-primary me-2"><?= $q['order_no'] ?></span>
                                                <div>
                                                    <h6 class="mb-1"><?= esc($q['question_title'] ?? $q['question_text']) ?></h6>
                                                    <small class="text-muted">
                                                        <span class="badge bg-info"><?= ucfirst($q['question_type']) ?></span>
                                                        <?php if ($q['is_required']): ?>
                                                            <span class="badge bg-warning">Required</span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($q['condition_json'])): ?>
                                                            <span class="badge bg-secondary">Conditional</span>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="question-actions">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-question" data-question-id="<?= $q['id'] ?>">
                                                    <i class="fas fa-edit"></i> edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-question" data-question-id="<?= $q['id'] ?>">
                                                    <i class="fas fa-trash"></i> delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="collapse" id="question-<?= $q['id'] ?>">
                                        <div class="question-details p-3">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h6>Question Text:</h6>
                                                    <p class="text-muted"><?= esc($q['question_text']) ?></p>
                                                    
                                                    <?php if (!empty($q['options'])): ?>
                                                        <h6>Options:</h6>
                                                        <ul class="list-unstyled">
                                                            <?php foreach ($q['options'] as $opt): ?>
                                                                <li><i class="fas fa-circle fa-xs me-2"></i><?= esc($opt['option_text']) ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($q['condition_json'])): ?>
                                                        <h6>Conditional Logic:</h6>
                                                        <p class="text-info">
                                                            <i class="fas fa-arrow-right me-1"></i>
                                                            Show when previous question matches the condition
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="question-preview border rounded p-2 bg-light">
                                                        <small class="text-muted d-block mb-2">Preview:</small>
                                                        <?= generateQuestionPreview($q) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Question Types Reference -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-question-circle me-2"></i>Question Types</h6>
                </div>
                <div class="card-body p-0">
                    <div class="question-types-grid">
                        <div class="row g-2 p-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary btn-sm w-100 question-type-btn" data-type="text">
                                    Single Line Text
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary btn-sm w-100 question-type-btn" data-type="email">
                                    Email
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-success btn-sm w-100 question-type-btn" data-type="dropdown">
                                    Dropdown List
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-success btn-sm w-100 question-type-btn" data-type="date">
                                    Date
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-warning btn-sm w-100 question-type-btn" data-type="checkbox">
                                    Checkboxes
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-warning btn-sm w-100 question-type-btn" data-type="number">
                                    Number
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-info btn-sm w-100 question-type-btn" data-type="radio">
                                    Radio Buttons
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-info btn-sm w-100 question-type-btn" data-type="phone">
                                    Phone
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 question-type-btn" data-type="scale">
                                    Scale
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-secondary btn-sm w-100 question-type-btn" data-type="user_field">
                                    User Field
                                </button>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-dark btn-sm w-100 question-type-btn" data-type="matrix">
                                    Grid/Matrix
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit Question -->
    <div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editQuestionModalLabel">Edit Pertanyaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editQuestionForm" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="question_id" id="edit_question_id">
                        
                        <!-- Question Title -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Question Title <span class="text-danger">*</span></label>
                            <input type="text" name="question_title" id="edit_question_title" class="form-control" required>
                        </div>

                        <!-- Question Text -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Question Text <span class="text-danger">*</span></label>
                            <textarea name="question_text" id="edit_question_text" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- Question Type -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Question Type <span class="text-danger">*</span></label>
                            <select name="question_type" id="edit_question_type" class="form-select" required>
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

                        <!-- Options for Selection Types -->
                        <div class="mb-3" id="edit_options_wrapper" style="display: none;">
                            <label class="form-label fw-bold">Answer Options</label>
                            <div id="edit_option_list"></div>
                            <button type="button" id="add_edit_option" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>

                        <!-- Scale Settings -->
                        <div class="mb-3" id="edit_scale_wrapper" style="display: none;">
                            <label class="form-label fw-bold">Scale Settings</label>
                            <div class="row">
                                <div class="col-md-4"><input type="number" name="scale_min" id="edit_scale_min" class="form-control" placeholder="Min"></div>
                                <div class="col-md-4"><input type="number" name="scale_max" id="edit_scale_max" class="form-control" placeholder="Max"></div>
                                <div class="col-md-4"><input type="number" name="scale_step" id="edit_scale_step" class="form-control" placeholder="Step"></div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><input type="text" name="scale_min_label" id="edit_scale_min_label" class="form-control" placeholder="Min Label"></div>
                                <div class="col-md-6"><input type="text" name="scale_max_label" id="edit_scale_max_label" class="form-control" placeholder="Max Label"></div>
                            </div>
                        </div>

                        <!-- File Settings -->
                        <div class="mb-3" id="edit_file_wrapper" style="display: none;">
                            <label class="form-label fw-bold">File Settings</label>
                            <div class="row">
                                <div class="col-md-6"><input type="text" name="allowed_types" id="edit_allowed_types" class="form-control" placeholder="Allowed Types (e.g., pdf,doc)"></div>
                                <div class="col-md-6"><input type="number" name="max_file_size" id="edit_max_file_size" class="form-control" placeholder="Max Size (MB)"></div>
                            </div>
                        </div>

                        <!-- Matrix Settings -->
                        <div class="mb-3" id="edit_matrix_wrapper" style="display: none;">
                            <label class="form-label fw-bold">Matrix Settings</label>
                            <div class="row">
                                <div class="col-md-6"><input type="text" name="matrix_rows" id="edit_matrix_rows" class="form-control" placeholder="Rows (e.g., Baris 1, Baris 2)"></div>
                                <div class="col-md-6"><input type="text" name="matrix_columns" id="edit_matrix_columns" class="form-control" placeholder="Columns (e.g., Kolom 1, Kolom 2)"></div>
                                <div class="col-md-6"><input type="text" name="matrix_options" id="edit_matrix_options" class="form-control" placeholder="Options (e.g., Sangat Baik,Baik,Kurang Baik)"></div>
                            </div>
                        </div>

                        <!-- Validation Settings -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Validation</label>
                                <div class="form-check">
                                    <input type="checkbox" name="is_required" id="edit_is_required" class="form-check-input">
                                    <label class="form-check-label" for="edit_is_required">Required</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Order</label>
                                <input type="number" name="order_no" id="edit_order_no" class="form-control" min="1" required>
                            </div>
                        </div>

                        <!-- Conditional Logic -->
                        <div class="mb-3" id="edit_conditional_wrapper" style="display: none;">
                            <label class="form-label fw-bold">Conditional Logic</label>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label class="form-label">Show if:</label>
                                    <select name="parent_question_id" id="edit_parent_question_id" class="form-select" onchange="loadEditConditionOptions()">
                                        <option value="">-- Select Question --</option>
                                        <?php foreach ($all_questions as $parent): ?>
                                            <option value="<?= $parent['id'] ?>"><?= esc($parent['question_title'] ?? $parent['question_text']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Condition:</label>
                                    <select name="condition_operator" id="edit_condition_operator" class="form-select">
                                        <option value="is">Is</option>
                                        <option value="is not">Is Not</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Value:</label>
                                    <select name="condition_value" id="edit_condition_value_select" class="form-select" disabled>
                                        <option value="">-- Pilih Nilai --</option>
                                    </select>
                                    <input type="text" name="condition_value" id="edit_condition_value_text" class="form-control" style="display:none;" disabled>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="editQuestionForm" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Form elements
    const toggleFormBtn = document.getElementById("toggleForm");
    const formContainer = document.getElementById("formContainer");
    const cancelFormBtn = document.getElementById("cancelForm");
    const questionForm = document.getElementById("questionForm");
    const questionTypeSelect = document.getElementById("question_type");
    const optionsWrapper = document.getElementById("options_wrapper");
    const scaleWrapper = document.getElementById("scale_wrapper");
    const fileWrapper = document.getElementById("file_wrapper");
    const matrixWrapper = document.getElementById("matrix_wrapper");
    const conditionalWrapper = document.getElementById("conditional_wrapper");
    const enableConditionalCheck = document.getElementById("enable_conditional");

    // Toggle form visibility
    toggleFormBtn.addEventListener("click", function() {
        const isVisible = formContainer.style.display !== "none";
        formContainer.style.display = isVisible ? "none" : "block";
        toggleFormBtn.innerHTML = isVisible 
            ? '<i class="fas fa-plus"></i> Add' 
            : '<i class="fas fa-minus"></i> Hide';
    });

    cancelFormBtn.addEventListener("click", function() {
        formContainer.style.display = "none";
        toggleFormBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
        questionForm.reset();
    });

    // Question type change handler
    questionTypeSelect.addEventListener("change", function() {
        const type = this.value;
        
        // Hide all specific wrappers
        optionsWrapper.style.display = "none";
        scaleWrapper.style.display = "none";
        fileWrapper.style.display = "none";
        matrixWrapper.style.display = "none";
        
        // Show relevant wrapper based on type
        if (["radio", "checkbox", "dropdown"].includes(type)) {
            optionsWrapper.style.display = "block";
        } else if (type === "scale") {
            scaleWrapper.style.display = "block";
        } else if (type === "file") {
            fileWrapper.style.display = "block";
        } else if (type === "matrix") {
            matrixWrapper.style.display = "block";
        }
    });

    // Conditional logic toggle
    enableConditionalCheck.addEventListener("change", function() {
        conditionalWrapper.style.display = this.checked ? "block" : "none";
    });

    // Add option functionality
    document.getElementById("add_option").addEventListener("click", function() {
        const optionList = document.getElementById("option_list");
        const optionHtml = `
            <div class="input-group mb-2">
                <input type="text" name="options[]" class="form-control" placeholder="Option text...">
                <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)">
                <select name="next_question_ids[]" class="form-control">
                    <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                    <?php foreach ($all_questions as $q): ?>
                        <option value="<?= $q['id'] ?>"><?= esc($q['question_title'] ?? $q['question_text']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-danger remove-option">&times;</button>
            </div>
        `;
        optionList.insertAdjacentHTML("beforeend", optionHtml);
    });

    // Remove option functionality
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("remove-option")) {
            e.target.closest(".input-group").remove();
        }
    });

    // Question type quick selection
    document.querySelectorAll(".question-type-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const type = this.dataset.type;
            questionTypeSelect.value = type;
            questionTypeSelect.dispatchEvent(new Event('change'));
            
            // Show form if hidden
            if (formContainer.style.display === "none") {
                toggleFormBtn.click();
            }
            
            // Scroll to form
            formContainer.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Expand/Collapse all questions
    document.getElementById("expandAll")?.addEventListener("click", function() {
        document.querySelectorAll(".question-item .collapse").forEach(collapse => {
            new bootstrap.Collapse(collapse, { show: true });
        });
    });

    document.getElementById("collapseAll")?.addEventListener("click", function() {
        document.querySelectorAll(".question-item .collapse.show").forEach(collapse => {
            new bootstrap.Collapse(collapse, { hide: true });
        });
    });

    // Question actions
    document.addEventListener("click", function(e) {
       if (e.target.closest(".delete-question")) {
            const questionId = e.target.closest(".delete-question").dataset.questionId;
            if (confirm("Are you sure you want to delete this question?")) {
                console.log("Deleting question ID:", questionId); // Debug
                const csrfToken = document.querySelector('[name="csrf_token"]')?.value;
                if (!csrfToken) console.warn("CSRF token not found!");
                fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/delete/") ?>${questionId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Content-Type': 'application/json' // Tambahkan ini
                    },
                    body: JSON.stringify({ question_id: questionId })
                })
                .then(response => {
                    console.log("Response status:", response.status); // Debug
                    return response.json();
                })
                .then(data => {
                    console.log("Response data:", data); // Debug
                    if (data.status === 'success') {
                        e.target.closest('.question-item').remove();
                        showNotification('Question deleted successfully', 'success');
                    } else {
                        showNotification(data.message || 'Failed to delete question', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Check console for details.', 'error');
                });
            }
        }
        
        if (e.target.closest(".duplicate-question")) {
            const questionId = e.target.closest(".duplicate-question").dataset.questionId;
            // Ajax duplicate request
            fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/duplicate/") ?>${questionId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('[name="csrf_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload(); // Simple reload for now
                } else {
                    showNotification('Failed to duplicate question', 'error');
                }
            });
        }
    });

    // Utility function for notifications
    function showNotification(message, type) {
        // You can implement your preferred notification system here
        alert(message);
    }

    // Form submission with Ajax
    questionForm.addEventListener("submit", function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);

        for (let [name, value] of formData.entries()) {
        console.log(name, value);
        }

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification('Question added successfully', 'success');
                location.reload(); // Reload to show new question
            } else {
                showNotification(data.message || 'Failed to add question', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    });
    // Handler untuk memuat opsi kondisional saat parent question berubah
    // Handler untuk memuat opsi kondisional saat parent question berubah
document.getElementById('parent_question_id').addEventListener('change', function() {
    const parentId = this.value;
    const selectElement = document.getElementById('condition_value_select');
    const textElement = document.getElementById('condition_value_text');
    const conditionOperator = document.getElementById('condition_operator');

    // Reset fields and disable by default
    selectElement.innerHTML = '<option value="">-- Select Value --</option>';
    selectElement.disabled = true;
    textElement.disabled = true;

    if (parentId) {
        fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/get-options/") ?>${parentId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.options && data.options.length > 0) {
                // Show select input and hide text input
                selectElement.style.display = 'block';
                textElement.style.display = 'none';

                data.options.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt.option_value;
                    option.textContent = opt.option_text;
                    selectElement.appendChild(option);
                });
                selectElement.disabled = false;
                
            } else {
                // Show text input and hide select input
                selectElement.style.display = 'none';
                textElement.style.display = 'block';
                textElement.disabled = false;

                // Set value from server if any
                if (data.question && data.question.condition_value) {
                    textElement.value = data.question.condition_value;
                }
            }
            // Reset operator options
            conditionOperator.querySelectorAll('option').forEach(opt => opt.disabled = false);
        })
        .catch(error => {
            console.error('Error loading options:', error);
            selectElement.disabled = true;
            textElement.disabled = true;
        });
    }
});

// Jika old value ada, trigger load saat halaman dimuat
if (document.getElementById('parent_question_id').value) {
    document.getElementById('parent_question_id').dispatchEvent(new Event('change'));
}
});

// Edit question functionality
document.addEventListener("DOMContentLoaded", function() {
    // Edit Question Handler
    document.addEventListener("click", function(e) {
        if (e.target.closest(".edit-question")) {
            const questionId = e.target.closest(".edit-question").dataset.questionId;
            console.log("Edit clicked:", questionId);
            fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/get/") ?>${questionId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                }
            })
            .then(response => {
                console.log("Fetch response status:", response.status);
                return response.json();
            })
            .then(data => {
                console.log("Fetch data:", data);
                if (data.status === 'success') {
                    const q = data.question;
                    document.getElementById('edit_question_id').value = q.id;
                    document.getElementById('edit_question_title').value = q.question_title || '';
                    document.getElementById('edit_question_text').value = q.question_text || '';
                    document.getElementById('edit_question_type').value = q.question_type || '';
                    document.getElementById('edit_is_required').checked = q.is_required === 1;
                    document.getElementById('edit_order_no').value = q.order_no || 1;

                    // Hide all special wrappers
                    const wrappers = ['edit_options_wrapper', 'edit_scale_wrapper', 'edit_file_wrapper', 'edit_matrix_wrapper', 'edit_conditional_wrapper'];
                    wrappers.forEach(w => document.getElementById(w).style.display = 'none');

                    // Show relevant wrapper based on type
                    if (['radio', 'checkbox', 'dropdown'].includes(q.question_type)) {
                        const editOptionList = document.getElementById('edit_option_list');
                        editOptionList.innerHTML = '';
                        (q.options || []).forEach(opt => {
                            const optionHtml = `
                                <div class="input-group mb-2">
                                    <input type="text" name="options[]" value="${opt.option_text || ''}" class="form-control">
                                    <input type="text" name="option_values[]" value="${opt.option_value || ''}" class="form-control">
                                    <select name="next_question_ids[]" class="form-control">
                                        <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                                        <?php foreach ($all_questions as $q): ?>
                                            <option value="<?= $q['id'] ?>" ${opt.next_question_id == <?= $q['id'] ?> ? 'selected' : ''}>
                                                <?= esc($q['question_title'] ?? $q['question_text']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-danger remove-option">&times;</button>
                                </div>
                            `;
                            editOptionList.insertAdjacentHTML('beforeend', optionHtml);
                        });
                        document.getElementById('edit_options_wrapper').style.display = 'block';
                    } else if (q.question_type === 'scale') {
                        document.getElementById('edit_scale_wrapper').style.display = 'block';
                        document.getElementById('edit_scale_min').value = q.scale_min || 1;
                        document.getElementById('edit_scale_max').value = q.scale_max || 5;
                        document.getElementById('edit_scale_step').value = q.scale_step || 1;
                        document.getElementById('edit_scale_min_label').value = q.scale_min_label || '';
                        document.getElementById('edit_scale_max_label').value = q.scale_max_label || '';
                    } else if (q.question_type === 'file') {
                        document.getElementById('edit_file_wrapper').style.display = 'block';
                        document.getElementById('edit_allowed_types').value = q.allowed_types || 'pdf,doc,docx';
                        document.getElementById('edit_max_file_size').value = q.max_file_size || 5;
                    } else if (q.question_type === 'matrix') {
                        document.getElementById('edit_matrix_wrapper').style.display = 'block';
                        document.getElementById('edit_matrix_rows').value = (q.matrix_rows ? q.matrix_rows.join(', ') : '');
                        document.getElementById('edit_matrix_columns').value = (q.matrix_columns ? q.matrix_columns.join(', ') : '');
                        document.getElementById('edit_matrix_options').value = (q.matrix_options ? q.matrix_options.join(', ') : '');
                    }

                    // Handle conditional logic
                    const conditionalWrapper = document.getElementById('edit_conditional_wrapper');
                    const conditionValueSelect = document.getElementById('edit_condition_value_select');
                    const conditionValueText = document.getElementById('edit_condition_value_text');
                    if (q.condition_json) {
                        const condition = JSON.parse(q.condition_json)[0] || {};
                        conditionalWrapper.style.display = 'block';
                        document.getElementById('edit_parent_question_id').value = condition.field ? condition.field.replace('question_', '') : '';
                        document.getElementById('edit_condition_operator').value = condition.operator || 'is';
                        conditionValueText.value = condition.value || '';
                        loadEditConditionOptions(); // Load options and sync
                    } else {
                        conditionalWrapper.style.display = 'none';
                        conditionValueSelect.disabled = true;
                        conditionValueText.disabled = true;
                    }

                    // Trigger type change to show wrappers
                    const typeSelect = document.getElementById('edit_question_type');
                    typeSelect.dispatchEvent(new Event('change'));

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editQuestionModal'));
                    modal.show();
                    document.getElementById('editQuestionModal').scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    showNotification('Failed to load question', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            });
        }
    });

    // Type change handler for edit form
    document.getElementById('edit_question_type').addEventListener("change", function() {
        const type = this.value;
        const wrappers = {
            'edit_options_wrapper': ['radio', 'checkbox', 'dropdown'],
            'edit_scale_wrapper': ['scale'],
            'edit_file_wrapper': ['file'],
            'edit_matrix_wrapper': ['matrix']
        };
        for (let wrapper in wrappers) {
            document.getElementById(wrapper).style.display = wrappers[wrapper].includes(type) ? 'block' : 'none';
        }
        document.getElementById('edit_conditional_wrapper').style.display = 'none'; // Hide unless set manually
    });

    // Submit edit form
   // Submit edit form
           // Submit edit form
   // Submit edit form
    document.getElementById('editQuestionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('editQuestionForm');
        const questionIdElement = document.getElementById('edit_question_id');
        const isRequiredElement = document.getElementById('edit_is_required');
        const csrfTokenElement = document.querySelector('[name="csrf_test_name"]');

        if (!form || !questionIdElement || !isRequiredElement || !csrfTokenElement) {
            console.error("Missing elements:", {
                form: !!form,
                questionId: !!questionIdElement,
                isRequired: !!isRequiredElement,
                csrfToken: !!csrfTokenElement
            });
            showNotification('One or more required elements are missing', 'error');
            return;
        }

        const formData = new FormData(form);
        const questionId = questionIdElement.value;
        console.log("Submitting form data:", Object.fromEntries(formData));

        formData.set('is_required', isRequiredElement.checked ? 1 : 0);

        fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/") ?>${questionId}/update`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfTokenElement.value
            }
        })
        .then(response => {
            console.log("Response status:", response.status);
            return response.json();
        })
        .then(data => {
            console.log("Response data:", data);
            if (data.status === 'success') {
                showNotification('Question updated successfully', 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('editQuestionModal'));
                modal.hide();
                location.reload();
            } else {
                showNotification(data.message || 'Failed to update question', 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showNotification('An error occurred during fetch', 'error');
        });
    });

    // Add option in edit modal
    document.getElementById('add_edit_option').addEventListener('click', function() {
        const editOptionList = document.getElementById('edit_option_list');
        const optionHtml = `
            <div class="input-group mb-2">
                <input type="text" name="options[]" class="form-control" placeholder="Option text...">
                <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)">
                <select name="next_question_ids[]" class="form-control">
                    <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                    <?php foreach ($all_questions as $q): ?>
                        <option value="<?= $q['id'] ?>"><?= esc($q['question_title'] ?? $q['question_text']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-danger remove-option">&times;</button>
            </div>
        `;
        editOptionList.insertAdjacentHTML('beforeend', optionHtml);
    });

    // Remove option in edit modal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option')) {
            e.target.closest('.input-group').remove();
        }
    });

    // Load conditional options for edit
    window.loadEditConditionOptions = function() {
        const parentId = document.getElementById('edit_parent_question_id').value;
        const conditionValueSelect = document.getElementById('edit_condition_value_select');
        const conditionValueText = document.getElementById('edit_condition_value_text');

        conditionValueSelect.innerHTML = '<option value="">-- Pilih Nilai --</option>';
        conditionValueSelect.disabled = true;
        conditionValueText.disabled = true;
        conditionValueSelect.style.display = 'block';
        conditionValueText.style.display = 'none';

        if (parentId) {
            fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/get-options/") ?>${parentId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.options && data.options.length > 0) {
                    data.options.forEach(opt => {
                        const option = document.createElement('option');
                        option.value = opt.option_value || opt.option_text;
                        option.textContent = opt.option_text;
                        conditionValueSelect.appendChild(option);
                    });
                    conditionValueSelect.disabled = false;
                    const currentValue = document.getElementById('edit_condition_value_text').value;
                    if (currentValue) conditionValueSelect.value = currentValue;
                } else {
                    conditionValueText.style.display = 'block';
                    conditionValueText.disabled = false;
                    conditionValueSelect.style.display = 'none';
                    conditionValueText.value = document.getElementById('edit_condition_value_text').value || '';
                }
            })
            .catch(error => console.error('Error loading options:', error));
        }
    };

    // Trigger load if parent is set
    document.getElementById('edit_parent_question_id').addEventListener('change', function() {
        loadEditConditionOptions();
    });

    // Utility function for notifications
    function showNotification(message, type) {
        alert(message); // Replace with your notification system
    }
});
</script>

<style>
.cursor-pointer { cursor: pointer; }
.question-item:hover .question-header { background-color: #f8f9fa !important; }
.question-types-grid .btn { font-size: 0.75rem; padding: 0.25rem 0.5rem; }
.sticky-top { z-index: 1020; }
.question-actions .btn { margin-left: 2px; }

/* Custom animations */
.question-item {
    transition: all 0.3s ease;
}

.question-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Form styling */
.card-body {
    position: relative;
}

.form-label.fw-bold {
    color: #495057;
    font-size: 0.9rem;
}

/* Badge styling */
.badge {
    font-size: 0.7rem;
}

/* Question preview styling */
.question-preview {
    background: #f8f9fa !important;
    min-height: 100px;
}
</style>

<?php
// Helper function for question preview
function generateQuestionPreview($q) {
    $type = $q['question_type'];
    $text = esc($q['question_text']);
    
    switch ($type) {
        
        case 'matrix':
            $rows = $q['matrix_rows'] ?? [];
            $columns = $q['matrix_columns'] ?? [];
            $options = $q['matrix_options'] ?? [];
            $html = "<label class='form-label small'>{$text}</label><table class='table table-sm border'>";
            $html .= "<thead><tr><th></th>";
            foreach ($columns as $col) {
                $html .= "<th>" . esc($col) . "</th>";
            }
            $html .= "</tr></thead><tbody>";
            foreach ($rows as $row) {
                $html .= "<tr><td>" . esc($row) . "</td>";
                foreach ($columns as $col) {
                    $html .= "<td>";
                    foreach ($options as $index => $opt) {
                        $html .= "<label><input type='radio' name='matrix_{$row}_{$col}' disabled> " . esc($opt) . "</label>";
                    }
                    $html .= "</td>";
                }
                $html .= "</tr>";
            }
            $html .= "</tbody></table>";
            return $html;
        
        // Case lain (scale, file, dll.) sesuai kebutuhan
       case 'scale':
            $min = $q['scale_min'] ?? 1;
            $max = $q['scale_max'] ?? 5;
            $step = $q['scale_step'] ?? 1;
            // Pastikan step tidak nol
            $step = max(1, (int)$step); // Set minimum 1 jika nol
            $maxItems = 100;
            $items = max(1, min(floor(($max - $min) / $step) + 1, $maxItems));
            $html = "<label class='form-label small'>{$text}</label><div class='d-flex justify-content-between small'>";
            for ($i = $min, $count = 0; $i <= $max && $count < $items; $i += $step, $count++) {
                $html .= "<label><input type='radio' disabled> {$i}</label>";
            }
            $html .= "</div>";
            $html .= "<small>" . esc($q['scale_min_label'] ?? 'Min') . " to " . esc($q['scale_max_label'] ?? 'Max') . "</small>";
            return $html;
        
        case 'file':
            return "<label class='form-label small'>{$text}</label><input type='file' class='form-control form-control-sm' disabled><small>Allowed: " . esc($q['allowed_types'] ?? 'pdf,doc') . ", Max: " . ($q['max_file_size'] ?? 5) . "MB</small>";
        
        case 'radio':
        case 'checkbox':
            // Baris ini sudah benar, karena sudah diperbaiki di respons sebelumnya
            $options = $q['options'] ?? []; 
            $inputType = ($type === 'radio') ? 'radio' : 'checkbox';
            $html = "<div><label class='form-label small'>{$text}</label></div>";
            foreach (array_slice($options, 0, 2) as $i => $option) {
                // Perhatikan: akses 'option_text' di sini
                $html .= "<div class='form-check'><input class='form-check-input form-check-input-sm' type='{$inputType}' disabled><label class='form-check-label small'>" . esc($option['option_text']) . "</label></div>";
            }
            if (count($options) > 2) $html .= "<small class='text-muted'>... and " . (count($options) - 2) . " more</small>";
            return $html;
            
        case 'dropdown':
            // Ganti baris ini dari json_decode
            $options = $q['options'] ?? [];
            $html = "<label class='form-label small'>{$text}</label><select class='form-select form-select-sm' disabled>";
            $html .= "<option>-- Select --</option>";
            foreach (array_slice($options, 0, 3) as $option) {
                // Perhatikan: akses 'option_text' di sini
                $html .= "<option>" . esc($option['option_text']) . "</option>";
            }
            $html .= "</select>";
            return $html;
            
        default:
            return "<label class='form-label small'>{$text}</label><input type='text' class='form-control form-control-sm' disabled>";
    }
}
?>

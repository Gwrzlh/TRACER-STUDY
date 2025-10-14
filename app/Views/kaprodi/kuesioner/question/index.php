<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="/css/questioner/question/index.css">

<!-- Main Container -->
<div class="question-manager-container">
    <!-- Header (optimasi: gap 8px, logo size 48px) -->
    <div class="page-header" style="padding: 16px; border-radius: 8px; margin-bottom: 16px;">
        <?= $this->include('kaprodi/kuesioner/breadcupp') ?>
        <div class="header-content" style="gap: 12px;">
            <!-- Breadcrumb (baru, rapatkan) -->
            <img src="/images/logo.png" alt="Tracer Study" class="header-logo" style="height: 48px;">
            <div class="header-text">
                <h2 class="page-title" style="margin: 0; font-size: 24px;">Kelola Pertanyaan - <?= esc($section['section_title']) ?></h2>
            </div>
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="content-grid">
        <!-- Main Content Area -->
        <div class="content-main">
            <!-- Add Question Card -->
            <div class="card add-question-card">
                <div class="card-header card-header-primary">
                    <h3 class="card-title">Tambah Pertanyaan Baru</h3>
                    <button type="button" id="toggleForm" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
                <div class="card-body form-container hidden" id="formContainer">
                    <form id="questionForm" action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/store") ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- Question Text -->
                        <div class="form-group">
                            <label for="question_text" class="form-label">Question Text <span class="required-mark">*</span></label>
                            <textarea id="question_text" name="question_text" class="form-control" rows="3" placeholder="Masukkan teks pertanyaan lengkap..." required><?= old('question_text') ?></textarea>
                            <p class="form-helper">Teks pertanyaan yang akan ditampilkan kepada responden</p>
                        </div>

                        <!-- Question Type -->
                        <div class="form-group">
                            <label for="question_type" class="form-label">Question Type <span class="required-mark">*</span></label>
                            <select id="question_type" name="question_type" class="form-control" required>
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
                        <div id="options_wrapper" class="form-group options-wrapper hidden">
                            <label class="form-label">Answer Options</label>
                            <div id="option_list" class="option-list">
                                <div class="option-item">
                                    <input type="text" name="options[]" class="form-control" placeholder="Option text...">
                                    <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)">
                                    <select name="next_question_ids[]" class="form-control">
                                        <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                                        <?php foreach ($all_questions as $q): ?>
                                            <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-danger btn-icon remove-option"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button type="button" id="add_option" class="btn btn-secondary btn-sm">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>

                        <!-- Scale Settings -->
                        <div id="scale_wrapper" class="form-group scale-wrapper hidden">
                            <label class="form-label">Scale Settings</label>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label-sm">Min Value</label>
                                    <input type="number" name="scale_min" class="form-control" value="1">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Max Value</label>
                                    <input type="number" name="scale_max" class="form-control" value="5">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Step</label>
                                    <input type="number" name="scale_step" class="form-control" value="1" min="1">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label-sm">Min Label</label>
                                    <input type="text" name="scale_min_label" class="form-control" placeholder="e.g., Sangat Tidak Setuju">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Max Label</label>
                                    <input type="text" name="scale_max_label" class="form-control" placeholder="e.g., Sangat Setuju">
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Settings -->
                        <div id="file_wrapper" class="form-group file-wrapper hidden">
                            <label class="form-label">File Upload Settings</label>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label-sm">Allowed File Types</label>
                                    <input type="text" name="allowed_types" class="form-control" placeholder="pdf,doc,docx,jpg,png" value="pdf,doc,docx">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Max File Size (MB)</label>
                                    <input type="number" name="max_file_size" class="form-control" value="5" min="1">
                                </div>
                            </div>
                        </div>

                        <!-- Matrix Settings -->
                        <div id="matrix_wrapper" class="form-group matrix-wrapper hidden">
                            <label class="form-label">Matrix Settings</label>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label-sm">Rows</label>
                                    <input type="text" name="matrix_rows" class="form-control" placeholder="e.g., Baris 1, Baris 2, Baris 3">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Columns</label>
                                    <input type="text" name="matrix_columns" class="form-control" placeholder="e.g., Kolom 1, Kolom 2, Kolom 3">
                                </div>
                            </div>
                        </div>

                        <!-- User Field Selection -->
                        <div id="user_field_wrapper" class="form-group user-field-wrapper hidden">
                            <label for="user_field_name" class="form-label">User Profile Field <span class="required-mark">*</span></label>
                            <select id="user_field_name" name="user_field_name" class="form-control">
                                <option value="">-- Pilih Field Profil --</option>
                                <?php
                                $fieldFriendlyNames = [
                                    'nama_lengkap' => 'Nama Lengkap',
                                    'nim' => 'NIM',
                                    'id_jurusan' => 'ID Jurusan',
                                    'id_prodi' => 'ID Prodi',
                                    'angkatan' => 'Angkatan',
                                    'tahun_kelulusan' => 'Tahun Kelulusan',
                                    'ipk' => 'IPK',
                                    'alamat' => 'Alamat',
                                    'alamat2' => 'Alamat 2',
                                    'kodepos' => 'Kode Pos',
                                    'jenisKelamin' => 'Jenis Kelamin',
                                    'notlp' => 'No. Telepon',
                                    'id_provinsi' => 'ID Provinsi',
                                    'id_cities' => 'ID Kota',
                                    'email' => 'Email',
                                ];
                                foreach ($fieldFriendlyNames as $field => $name): ?>
                                    <option value="<?= esc($field) ?>"><?= esc($name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Validation Settings -->
                        <div class="form-group">
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Validation</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_required" id="is_required" value="1" class="form-check-input" <?= old('is_required') ? 'checked' : '' ?>>
                                        <label for="is_required" class="form-check-label">Required</label>
                                    </div>
                                </div>
                                <div class="form-col">
                                    <label for="order_no" class="form-label">Order</label>
                                    <input type="number" id="order_no" name="order_no" class="form-control" value="<?= old('order_no', $next_order) ?>" min="1" required>
                                    <p class="form-helper">Position of this question</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
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
                    <h3 class="card-title">Questions List (<?= count($questions) ?>)</h3>
                </div>
                <div class="card-body questions-body">
                    <?php if (empty($questions)): ?>
                        <div class="empty-state">
                            <i class="fas fa-question-circle"></i>
                            <h5>No Questions Yet</h5>
                            <p>Start by adding your first question using the form above.</p>
                        </div>
                    <?php else: ?>
                        <div id="questionsList" class="questions-list">
                            <?php foreach ($questions as $index => $q): ?>
                                <div class="question-item" data-question-id="<?= $q['id'] ?>">
                                    <div class="question-header">
                                        <span class="question-order"><?= $q['order_no'] ?></span>
                                        <div class="question-info">
                                            <h6 class="question-text"><?= esc($q['question_text']) ?></h6>
                                            <div class="question-badges">
                                                <span class="badge badge-type"><?= ucfirst($q['question_type']) ?></span>
                                                <?php if ($q['is_required']): ?>
                                                    <span class="badge badge-required">Required</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="question-actions">
                                            <button type="button" class="btn btn-blue btn-sm edit-question" data-question-id="<?= $q['id'] ?>">
                                                <i class="fas fa-edit"></i> <span class="btn-text">Edit</span>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm delete-question" data-question-id="<?= $q['id'] ?>">
                                                <i class="fas fa-trash"></i> <span class="btn-text">Delete</span>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm duplicate-question" data-question-id="<?= $q['id'] ?>">
                                                <i class="fas fa-copy"></i> <span class="btn-text">Duplicate</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="question-details show">
                                        <div class="details-content">
                                            <div class="details-text">
                                                <h6 class="detail-label">Question Text:</h6>
                                                <p class="detail-value"><?= esc($q['question_text']) ?></p>
                                                <?php if (!empty($q['options'])): ?>
                                                    <h6 class="detail-label">Options:</h6>
                                                    <ul class="detail-list">
                                                        <?php foreach ($q['options'] as $opt): ?>
                                                            <li><?= esc($opt['option_text']) ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                            <div class="question-preview">
                                                <small class="preview-label">Preview:</small>
                                                <div class="preview-content show">
                                                    <?= generateQuestionPreview($q) ?>
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

        <!-- Question Types Sidebar -->
        <div class="content-sidebar">
            <div class="card sidebar-card">
                <div class="card-header card-header-secondary">
                    <h3 class="card-title"><i class="fas fa-question-circle"></i> Question Types</h3>
                </div>
                <div class="card-body sidebar-body">
                    <div class="question-types">
                        <?php
                        $typeGroups = [
                            'Text Input' => [
                                ['type' => 'text', 'label' => 'Single Line', 'color' => 'orange'],
                                ['type' => 'textarea', 'label' => 'Multi Line', 'color' => 'orange'],
                                ['type' => 'email', 'label' => 'Email', 'color' => 'orange'],
                                ['type' => 'number', 'label' => 'Number', 'color' => 'orange'],
                                ['type' => 'phone', 'label' => 'Phone', 'color' => 'orange'],
                            ],
                            'Selection' => [
                                ['type' => 'radio', 'label' => 'Radio', 'color' => 'blue'],
                                ['type' => 'checkbox', 'label' => 'Checkbox', 'color' => 'blue'],
                                ['type' => 'dropdown', 'label' => 'Dropdown', 'color' => 'blue'],
                            ],
                            'Date & Time' => [
                                ['type' => 'date', 'label' => 'Date', 'color' => 'yellow'],
                                ['type' => 'time', 'label' => 'Time', 'color' => 'yellow'],
                                ['type' => 'datetime', 'label' => 'Date Time', 'color' => 'yellow'],
                            ],
                            'Advanced' => [
                                ['type' => 'scale', 'label' => 'Scale', 'color' => 'neutral'],
                                ['type' => 'matrix', 'label' => 'Matrix', 'color' => 'neutral'],
                                ['type' => 'file', 'label' => 'File', 'color' => 'neutral'],
                                ['type' => 'user_field', 'label' => 'User Field', 'color' => 'neutral'],
                            ],
                        ];
                        foreach ($typeGroups as $group => $types): ?>
                            <div class="type-group">
                                <h4 class="type-group-title"><?= $group ?></h4>
                                <div class="type-grid">
                                    <?php foreach ($types as $type): ?>
                                        <button type="button" class="btn btn-type btn-type-<?= $type['color'] ?>" data-type="<?= $type['type'] ?>">
                                            <i class="fas fa-circle"></i> <?= $type['label'] ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Question Modal -->
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

                        <!-- Question Text -->
                        <div class="form-group">
                            <label for="edit_question_text" class="form-label">Question Text <span class="required-mark">*</span></label>
                            <textarea id="edit_question_text" name="question_text" class="form-control" rows="3" required></textarea>
                        </div>

                        <!-- Question Type -->
                        <div class="form-group">
                            <label for="edit_question_type" class="form-label">Question Type <span class="required-mark">*</span></label>
                            <select id="edit_question_type" name="question_type" class="form-control" required>
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
                        <div id="edit_options_wrapper" class="form-group options-wrapper hidden">
                            <label class="form-label">Answer Options</label>
                            <div id="edit_option_list" class="option-list"></div>
                            <button type="button" id="add_edit_option" class="btn btn-secondary btn-sm">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>

                        <!-- Scale Settings -->
                        <div id="edit_scale_wrapper" class="form-group scale-wrapper hidden">
                            <label class="form-label">Scale Settings</label>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label-sm">Min Value</label>
                                    <input type="number" name="scale_min" id="edit_scale_min" class="form-control">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Max Value</label>
                                    <input type="number" name="scale_max" id="edit_scale_max" class="form-control">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Step</label>
                                    <input type="number" name="scale_step" id="edit_scale_step" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label-sm">Min Label</label>
                                    <input type="text" name="scale_min_label" id="edit_scale_min_label" class="form-control">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Max Label</label>
                                    <input type="text" name="scale_max_label" id="edit_scale_max_label" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- File Settings -->
                        <div id="edit_file_wrapper" class="form-group file-wrapper hidden">
                            <label class="form-label">File Settings</label>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label-sm">Allowed File Types</label>
                                    <input type="text" name="allowed_types" id="edit_allowed_types" class="form-control">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Max File Size (MB)</label>
                                    <input type="number" name="max_file_size" id="edit_max_file_size" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Matrix Settings -->
                        <div id="edit_matrix_wrapper" class="form-group matrix-wrapper hidden">
                            <label class="form-label">Matrix Settings</label>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label-sm">Rows</label>
                                    <input type="text" name="matrix_rows" id="edit_matrix_rows" class="form-control">
                                </div>
                                <div class="form-col">
                                    <label class="form-label-sm">Columns</label>
                                    <input type="text" name="matrix_columns" id="edit_matrix_columns" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- User Field Selection -->
                        <div id="edit_user_field_wrapper" class="form-group user-field-wrapper hidden">
                            <label for="edit_user_field_name" class="form-label">User Profile Field <span class="required-mark">*</span></label>
                            <select id="edit_user_field_name" name="user_field_name" class="form-control">
                                <option value="">-- Pilih Field Profil --</option>
                                <?php foreach ($fieldFriendlyNames as $field => $name): ?>
                                    <option value="<?= esc($field) ?>"><?= esc($name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Validation Settings -->
                        <div class="form-group">
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Validation</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_required" id="edit_is_required" class="form-check-input">
                                        <label for="edit_is_required" class="form-check-label">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="submit" form="editQuestionForm" class="btn btn-brand">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="toast-container"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/questioner/question/index.js"></script>
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
        const userFieldWrapper = document.getElementById("user_field_wrapper");

        // Sticky sidebar functionality
        const sidebar = document.querySelector('.content-sidebar');
        const sidebarCard = document.querySelector('.sidebar-card');

        function handleStickyState() {
            if (window.innerWidth >= 1024) {
                const sidebar = document.querySelector('.content-sidebar');
                const sidebarCard = document.querySelector('.sidebar-card');

                if (sidebar && sidebarCard) {
                    const rect = sidebar.getBoundingClientRect();
                    // Check if sidebar has reached sticky position
                    if (rect.top <= 20) {
                        sidebarCard.classList.add('is-stuck');
                    } else {
                        sidebarCard.classList.remove('is-stuck');
                    }
                }
            }
        }

        // Attach event listeners
        window.addEventListener('scroll', handleStickyState);
        window.addEventListener('resize', handleStickyState);
        // Run on load
        handleStickyState();

        // Toggle form visibility
        toggleFormBtn.addEventListener("click", function() {
            const isVisible = !formContainer.classList.contains("hidden");
            if (isVisible) {
                formContainer.classList.add("hidden");
                toggleFormBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
            } else {
                formContainer.classList.remove("hidden");
                toggleFormBtn.innerHTML = '<i class="fas fa-minus"></i> Hide';
            }
        });

        // Cancel form
        cancelFormBtn.addEventListener("click", function() {
            formContainer.classList.add("hidden");
            toggleFormBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
            questionForm.reset();
            [optionsWrapper, scaleWrapper, fileWrapper, matrixWrapper, userFieldWrapper].forEach(wrapper => {
                wrapper.classList.add("hidden");
            });
        });

        // Question type change handler
        questionTypeSelect.addEventListener("change", function() {
            const type = this.value;
            const wrappers = {
                options_wrapper: ["radio", "checkbox", "dropdown"],
                scale_wrapper: ["scale"],
                file_wrapper: ["file"],
                matrix_wrapper: ["matrix"],
                user_field_wrapper: ["user_field"]
            };

            Object.keys(wrappers).forEach(wrapper => {
                const wrapperEl = document.getElementById(wrapper);
                wrapperEl.classList.add("hidden");
                if (wrappers[wrapper].includes(type)) {
                    wrapperEl.classList.remove("hidden");
                }
            });
        });

        // Add option functionality
        document.getElementById("add_option").addEventListener("click", function() {
            const optionList = document.getElementById("option_list");
            const optionHtml = `
            <div class="option-item">
                <input type="text" name="options[]" class="form-control" placeholder="Option text...">
                <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)">
                <select name="next_question_ids[]" class="form-control">
                    <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                    <?php foreach ($all_questions as $q): ?>
                        <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-danger btn-icon remove-option"><i class="fas fa-times"></i></button>
            </div>`;
            optionList.insertAdjacentHTML("beforeend", optionHtml);
        });

        // Remove option functionality
        document.addEventListener("click", function(e) {
            if (e.target.closest(".remove-option")) {
                e.target.closest(".option-item").remove();
            }
        });

        // Question type quick selection
        document.querySelectorAll(".btn-type").forEach(btn => {
            btn.addEventListener("click", function() {
                document.querySelectorAll(".btn-type").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
                questionTypeSelect.value = this.dataset.type;
                questionTypeSelect.dispatchEvent(new Event("change"));
                if (formContainer.classList.contains("hidden")) {
                    toggleFormBtn.click();
                }
                formContainer.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            });
        });

        // Handle preview animation on collapse show/hide
        document.querySelectorAll(".question-item .collapse").forEach(collapse => {
            collapse.addEventListener('shown.bs.collapse', function() {
                const preview = this.querySelector('.preview-content');
                if (preview) {
                    preview.style.opacity = '1';
                }
            });

            collapse.addEventListener('hidden.bs.collapse', function() {
                const preview = this.querySelector('.preview-content');
                if (preview) {
                    preview.style.opacity = '0';
                }
            });
        });

        // Question actions
        document.addEventListener("click", function(e) {
            if (e.target.closest(".delete-question")) {
                const questionId = e.target.closest(".delete-question").dataset.questionId;
                if (confirm("Are you sure you want to delete this question?")) {
                    showLoadingToast();
                    fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/delete/") ?>${questionId}`, {
                            method: "POST",
                            headers: {
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": document.querySelector('[name="csrf_test_name"]').value,
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                question_id: questionId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                showNotification("Question deleted successfully", "success");
                                document.querySelector(`.question-item[data-question-id="${questionId}"]`).remove();
                            } else {
                                showNotification(data.message || "Failed to delete question", "error");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            showNotification("An error occurred", "error");
                        });
                }
            }

            if (e.target.closest(".duplicate-question")) {
                const questionId = e.target.closest(".duplicate-question").dataset.questionId;
                showLoadingToast();
                fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/duplicate/") ?>${questionId}`, {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": document.querySelector('[name="csrf_test_name"]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            showNotification("Question duplicated successfully", "success");
                            location.reload();
                        } else {
                            showNotification(data.message || "Failed to duplicate question", "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        showNotification("An error occurred", "error");
                    });
            }
        });

        // Form submission
        questionForm.addEventListener("submit", function(e) {
            e.preventDefault();
            showLoadingToast();
            const formData = new FormData(this);
            fetch(this.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.querySelector('[name="csrf_test_name"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        showNotification("Question added successfully", "success");
                        location.reload();
                    } else {
                        showNotification(data.message || "Failed to add question", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showNotification("An error occurred", "error");
                });
        });

        // Edit question functionality
        document.addEventListener("click", function(e) {
            if (e.target.closest(".edit-question")) {
                const questionId = e.target.closest(".edit-question").dataset.questionId;
                showLoadingToast();
                fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/get/") ?>${questionId}`, {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": document.querySelector('[name="csrf_test_name"]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            const q = data.question;
                            document.getElementById("edit_question_id").value = q.id;
                            document.getElementById("edit_question_text").value = q.question_text || "";
                            document.getElementById("edit_question_type").value = q.question_type || "";
                            document.getElementById("edit_is_required").checked = q.is_required == 1;

                            const wrappers = ["edit_options_wrapper", "edit_scale_wrapper", "edit_file_wrapper", "edit_matrix_wrapper", "edit_user_field_wrapper"];
                            wrappers.forEach(w => document.getElementById(w).classList.add("hidden"));

                            if (["radio", "checkbox", "dropdown"].includes(q.question_type)) {
                                const editOptionList = document.getElementById("edit_option_list");
                                editOptionList.innerHTML = "";
                                (q.options || []).forEach(opt => {
                                    const optionHtml = `
                                <div class="option-item">
                                    <input type="text" name="options[]" value="${opt.option_text || ""}" class="form-control">
                                    <input type="text" name="option_values[]" value="${opt.option_value || ""}" class="form-control">
                                    <select name="next_question_ids[]" class="form-control">
                                        <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                                        <?php foreach ($all_questions as $q): ?>
                                            <option value="<?= $q['id'] ?>" ${opt.next_question_id == <?= $q['id'] ?> ? "selected" : ""}>
                                                <?= esc($q['question_text']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-danger btn-icon remove-option"><i class="fas fa-times"></i></button>
                                </div>`;
                                    editOptionList.insertAdjacentHTML("beforeend", optionHtml);
                                });
                                document.getElementById("edit_options_wrapper").classList.remove("hidden");
                            } else if (q.question_type === "scale") {
                                document.getElementById("edit_scale_wrapper").classList.remove("hidden");
                                document.getElementById("edit_scale_min").value = q.scale_min || 1;
                                document.getElementById("edit_scale_max").value = q.scale_max || 5;
                                document.getElementById("edit_scale_step").value = q.scale_step || 1;
                                document.getElementById("edit_scale_min_label").value = q.scale_min_label || "";
                                document.getElementById("edit_scale_max_label").value = q.scale_max_label || "";
                            } else if (q.question_type === "file") {
                                document.getElementById("edit_file_wrapper").classList.remove("hidden");
                                document.getElementById("edit_allowed_types").value = q.allowed_types || "pdf,doc,docx";
                                document.getElementById("edit_max_file_size").value = q.max_file_size || 5;
                            } else if (q.question_type === "matrix") {
                                document.getElementById("edit_matrix_wrapper").classList.remove("hidden");
                                document.getElementById("edit_matrix_rows").value = q.matrix_rows ? q.matrix_rows.join(", ") : "";
                                document.getElementById("edit_matrix_columns").value = q.matrix_columns ? q.matrix_columns.join(", ") : "";
                            } else if (q.question_type === "user_field") {
                                document.getElementById("edit_user_field_wrapper").classList.remove("hidden");
                                document.getElementById("edit_user_field_name").value = q.user_field_name || "";
                            }

                            const modal = new bootstrap.Modal(document.getElementById("editQuestionModal"));
                            modal.show();
                        } else {
                            showNotification("Failed to load question", "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        showNotification("An error occurred", "error");
                    });
            }
        });

        // Type change handler for edit form
        document.getElementById("edit_question_type").addEventListener("change", function() {
            const type = this.value;
            const wrappers = {
                edit_options_wrapper: ["radio", "checkbox", "dropdown"],
                edit_scale_wrapper: ["scale"],
                edit_file_wrapper: ["file"],
                edit_matrix_wrapper: ["matrix"],
                edit_user_field_wrapper: ["user_field"]
            };
            Object.keys(wrappers).forEach(wrapper => {
                const wrapperEl = document.getElementById(wrapper);
                wrapperEl.classList.add("hidden");
                if (wrappers[wrapper].includes(type)) {
                    wrapperEl.classList.remove("hidden");
                }
            });
        });

        // Submit edit form
        document.getElementById("editQuestionForm").addEventListener("submit", function(e) {
            e.preventDefault();
            showLoadingToast();
            const formData = new FormData(this);
            formData.set("is_required", document.getElementById("edit_is_required").checked ? 1 : 0);
            const questionId = document.getElementById("edit_question_id").value;
            fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/") ?>${questionId}/update`, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.querySelector('[name="csrf_test_name"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        showNotification("Question updated successfully", "success");
                        const modal = bootstrap.Modal.getInstance(document.getElementById("editQuestionModal"));
                        modal.hide();
                        location.reload();
                    } else {
                        showNotification(data.message || "Failed to update question", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showNotification("An error occurred", "error");
                });
        });

        // Add option in edit modal
        document.getElementById("add_edit_option").addEventListener("click", function() {
            const editOptionList = document.getElementById("edit_option_list");
            const optionHtml = `
            <div class="option-item">
                <input type="text" name="options[]" class="form-control" placeholder="Option text...">
                <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)">
                <select name="next_question_ids[]" class="form-control">
                    <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                    <?php foreach ($all_questions as $q): ?>
                        <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-danger btn-icon remove-option"><i class="fas fa-times"></i></button>
            </div>`;
            editOptionList.insertAdjacentHTML("beforeend", optionHtml);
        });

        // Notification utilities
        function showNotification(message, type) {
            const toastContainer = document.getElementById("toastContainer");
            const toast = document.createElement("div");
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `<i class="fas fa-${type === "success" ? "check-circle" : "exclamation-circle"}"></i> ${message}`;
            toastContainer.appendChild(toast);
            setTimeout(() => toast.classList.add("show"), 10);
            setTimeout(() => {
                toast.classList.remove("show");
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function showLoadingToast() {
            const toastContainer = document.getElementById("toastContainer");
            const toast = document.createElement("div");
            toast.className = "toast";
            toast.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            toastContainer.appendChild(toast);
            setTimeout(() => toast.classList.add("show"), 10);
            setTimeout(() => {
                toast.classList.remove("show");
                setTimeout(() => toast.remove(), 300);
            }, 1000);
        }
    });
</script>

<?php
function generateQuestionPreview($q)
{
    $type = $q['question_type'];
    $text = esc($q['question_text']);

    switch ($type) {
        case 'matrix':
            $rows = $q['matrix_rows'] ?? [];
            $columns = $q['matrix_columns'] ?? [];
            $options = $q['matrix_options'] ?? [];
            $html = "<label class='form-label'>{$text}</label><table class='table table-sm'>";
            $html .= "<thead><tr><th></th>";
            foreach ($columns as $col) {
                $colText = is_array($col) ? ($col['column_text'] ?? '') : $col;
                $html .= "<th>" . esc($colText) . "</th>";
            }
            $html .= "</tr></thead><tbody>";
            foreach ($rows as $row) {
                $rowText = is_array($row) ? ($row['row_text'] ?? '') : $row;
                $html .= "<tr><td>" . esc($rowText) . "</td>";
                foreach ($columns as $col) {
                    $html .= "<td class='text-center'>";
                    if (!empty($options)) {
                        foreach ($options as $index => $opt) {
                            $html .= "<label class='form-check-inline'><input type='radio' class='form-check-input' disabled> <span>" . esc($opt) . "</span></label>";
                        }
                    } else {
                        $html .= "<input type='radio' class='form-check-input' disabled>";
                    }
                    $html .= "</td>";
                }
                $html .= "</tr>";
            }
            $html .= "</tbody></table>";
            return $html;

        case 'scale':
            $min = $q['scale_min'] ?? 1;
            $max = $q['scale_max'] ?? 5;
            $step = max(1, (int)($q['scale_step'] ?? 1));
            $html = "<label class='form-label'>{$text}</label><div class='scale-preview'>";
            for ($i = $min; $i <= $max; $i += $step) {
                $html .= "<label class='form-check-inline'><input type='radio' class='form-check-input' disabled> <span>{$i}</span></label>";
            }
            $html .= "</div>";
            $html .= "<div class='scale-labels'><span>" . esc($q['scale_min_label'] ?? 'Min') . "</span><span>" . esc($q['scale_max_label'] ?? 'Max') . "</span></div>";
            return $html;

        case 'file':
            return "<label class='form-label'>{$text}</label><input type='file' class='form-control' disabled><div class='form-helper'>Allowed: " . esc($q['allowed_types'] ?? 'pdf,doc') . ", Max: " . ($q['max_file_size'] ?? 5) . "MB</div>";

        case 'radio':
        case 'checkbox':
            $options = $q['options'] ?? [];
            $inputType = ($type === 'radio') ? 'radio' : 'checkbox';
            $html = "<label class='form-label'>{$text}</label>";

            // TAMPILKAN SEMUA OPTIONS (hapus array_slice)
            foreach ($options as $option) {
                $html .= "<div class='form-check'><input class='form-check-input' type='{$inputType}' disabled><label class='form-check-label'>" . esc($option['option_text']) . "</label></div>";
            }

            return $html;

        case 'dropdown':
            $options = $q['options'] ?? [];
            $html = "<label class='form-label'>{$text}</label><select class='form-control' disabled>";
            $html .= "<option>-- Select --</option>";

            // TAMPILKAN SEMUA OPTIONS (hapus array_slice)
            foreach ($options as $option) {
                $html .= "<option>" . esc($option['option_text']) . "</option>";
            }

            $html .= "</select>";
            return $html;

        default:
            return "<label class='form-label'>{$text}</label><input type='text' class='form-control' disabled>";
    }
}
?>
<?= $this->endSection() ?>
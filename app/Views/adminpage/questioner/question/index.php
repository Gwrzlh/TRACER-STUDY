<link rel="stylesheet" href="/css/questioner/question/index.css">
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <h2>Kelola Pertanyaan - <?= esc($section['section_title']) ?></h2>
            <p class="text-muted"><?= esc($section['section_description'] ?? '') ?></p>

            <!-- Form Tambah Pertanyaan -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                  <h3>Tambah Pertanyaan Baru</h3>
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
                                            <select name="parent_question_id" class="form-select">
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
                                            <select name="condition_operator" class="form-select">
                                                <option value="equals">Equals</option>
                                                <option value="not_equals">Not Equals</option>
                                                <option value="contains">Contains</option>
                                                <option value="greater_than">Greater Than</option>
                                                <option value="less_than">Less Than</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Value:</label>
                                            <input type="text" name="condition_value" class="form-control" placeholder="Expected value..." value="<?= old('condition_value') ?>">
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
                    <span><h3>Questions List (<?= count($questions) ?>)</h3></span>
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
                                                        <?php if (!empty($q['parent_question_id'])): ?>
                                                            <span class="badge bg-secondary">Conditional</span>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="question-actions">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-question" data-question-id="<?= $q['id'] ?>">
                                                    <i class="fas fa-edit"></i> edit
                                                </button>
                                                <!-- <button type="button" class="btn btn-sm btn-outline-success duplicate-question" data-question-id="<?= $q['id'] ?>">
                                                    <i class="fas fa-copy"></i> duplicate
                                                </button> -->
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
                                                            <?php 
                                                            $options = json_decode($q['options'], true) ?: [];
                                                            foreach ($options as $opt): ?>
                                                                <li><i class="fas fa-circle fa-xs me-2"></i><?= esc($opt) ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($q['parent_question_id'])): ?>
                                                        <h6>Conditional Logic:</h6>
                                                        <p class="text-info">
                                                            <i class="fas fa-arrow-right me-1"></i>
                                                            Show when previous question <?= esc($q['condition_operator'] ?? 'equals') ?> "<?= esc($q['condition_value']) ?>"
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
                    <h3 class="mb-0"><i class="fas fa-question-circle me-2"></i>Question Types</h3>
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
</div>

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
        
        // Show relevant wrapper based on type
        if (["radio", "checkbox", "dropdown"].includes(type)) {
            optionsWrapper.style.display = "block";
        } else if (type === "scale") {
            scaleWrapper.style.display = "block";
        } else if (type === "file") {
            fileWrapper.style.display = "block";
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
                // Ajax delete request
                fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/delete/") ?>${questionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('[name="csrf_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        e.target.closest('.question-item').remove();
                        showNotification('Question deleted successfully', 'success');
                    } else {
                        showNotification('Failed to delete question', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred', 'error');
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
function generateQuestionPreview($question) {
    $type = $question['question_type'];
    $text = esc($question['question_text']);
    
    switch ($type) {
        case 'text':
        case 'email':
        case 'number':
        case 'phone':
            return "<label class='form-label small'>{$text}</label><input type='{$type}' class='form-control form-control-sm' disabled>";
            
        case 'textarea':
            return "<label class='form-label small'>{$text}</label><textarea class='form-control form-control-sm' rows='2' disabled></textarea>";
            
        case 'radio':
        case 'checkbox':
            $options = json_decode($question['options'] ?? '[]', true);
            $inputType = $type === 'radio' ? 'radio' : 'checkbox';
            $html = "<label class='form-label small'>{$text}</label>";
            foreach (array_slice($options, 0, 2) as $i => $option) {
                $html .= "<div class='form-check'><input class='form-check-input form-check-input-sm' type='{$inputType}' disabled><label class='form-check-label small'>" . esc($option) . "</label></div>";
            }
            if (count($options) > 2) $html .= "<small class='text-muted'>... and " . (count($options) - 2) . " more</small>";
            return $html;
            
        case 'dropdown':
            $options = json_decode($question['options'] ?? '[]', true);
            $html = "<label class='form-label small'>{$text}</label><select class='form-select form-select-sm' disabled>";
            $html .= "<option>-- Select --</option>";
            foreach (array_slice($options, 0, 3) as $option) {
                $html .= "<option>" . esc($option) . "</option>";
            }
            $html .= "</select>";
            return $html;
            
        case 'scale':
            $min = $question['scale_min'] ?? 1;
            $max = $question['scale_max'] ?? 5;
            $html = "<label class='form-label small'>{$text}</label><div class='d-flex justify-content-between small'>";
            for ($i = $min; $i <= min($max, $min + 4); $i++) {
                $html .= "<label><input type='radio' disabled> {$i}</label>";
            }
            $html .= "</div>";
            return $html;
            
        case 'date':
            return "<label class='form-label small'>{$text}</label><input type='date' class='form-control form-control-sm' disabled>";
            
        case 'file':
            return "<label class='form-label small'>{$text}</label><input type='file' class='form-control form-control-sm' disabled>";
            
        default:
            return "<label class='form-label small'>{$text}</label><input type='text' class='form-control form-control-sm' disabled>";
    }
}
?>

<!-- desain kelola pertanyaan -->
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="/css/questioner/question/index.css">
<!-- Modern Container Wrapper -->
<div class="bg-white rounded-xl shadow-md p-8 w-full mx-auto">
    <!-- Header + Divider -->
    <div class="-mx-8 mb-6 border-b border-gray-300 pb-3 px-8">
        <div class="flex items-center">
            <img src="/images/logo.png" alt="Tracer Study" class="h-12 mr-3">
            <div>
                <h2 class="text-xl font-semibold">Kelola Pertanyaan - <?= esc($section['section_title']) ?></h2>
                <p class="text-sm text-gray-600 mt-1"><?= esc($section['section_description'] ?? '') ?></p>
            </div>
        </div>
    </div>

    <!-- Original Content with Modern Styling -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Form Tambah Pertanyaan -->
                <div class="card mb-4" style="border: 1px solid #d1d5db; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; border-bottom: none; padding: 1rem 1.5rem;">
                        <span style="font-weight: 500;">Tambah Pertanyaan Baru</span>
                        <button type="button" class="btn btn-sm" id="toggleForm" style="background-color: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 0.375rem; padding: 0.5rem 1rem; font-size: 0.875rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.2)'">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                    <div class="card-body" id="formContainer" style="display: none; padding: 1.5rem;">
                        <form id="questionForm" action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/store") ?>" method="post" class="space-y-4">
                            <?= csrf_field() ?>

                            <!-- Question Text -->
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Question Text <span class="text-danger">*</span></label>
                                <textarea name="question_text" class="form-control" rows="3" placeholder="Masukkan teks pertanyaan lengkap..." required style="border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.75rem; transition: all 0.2s ease; font-size: 0.875rem;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'" onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"><?= old('question_text') ?></textarea>
                                <div class="form-text" style="color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem;">Teks pertanyaan yang akan ditampilkan kepada responden</div>
                            </div>

                            <!-- Question Type -->
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Question Type <span class="text-danger">*</span></label>
                                <select name="question_type" id="question_type" class="form-select" required style="border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.75rem; transition: all 0.2s ease; font-size: 0.875rem;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'" onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'">
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
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Answer Options</label>
                                <div id="option_list">
                                    <div class="input-group mb-2">
                                        <input type="text" name="options[]" class="form-control" placeholder="Option text..." style="border: 1px solid #d1d5db; font-size: 0.875rem;">
                                        <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)" style="border: 1px solid #d1d5db; font-size: 0.875rem;">
                                        <select name="next_question_ids[]" class="form-control" style="border: 1px solid #d1d5db; font-size: 0.875rem;">
                                            <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                                            <?php foreach ($all_questions as $q): ?>
                                                <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-danger remove-option" style="border-color: #ef4444; color: #ef4444;">&times;</button>
                                    </div>
                                </div>
                                <button type="button" id="add_option" class="btn btn-sm" style="background-color: #3b82f6; color: white; border: none; border-radius: 0.375rem; padding: 0.5rem 1rem; font-size: 0.875rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
                                    <i class="fas fa-plus"></i> Add Option
                                </button>
                            </div>

                            <!-- Scale Settings -->
                            <div class="mb-3" id="scale_wrapper" style="display: none;">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Scale Settings</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label" style="font-size: 0.75rem;">Min Value</label>
                                        <input type="number" name="scale_min" class="form-control" value="1" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" style="font-size: 0.75rem;">Max Value</label>
                                        <input type="number" name="scale_max" class="form-control" value="5" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" style="font-size: 0.75rem;">Step</label>
                                        <input type="number" name="scale_step" class="form-control" value="1" min="1" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.75rem;">Min Label</label>
                                        <input type="text" name="scale_min_label" class="form-control" placeholder="e.g., Sangat Tidak Setuju" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.75rem;">Max Label</label>
                                        <input type="text" name="scale_max_label" class="form-control" placeholder="e.g., Sangat Setuju" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    </div>
                                </div>
                            </div>
                            <!-- User Field Selection -->
                            <div class="mb-3" id="user_field_wrapper" style="display: none;">
                                <label class="form-label fw-bold">User Profile Field <span class="text-danger">*</span></label>
                                <select name="user_field_name" class="form-select">
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

                            <!-- File Upload Settings -->
                            <div class="mb-3" id="file_wrapper" style="display: none;">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">File Upload Settings</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.75rem;">Allowed File Types</label>
                                        <input type="text" name="allowed_types" class="form-control" placeholder="pdf,doc,docx,jpg,png" value="pdf,doc,docx" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.75rem;">Max File Size (MB)</label>
                                        <input type="number" name="max_file_size" class="form-control" value="5" min="1" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    </div>
                                </div>
                            </div>

                            <!-- Matrix Settings -->
                            <div class="mb-3" id="matrix_wrapper" style="display: none;">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Matrix Settings</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.75rem;">Rows</label>
                                        <input type="text" name="matrix_rows" class="form-control" placeholder="e.g., Baris 1, Baris 2, Baris 3" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.75rem;">Columns</label>
                                        <input type="text" name="matrix_columns" class="form-control" placeholder="e.g., Kolom 1, Kolom 2, Kolom 3" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    </div>
                                </div>
                            </div>

                            <!-- Validation Settings -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Validation</label>
                                    <div class="form-check" style="margin-bottom: 0.75rem;">
                                        <input type="checkbox" name="is_required" value="1" id="is_required" class="form-check-input" <?= old('is_required') ? 'checked' : '' ?> style="border-color: #d1d5db;">
                                        <label class="form-check-label" for="is_required" style="font-size: 0.875rem;">Required</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="enable_conditional" value="1" id="enable_conditional" class="form-check-input" style="border-color: #d1d5db;">
                                        <label class="form-check-label" for="enable_conditional" style="font-size: 0.875rem;">Enable Conditional Logic</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Order</label>
                                    <input type="number" name="order_no" class="form-control" value="<?= old('order_no', $next_order) ?>" min="1" required style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                                    <div class="form-text" style="color: #6b7280; font-size: 0.75rem;">Position of this question</div>
                                </div>
                            </div>

                            <!-- Conditional Logic -->
                            <div id="conditional_wrapper" style="display: none;">
                                <label style="color: #374151; font-size: 0.875rem; font-weight: 500;">Parent Question</label>
                                <select id="parent_question_id" name="parent_question_id" class="form-control" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; margin-bottom: 0.75rem;">
                                    <option value="">-- Pilih Pertanyaan Induk --</option>
                                    <?php foreach ($all_questions as $q): ?>
                                        <option value="<?= $q['id'] ?>"><?= $q['question_text'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label style="color: #374151; font-size: 0.875rem; font-weight: 500;">Operator</label>
                                <select id="condition_operator" name="condition_operator" class="form-control" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; margin-bottom: 0.75rem;">
                                    <option value="is">Is</option>
                                    <option value="is not">Is Not</option>
                                </select>
                                <label style="color: #374151; font-size: 0.875rem; font-weight: 500;">Value</label>
                                <select id="condition_value_select" name="condition_value" class="form-control" style="display: none; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></select>
                                <input id="condition_value_text" name="condition_value" class="form-control" style="display: none; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between pt-4 border-t" style="border-color: #e5e7eb !important; margin-top: 1.5rem;">
                                <button type="button" class="btn" id="cancelForm" style="background-color: #6b7280; color: white; border: none; border-radius: 0.375rem; padding: 0.625rem 1.5rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#4b5563'" onmouseout="this.style.backgroundColor='#6b7280'">Cancel</button>
                                <button type="submit" class="btn" style="background-color: #10b981; color: white; border: none; border-radius: 0.375rem; padding: 0.625rem 1.5rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#059669'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(16, 185, 129, 0.25)'" onmouseout="this.style.backgroundColor='#10b981'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-save"></i> Save Question
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Questions List -->
                <div class="card" style="border: 1px solid #d1d5db; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; border-bottom: none; padding: 1rem 1.5rem;">
                        <span style="font-weight: 500;">Questions List (<?= count($questions) ?>)</span>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn" id="expandAll" style="background-color: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 0.375rem 0 0 0.375rem; padding: 0.5rem 1rem; font-size: 0.875rem;">
                                <i class="fas fa-expand-alt"></i> Expand All
                            </button>
                            <button type="button" class="btn" id="collapseAll" style="background-color: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 0 0.375rem 0.375rem 0; padding: 0.5rem 1rem; font-size: 0.875rem;">
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
                                                    <span class="badge me-2" style="background-color: #3b82f6; color: white; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;"><?= $q['order_no'] ?></span>
                                                    <div>
                                                        <h6 class="mb-1" style="color: #374151; font-size: 1rem; font-weight: 500;"><?= esc($q['question_text']) ?></h6>
                                                        <small class="text-muted">
                                                            <span class="badge me-1" style="background-color: #06b6d4; color: white; font-size: 0.75rem;"><?= ucfirst($q['question_type']) ?></span>
                                                            <?php if ($q['is_required']): ?>
                                                                <span class="badge me-1" style="background-color: #f59e0b; color: white; font-size: 0.75rem;">Required</span>
                                                            <?php endif; ?>
                                                            <?php if (!empty($q['condition_json'])): ?>
                                                                <span class="badge" style="background-color: #6b7280; color: white; font-size: 0.75rem;">Conditional</span>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="question-actions">
                                                    <button type="button" class="btn btn-sm edit-question" data-question-id="<?= $q['id'] ?>" style="background-color: #3b82f6; color: white; border: none; border-radius: 0.375rem; padding: 0.5rem 1rem; font-size: 0.875rem; margin-right: 0.5rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
                                                        <i class="fas fa-edit"></i> edit
                                                    </button>
                                                    <button type="button" class="btn btn-sm delete-question" data-question-id="<?= $q['id'] ?>" style="background-color: #ef4444; color: white; border: none; border-radius: 0.375rem; padding: 0.5rem 1rem; font-size: 0.875rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#dc2626'" onmouseout="this.style.backgroundColor='#ef4444'">
                                                        <i class="fas fa-trash"></i> delete
                                                    </button>
                                                    <button type="button" class="btn btn-sm duplicate-question" data-question-id="<?= $q['id'] ?>" style="background-color: #f59e0b; color: white; border: none; border-radius: 0.375rem; padding: 0.5rem 1rem; font-size: 0.875rem; margin-right: 0.5rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#d97706'" onmouseout="this.style.backgroundColor='#f59e0b'">
                                                         <i class="fas fa-copy"></i> duplicate
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="collapse" id="question-<?= $q['id'] ?>">
                                            <div class="question-details p-3">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 style="color: #374151; font-size: 0.875rem; font-weight: 600;">Question Text:</h6>
                                                        <p class="text-muted" style="color: #6b7280; font-size: 0.875rem;"><?= esc($q['question_text']) ?></p>

                                                        <?php if (!empty($q['options'])): ?>
                                                            <h6 style="color: #374151; font-size: 0.875rem; font-weight: 600;">Options:</h6>
                                                            <ul class="list-unstyled">
                                                                <?php foreach ($q['options'] as $opt): ?>
                                                                    <li style="color: #6b7280; font-size: 0.875rem;"><i class="fas fa-circle fa-xs me-2"></i><?= esc($opt['option_text']) ?></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php endif; ?>

                                                        <?php if (!empty($q['condition_json'])): ?>
                                                            <h6 style="color: #374151; font-size: 0.875rem; font-weight: 600;">Conditional Logic:</h6>
                                                            <p style="color: #06b6d4; font-size: 0.875rem;">
                                                                <i class="fas fa-arrow-right me-1"></i>
                                                                Show when previous question matches the condition
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                       <div class="question-preview border rounded p-2" style="background-color: #f9fafb; border-color: #e5e7eb; display: block !important; opacity: 1 !important; min-height: 120px; visibility: visible !important;">
                                                            <small class="d-block mb-2" style="color: #6b7280; font-size: 0.75rem;">Preview:</small>
                                                            <div class="preview-content-wrapper">
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
                <div class="card sticky-top" style="top: 20px; border: 1px solid #d1d5db; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
                    <div class="card-header" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; border-bottom: none; padding: 1rem 1.5rem;">
                        <h6 class="mb-0" style="font-weight: 500;"><i class="fas fa-question-circle me-2"></i>Question Types</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="question-types-grid">
                            <div class="row g-2 p-3">
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="text" style="border: 1px solid #3b82f6; color: #3b82f6; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#3b82f6'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#3b82f6'">
                                        Single Line Text
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="email" style="border: 1px solid #3b82f6; color: #3b82f6; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#3b82f6'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#3b82f6'">
                                        Email
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="dropdown" style="border: 1px solid #10b981; color: #10b981; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#10b981'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#10b981'">
                                        Dropdown List
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="date" style="border: 1px solid #10b981; color: #10b981; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#10b981'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#10b981'">
                                        Date
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="checkbox" style="border: 1px solid #f59e0b; color: #f59e0b; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#f59e0b'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#f59e0b'">
                                        Checkboxes
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="number" style="border: 1px solid #f59e0b; color: #f59e0b; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#f59e0b'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#f59e0b'">
                                        Number
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="radio" style="border: 1px solid #06b6d4; color: #06b6d4; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#06b6d4'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#06b6d4'">
                                        Radio Buttons
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="phone" style="border: 1px solid #06b6d4; color: #06b6d4; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#06b6d4'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#06b6d4'">
                                        Phone
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="scale" style="border: 1px solid #ef4444; color: #ef4444; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#ef4444'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#ef4444'">
                                        Scale
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="user_field" style="border: 1px solid #6b7280; color: #6b7280; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#6b7280'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#6b7280'">
                                        User Field
                                    </button>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-sm w-100 question-type-btn" data-type="matrix" style="border: 1px solid #374151; color: #374151; background: white; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.75rem; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#374151'; this.style.color='white'" onmouseout="this.style.backgroundColor='white'; this.style.color='#374151'">
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
                <div class="modal-content" style="border-radius: 0.75rem; border: none; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);">
                    <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; border-bottom: none; padding: 1.5rem;">
                        <h5 class="modal-title" id="editQuestionModalLabel" style="font-weight: 500;">Edit Pertanyaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
                    </div>
                    <div class="modal-body" style="padding: 1.5rem;">
                        <form id="editQuestionForm" method="post" class="space-y-4">
                            <?= csrf_field() ?>
                            <input type="hidden" name="question_id" id="edit_question_id">

                            <!-- Question Text -->
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Question Text <span class="text-danger">*</span></label>
                                <textarea name="question_text" id="edit_question_text" class="form-control" rows="3" required style="border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.75rem; font-size: 0.875rem;"></textarea>
                            </div>

                            <!-- Question Type -->
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Question Type <span class="text-danger">*</span></label>
                                <select name="question_type" id="edit_question_type" class="form-select" required style="border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.75rem; font-size: 0.875rem;">
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
                            <div class="mb-3" id="edit_user_field_wrapper" style="display: none;">
                                <label class="form-label fw-bold">User Profile Field <span class="text-danger">*</span></label>
                                <select name="user_field_name" id="edit_user_field_name" class="form-select">
                                    <option value="">-- Pilih Field Profil --</option>
                                    <?php foreach ($fieldFriendlyNames as $field => $name): ?>
                                        <option value="<?= esc($field) ?>"><?= esc($name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Options for Selection Types -->
                            <div class="mb-3" id="edit_options_wrapper" style="display: none;">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Answer Options</label>
                                <div id="edit_option_list"></div>
                                <button type="button" id="add_edit_option" class="btn btn-sm" style="background-color: #3b82f6; color: white; border: none; border-radius: 0.375rem; padding: 0.5rem 1rem; font-size: 0.875rem;">
                                    <i class="fas fa-plus"></i> Add Option
                                </button>
                            </div>

                            <!-- Scale Settings -->
                            <div class="mb-3" id="edit_scale_wrapper" style="display: none;">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Scale Settings</label>
                                <div class="row">
                                    <div class="col-md-4"><input type="number" name="scale_min" id="edit_scale_min" class="form-control" placeholder="Min" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                    <div class="col-md-4"><input type="number" name="scale_max" id="edit_scale_max" class="form-control" placeholder="Max" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                    <div class="col-md-4"><input type="number" name="scale_step" id="edit_scale_step" class="form-control" placeholder="Step" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6"><input type="text" name="scale_min_label" id="edit_scale_min_label" class="form-control" placeholder="Min Label" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                    <div class="col-md-6"><input type="text" name="scale_max_label" id="edit_scale_max_label" class="form-control" placeholder="Max Label" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                </div>
                            </div>

                            <!-- File Settings -->
                            <div class="mb-3" id="edit_file_wrapper" style="display: none;">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">File Settings</label>
                                <div class="row">
                                    <div class="col-md-6"><input type="text" name="allowed_types" id="edit_allowed_types" class="form-control" placeholder="Allowed Types (e.g., pdf,doc)" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                    <div class="col-md-6"><input type="number" name="max_file_size" id="edit_max_file_size" class="form-control" placeholder="Max Size (MB)" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                </div>
                            </div>

                            <!-- Matrix Settings -->
                            <div class="mb-3" id="edit_matrix_wrapper" style="display: none;">
                                <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Matrix Settings</label>
                                <div class="row">
                                    <div class="col-md-6"><input type="text" name="matrix_rows" id="edit_matrix_rows" class="form-control" placeholder="Rows (e.g., Baris 1, Baris 2)" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                    <div class="col-md-6"><input type="text" name="matrix_columns" id="edit_matrix_columns" class="form-control" placeholder="Columns (e.g., Kolom 1, Kolom 2)" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                    <div class="col-md-6"><input type="text" name="matrix_options" id="edit_matrix_options" class="form-control" placeholder="Options (e.g., Sangat Baik,Baik,Kurang Baik)" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></div>
                                </div>
                            </div>

                            <!-- Validation Settings -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: #374151; font-size: 0.875rem; margin-bottom: 0.5rem;">Validation</label>
                                    <div class="form-check" style="margin-bottom: 0.75rem;">
                                        <input type="checkbox" name="is_required" id="edit_is_required" class="form-check-input" style="border-color: #d1d5db;">
                                        <label class="form-check-label" for="edit_is_required" style="font-size: 0.875rem;">Required</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="enable_conditional" value="1" id="edit_enable_conditional" class="form-check-input" style="border-color: #d1d5db;">
                                        <label class="form-check-label" for="edit_enable_conditional" style="font-size: 0.875rem;">Enable Conditional Logic</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Conditional Logic -->
                            <div id="edit_conditional_wrapper" style="display: none;">
                                <label style="color: #374151; font-size: 0.875rem; font-weight: 500;">Parent Question</label>
                                <select id="edit_parent_question_id" name="parent_question_id" class="form-control" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; margin-bottom: 0.75rem;">
                                    <option value="">-- Pilih Pertanyaan Induk --</option>
                                    <?php foreach ($all_questions as $q): ?>
                                        <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label style="color: #374151; font-size: 0.875rem; font-weight: 500;">Operator</label>
                                <select id="edit_condition_operator" name="condition_operator" class="form-control" style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; margin-bottom: 0.75rem;">
                                    <option value="is">Is</option>
                                    <option value="is not">Is Not</option>
                                </select>
                                <label style="color: #374151; font-size: 0.875rem; font-weight: 500;">Value</label>
                                <select id="edit_condition_value_select" name="condition_value" class="form-control" style="display: none; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"></select>
                                <input id="edit_condition_value_text" name="condition_value" class="form-control" style="display: none; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 1.5rem; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem;">
                        <button type="button" class="btn" data-bs-dismiss="modal" style="background-color: #6b7280; color: white; border: none; border-radius: 0.375rem; padding: 0.625rem 1.5rem; font-size: 0.875rem; font-weight: 500;">Close</button>
                        <button type="submit" form="editQuestionForm" class="btn" style="background-color: #3b82f6; color: white; border: none; border-radius: 0.375rem; padding: 0.625rem 1.5rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Form elements (add form)
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

        // Toggle form visibility (add form)
        toggleFormBtn.addEventListener("click", function() {
            const isVisible = formContainer.style.display !== "none";
            formContainer.style.display = isVisible ? "none" : "block";
            toggleFormBtn.innerHTML = isVisible ?
                '<i class="fas fa-plus"></i> Add' :
                '<i class="fas fa-minus"></i> Hide';
        });

        cancelFormBtn.addEventListener("click", function() {
            formContainer.style.display = "none";
            toggleFormBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
            questionForm.reset();
        });

        // Question type change handler (add form)
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

            if (type === "user_field") {
                document.getElementById("user_field_wrapper").style.display = "block";
            } else {
                document.getElementById("user_field_wrapper").style.display = "none";
            }
        });

        // Conditional logic toggle (add form)
        enableConditionalCheck.addEventListener("change", function() {
            conditionalWrapper.style.display = this.checked ? "block" : "none";
        });

        // Add option functionality (add form)
        document.getElementById("add_option").addEventListener("click", function() {
            const optionList = document.getElementById("option_list");
            const optionHtml = `
            <div class="input-group mb-2">
                <input type="text" name="options[]" class="form-control" placeholder="Option text...">
                <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)">
                <select name="next_question_ids[]" class="form-control">
                    <option value="">-- Pilih Pertanyaan Berikutnya --</option>
                    <?php foreach ($all_questions as $q): ?>
                        <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline-danger remove-option">&times;</button>
            </div>
        `;
            optionList.insertAdjacentHTML("beforeend", optionHtml);
        });

        // Remove option functionality (global)
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
                formContainer.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Expand/Collapse all questions
        document.getElementById("expandAll")?.addEventListener("click", function() {
            document.querySelectorAll(".question-item .collapse").forEach(collapse => {
                new bootstrap.Collapse(collapse, {
                    show: true
                });
            });
        });

        document.getElementById("collapseAll")?.addEventListener("click", function() {
            document.querySelectorAll(".question-item .collapse.show").forEach(collapse => {
                new bootstrap.Collapse(collapse, {
                    hide: true
                });
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
                            body: JSON.stringify({
                                question_id: questionId
                            })
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

        // Form submission with Ajax (add form)
        questionForm.addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            console.log("Submitting form data:", Object.fromEntries(formData));

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('[name="csrf_test_name"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Response data:", data);
                    if (data.status === 'success') {
                        showNotification('Question added successfully', 'success');
                        location.reload();
                    } else {
                        showNotification(data.message || 'Failed to add question', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred', 'error');
                });
        });

        // Handler untuk memuat opsi kondisional saat parent question berubah (add form)
        document.getElementById('parent_question_id').addEventListener('change', function() {
            loadConditionOptions();
        });

        // Trigger load jika ada nilai awal (add form)
        if (document.getElementById('parent_question_id').value) {
            loadConditionOptions();
        }

        // Edit question functionality
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
                            document.getElementById('edit_question_text').value = q.question_text || '';
                            document.getElementById('edit_question_type').value = q.question_type || '';
                            document.getElementById('edit_is_required').checked = q.is_required == 1 || q.is_required === true || q.is_required === '1';
                            document.getElementById('edit_enable_conditional').checked = !!q.condition_json;

                            // Hide all special wrappers
                            const wrappers = ['edit_options_wrapper', 'edit_scale_wrapper', 'edit_file_wrapper', 'edit_matrix_wrapper', 'edit_conditional_wrapper'];
                            wrappers.forEach(w => document.getElementById(w).style.display = 'none');

                            // Show conditional wrapper if enabled
                            document.getElementById('edit_conditional_wrapper').style.display = q.condition_json ? 'block' : 'none';

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
                                                <?= esc($q['question_text']) ?>
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
                            } else if (q.question_type === 'user_field') {
                                document.getElementById('edit_user_field_wrapper').style.display = 'block';
                                document.getElementById('edit_user_field_name').value = q.user_field_name || '';
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
                            document.getElementById('editQuestionModal').scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
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
                'edit_matrix_wrapper': ['matrix'],  
            };
            for (let wrapper in wrappers) {
                document.getElementById(wrapper).style.display = wrappers[wrapper].includes(type) ? 'block' : 'none';
            }

            document.getElementById('edit_user_field_wrapper').style.display = (type === 'user_field') ? 'block' : 'none';
        });

        // Conditional logic toggle for edit form
        document.getElementById('edit_enable_conditional').addEventListener("change", function() {
            document.getElementById('edit_conditional_wrapper').style.display = this.checked ? 'block' : 'none';
        });

        // Submit edit form
        document.getElementById('editQuestionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const questionId = document.getElementById('edit_question_id').value;
            console.log("Submitting form data:", Object.fromEntries(formData));

            formData.set('is_required', document.getElementById('edit_is_required').checked ? 1 : 0);
            formData.set('enable_conditional', document.getElementById('edit_enable_conditional').checked ? 1 : 0);

            fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/") ?>${questionId}/update`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('[name="csrf_test_name"]').value
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

      document.addEventListener("click", function(e) {
        if (e.target.closest(".duplicate-question")) {
            e.preventDefault();
            const questionId = e.target.closest(".duplicate-question").dataset.questionId;
            if (confirm("Are you sure you want to duplicate this question?")) {
                console.log("Duplicating question ID:", questionId);
                console.log("Questionnaire ID:", '<?= $questionnaire_id ?>');
                console.log("Page ID:", '<?= $page_id ?>');
                console.log("Section ID:", '<?= $section_id ?>');
                console.log("CSRF Token:", document.querySelector('[name="csrf_test_name"]')?.value);
              const duplicateUrl = `<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions") ?>/${questionId}/duplicate`;
                console.log("Duplicate URL:", duplicateUrl);

                fetch(duplicateUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('[name="csrf_test_name"]')?.value || ''
                    }
                })
                .then(response => {
                    console.log("Response status:", response.status);
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    console.log("Duplicate response:", data);
                    showNotification('Question duplicated successfully', 'success');
                })
                .catch(error => {
                    console.error('Error duplicating question:', error);
                    showNotification('An error occurred while duplicating question: ' + error.message, 'error');
                });
            }
        }
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
                        <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
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
            const parentId = document.getElementById('edit_parent_question_id')?.value || '';
            const conditionValueSelect = document.getElementById('edit_condition_value_select');
            const conditionValueText = document.getElementById('edit_condition_value_text');

            if (!conditionValueSelect || !conditionValueText) {
                console.error('Edit conditional elements missing:', {
                    conditionValueSelect: !!conditionValueSelect,
                    conditionValueText: !!conditionValueText
                });
                return;
            }

            conditionValueSelect.innerHTML = '<option value="">-- Select Value --</option>';
            conditionValueSelect.disabled = true;
            conditionValueText.disabled = true;
            conditionValueSelect.style.display = 'none';
            conditionValueText.style.display = 'none';

            if (parentId) {
                console.log('Fetching edit options for parentId:', parentId);
                fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/get-op/") ?>${parentId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('[name="csrf_test_name"]')?.value || '<?= csrf_hash() ?>'
                        }
                    })
                    .then(response => {
                        console.log('Fetch edit response status:', response.status);
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetch edit data:', data);
                        if (data.status === 'success') {
                            const questionType = data.question_type;
                            const options = data.options || [];

                            if (['text', 'textarea', 'email', 'number', 'phone'].includes(questionType)) {
                                conditionValueText.style.display = 'block';
                                conditionValueText.disabled = false;
                                conditionValueText.value = '';
                            } else if (['radio', 'checkbox', 'dropdown'].includes(questionType) && options.length > 0) {
                                conditionValueSelect.style.display = 'block';
                                conditionValueSelect.innerHTML = '<option value="">-- Select Value --</option>';
                                options.forEach(opt => {
                                    const option = document.createElement('option');
                                    option.value = opt.option_value || opt.option_text;
                                    option.textContent = opt.option_text;
                                    conditionValueSelect.appendChild(option);
                                });
                                conditionValueSelect.disabled = false;
                                const currentValue = conditionValueText.value;
                                if (currentValue) conditionValueSelect.value = currentValue;
                            } else {
                                conditionValueText.style.display = 'block';
                                conditionValueText.disabled = false;
                                conditionValueText.value = '';
                            }
                        } else {
                            console.error('Failed to load edit options:', data.message);
                            conditionValueText.style.display = 'block';
                            conditionValueText.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading edit options:', error);
                        conditionValueText.style.display = 'block';
                        conditionValueText.disabled = false;
                    });
            }
        };

        // Trigger saat parent berubah (edit form)
        document.getElementById('edit_parent_question_id').addEventListener('change', function() {
            loadEditConditionOptions();
        });

        // Trigger load jika ada nilai awal (edit form)
        if (document.getElementById('edit_parent_question_id').value) {
            loadEditConditionOptions();
        }
    });

    //   loadcondition function

    window.loadConditionOptions = function() {
        const parentId = document.getElementById('parent_question_id')?.value || '';
        const conditionValueSelect = document.getElementById('condition_value_select');
        const conditionValueText = document.getElementById('condition_value_text');
        const conditionOperator = document.getElementById('condition_operator');

        if (!conditionValueSelect || !conditionValueText || !conditionOperator) {
            console.error('Conditional elements missing:', {
                conditionValueSelect: !!conditionValueSelect,
                conditionValueText: !!conditionValueText,
                conditionOperator: !!conditionOperator
            });
            return;
        }

        conditionValueSelect.innerHTML = '<option value="">-- Select Value --</option>';
        conditionValueSelect.disabled = true;
        conditionValueText.disabled = true;
        conditionValueSelect.style.display = 'none';
        conditionValueText.style.display = 'none';
        conditionOperator.disabled = !parentId;

        if (parentId) {
            console.log('Fetching options for parentId:', parentId);
            fetch(`<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions/get-op/") ?>${parentId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('[name="csrf_test_name"]')?.value || '<?= csrf_hash() ?>'
                    }
                })
                .then(response => {
                    console.log('Fetch response status:', response.status);
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    console.log('Fetch data:', data);
                    if (data.status === 'success') {
                        const questionType = data.question_type;
                        const options = data.options || [];

                        if (['text', 'textarea', 'email', 'number', 'phone'].includes(questionType)) {
                            conditionValueText.style.display = 'block';
                            conditionValueText.disabled = false;
                            conditionValueText.value = '';
                        } else if (['radio', 'checkbox', 'dropdown'].includes(questionType) && options.length > 0) {
                            conditionValueSelect.style.display = 'block';
                            conditionValueSelect.innerHTML = '<option value="">-- Select Value --</option>';
                            options.forEach(opt => {
                                const option = document.createElement('option');
                                option.value = opt.option_value || opt.option_text;
                                option.textContent = opt.option_text;
                                conditionValueSelect.appendChild(option);
                            });
                            conditionValueSelect.disabled = false;
                            const currentValue = conditionValueText.value;
                            if (currentValue) conditionValueSelect.value = currentValue;
                        } else {
                            conditionValueText.style.display = 'block';
                            conditionValueText.disabled = false;
                            conditionValueText.value = '';
                        }
                    } else {
                        console.error('Failed to load options:', data.message);
                        conditionValueText.style.display = 'block';
                        conditionValueText.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error loading options:', error);
                    conditionValueText.style.display = 'block';
                    conditionValueText.disabled = false;
                });
        }
    };
    // });
</script>

<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .question-item:hover .question-header {
        background-color: #f8f9fa !important;
    }

    .question-types-grid .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .sticky-top {
        z-index: 1020;
    }

    .question-actions .btn {
        margin-left: 2px;
    }

    /* Custom animations */
    .question-item {
        transition: all 0.3s ease;
    }

    .question-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
function generateQuestionPreview($q)
{
    $type = $q['question_type'];
    $text = esc($q['question_text']);

    switch ($type) {

        case 'matrix':
            $rows = $q['matrix_rows'] ?? [];
            $columns = $q['matrix_columns'] ?? [];
            $options = $q['matrix_options'] ?? [];

            $html = "<label class='form-label small'>{$text}</label><table class='table table-sm border'>";
            $html .= "<thead><tr><th></th>";

            // tampilkan header kolom
            foreach ($columns as $col) {
                $colText = is_array($col) ? ($col['column_text'] ?? '') : $col;
                $html .= "<th>" . esc($colText) . "</th>";
            }
            $html .= "</tr></thead><tbody>";

            // tampilkan setiap row
            foreach ($rows as $row) {
                $rowText = is_array($row) ? ($row['row_text'] ?? '') : $row;
                $html .= "<tr><td>" . esc($rowText) . "</td>";

                foreach ($columns as $col) {
                    $colText = is_array($col) ? ($col['column_text'] ?? '') : $col;
                    $html .= "<td>";

                    // kalau ada opsi tambahan
                    if (!empty($options)) {
                        foreach ($options as $index => $opt) {
                            $html .= "<label><input type='radio' name='matrix_{$rowText}_{$colText}' disabled> " . esc($opt) . "</label>";
                        }
                    } else {
                        // default: hanya tampilkan radio tanpa label opsi
                        $html .= "<input type='radio' name='matrix_{$rowText}_{$colText}' disabled>";
                    }

                    $html .= "</td>";
                }
                $html .= "</tr>";
            }

            $html .= "</tbody></table>";
            return $html;;

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
<?= $this->endSection() ?>
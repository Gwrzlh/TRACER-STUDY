<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pertanyaan - Tracer Study</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 20px;
        }
        
        .option-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            position: relative;
        }
        
        .option-item:hover {
            border-color: #007bff;
            box-shadow: 0 2px 5px rgba(0,123,255,0.1);
        }
        
        .remove-option {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #dc3545;
            cursor: pointer;
            font-size: 18px;
        }
        
        .remove-option:hover {
            color: #a71d2a;
        }
        
        .add-option-btn {
            border: 2px dashed #007bff;
            background: transparent;
            color: #007bff;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .add-option-btn:hover {
            background: #007bff;
            color: white;
        }
        
        .question-type-selector {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .type-option {
            flex: 1;
            min-width: 120px;
            padding: 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        
        .type-option:hover {
            border-color: #007bff;
            background: #f8f9fa;
        }
        
        .type-option.active {
            border-color: #007bff;
            background: #007bff;
            color: white;
        }
        
        .type-option i {
            display: block;
            font-size: 24px;
            margin-bottom: 8px;
        }
        
        .options-section {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .options-section.show {
            display: block;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .preview-section {
            background: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/admin/questionnaire">Kuesioner</a></li>
            <li class="breadcrumb-item"><a href="/admin/questionnaire/<?= $questionnaire_id ?>/questions">Pertanyaan</a></li>
            <li class="breadcrumb-item active">Tambah Pertanyaan</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="form-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="fas fa-plus-circle text-primary"></i> Tambah Pertanyaan Baru</h2>
                    <a href="/admin/questionnaire/<?= $questionnaire_id ?>/questions" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>

                <form action="/admin/questionnaire/<?= $questionnaire_id ?>/questions/store" method="POST" id="questionForm">
                    <?= csrf_field() ?>
                    
                    <!-- Question Text -->
                    <div class="mb-4">
                        <label for="question_text" class="form-label fw-bold">
                            <i class="fas fa-question-circle text-primary"></i> Teks Pertanyaan
                        </label>
                        <textarea name="question_text" id="question_text" class="form-control" rows="3" 
                                placeholder="Masukkan teks pertanyaan..." required><?= old('question_text') ?></textarea>
                        <div class="form-text">Tulis pertanyaan yang jelas dan mudah dipahami alumni.</div>
                    </div>

                    <!-- Question Type Selector -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-cogs text-primary"></i> Tipe Pertanyaan
                        </label>
                        <div class="question-type-selector">
                            <div class="type-option" data-type="text">
                                <i class="fas fa-font"></i>
                                <div>Text Pendek</div>
                            </div>
                            <div class="type-option" data-type="textarea">
                                <i class="fas fa-align-left"></i>
                                <div>Text Panjang</div>
                            </div>
                            <div class="type-option" data-type="radio">
                                <i class="fas fa-dot-circle"></i>
                                <div>Pilihan Tunggal</div>
                            </div>
                            <div class="type-option" data-type="checkbox">
                                <i class="fas fa-check-square"></i>
                                <div>Pilihan Ganda</div>
                            </div>
                            <div class="type-option" data-type="dropdown">
                                <i class="fas fa-chevron-down"></i>
                                <div>Dropdown</div>
                            </div>
                            <div class="type-option" data-type="number">
                                <i class="fas fa-hashtag"></i>
                                <div>Angka</div>
                            </div>
                            <div class="type-option" data-type="date">
                                <i class="fas fa-calendar"></i>
                                <div>Tanggal</div>
                            </div>
                            <div class="type-option" data-type="email">
                                <i class="fas fa-envelope"></i>
                                <div>Email</div>
                            </div>
                        </div>
                        <input type="hidden" name="question_type" id="question_type" required>
                    </div>

                    <!-- Options Section (Hidden by default) -->
                    <div class="options-section" id="optionsSection">
                        <h5 class="mb-3">
                            <i class="fas fa-list text-primary"></i> Opsi Jawaban
                            <small class="text-muted">(dengan conditional logic)</small>
                        </h5>
                        
                        <div id="optionsContainer">
                            <!-- Options will be added here dynamically -->
                        </div>
                        
                        <div class="add-option-btn" onclick="addOption()">
                            <i class="fas fa-plus"></i> Tambah Opsi
                        </div>
                    </div>

                    <!-- Question Settings -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <!-- Hidden input to ensure value is always sent -->
                                <input type="hidden" name="is_required" value="0">
                                <input class="form-check-input" type="checkbox" name="is_required" id="is_required" value="1"
                                       <?= old('is_required') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_required">
                                    <i class="fas fa-exclamation-triangle text-warning"></i> Pertanyaan Wajib
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="order_no" class="form-label">Urutan Pertanyaan</label>
                            <input type="number" name="order_no" id="order_no" class="form-control" 
                                   value="<?= $next_order ?? 1 ?>" min="1" required>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-info" onclick="previewQuestion()">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Pertanyaan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="col-lg-4">
            <div class="preview-section">
                <h5 class="mb-3"><i class="fas fa-eye text-info"></i> Preview Pertanyaan</h5>
                <div id="questionPreview">
                    <p class="text-muted">Preview akan muncul setelah Anda mengisi pertanyaan...</p>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-4 p-3 bg-white rounded shadow-sm">
                <h6 class="text-primary"><i class="fas fa-info-circle"></i> Tips Membuat Pertanyaan</h6>
                <ul class="small mb-0">
                    <li>Gunakan bahasa yang jelas dan mudah dipahami</li>
                    <li>Hindari pertanyaan yang bias atau mengarahkan</li>
                    <li>Untuk conditional logic, atur "Pertanyaan Selanjutnya" pada setiap opsi</li>
                    <li>Pertanyaan wajib akan ditandai dengan (*)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
let optionCounter = 0;

// Question type selection
document.addEventListener('DOMContentLoaded', function() {
    const typeOptions = document.querySelectorAll('.type-option');
    const questionTypeInput = document.getElementById('question_type');
    const optionsSection = document.getElementById('optionsSection');

    typeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            typeOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Set hidden input value
            const type = this.dataset.type;
            questionTypeInput.value = type;
            
            // Show/hide options section
            if (['radio', 'checkbox', 'dropdown'].includes(type)) {
                optionsSection.classList.add('show');
                if (optionCounter === 0) {
                    addOption();
                    addOption();
                }
            } else {
                optionsSection.classList.remove('show');
            }
            
            updatePreview();
        });
    });

    // Auto-update preview when typing
    document.getElementById('question_text').addEventListener('input', updatePreview);
    document.getElementById('is_required').addEventListener('change', updatePreview);
});

function addOption() {
    optionCounter++;
    const container = document.getElementById('optionsContainer');
    
    const optionHtml = `
        <div class="option-item" data-option="${optionCounter}">
            <div class="remove-option" onclick="removeOption(this)">
                <i class="fas fa-times"></i>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label small">Teks Opsi</label>
                    <input type="text" name="options[]" class="form-control option-text" 
                           placeholder="Masukkan teks opsi..." onchange="updatePreview()">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label small">Pertanyaan Selanjutnya <small class="text-muted">(opsional)</small></label>
                    <select name="next_questions[]" class="form-control">
                        <option value="">-- Pilih pertanyaan selanjutnya --</option>
                        <option value="end">Selesai (Akhir kuesioner)</option>
                        <!-- Options will be populated from existing questions -->
                    </select>
                </div>
            </div>
            <div class="small text-muted">
                <i class="fas fa-info-circle"></i> Jika "Pertanyaan Selanjutnya" tidak dipilih, akan lanjut ke pertanyaan berikutnya secara berurutan.
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', optionHtml);
    updatePreview();
}

function removeOption(element) {
    if (document.querySelectorAll('.option-item').length > 1) {
        element.closest('.option-item').remove();
        updatePreview();
    } else {
        alert('Minimal harus ada satu opsi!');
    }
}

function updatePreview() {
    const questionText = document.getElementById('question_text').value;
    const questionType = document.getElementById('question_type').value;
    const isRequired = document.getElementById('is_required').checked;
    const previewContainer = document.getElementById('questionPreview');
    
    if (!questionText && !questionType) {
        previewContainer.innerHTML = '<p class="text-muted">Preview akan muncul setelah Anda mengisi pertanyaan...</p>';
        return;
    }
    
    let previewHtml = `
        <div class="mb-3">
            <label class="form-label fw-bold">
                ${questionText} ${isRequired ? '<span class="text-danger">*</span>' : ''}
            </label>
    `;
    
    switch(questionType) {
        case 'text':
            previewHtml += '<input type="text" class="form-control" placeholder="Jawaban..." disabled>';
            break;
        case 'textarea':
            previewHtml += '<textarea class="form-control" rows="3" placeholder="Jawaban..." disabled></textarea>';
            break;
        case 'number':
            previewHtml += '<input type="number" class="form-control" placeholder="0" disabled>';
            break;
        case 'date':
            previewHtml += '<input type="date" class="form-control" disabled>';
            break;
        case 'email':
            previewHtml += '<input type="email" class="form-control" placeholder="email@example.com" disabled>';
            break;
        case 'radio':
        case 'checkbox':
        case 'dropdown':
            const options = document.querySelectorAll('.option-text');
            if (options.length > 0) {
                if (questionType === 'dropdown') {
                    previewHtml += '<select class="form-control" disabled><option>-- Pilih --</option>';
                    options.forEach(option => {
                        if (option.value.trim()) {
                            previewHtml += `<option>${option.value}</option>`;
                        }
                    });
                    previewHtml += '</select>';
                } else {
                    options.forEach((option, index) => {
                        if (option.value.trim()) {
                            const inputType = questionType === 'radio' ? 'radio' : 'checkbox';
                            const name = questionType === 'radio' ? 'preview_option' : `preview_option_${index}`;
                            previewHtml += `
                                <div class="form-check">
                                    <input class="form-check-input" type="${inputType}" name="${name}" disabled>
                                    <label class="form-check-label">${option.value}</label>
                                </div>
                            `;
                        }
                    });
                }
            } else {
                previewHtml += '<p class="text-muted small">Tambahkan opsi untuk melihat preview</p>';
            }
            break;
    }
    
    previewHtml += '</div>';
    previewContainer.innerHTML = previewHtml;
}

function previewQuestion() {
    const questionText = document.getElementById('question_text').value;
    if (!questionText) {
        alert('Silakan isi teks pertanyaan terlebih dahulu!');
        return;
    }
    
    // Create modal for larger preview
    const modalHtml = `
        <div class="modal fade" id="previewModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview Pertanyaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${document.getElementById('questionPreview').innerHTML}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('previewModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
    
    // Remove modal when hidden
    document.getElementById('previewModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Form validation
document.getElementById('questionForm').addEventListener('submit', function(e) {
    const questionType = document.getElementById('question_type').value;
    
    if (!questionType) {
        e.preventDefault();
        alert('Silakan pilih tipe pertanyaan!');
        return;
    }
    
    if (['radio', 'checkbox', 'dropdown'].includes(questionType)) {
        const options = document.querySelectorAll('.option-text');
        let hasValidOption = false;
        
        options.forEach(option => {
            if (option.value.trim()) {
                hasValidOption = true;
            }
        });
        
        if (!hasValidOption) {
            e.preventDefault();
            alert('Silakan tambahkan minimal satu opsi untuk pertanyaan ini!');
            return;
        }
    }
});
</script>

</body>
</html>
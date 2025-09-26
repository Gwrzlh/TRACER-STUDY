<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Isi Kuesioner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden; /* Cegah scroll body ke page hidden */
        }
        .container {
            height: 100vh;
            overflow-y: auto; /* Scroll hanya dalam container jika konten halaman panjang */
            padding-bottom: 50px; /* Ruang untuk tombol */
        }
        .page-step {
            display: none; /* Semua page hidden secara default */
            min-height: 100%; /* Isi penuh container */
        }
        .page-step.active {
            display: block; /* Hanya active yang tampil */
        }
        
        /* NEW: Announcement overlay styles */
        .announcement-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .announcement-modal {
            background: white;
            border-radius: 15px;
            max-width: 600px;
            width: 100%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.7);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .announcement-modal.show {
            transform: scale(1);
            opacity: 1;
        }
        
        .announcement-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .announcement-body {
            padding: 30px;
            text-align: center;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .announcement-footer {
            padding: 20px 30px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }
        
        .btn-announcement {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-announcement:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h3><?= esc($structure['questionnaire']['title']) ?></h3>
        <div class="progress mb-3">
            <div class="progress-bar" id="progress-bar" style="width: <?= esc($progress) ?>%"
                role="progressbar" aria-valuenow="<?= esc($progress) ?>" aria-valuemin="0" aria-valuemax="100">
                <?= round($progress, 1) ?>%
            </div>
        </div>

        <form id="questionnaire-form" method="post" action="<?= base_url('alumni/questionnaires/save-answer') ?>" enctype="multipart/form-data">
            <input type="hidden" name="q_id" value="<?= esc($q_id) ?>">

            <?php $pageIndex = 0; ?>
            <?php foreach ($structure['pages'] as $page): ?>
                <div class="card mb-3 page-step <?= $pageIndex === 0 ? 'active' : '' ?>"
                    data-step="<?= $pageIndex ?>"
                    data-conditions="<?= htmlspecialchars($page['conditional_logic'] ?? '[]') ?>">
                    <div class="card-header">
                        <h5><?= esc($page['page_title']) ?></h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($page['sections'] as $section): ?>
                            <div class="section-container mb-3"
                                data-conditions="<?= htmlspecialchars($section['conditional_logic'] ?? '[]') ?>">
                                <?php if ($section['show_section_title']): ?>
                                    <h6><?= esc($section['section_title']) ?></h6>
                                <?php endif; ?>
                                <?php if ($section['show_section_description']): ?>
                                    <p><?= esc($section['section_description']) ?></p>
                                <?php endif; ?>
                                <?php foreach ($section['questions'] as $q): ?>
                                    <div class="mb-3 question-container"
                                        data-conditions="<?= htmlspecialchars($q['condition_json'] ?? '[]') ?>">
                                        <label class="form-label">
                                            <?= esc($q['question_text']) ?><?= $q['is_required'] ? ' <span class="text-danger">*</span>' : '' ?>
                                        </label>
                                        <?php
                                        $options = $q['options'] ?? [];
                                        $existing_answer = $previous_answers['q_' . $q['id']] ?? '';
                                        $existing_answers = is_array(json_decode($existing_answer, true)) ? json_decode($existing_answer, true) : [$existing_answer];
                                        ?>
                                        <?php if (strtolower($q['question_type']) === 'text'): ?>
                                            <input type="text" class="form-control" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                value="<?= esc($existing_answer) ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                        <?php elseif (strtolower($q['question_type']) === 'email'): ?>
                                            <input type="email" class="form-control" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                value="<?= esc($existing_answer) ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                        <?php elseif (strtolower($q['question_type']) === 'number'): ?>
                                            <input type="number" class="form-control" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                value="<?= esc($existing_answer) ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                        <?php elseif (in_array(strtolower($q['question_type']), ['dropdown', 'select'])): ?>
                                            <select class="form-select" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                                <option value="">Pilih...</option>
                                                <?php foreach ($options as $opt): ?>
                                                    <option value="<?= esc($opt) ?>" <?= in_array($opt, $existing_answers) ? 'selected' : '' ?>><?= esc($opt) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php elseif (strtolower($q['question_type']) === 'radio'): ?>
                                            <?php foreach ($options as $opt): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                        value="<?= esc($opt) ?>" id="radio-<?= $q['id'] ?>-<?= md5($opt) ?>"
                                                        <?= in_array($opt, $existing_answers) ? 'checked' : '' ?>
                                                        <?= $q['is_required'] ? 'required' : '' ?>>
                                                    <label class="form-check-label" for="radio-<?= $q['id'] ?>-<?= md5($opt) ?>"><?= esc($opt) ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php elseif (strtolower($q['question_type']) === 'checkbox'): ?>
                                            <?php foreach ($options as $opt): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="answer[<?= $q['id'] ?>][]" data-qid="<?= $q['id'] ?>"
                                                        value="<?= esc($opt) ?>" id="check-<?= $q['id'] ?>-<?= md5($opt) ?>"
                                                        <?= in_array($opt, $existing_answers) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="check-<?= $q['id'] ?>-<?= md5($opt) ?>"><?= esc($opt) ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php elseif ($q['question_type'] === 'user_field'): ?>
                                       <?php
                                            $fieldName = $q['user_field_name'] ?? '';
                                            $friendlyLabel = isset($field_friendly_names[$fieldName]) ? $field_friendly_names[$fieldName] : ucwords(str_replace('_', ' ', $fieldName));
                                            $fieldType = $field_types[$fieldName] ?? 'text';
                                            $preValue = isset($user_profile[$fieldName]) ? $user_profile[$fieldName] : '';
                                            $displayValue = isset($user_profile_display[$fieldName . '_name']) ? $user_profile_display[$fieldName . '_name'] : $preValue;
                                            ?>
                                           
                                            <?php if (strpos($fieldType, 'foreign_key') === 0): ?>
                                                <?php
                                                // Map foreign key table to options and keys
                                                $fkTable = explode(':', $fieldType)[1] ?? '';
                                                $fkConfig = [
                                                    'jurusan' => ['options' => $jurusan_options, 'key' => isset($jurusan_options[0]['id_jurusan']) ? 'id_jurusan' : 'id', 'label' => 'nama_jurusan'],
                                                    'cities' => ['options' => $cities_options, 'key' => isset($cities_options[0]['id_cities']) ? 'id_cities' : 'id', 'label' => 'name'],
                                                    'prodi' => ['options' => $prodi_options, 'key' => isset($prodi_options[0]['id_prodi']) ? 'id_prodi' : 'id', 'label' => 'nama_prodi'],
                                                    'provinces' => ['options' => $provinsi_options, 'key' => isset($provinces_options[0]['id_provinsi']) ? 'id_provinsi' : 'id', 'label' => 'name'],
                                                ];
                                                $options = $fkConfig[$fkTable]['options'] ?? [];
                                                $optionKey = $fkConfig[$fkTable]['key'] ?? 'id';
                                                $optionLabel = $fkConfig[$fkTable]['label'] ?? 'name';
                                                ?>
                                                <?php if (!empty($options)): ?>
                                                    <select class="form-control" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                                        <option value="">-- Pilih <?= esc($friendlyLabel) ?> --</option>
                                                        <?php foreach ($options as $option): ?>
                                                            <option value="<?= esc($option[$optionKey]) ?>" <?= $option[$optionKey] == $preValue ? 'selected' : '' ?>>
                                                                <?= esc($option[$optionLabel]) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php if (!$preValue && $fieldName): ?>
                                                        <small class="form-text text-muted">Pilih <?= esc($friendlyLabel) ?>.</small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <input type="text" class="form-control" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                        value="<?= esc($displayValue) ?>" <?= $q['is_required'] ? 'required' : '' ?>
                                                        placeholder="No options available for <?= esc($friendlyLabel) ?>">
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <input type="text" class="form-control" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                    value="<?= esc($displayValue) ?>" <?= $q['is_required'] ? 'required' : '' ?>
                                                    placeholder="<?= $displayValue ? '' : 'Enter your ' . esc($friendlyLabel) ?>">
                                                <?php if (!$preValue && $fieldName): ?>
                                                    <small class="form-text text-muted">No data found for <?= esc($friendlyLabel) ?>. Please enter it.</small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php elseif (in_array(strtolower($q['question_type']), ['scale', 'matrix_scale'])): ?>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input type="range" class="form-range" id="scale-<?= $q['id'] ?>" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                        min="<?= $q['scale_min'] ?? 1 ?>" max="<?= $q['scale_max'] ?? 10 ?>"
                                                        step="<?= $q['scale_step'] ?? 1 ?>"
                                                        value="<?= esc($existing_answer ?: ($q['scale_min'] ?? 1)) ?>"
                                                        <?= $q['is_required'] ? 'required' : '' ?>
                                                        oninput="updateScaleValue(<?= $q['id'] ?>)">
                                                </div>
                                                <div class="col-md-2">
                                                    <span id="scale-value-<?= $q['id'] ?>" class="badge bg-secondary">
                                                        <?= esc($existing_answer ?: ($q['scale_min'] ?? 1)) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php elseif (strtolower($q['question_type']) === 'matrix'): ?>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <?php foreach ($q['matrix_columns'] as $col): ?>
                                                            <th><?= esc($col['column_text']) ?></th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($q['matrix_rows'] as $row): ?>
                                                        <tr>
                                                            <td><?= esc($row['row_text']) ?></td>
                                                            <?php foreach ($q['matrix_columns'] as $col): ?>
                                                                <td>
                                                                    <input type="radio" name="answer[<?= $q['id'] ?>][<?= $row['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                                        value="<?= esc($col['column_text']) ?>"
                                                                        id="matrix-<?= $q['id'] ?>-<?= $row['id'] ?>-<?= $col['id'] ?>"
                                                                        <?= in_array($col['column_text'], (array)($existing_answers[$row['id']] ?? [])) ? 'checked' : '' ?>
                                                                        <?= $q['is_required'] ? 'required' : '' ?>>
                                                                </td>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        
                                        <?php elseif (strtolower($q['question_type']) === 'file'): ?>
                                            <input type="file" class="form-control" name="answer_<?= $q['id'] ?>" data-qid="<?= $q['id'] ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                            <?php if ($existing_answer): ?>
                                                <small class="text-success">File sebelumnya: <?= esc(basename($existing_answer)) ?></small>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-between mt-3">
                            <?php if ($pageIndex > 0): ?>
                                <button type="button" class="btn btn-secondary prev-btn">Sebelumnya</button>
                            <?php endif; ?>
                            <?php if ($pageIndex < count($structure['pages']) - 1): ?>
                                <button type="button" class="btn btn-primary next-btn">Selanjutnya</button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php $pageIndex++; ?>
            <?php endforeach; ?>
        </form>
        <div class="mt-3">
            <a href="<?= base_url('alumni/questionnaires') ?>" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <!-- NEW: Announcement Overlay -->
    <div class="announcement-overlay" id="announcementOverlay">
        <div class="announcement-modal" id="announcementModal">
            <div class="announcement-header">
                <h4 class="mb-0">🎉 Selamat!</h4>
                <p class="mb-0 mt-2">Kuesioner Berhasil Diselesaikan</p>
            </div>
            <div class="announcement-body" id="announcementContent">
                <!-- Content will be inserted here -->
            </div>
            <div class="announcement-footer">
                <button type="button" class="btn btn-announcement" onclick="redirectToQuestionnaires()">
                    Kembali ke Daftar Kuesioner
                </button>
            </div>
        </div>
    </div>

<script>
   // Enhanced questionnaire navigation with dynamic submit detection and proper validation
let currentStep = 0;
const steps = $(".page-step");

// NEW: Announcement handling functions
function showAnnouncement(content) {
    console.log('[DEBUG] Showing announcement');
    $('#announcementContent').html(content.replace(/\n/g, '<br>'));
    $('#announcementOverlay').fadeIn(300);
    
    setTimeout(function() {
        $('#announcementModal').addClass('show');
    }, 100);
}

function redirectToQuestionnaires() {
    window.location.href = "<?= base_url('alumni/questionnaires') ?>";
}

// Utility function to check if a page has any visible required fields
function hasVisibleRequiredFields(pageElement) {
    const visibleRequired = $(pageElement).find("input[required], select[required], textarea[required]").filter(":visible");
    return visibleRequired.length > 0;
}

// Function to check if there are any valid next pages from current position
function hasValidNextPages(fromIndex) {
    for (let i = fromIndex + 1; i < steps.length; i++) {
        // Temporarily evaluate the page to see if it would be valid
        const testPage = steps.eq(i);
        if (wouldPageBeValid(testPage[0])) {
            return true;
        }
    }
    return false;
}

// Function to test if a page would be valid without showing it
function wouldPageBeValid(element) {
    const $el = $(element);
    const conditionsJson = $el.data('conditions');
    
    if (!conditionsJson || conditionsJson === '[]' || conditionsJson === '') {
        return true; // No conditions = always valid
    }
    
    let pass = false;
    let logicType = 'any'; // Default
    
    try {
        const parsed = (typeof conditionsJson === 'string') ? JSON.parse(conditionsJson) : conditionsJson;
        const conds = Array.isArray(parsed) ? parsed : (parsed.conditions || []);
        logicType = parsed.logic_type || 'any';
        
        if (!Array.isArray(conds) || conds.length === 0) {
            pass = true;
        } else {
            pass = logicType === 'all' ? true : false;
            for (let cond of conds) {
                const field = (cond.field || '').trim();
                const operator = cond.operator;
                const value = (cond.value || '').toString().trim();
                
                if (!field || !operator) continue;
                
                const inputs = $(`input[name^="answer[${field}]"], select[name^="answer[${field}]"], textarea[name^="answer[${field}]"]`);
                let formValue = [];
                inputs.each(function() {
                    if ($(this).is(':checkbox,:radio')) {
                        if ($(this).is(':checked')) formValue.push($(this).val().trim());
                    } else if ($(this).val()) {
                        formValue.push($(this).val().trim());
                    }
                });
                
                if (formValue.length === 0) {
                    if (logicType === 'all') {
                        pass = false;
                        break;
                    }
                    continue;
                }
                
                let match = false;
                const expected = value.toLowerCase();
                const formValuesLower = formValue.map(v => v.toLowerCase());
                
                switch (operator) {
                    case 'is':
                        match = formValuesLower.some(v => v === expected);
                        break;
                    case 'is_not':
                        match = formValuesLower.every(v => v !== expected);
                        break;
                    case 'contains':
                        match = formValuesLower.some(v => v.includes(expected));
                        break;
                    case 'not_contains':
                        match = formValuesLower.every(v => !v.includes(expected));
                        break;
                    case 'greater':
                        match = formValue.some(v => parseFloat(v) > parseFloat(value));
                        break;
                    case 'less':
                        match = formValue.some(v => parseFloat(v) < parseFloat(value));
                        break;
                }
                
                if (logicType === 'all') {
                    if (!match) {
                        pass = false;
                        break;
                    }
                } else {
                    if (match) {
                        pass = true;
                        break;
                    }
                }
            }
        }
    } catch (e) {
        console.error('Error evaluating page conditions:', e);
        return false;
    }
    
    return pass;
}

// Enhanced function to update navigation buttons
function updateNavigationButtons() {
    const currentPage = steps.eq(currentStep);
    const buttonsContainer = currentPage.find('.d-flex.justify-content-between');
    
    // Clear existing buttons
    buttonsContainer.empty();
    
    // Add Previous button if not on first step
    if (currentStep > 0) {
        buttonsContainer.append('<button type="button" class="btn btn-secondary prev-btn">Sebelumnya</button>');
    }
    
    // Determine if we should show Next or Submit
    const hasNextValidPages = hasValidNextPages(currentStep);
    const isActualLastPage = currentStep === steps.length - 1;
    
    if (hasNextValidPages && !isActualLastPage) {
        // Show Next button - there are valid pages ahead
        buttonsContainer.append('<button type="button" class="btn btn-primary next-btn">Selanjutnya</button>');
        console.log(`[DEBUG] Showing Next button - valid pages exist after index ${currentStep}`);
    } else {
        // Show Submit button - this is a logical endpoint
        buttonsContainer.append('<button type="submit" class="btn btn-success submit-btn">Simpan Jawaban</button>');
        console.log(`[DEBUG] Showing Submit button - no valid next pages after index ${currentStep}`);
    }
}

// Function to evaluate conditions (OR logic: show if ANY condition is met)
function evaluateConditions(element) {
    const $el = $(element);
    const conditionsJson = $el.data('conditions');
    const elementType = $el.hasClass('section-container') ? 'section' : $el.hasClass('question-container') ? 'question' : 'page';

    console.log(`[DEBUG] Mengevaluasi ${elementType} dengan kondisi mentah:`, conditionsJson);

    if (!conditionsJson || conditionsJson === '[]' || conditionsJson === '') {
        console.log(`[DEBUG] ${elementType} tidak memiliki kondisi, ditampilkan secara default`);
        $el.show();
        // Rekursif evaluasi child elements
        $el.find('.section-container, .question-container').each(function() {
            evaluateConditions(this);
        });
        return true;
    }

    let pass = false;
    let logicType = 'any'; // Default di luar try untuk menghindari undefined

    try {
        const parsed = (typeof conditionsJson === 'string') ? JSON.parse(conditionsJson) : conditionsJson;
        const conds = Array.isArray(parsed) ? parsed : (parsed.conditions || []);
        logicType = parsed.logic_type || 'any'; // Override jika ada
        console.log(`[DEBUG] Kondisi yang diuraikan untuk ${elementType}:`, conds, `Tipe logika: ${logicType}`);

        if (!Array.isArray(conds) || conds.length === 0) {
            console.warn(`[DEBUG] Kondisi tidak valid atau kosong untuk ${elementType}, ditampilkan secara default`);
            pass = true;
        } else {
            pass = logicType === 'all' ? true : false;
            for (let cond of conds) {
                const field = (cond.field || '').trim();
                const operator = cond.operator;
                const value = (cond.value || '').toString().trim();

                if (!field || !operator) {
                    console.warn(`[DEBUG] Melewati kondisi tidak valid di ${elementType}: field=${field}, operator=${operator}`);
                    continue;
                }

                const inputs = $(`input[name^="answer[${field}]"], select[name^="answer[${field}]"], textarea[name^="answer[${field}]"]`);
                let formValue = [];
                inputs.each(function() {
                    if ($(this).is(':checkbox,:radio')) {
                        if ($(this).is(':checked')) formValue.push($(this).val().trim());
                    } else if ($(this).val()) {
                        formValue.push($(this).val().trim());
                    }
                });

                if (formValue.length === 0) {
                    console.warn(`[DEBUG] Tidak ada jawaban ditemukan untuk field ${field} di ${elementType}`);
                    if (logicType === 'all') {
                        pass = false;
                        break;
                    }
                    continue;
                }

                console.log(`[DEBUG] Jawaban untuk field ${field}:`, formValue);

                let match = false;
                const expected = value.toLowerCase();
                const formValuesLower = formValue.map(v => v.toLowerCase());

                switch (operator) {
                    case 'is':
                        match = formValuesLower.some(v => v === expected);
                        break;
                    case 'is_not':
                        match = formValuesLower.every(v => v !== expected);
                        break;
                    case 'contains':
                        match = formValuesLower.some(v => v.includes(expected));
                        break;
                    case 'not_contains':
                        match = formValuesLower.every(v => !v.includes(expected));
                        break;
                    case 'greater':
                        match = formValue.some(v => parseFloat(v) > parseFloat(value));
                        break;
                    case 'less':
                        match = formValue.some(v => parseFloat(v) < parseFloat(value));
                        break;
                    default:
                        console.warn(`[DEBUG] Operator tidak dikenal ${operator} untuk field ${field} di ${elementType}`);
                }

                console.log(`[DEBUG] Hasil kondisi untuk field ${field}: operator=${operator}, expected=${value}, match=${match}`);

                if (logicType === 'all') {
                    if (!match) {
                        pass = false;
                        break;
                    }
                } else {
                    if (match) {
                        pass = true;
                        break;
                    }
                }
            }
        }
    } catch (e) {
        console.error(`[ERROR] Gagal menguraikan JSON untuk kondisi ${elementType}:`, e, 'JSON mentah:', conditionsJson);
        pass = false; // Default hide jika error
        logicType = 'error'; // Set untuk log
    }

    // Console.log aman sekarang karena logicType selalu defined
    if (pass) {
        console.log(`[DEBUG] ${elementType} lulus (logika ${logicType}), ditampilkan`);
        $el.show();
        // Rekursif evaluasi child elements
        $el.find('.section-container, .question-container').each(function() {
            evaluateConditions(this);
        });
    } else {
        console.log(`[DEBUG] ${elementType} gagal (kondisi tidak terpenuhi, logika ${logicType}), disembunyikan`);
        $el.hide();
        $el.find('.section-container, .question-container').hide();
    }

    return pass;
}

// Enhanced function to show step (page) with dynamic button updates
function showStep(index) {
    steps.removeClass('active').hide(); // Hide all pages
    const step = steps.eq(index);
    const passed = evaluateConditions(step[0]); // Evaluate page conditions

    if (passed) {
        step.addClass('active').show();
        console.log(`[DEBUG] Showing valid page at index ${index}`);
        
        // Update navigation buttons based on current state
        updateNavigationButtons();
    } else {
        console.warn(`[DEBUG] Page at index ${index} failed conditions, not showing`);
        return false;
    }

    const progress = ((index + 1) / steps.length) * 100;
    $("#progress-bar").css("width", progress + "%").attr("aria-valuenow", progress).text(Math.round(progress) + "%");

    $('.container').scrollTop(0); // Reset scroll to top
    return true;
}

// Enhanced form validation that ignores hidden required fields
function validateCurrentPage() {
    let isValid = true;
    const currentPage = steps.eq(currentStep);
    
    // Only validate VISIBLE required fields
    const visibleRequiredInputs = currentPage.find("input[required], select[required], textarea[required]").filter(":visible");
    
    console.log(`[DEBUG] Validating ${visibleRequiredInputs.length} visible required fields on current page`);
    
    visibleRequiredInputs.each(function() {
        const $input = $(this);
        let fieldValid = true;
        
        // Custom validation logic based on input type
        if ($input.is('[type="radio"]')) {
            // For radio buttons, check if any in the group is selected
            const name = $input.attr('name');
            const radioGroup = currentPage.find(`input[name="${name}"]:visible`);
            fieldValid = radioGroup.is(':checked');
        } else if ($input.is('[type="checkbox"]') && $input.attr('name').endsWith('[]')) {
            // For checkbox groups, check if at least one is selected
            const baseName = $input.attr('name').replace('[]', '');
            const checkboxGroup = currentPage.find(`input[name="${baseName}[]"]:visible`);
            fieldValid = checkboxGroup.is(':checked');
        } else {
            // For other inputs, check if they have a value
            fieldValid = $input.val() && $input.val().trim() !== '';
        }
        
        if (!fieldValid) {
            isValid = false;
            $input.addClass("is-invalid");
            console.log(`[DEBUG] Field validation failed:`, $input.attr('name'));
        } else {
            $input.removeClass("is-invalid");
        }
    });
    
    return isValid;
}

let saveTimer;

// Event handler for answer changes - re-evaluate current page and update buttons
    $(document).on('change input keyup click', 'input[name^="answer["], select[name^="answer["], textarea[name^="answer["]', function() {
        console.log('[DEBUG] Answer changed, re-evaluating current page elements and buttons');
        steps.hide(); // Hide all other pages
        const currentPage = steps.eq(currentStep);
        evaluateConditions(currentPage[0]); // Re-evaluate current page only
        updateNavigationButtons();
        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveDraft, 1000); // Update buttons based on new state
    });
    
    function saveDraft() {
        const formData = $('#questionnaire-form').serializeArray();
        const postData = {
            q_id: $('[name="q_id"]').val(),
            is_logically_complete: '0'  // Secara eksplisit tandai sebagai draft (bukan selesai)
        };

        formData.forEach(item => {
            if (item.name.startsWith('answer[')) {
                postData[item.name] = item.value;  // Biarkan CI menangani parsing array untuk checkbox, dll.
            }
        });

        $.ajax({
            url: "<?= base_url('alumni/questionnaires/save-answer') ?>",
            type: 'POST',
            data: postData,
            success: function(response) {
                console.log('[DEBUG] Draft berhasil disimpan');
            },
            error: function(xhr, status, error) {
                console.error('[ERROR] Gagal menyimpan draft:', error);
            }
        });
    }

// Navigation: Next button click
$(document).on("click", ".next-btn", function() {
    if (!validateCurrentPage()) {
        alert("Harap lengkapi semua pertanyaan wajib yang terlihat");
        return;
    }

    let nextIndex = currentStep + 1;
    while (nextIndex < steps.length) {
        if (showStep(nextIndex)) {
            currentStep = nextIndex;
            return;
        }
        console.warn(`[DEBUG] Skipping invalid next page at ${nextIndex}`);
        nextIndex++;
    }

    console.log('[DEBUG] No more valid pages found');
    alert("Tidak ada halaman selanjutnya yang valid. Sistem akan menampilkan tombol simpan.");
    updateNavigationButtons(); // Force button update
});

// Navigation: Previous button click
$(document).on("click", ".prev-btn", function() {
    let prevIndex = currentStep - 1;
    while (prevIndex >= 0) {
        if (showStep(prevIndex)) {
            currentStep = prevIndex;
            return;
        }
        console.warn(`[DEBUG] Skipping invalid previous page at ${prevIndex}`);
        prevIndex--;
    }
    
    console.log('[DEBUG] No valid previous pages found');
});

// Enhanced form submission handler
function isLogicallyComplete(currentPageIndex) {
    // Cek apakah tidak ada halaman valid setelah halaman saat ini
    for (let i = currentPageIndex + 1; i < steps.length; i++) {
        if (wouldPageBeValid(steps[i])) {
            return false; // Masih ada halaman valid
        }
    }
    return true; // Tidak ada halaman valid lagi = logically complete
}

// Enhanced submit handler dengan logical completion
$(document).on("click", ".submit-btn", function(e) {
    e.preventDefault();
    
    console.log('[DEBUG] Submit button clicked, performing final validation');
    
    if (!validateCurrentPage()) {
        alert("Harap lengkapi semua pertanyaan wajib sebelum menyimpan");
        return;
    }
    
    // Tentukan apakah ini logical completion
    const isComplete = isLogicallyComplete(currentStep);
    console.log(`[DEBUG] Logical completion status: ${isComplete}`);
    
    // Tambahkan hidden input untuk memberitahu backend tentang completion status
    const completionInput = $('<input>').attr({
        type: 'hidden',
        name: 'is_logically_complete',
        value: isComplete ? '1' : '0'
    });
    
    const currentPageInput = $('<input>').attr({
        type: 'hidden', 
        name: 'logical_end_page',
        value: currentStep
    });
    
    $("#questionnaire-form").append(completionInput).append(currentPageInput);
    
    // Remove required attribute from hidden fields
    const hiddenRequired = $("input[required], select[required], textarea[required]").filter(":hidden");
    hiddenRequired.each(function() {
        $(this).removeAttr('required').attr('data-was-required', 'true');
    });
    
    console.log('[DEBUG] Submitting questionnaire with logical completion data');
    $("#questionnaire-form")[0].submit();
});

// Handle regular form submission (fallback)
$(document).on('submit', '#questionnaire-form', function(e) {
    console.log('[DEBUG] Form submit triggered');
    
    let isValid = true;
    const visibleRequired = $(this).find('input[required], select[required]').filter(':visible');
    visibleRequired.each(function() {
        if (!this.checkValidity()) {
            isValid = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Harap lengkapi semua pertanyaan wajib yang terlihat');
        return false;
    }

    // Ignore hidden required for browser validation
    const hiddenRequired = $(this).find('input[required], select[required]').filter(':hidden');
    hiddenRequired.removeAttr('required');

    console.log('[DEBUG] Form valid, submitting to server');
    // Form will submit normally
});

$(document).on('change', '[data-conditions]', function() {
    const allRequired = $('#questionnaire-form').find('input[data-was-required], select[data-was-required]');
    allRequired.each(function() {
        if ($(this).is(':visible')) {
            $(this).attr('required', 'required').removeAttr('data-was-required');
        }
    });
});

// Initial page load
$(document).ready(function() {
    console.log('[DEBUG] Document ready, initializing questionnaire');
    steps.removeClass('active').hide();
    
    let startIndex = 0;
    while (startIndex < steps.length) {
        if (showStep(startIndex)) {
            currentStep = startIndex;
            console.log(`[DEBUG] Started questionnaire at page index ${startIndex}`);
            break;
        }
        startIndex++;
    }
    
    if (startIndex === steps.length) {
        alert("Tidak ada halaman yang memenuhi kondisi awal. Silakan kembali ke daftar kuesioner.");
        console.error('[ERROR] No valid initial pages found');
        return;
    }
    
    // Tambahan: Trigger re-evaluate pada semua fields yang sudah diisi (dari previous_answers) untuk memastikan sections/questions muncul jika kondisi met awalnya
    $('input[name^="answer["], select[name^="answer["], textarea[name^="answer["]').each(function() {
        if ($(this).val().trim() !== '') {
            $(this).trigger('change');
        }
    });
    console.log('[DEBUG] Initial re-evaluation triggered for pre-filled fields');
});

// Scale value update function (unchanged)
function updateScaleValue(qId) {
    const slider = document.getElementById('scale-' + qId);
    const badge = document.getElementById('scale-value-' + qId);
    if (slider && badge) {
        badge.textContent = slider.value;
        badge.className = 'badge bg-primary';
    }
}
</script>
</body>

</html>
<?php


$isRequired ??= false;           // Atasan gak wajib isi data alumni
$isReadonly ??= false;           // Atasan BOLEH EDIT kalau data alumni salah
$isAtasanMode ??= true;

// Biar view alumni gak error saat dipakai atasan
$user_profile           ??= $alumni_profile ?? [];
$user_profile_display   ??= $alumni_profile_display ?? [];
$field_friendly_names   ??= [];
$field_types            ??= [];
$jurusan_options        ??= [];
$prodi_options          ??= [];
$provinsi_options       ??= [];
$cities_options         ??= [];
$progress               ??= 0;
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Isi Kuesioner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            --card-hover-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            height: 100vh;
            overflow-y: auto;
            padding: 2rem 0;
        }

        .content-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styling */
        .questionnaire-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            border-left: 4px solid var(--primary-color);
        }

        .questionnaire-header h3 {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
            font-size: 1.75rem;
        }

        /* Progress Bar Styling */
        .progress-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .progress {
            height: 12px;
            border-radius: 10px;
            background: #e9ecef;
            overflow: visible;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary-color) 0%, #357abd 100%);
            border-radius: 10px;
            transition: width 0.6s ease;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(74, 144, 226, 0.3);
        }

        /* Page Card Styling */
        .page-step {
            display: none;
        }

        .page-step.active {
            display: block;
            animation: fadeInUp 0.4s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
            background: white;
        }

        .card:hover {
            box-shadow: var(--card-hover-shadow);
        }

        .card-header {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            border: none;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.5rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Section Styling */
        .section-container {
            background: var(--light-bg);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 3px solid var(--primary-color);
        }

        .section-container h6 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        .section-container > p {
            color: #6c757d;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        /* Question Container */
        .question-container {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
            border: 1px solid #e9ecef;
            transition: border-color 0.3s ease;
        }

        .question-container:hover {
            border-color: var(--primary-color);
        }

        /* Form Labels */
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            display: block;
            font-size: 1rem;
        }

        .text-danger {
            color: var(--danger-color);
            font-weight: bold;
        }

        /* Input Styling */
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        /* Radio & Checkbox Styling */
        .form-check {
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        .form-check:hover {
            background: var(--light-bg);
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-top: 0.125rem;
            border: 2px solid #d1d5db;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            margin-left: 0.5rem;
            cursor: pointer;
            color: #495057;
            font-size: 0.95rem;
        }

        /* Scale/Range Input */
        .form-range {
            height: 8px;
            border-radius: 10px;
        }

        .form-range::-webkit-slider-thumb {
            width: 20px;
            height: 20px;
            background: var(--primary-color);
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .form-range::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: var(--primary-color);
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .badge {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 6px;
        }

        /* Table Styling */
        .table {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .table thead th {
            background: var(--light-bg);
            color: #2c3e50;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        /* File Input */
        .form-control[type="file"] {
            padding: 0.5rem;
        }

        small.text-success, small.text-muted {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        /* Button Styling */
        .btn {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            transition: all 0.3s ease;
            text-transform: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #357abd 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.4);
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #218838 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }

        .btn-outline-secondary {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            background: white;
        }

        .btn-outline-secondary:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Navigation Buttons */
        .d-flex.justify-content-between {
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e9ecef;
        }

        /* Back Link */
        .back-link-container {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: var(--card-shadow);
        }

        /* Announcement Overlay */
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
            border-radius: 15px 15px 0 0;
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
            border-radius: 0 0 15px 15px;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 0 15px;
            }

            .questionnaire-header {
                padding: 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .section-container {
                padding: 1rem;
            }

            .question-container {
                padding: 1rem;
            }

            .btn {
                padding: 0.65rem 1.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="content-wrapper">
            <!-- Header -->
            <div class="questionnaire-header">
                <h3><?= esc($structure['questionnaire']['title']) ?></h3>
                <div class="alert alert-info">
                    <strong>Perhatian:</strong> Anda sedang mengisi <u>Penilaian Atasan</u> untuk alumni: 
                    <strong><?= esc($alumni_profile['nama_lengkap'] ?? 'Nama Tidak Diketahui') ?></strong> 
                    (NIM: <?= esc($alumni_profile['nim'] ?? '-') ?>)
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress">
                    <div class="progress-bar" id="progress-bar" style="width: <?= esc($progress) ?>%"
                        role="progressbar" aria-valuenow="<?= esc($progress) ?>" aria-valuemin="0" aria-valuemax="100">
                        <?= round($progress, 1) ?>%
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="questionnaire-form" 
                    method="post" 
                    action="<?= base_url('atasan/kuesioner/save-answer') ?>" 
                    enctype="multipart/form-data">

                 <?= csrf_field() ?>
               <input type="hidden" name="id_alumni_account" value="<?= esc($detail_alumni['id_account'] ?? '') ?>">
                <input type="hidden" name="q_id" value="<?= esc($q_id) ?>">

                <?php $pageIndex = 0; ?>
                <?php foreach ($structure['pages'] as $page): ?>
                    <div class="page-step <?= $pageIndex === 0 ? 'active' : '' ?>"
                        data-step="<?= $pageIndex ?>"
                        data-conditions="<?= htmlspecialchars($page['conditional_logic'] ?? '[]') ?>">
                        <div class="card">
                            <div class="card-header">
                                <h5><?= esc($page['page_title']) ?></h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($page['sections'] as $section): ?>
                                    <div class="section-container"
                                        data-conditions="<?= htmlspecialchars($section['conditional_logic'] ?? '[]') ?>">
                                        <?php if ($section['show_section_title']): ?>
                                            <h6><?= esc($section['section_title']) ?></h6>
                                        <?php endif; ?>
                                        <?php if ($section['show_section_description']): ?>
                                            <p><?= esc($section['section_description']) ?></p>
                                        <?php endif; ?>
                                        <?php foreach ($section['questions'] as $q): ?>
                                            <div class="question-container"
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
                                                <?php elseif (strtolower($q['question_type']) === 'date'): ?>
                                                    <input type="date" class="form-control" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>"
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

                                              <?php elseif (strtolower($q['question_type']) === 'user_field'): ?>
                                                    <?php
                                                    $fieldName = $q['user_field_name'] ?? '';
                                                    $friendlyLabel = $field_friendly_names[$fieldName] ?? ucwords(str_replace('_', ' ', $fieldName));
                                                    $fieldType = $field_types[$fieldName] ?? 'text';
                                                    $preValue = $user_profile[$fieldName] ?? '';
                                                    $displayValue = $user_profile_display[$fieldName . '_name'] ?? $user_profile_display[$fieldName] ?? $preValue;
                                                    ?>
                                                    <input type="<?= esc($fieldType) ?>" 
                                                        class="form-control" 
                                                        name="answer[<?= $q['id'] ?>]" 
                                                        value="<?= esc($displayValue) ?>" 
                                                        <?= $isReadonly ? 'readonly' : '' ?>
                                                        <?= $isRequired && !$isReadonly ? 'required' : '' ?>>
                                                    <?php if ($isReadonly): ?>
                                                        <small class="text-muted">Data ini bersumber dari profil alumni. Atasan dapat mengedit jika terdapat kesalahan.</small>
                                                    <?php endif; ?>
                                                <?php elseif (strtolower($q['question_type']) === 'scale' || strtolower($q['question_type']) === 'matrix_scale'): ?>
                                                    <div class="row align-items-center">
                                                        <div class="col-md-10">
                                                            <input type="range" class="form-range" id="scale-<?= $q['id'] ?>" name="answer[<?= $q['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                                min="<?= $q['scale_min'] ?? 1 ?>" max="<?= $q['scale_max'] ?? 10 ?>"
                                                                step="<?= $q['scale_step'] ?? 1 ?>"
                                                                value="<?= esc($existing_answer ?: ($q['scale_min'] ?? 1)) ?>"
                                                                <?= $q['is_required'] ? 'required' : '' ?>
                                                                oninput="updateScaleValue(<?= $q['id'] ?>)">
                                                        </div>
                                                        <div class="col-md-2 text-center">
                                                            <span id="scale-value-<?= $q['id'] ?>" class="badge bg-primary">
                                                                <?= esc($existing_answer ?: ($q['scale_min'] ?? 1)) ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php elseif (strtolower($q['question_type']) === 'matrix'): ?>
                                                    <div class="table-responsive">
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
                                                                        <td><strong><?= esc($row['row_text']) ?></strong></td>
                                                                        <?php foreach ($q['matrix_columns'] as $col): ?>
                                                                            <td class="text-center">
                                                                                <input type="radio" name="answer[<?= $q['id'] ?>][<?= $row['id'] ?>]" data-qid="<?= $q['id'] ?>"
                                                                                    value="<?= esc($col['column_text']) ?>"
                                                                                    id="matrix-<?= $q['id'] ?>-<?= $row['id'] ?>-<?= $col['id'] ?>"
                                                                                    <?= in_array($col['column_text'], (array)($existing_answers[$row['id']] ?? [])) ? 'checked' : '' ?>
                                                                                    <?= $q['is_required'] ? 'required' : '' ?>
                                                                                    class="form-check-input">
                                                                            </td>
                                                                        <?php endforeach; ?>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
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

                                <div class="d-flex justify-content-between">
                                    <?php if ($pageIndex > 0): ?>
                                        <button type="button" class="btn btn-secondary prev-btn">
                                            <svg width="16" height="16" fill="currentColor" style="margin-right: 5px; vertical-align: middle;">
                                                <path d="M11 1L4 8l7 7" stroke="currentColor" stroke-width="2" fill="none"/>
                                            </svg>
                                            Sebelumnya
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($pageIndex < count($structure['pages']) - 1): ?>
                                        <button type="button" class="btn btn-primary next-btn ms-auto">
                                            Selanjutnya
                                            <svg width="16" height="16" fill="currentColor" style="margin-left: 5px; vertical-align: middle;">
                                                <path d="M5 1l7 7-7 7" stroke="currentColor" stroke-width="2" fill="none"/>
                                            </svg>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-success submit-btn ms-auto">
                                                <svg width="16" height="16" fill="currentColor" style="margin-right: 5px; vertical-align: middle;">
                                                    <path d="M13 3L5 11 2 8" stroke="currentColor" stroke-width="2" fill="none"/>
                                                </svg>
                                                Selesaikan penilaian 
                                            </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $pageIndex++; ?>
                <?php endforeach; ?>
            </form>

            <!-- Back Link -->
            <div class="back-link-container">
             <a href="<?= base_url("atasan/kuesioner/daftar-alumni/{$q_id}") ?>" class="btn btn-outline-secondary">
                    <svg width="16" height="16" fill="currentColor" style="margin-right: 5px; vertical-align: middle;">
                        <path d="M11 1L4 8l7 7" stroke="currentColor" stroke-width="2" fill="none"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Announcement Overlay -->
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            window.location.href = "<?= base_url('atasan/kuesioner/daftar_alumni/' . $q_id) ?>";
        }

        // Utility function to check if a page has any visible required fields
        function hasVisibleRequiredFields(pageElement) {
            const visibleRequired = $(pageElement).find("input[required], select[required], textarea[required]").filter(":visible");
            return visibleRequired.length > 0;
        }

        // Function to check if there are any valid next pages from current position
        function hasValidNextPages(fromIndex) {
            for (let i = fromIndex + 1; i < steps.length; i++) {
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
                return true;
            }
            
            let pass = false;
            let logicType = 'any';
            
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
            
            buttonsContainer.empty();
            
            if (currentStep > 0) {
                buttonsContainer.append(`
                    <button type="button" class="btn btn-secondary prev-btn">
                        <svg width="16" height="16" fill="currentColor" style="margin-right: 5px; vertical-align: middle;">
                            <path d="M11 1L4 8l7 7" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                        Sebelumnya
                    </button>
                `);
            }
            
            const hasNextValidPages = hasValidNextPages(currentStep);
            const isActualLastPage = currentStep === steps.length - 1;
            
            if (hasNextValidPages && !isActualLastPage) {
                buttonsContainer.append(`
                    <button type="button" class="btn btn-primary next-btn ms-auto">
                        Selanjutnya
                        <svg width="16" height="16" fill="currentColor" style="margin-left: 5px; vertical-align: middle;">
                            <path d="M5 1l7 7-7 7" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                    </button>
                `);
                console.log(`[DEBUG] Showing Next button - valid pages exist after index ${currentStep}`);
            } else {
                buttonsContainer.append(`
                    <button type="submit" class="btn btn-success submit-btn ms-auto">
                        <svg width="16" height="16" fill="currentColor" style="margin-right: 5px; vertical-align: middle;">
                            <path d="M13 3L5 11 2 8" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                        Simpan
                    </button>
                `);
                console.log(`[DEBUG] Showing Submit button - no valid next pages after index ${currentStep}`);
            }
        }

        // Function to evaluate conditions
        function evaluateConditions(element) {
            const $el = $(element);
            const conditionsJson = $el.data('conditions');
            const elementType = $el.hasClass('section-container') ? 'section' : $el.hasClass('question-container') ? 'question' : 'page';

            console.log(`[DEBUG] Mengevaluasi ${elementType} dengan kondisi mentah:`, conditionsJson);

            if (!conditionsJson || conditionsJson === '[]' || conditionsJson === '') {
                console.log(`[DEBUG] ${elementType} tidak memiliki kondisi, ditampilkan secara default`);
                $el.show();
                $el.find('.section-container, .question-container').each(function() {
                    evaluateConditions(this);
                });
                return true;
            }

            let pass = false;
            let logicType = 'any';

            try {
                const parsed = (typeof conditionsJson === 'string') ? JSON.parse(conditionsJson) : conditionsJson;
                const conds = Array.isArray(parsed) ? parsed : (parsed.conditions || []);
                logicType = parsed.logic_type || 'any';
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
                pass = false;
                logicType = 'error';
            }

            if (pass) {
                console.log(`[DEBUG] ${elementType} lulus (logika ${logicType}), ditampilkan`);
                $el.show();
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
            steps.removeClass('active').hide();
            const step = steps.eq(index);
            const passed = evaluateConditions(step[0]);

            if (passed) {
                step.addClass('active').show();
                console.log(`[DEBUG] Showing valid page at index ${index}`);
                updateNavigationButtons();
            } else {
                console.warn(`[DEBUG] Page at index ${index} failed conditions, not showing`);
                return false;
            }

            const progress = ((index + 1) / steps.length) * 100;
            $("#progress-bar").css("width", progress + "%").attr("aria-valuenow", progress).text(Math.round(progress) + "%");

            $('.main-container').scrollTop(0);
            return true;
        }

        // Enhanced form validation that ignores hidden required fields
        function validateCurrentPage() {
            let isValid = true;
            const currentPage = steps.eq(currentStep);
            
            const visibleRequiredInputs = currentPage.find("input[required], select[required], textarea[required]").filter(":visible");
            
            console.log(`[DEBUG] Validating ${visibleRequiredInputs.length} visible required fields on current page`);
            
            visibleRequiredInputs.each(function() {
                const $input = $(this);
                let fieldValid = true;
                
                if ($input.is('[type="radio"]')) {
                    const name = $input.attr('name');
                    const radioGroup = currentPage.find(`input[name="${name}"]:visible`);
                    fieldValid = radioGroup.is(':checked');
                } else if ($input.is('[type="checkbox"]') && $input.attr('name').endsWith('[]')) {
                    const baseName = $input.attr('name').replace('[]', '');
                    const checkboxGroup = currentPage.find(`input[name="${baseName}[]"]:visible`);
                    fieldValid = checkboxGroup.is(':checked');
                } else {
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

        $(document).on('change input keyup click', 'input[name^="answer["], select[name^="answer["], textarea[name^="answer["]', function() {
            console.log('[DEBUG] Answer changed, re-evaluating current page elements and buttons');
            steps.hide();
            const currentPage = steps.eq(currentStep);
            evaluateConditions(currentPage[0]);
            updateNavigationButtons();
            clearTimeout(saveTimer);
            saveTimer = setTimeout(saveDraft, 1000);
        });
        
        function saveDraft() {
            const formData = new FormData($('#questionnaire-form')[0]);

            $.ajax({
                url: "<?= base_url('atasan/kuesioner/save-answer') ?>",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success && !res.completed) {
                       console.log("auto save succsess");// "Draft tersimpan otomatis"
                    }
                },
                error: function() {
                    console.error('Gagal autosave');
                }
            });
        }

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
            updateNavigationButtons();
        });

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

        function isLogicallyComplete(currentPageIndex) {
            for (let i = currentPageIndex + 1; i < steps.length; i++) {
                if (wouldPageBeValid(steps[i])) {
                    return false;
                }
            }
            return true;
        }

      $(document).on("click", ".submit-btn", function(e) {
            e.preventDefault();

            // Validasi halaman saat ini
            if (!validateCurrentPage()) {
                Swal.fire('Peringatan', 'Harap lengkapi semua pertanyaan wajib di halaman ini!', 'warning');
                return;
            }

            // Konfirmasi dulu biar user yakin
            Swal.fire({
                title: 'Yakin selesai?',
                text: "Setelah disimpan, kuesioner tidak bisa diubah lagi!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) return;

                const formData = new FormData($('#questionnaire-form')[0]);
                formData.set('is_logically_complete', '1'); // INI WAJIB!

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Tunggu sebentar ya...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "<?= base_url('atasan/kuesioner/save-answer') ?>",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success && res.completed === true) {
                            if (res.announcement) {
                                // Ganti seluruh halaman dengan announcement
                                $('body').html(res.announcement);
                            } else if (res.redirect) {
                                // Kalau gak ada announcement → pake redirect lama
                                window.location.href = res.redirect;
                            }
                        } else {
                            Swal.fire('Info', res.message || 'Draft tersimpan', 'info');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menyimpan. Coba lagi atau hubungi admin.', 'error');
                    }
                });
            });
        });

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

            const hiddenRequired = $(this).find('input[required], select[required]').filter(':hidden');
            hiddenRequired.removeAttr('required');

            console.log('[DEBUG] Form valid, submitting to server');
        });

        $(document).on('change', '[data-conditions]', function() {
            const allRequired = $('#questionnaire-form').find('input[data-was-required], select[data-was-required]');
            allRequired.each(function() {
                if ($(this).is(':visible')) {
                    $(this).attr('required', 'required').removeAttr('data-was-required');
                }
            });
        });
        $(document).ready(function() {
            const path = window.location.pathname;
            const isLanjutkan = path.includes('/lanjutkan/');

            steps.removeClass('active').hide();

            let targetStep = 0;

            if (isLanjutkan) {
                // Cari halaman terakhir yang ADA jawaban (bukan user_field)
                let highest = -1;
                steps.each(function(index) {
                    const hasRealAnswer = $(this).find('input[name^="answer["], select[name^="answer["], textarea[name^="answer["]').filter(function() {
                        const val = $(this).val();
                        const name = $(this).attr('name');
                        // Abaikan user_field (karena otomatis terisi)
                        if (name && name.includes('[user_field_')) return false;
                        return val && val !== '' && val !== '[]' && val !== null;
                    }).length > 0;

                    if (hasRealAnswer) {
                        highest = index;
                    }
                });
                if (highest >= 0) targetStep = highest;
            }
            // Kalau bukan lanjutkan → mulai dari 0 (halaman 1)

            currentStep = targetStep;
            showStep(currentStep);
            
            // Trigger change supaya conditional logic jalan
            $('input[name^="answer["], select[name^="answer["], textarea[name^="answer["]').trigger('change');
            
            updateNavigationButtons();
            updateProgressBar();
        });
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
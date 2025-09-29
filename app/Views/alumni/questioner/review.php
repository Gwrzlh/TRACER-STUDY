<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Jawaban</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('css/alumni/kuesioner/review.css') ?>">
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="review-header">
            <h3 class="review-title">Review Jawaban: <?= esc($structure['questionnaire']['title']) ?></h3>
            <div class="progress-container">
                <div class="progress">
                    <div class="progress-bar" 
                         style="width: <?= esc($progress) ?>%" 
                         role="progressbar"
                         aria-valuenow="<?= esc($progress) ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <?= round($progress, 1) ?>%
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Content -->
        <div class="card">
            <?php foreach ($structure['pages'] as $page): ?>
                <div class="card-header">
                    <h5><?= esc($page['page_title']) ?></h5>
                </div>
                <div class="card-body">
                    <?php foreach ($page['sections'] as $section): ?>
                        <?php if ($section['show_section_title']): ?>
                            <h6><?= esc($section['section_title']) ?></h6>
                        <?php endif; ?>
                        
                        <?php foreach ($section['questions'] as $q): ?>
                            <div class="mb-3">
                                <label class="form-label">
                                    <?= esc($q['question_text']) ?>
                                    <?= $q['is_required'] ? ' <span class="text-danger">*</span>' : '' ?>
                                </label>
                                
                                <?php
                                $answer  = $previous_answers['q_' . $q['id']] ?? '';
                                $answers = is_array(json_decode($answer, true)) ? json_decode($answer, true) : [$answer];
                                ?>
                                
                                <?php if (in_array(strtolower($q['question_type']), ['text', 'email'])): ?>
                                    <p class="form-control-static"><?= esc($answer ?: 'Belum dijawab') ?></p>
                                
                                <?php elseif (in_array(strtolower($q['question_type']), ['text','user_field'])): ?>
                                    <p class="form-control-static"><?= esc($answer ?: 'Belum dijawab') ?></p>
                                
                                <?php elseif (in_array(strtolower($q['question_type']), ['text', 'number'])): ?>
                                    <p class="form-control-static"><?= esc($answer ?: 'Belum dijawab') ?></p>
                                
                                <?php elseif (in_array(strtolower($q['question_type']), ['dropdown', 'select', 'radio'])): ?>
                                    <p class="form-control-static"><?= esc($answer ?: 'Belum dijawab') ?></p>
                                
                                <?php elseif (strtolower($q['question_type']) === 'checkbox'): ?>
                                    <p class="form-control-static"><?= esc(implode(', ', $answers) ?: 'Belum dijawab') ?></p>
                                
                                <?php elseif (in_array(strtolower($q['question_type']), ['scale', 'matrix_scale'])): ?>
                                    <p class="form-control-static">
                                        <?= esc($answer ?: 'Belum dijawab') ?> 
                                        (Skala: <?= esc($q['scale_min'] ?? 1) ?> - <?= esc($q['scale_max'] ?? 10) ?>)
                                    </p>
                                
                                <?php elseif (strtolower($q['question_type']) === 'matrix'): ?>
                                    <?php if (empty($q['matrix_rows']) || empty($q['matrix_columns'])): ?>
                                        <div class="text-danger">
                                            Data baris/kolom tidak tersedia untuk pertanyaan ini (ID: <?= $q['id'] ?>).
                                        </div>
                                    <?php else: ?>
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
                                                                <?php $row_answer = $answers[$row['id']] ?? ''; ?>
                                                                <?= $row_answer === $col['column_text'] ? '<span class="badge bg-success">✓</span>' : '' ?>
                                                            </td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                
                                <?php elseif (strtolower($q['question_type']) === 'file'): ?>
                                    <p class="form-control-static">
                                        <?php if ($answer && strpos($answer, 'uploaded_file:') === 0): ?>
                                            <a href="<?= base_url(str_replace('uploaded_file:', 'uploads/answers/', $answer)) ?>" target="_blank">
                                                Lihat file: <?= esc(basename($answer)) ?>
                                            </a>
                                        <?php else: ?>
                                            Belum ada file diunggah
                                        <?php endif; ?>
                                    </p>
                                
                                <?php else: ?>
                                    <div class="text-danger">
                                        Jenis pertanyaan tidak dikenali: <?= esc($q['question_type']) ?> (ID: <?= $q['id'] ?>)
                                    </div>
                                
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Back Button -->
        <div class="back-button-container">
            <a href="<?= base_url('alumni/questionnaires') ?>" class="btn btn-secondary">
                Kembali ke Daftar Kuesioner
            </a>
        </div>
    </div>
</body>

</html>
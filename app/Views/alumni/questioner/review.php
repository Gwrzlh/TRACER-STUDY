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
                                  
                                <?php elseif (in_array(strtolower($q['question_type']), ['date', 'date'])): ?>
                                    <p class="form-control-static"><?= esc($answer ?: 'Belum dijawab') ?></p>

                                <?php elseif (in_array(strtolower($q['question_type']), ['date', 'date'])): ?>
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
                                                <?php 
                                                // FIXED: Sanitasi - ambil filename
                                                $cleanPath = str_replace('uploaded_file:', '', $answer);
                                                $filename = basename($cleanPath);
                                                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                                
                                                // Direct URL: /uploads/answers/filename.ext (karena di public/)
                                                $fileUrl = base_url('uploads/answers/' . $filename);
                                                
                                                // Detect: Gambar → preview inline; Lainnya → download link
                                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);  // Tambah webp jika perlu
                                                ?>
                                                <?php if ($isImage): ?>
                                                    <!-- Preview Gambar + Download Button -->
                                                    <div class="file-preview d-flex align-items-center gap-2">
                                                        <img src="<?= $fileUrl ?>" alt="<?= esc($filename) ?>" 
                                                            style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;" 
                                                            class="img-thumbnail" 
                                                            onerror="this.style.display='none'; this.parentNode.querySelector('.download-btn').style.display='block';">
                                                        <div class="ms-2">
                                                            <a href="<?= $fileUrl ?>" download="<?= $filename ?>" class="btn btn-sm btn-outline-primary download-btn" title="Download <?= $filename ?>">
                                                                <i class="fas fa-download"></i> Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <!-- Non-Gambar: Download Link -->
                                                    <a href="<?= $fileUrl ?>" download="<?= $filename ?>" class="btn btn-sm btn-secondary" title="Download <?= $filename ?>">
                                                        <i class="fas fa-download"></i> Download <?= esc($filename) ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Belum ada file diunggah</span>
                                            <?php endif; ?>
                                        </p>
                                    <?php else: ?>
                                    <div class="text-danger">Jenis pertanyaan tidak dikenali: <?= esc($q['question_type']) ?> (ID: <?= $q['id'] ?>)</div>
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Penilaian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('css/atasan/kuesioner/review.css') ?>">
</head>
<body>
    <div class="container">
        <!-- Review Content seperti alumni, gunakan $previous_answers dan $user_profile untuk user_field -->
        <div class="card">
            <?php foreach ($structure['pages'] as $page): ?>
                <div class="card-header">
                    <h5><?= esc($page['title']) ?></h5>
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
                                </label>
                                
                                <?php
                                $answer = $previous_answers[$q['id']] ?? '';
                                $answers = is_array(json_decode($answer, true)) ? json_decode($answer, true) : [$answer];
                                ?>
                                
                                <?php if (strtolower($q['question_type']) === 'user_field'): ?>
                                    <?php
                                    $fieldName = $q['user_field_name'] ?? '';
                                    $preValue = $user_profile[$fieldName] ?? '';
                                    $displayValue = $user_profile_display[$fieldName . '_name'] ?? $user_profile_display[$fieldName] ?? $preValue;
                                    ?>
                                    <p class="form-control-static"><?= esc($displayValue ?: 'Tidak tersedia') ?></p>
                                <?php else: ?>
                                    <!-- Tampil answer seperti alumni review.php untuk type lain -->
                                    <p class="form-control-static"><?= esc($answer ?: 'Belum dijawab') ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="back-button-container">
            <a href="<?= base_url('atasan/kuesioner/daftar-alumni/' . $q_id) ?>" class="btn btn-secondary">
                Kembali ke Daftar Alumni
            </a>
        </div>
    </div>
</body>
</html>
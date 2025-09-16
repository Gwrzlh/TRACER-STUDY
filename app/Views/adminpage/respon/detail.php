<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Detail Jawaban Alumni</h3>
    <a href="<?= base_url('admin/respon') ?>" class="btn btn-secondary mb-3">← Kembali</a>

    <div class="card mb-3">
        <?php foreach ($structure['pages'] as $page): ?>
            <div class="card-header bg-light">
                <h5><?= esc($page['page_title'] ?? 'Halaman') ?></h5>
            </div>
            <div class="card-body">
                <?php if (!empty($page['sections'])): ?>
                    <?php foreach ($page['sections'] as $section): ?>
                        <?php if ($section['show_section_title']): ?>
                            <h6 class="mt-2"><?= esc($section['section_title']) ?></h6>
                        <?php endif; ?>

                        <?php if (!empty($section['questions'])): ?>
                            <?php foreach ($section['questions'] as $q): ?>
                                <div class="mb-3">
                                    <label class="form-label">
                                        <?= esc($q['question_text']) ?>
                                        <?= $q['is_required'] ? ' <span class="text-danger">*</span>' : '' ?>
                                    </label>
                                    <?php
                                    $key     = 'q_' . $q['id'];
                                    $answer  = $answers[$key] ?? '';
                                    $decoded = json_decode($answer, true);
                                    $answersArr = is_array($decoded) ? $decoded : (strlen($answer) ? [$answer] : []);
                                    ?>
                                    <?php if (in_array(strtolower($q['question_type']), ['text', 'email'])): ?>
                                        <p class="form-control-static"><?= esc($answer ?: 'Belum dijawab') ?></p>
                                    <?php elseif (in_array(strtolower($q['question_type']), ['dropdown', 'select', 'radio'])): ?>
                                        <p class="form-control-static"><?= esc($answer ?: 'Belum dijawab') ?></p>
                                    <?php elseif (strtolower($q['question_type']) === 'checkbox'): ?>
                                        <p class="form-control-static"><?= esc(implode(', ', $answersArr) ?: 'Belum dijawab') ?></p>
                                    <?php elseif (in_array(strtolower($q['question_type']), ['scale', 'matrix_scale'])): ?>
                                        <p class="form-control-static">
                                            <?= esc($answer ?: 'Belum dijawab') ?>
                                            (Skala: <?= esc($q['scale_min'] ?? 1) ?> - <?= esc($q['scale_max'] ?? 10) ?>)
                                        </p>
                                    <?php elseif (strtolower($q['question_type']) === 'matrix'): ?>
                                        <?php if (empty($q['matrix_rows']) || empty($q['matrix_columns'])): ?>
                                            <div class="text-danger">Data matrix tidak lengkap (ID: <?= $q['id'] ?>)</div>
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
                                                                    <?php
                                                                    $row_answer = $answersArr[$row['id']] ?? '';
                                                                    ?>
                                                                    <?= $row_answer === $col['column_text']
                                                                        ? '<span class="badge bg-success">✓</span>'
                                                                        : '' ?>
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
                                                <?php $file = str_replace('uploaded_file:', 'uploads/answers/', $answer); ?>
                                                <a href="<?= base_url($file) ?>" target="_blank">
                                                    Lihat file: <?= esc(basename($file)) ?>
                                                </a>
                                            <?php else: ?>
                                                Belum ada file diunggah
                                            <?php endif; ?>
                                        </p>
                                    <?php else: ?>
                                        <div class="text-danger">Jenis pertanyaan tidak dikenali: <?= esc($q['question_type']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
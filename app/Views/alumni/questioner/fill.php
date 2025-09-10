<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<div class="container mt-4">
    <h3><?= esc($structure['questionnaire']['title']) ?></h3>
    <div class="progress mb-3">
        <div class="progress-bar" style="width: <?= esc($progress) ?>%" role="progressbar" aria-valuenow="<?= esc($progress) ?>" aria-valuemin="0" aria-valuemax="100"><?= round($progress, 1) ?>%</div>
    </div>
    <form id="questionnaire-form" method="post" action="<?= base_url('alumni/questionnaires/save-answer') ?>" enctype="multipart/form-data">
        <input type="hidden" name="q_id" value="<?= esc($q_id) ?>">
        <?php foreach ($structure['pages'] as $page): ?>
            <div class="card mb-3">
                <div class="card-header"><h5><?= esc($page['page_title']) ?></h5></div>
                <div class="card-body">
                    <?php foreach ($page['sections'] as $section): ?>
                        <?php if ($section['show_section_title']): ?>
                            <h6><?= esc($section['section_title']) ?></h6>
                        <?php endif; ?>
                        <?php foreach ($section['questions'] as $q): ?>
                            <div class="mb-3" data-conditions="<?= htmlspecialchars($q['condition_json'] ?? '[]') ?>">
                                <label class="form-label"><?= esc($q['question_text']) ?><?= $q['is_required'] ? ' <span class="text-danger">*</span>' : '' ?></label>
                                <?php
                                // Debug: Log detail pertanyaan
                                log_message('debug', "[fill.php] Question ID: {$q['id']}, Text: {$q['question_text']}, Type: {$q['question_type']}, Options: " . print_r($q['options'] ?? [], true));
                                $options = $q['options'] ?? [];
                                $existing_answer = $previous_answers['q_' . $q['id']] ?? '';
                                $existing_answers = is_array(json_decode($existing_answer, true)) ? json_decode($existing_answer, true) : [$existing_answer];
                                ?>
                                <?php if (strtolower($q['question_type']) === 'text'): ?>
                                    <input type="text" class="form-control" name="answer[<?= $q['id'] ?>]" value="<?= esc($existing_answer) ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                <?php elseif (strtolower($q['question_type']) === 'email'): ?>
                                    <input type="email" class="form-control" name="answer[<?= $q['id'] ?>]" value="<?= esc($existing_answer) ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                <?php elseif (in_array(strtolower($q['question_type']), ['dropdown', 'select'])): ?>
                                    <?php if (empty($options)): ?>
                                        <div class="text-danger">Tidak ada opsi tersedia untuk pertanyaan ini (ID: <?= $q['id'] ?>, Type: <?= esc($q['question_type']) ?>). Silakan hubungi admin untuk memperbaiki opsi.</div>
                                        <select class="form-select" name="answer[<?= $q['id'] ?>]" disabled>
                                            <option value="">Opsi belum diatur</option>
                                        </select>
                                    <?php else: ?>
                                        <select class="form-select" name="answer[<?= $q['id'] ?>]" <?= $q['is_required'] ? 'required' : '' ?>>
                                            <option value="">Pilih...</option>
                                            <?php foreach ($options as $opt): ?>
                                                <option value="<?= esc($opt) ?>" <?= in_array($opt, $existing_answers) ? 'selected' : '' ?>><?= esc($opt) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                <?php elseif (strtolower($q['question_type']) === 'radio'): ?>
                                    <?php if (empty($options)): ?>
                                        <div class="text-danger">Tidak ada opsi tersedia untuk pertanyaan ini (ID: <?= $q['id'] ?>, Type: <?= esc($q['question_type']) ?>). Silakan hubungi admin untuk memperbaiki opsi.</div>
                                    <?php else: ?>
                                        <?php foreach ($options as $opt): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="answer[<?= $q['id'] ?>]" value="<?= esc($opt) ?>" id="radio-<?= $q['id'] ?>-<?= md5($opt) ?>" <?= in_array($opt, $existing_answers) ? 'checked' : '' ?> <?= $q['is_required'] ? 'required' : '' ?>>
                                                <label class="form-check-label" for="radio-<?= $q['id'] ?>-<?= md5($opt) ?>"><?= esc($opt) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php elseif (strtolower($q['question_type']) === 'checkbox'): ?>
                                    <?php if (empty($options)): ?>
                                        <div class="text-danger">Tidak ada opsi tersedia untuk pertanyaan ini (ID: <?= $q['id'] ?>, Type: <?= esc($q['question_type']) ?>). Silakan hubungi admin untuk memperbaiki opsi.</div>
                                    <?php else: ?>
                                        <?php foreach ($options as $opt): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="answer[<?= $q['id'] ?>][]" value="<?= esc($opt) ?>" id="check-<?= $q['id'] ?>-<?= md5($opt) ?>" <?= in_array($opt, $existing_answers) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="check-<?= $q['id'] ?>-<?= md5($opt) ?>"><?= esc($opt) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php elseif (in_array(strtolower($q['question_type']), ['scale', 'matrix_scale'])): ?>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="range" class="form-range" id="scale-<?= $q['id'] ?>" name="answer[<?= $q['id'] ?>]" min="<?= $q['scale_min'] ?? 1 ?>" max="<?= $q['scale_max'] ?? 10 ?>" step="<?= $q['scale_step'] ?? 1 ?>" value="<?= esc($existing_answer ?: ($q['scale_min'] ?? 1)) ?>" <?= $q['is_required'] ? 'required' : '' ?> oninput="updateScaleValue(<?= $q['id'] ?>)">
                                        </div>
                                        <div class="col-md-2">
                                            <span id="scale-value-<?= $q['id'] ?>" class="badge bg-secondary"><?= esc($existing_answer ?: ($q['scale_min'] ?? 1)) ?></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span><?= esc($q['scale_min_label'] ?? 'Sangat Tidak Setuju') ?></span>
                                        <span><?= esc($q['scale_max_label'] ?? 'Sangat Setuju') ?></span>
                                    </div>
                                <?php elseif (strtolower($q['question_type']) === 'matrix'): ?>
                                    <?php if (empty($q['matrix_rows']) || empty($q['matrix_columns'])): ?>
                                        <div class="text-danger">Data baris atau kolom tidak tersedia untuk pertanyaan ini (ID: <?= $q['id'] ?>).</div>
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
                                                                <input type="radio" name="answer[<?= $q['id'] ?>][<?= $row['id'] ?>]" value="<?= esc($col['column_text']) ?>" id="matrix-<?= $q['id'] ?>-<?= $row['id'] ?>-<?= $col['id'] ?>" <?= in_array($col['column_text'], $existing_answers[$row['id']] ?? []) ? 'checked' : '' ?> <?= $q['is_required'] ? 'required' : '' ?>>
                                                            </td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                <?php elseif (strtolower($q['question_type']) === 'file'): ?>
                                    <input type="file" class="form-control" name="answer_<?= $q['id'] ?>" <?= $q['is_required'] ? 'required' : '' ?> <?= $q['max_file_size'] ? 'data-max-size="' . $q['max_file_size'] . '"' : '' ?> accept="<?= $q['allowed_types'] ? esc($q['allowed_types']) : 'image/*,application/pdf' ?>">
                                    <?php if ($existing_answer): ?>
                                        <small class="text-success">File sebelumnya: <?= esc(basename($existing_answer)) ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="text-danger">Jenis pertanyaan tidak dikenali: <?= esc($q['question_type']) ?> (ID: <?= $q['id'] ?>)</div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
function updateScaleValue(qId) {
    const slider = document.getElementById('scale-' + qId);
    const valueSpan = document.getElementById('scale-value-' + qId);
    valueSpan.textContent = slider.value;
    valueSpan.className = 'badge bg-primary';
}
</script>
<?= $this->endSection() ?>
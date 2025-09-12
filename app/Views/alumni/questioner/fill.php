<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Isi Kuesioner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
                <div class="card mb-3 page-step" data-step="<?= $pageIndex ?>" style="<?= $pageIndex > 0 ? 'display:none;' : '' ?>">
                    <div class="card-header">
                        <h5><?= esc($page['page_title']) ?></h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($page['sections'] as $section): ?>
                            <?php if ($section['show_section_title']): ?>
                                <h6><?= esc($section['section_title']) ?></h6>
                            <?php endif; ?>
                            <?php foreach ($section['questions'] as $q): ?>
                                <div class="mb-3" data-conditions="<?= htmlspecialchars($q['condition_json'] ?? '[]') ?>">
                                    <label class="form-label">
                                        <?= esc($q['question_text']) ?><?= $q['is_required'] ? ' <span class="text-danger">*</span>' : '' ?>
                                    </label>
                                    <?php
                                    $options = $q['options'] ?? [];
                                    $existing_answer  = $previous_answers['q_' . $q['id']] ?? '';
                                    $existing_answers = is_array(json_decode($existing_answer, true)) ? json_decode($existing_answer, true) : [$existing_answer];
                                    ?>
                                    <?php if (strtolower($q['question_type']) === 'text'): ?>
                                        <input type="text" class="form-control" name="answer[<?= $q['id'] ?>]"
                                            value="<?= esc($existing_answer) ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                    <?php elseif (strtolower($q['question_type']) === 'email'): ?>
                                        <input type="email" class="form-control" name="answer[<?= $q['id'] ?>]"
                                            value="<?= esc($existing_answer) ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                    <?php elseif (in_array(strtolower($q['question_type']), ['dropdown', 'select'])): ?>
                                        <select class="form-select" name="answer[<?= $q['id'] ?>]" <?= $q['is_required'] ? 'required' : '' ?>>
                                            <option value="">Pilih...</option>
                                            <?php foreach ($options as $opt): ?>
                                                <option value="<?= esc($opt) ?>" <?= in_array($opt, $existing_answers) ? 'selected' : '' ?>><?= esc($opt) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php elseif (strtolower($q['question_type']) === 'radio'): ?>
                                        <?php foreach ($options as $opt): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="answer[<?= $q['id'] ?>]"
                                                    value="<?= esc($opt) ?>" id="radio-<?= $q['id'] ?>-<?= md5($opt) ?>"
                                                    <?= in_array($opt, $existing_answers) ? 'checked' : '' ?>
                                                    <?= $q['is_required'] ? 'required' : '' ?>>
                                                <label class="form-check-label" for="radio-<?= $q['id'] ?>-<?= md5($opt) ?>"><?= esc($opt) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php elseif (strtolower($q['question_type']) === 'checkbox'): ?>
                                        <?php foreach ($options as $opt): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="answer[<?= $q['id'] ?>][]"
                                                    value="<?= esc($opt) ?>" id="check-<?= $q['id'] ?>-<?= md5($opt) ?>"
                                                    <?= in_array($opt, $existing_answers) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="check-<?= $q['id'] ?>-<?= md5($opt) ?>"><?= esc($opt) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php elseif (in_array(strtolower($q['question_type']), ['scale', 'matrix_scale'])): ?>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <input type="range" class="form-range" id="scale-<?= $q['id'] ?>" name="answer[<?= $q['id'] ?>]"
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
                                                                <input type="radio" name="answer[<?= $q['id'] ?>][<?= $row['id'] ?>]"
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
                                        <input type="file" class="form-control" name="answer_<?= $q['id'] ?>" <?= $q['is_required'] ? 'required' : '' ?>>
                                        <?php if ($existing_answer): ?>
                                            <small class="text-success">File sebelumnya: <?= esc(basename($existing_answer)) ?></small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
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

    <script>
        let currentStep = 0;
        const steps = $(".page-step");

        function showStep(index) {
            steps.hide();
            $(steps[index]).show();
            let progress = ((index + 1) / steps.length) * 100;
            $("#progress-bar").css("width", progress + "%").attr("aria-valuenow", progress).text(Math.round(progress) + "%");
        }

        $(document).on("click", ".next-btn", function() {
            let currentForm = $(steps[currentStep]).find("input, select, textarea");
            let isValid = true;
            currentForm.removeClass("is-invalid");
            currentForm.each(function() {
                if (!this.checkValidity()) {
                    isValid = false;
                    $(this).addClass("is-invalid");
                }
            });
            if (!isValid) {
                alert("Harap lengkapi semua pertanyaan yang wajib diisi sebelum melanjutkan.");
                return;
            }
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });

        $(document).on("click", ".prev-btn", function() {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });

        showStep(currentStep);

        function updateScaleValue(qId) {
            const slider = document.getElementById('scale-' + qId);
            const valueSpan = document.getElementById('scale-value-' + qId);
            valueSpan.textContent = slider.value;
            valueSpan.className = 'badge bg-primary';
        }
    </script>
</body>

</html>
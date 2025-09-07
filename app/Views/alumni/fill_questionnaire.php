<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Isi Kuesioner</h4>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('alumni/questionnaire/submit') ?>">
                <input type="hidden" name="questionnaire_id" value="<?= esc($questionnaire_id) ?>">

                <?php if (empty($questions)): ?>
                    <p class="text-center">Belum ada pertanyaan pada kuesioner ini.</p>
                <?php else: ?>
                    <?php foreach ($questions as $index => $q): ?>
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <?= ($index + 1) . '. ' . esc($q['question_text']) ?>
                                <?php if ($q['is_required']): ?>
                                    <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>

                            <?php if ($q['question_type'] === 'text'): ?>
                                <input type="text"
                                    name="answers[<?= $q['id'] ?>]"
                                    class="form-control"
                                    <?= $q['is_required'] ? 'required' : '' ?>>

                            <?php elseif ($q['question_type'] === 'textarea'): ?>
                                <textarea name="answers[<?= $q['id'] ?>]"
                                    class="form-control"
                                    rows="3"
                                    <?= $q['is_required'] ? 'required' : '' ?>></textarea>

                            <?php elseif ($q['question_type'] === 'radio' && !empty($q['options'])): ?>
                                <?php foreach ($q['options'] as $opt): ?>
                                    <div class="form-check">
                                        <input type="radio"
                                            name="answers[<?= $q['id'] ?>]"
                                            value="<?= esc($opt['value']) ?>"
                                            class="form-check-input"
                                            <?= $q['is_required'] ? 'required' : '' ?>>
                                        <label class="form-check-label"><?= esc($opt['label']) ?></label>
                                    </div>
                                <?php endforeach; ?>

                            <?php elseif ($q['question_type'] === 'checkbox' && !empty($q['options'])): ?>
                                <?php foreach ($q['options'] as $opt): ?>
                                    <div class="form-check">
                                        <input type="checkbox"
                                            name="answers[<?= $q['id'] ?>][]"
                                            value="<?= esc($opt['value']) ?>"
                                            class="form-check-input">
                                        <label class="form-check-label"><?= esc($opt['label']) ?></label>
                                    </div>
                                <?php endforeach; ?>

                            <?php elseif ($q['question_type'] === 'dropdown' && !empty($q['options'])): ?>
                                <select name="answers[<?= $q['id'] ?>]" class="form-select" <?= $q['is_required'] ? 'required' : '' ?>>
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($q['options'] as $opt): ?>
                                        <option value="<?= esc($opt['value']) ?>"><?= esc($opt['label']) ?></option>
                                    <?php endforeach; ?>
                                </select>

                            <?php else: ?>
                                <input type="text"
                                    name="answers[<?= $q['id'] ?>]"
                                    class="form-control"
                                    placeholder="Jawaban"
                                    <?= $q['is_required'] ? 'required' : '' ?>>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" name="save_draft" value="1" class="btn btn-warning">
                            💾 Simpan Draft
                        </button>
                        <button type="submit" name="submit_final" value="1" class="btn btn-success">
                            ✅ Kirim & Selesai
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
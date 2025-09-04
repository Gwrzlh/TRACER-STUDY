```php
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/questioner/page/edit.css') ?>">
<div class="container mt-4">
    <h2>Edit Halaman Kuesioner</h2>

    <form action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page['id']}/update") ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Judul Halaman</label>
            <input type="text" name="title" class="form-control" value="<?= esc($page['page_title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control"><?= esc($page['page_description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="order_no" class="form-control" value="<?= esc($page['order_no']) ?>" min="1" required>
        </div>

        <div class="mb-3">
            <label for="conditional_logic">
                <input type="checkbox" name="conditional_logic" id="conditional_logic" value="1" <?= !empty($conditionalLogic) ? 'checked' : '' ?>>
                <strong>Aktifkan Conditional Logic</strong>
            </label>
        </div>

        <div id="conditional-form" style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>;">
            <div class="d-flex align-items-center mb-2">
                <span class="me-2">Show this page if</span>
                <select name="logic_type" class="form-control me-2" style="width: auto;">
                    <option value="any" <?= isset($conditionalLogic['logic_type']) && $conditionalLogic['logic_type'] === 'any' ? 'selected' : '' ?>>Any</option>
                    <option value="all" <?= isset($conditionalLogic['logic_type']) && $conditionalLogic['logic_type'] === 'all' ? 'selected' : '' ?>>All</option>
                </select>
                <span>of this/these following match:</span>
            </div>

            <div id="conditional-container" class="mb-3">
                <?php if (!empty($conditionalLogic)): ?>
                    <?php foreach ($conditionalLogic as $condition): ?>
                        <div class="condition-row d-flex align-items-center gap-2 mb-2">
                            <select name="condition_question_id[]" class="question-selector form-control">
                                <option value="">Pilih Pertanyaan</option>
                                <?php foreach ($questions as $q): ?>
                                    <option value="<?= $q['id'] ?>" <?= $q['id'] == $condition['question_id'] ? 'selected' : '' ?>><?= esc($q['question_text']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="operator[]" class="form-control" style="width: auto;">
                                <?php foreach ($operators as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $key == $condition['operator'] ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="value-input-container w-100">
                                <input type="text" name="condition_value[]" placeholder="Value" class="form-control" value="<?= esc($condition['value']) ?>" required>
                            </span>
                            <button type="button" class="remove-condition-btn btn btn-danger btn-sm">Hapus</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="condition-row d-flex align-items-center gap-2 mb-2" style="display:none;">
                        <select name="condition_question_id[]" class="question-selector form-control">
                            <option value="">Pilih Pertanyaan</option>
                            <?php foreach ($questions as $q): ?>
                                <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="operator[]" class="form-control" style="width: auto;">
                            <?php foreach ($operators as $key => $label): ?>
                                <option value="<?= $key ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="value-input-container w-100">
                            <input type="text" name="condition_value[]" placeholder="Value" class="form-control" required>
                        </span>
                        <button type="button" class="remove-condition-btn btn btn-danger btn-sm" style="display:none;">Hapus</button>
                    </div>
                <?php endif; ?>
            </div>
            
            <button type="button" id="add-condition-btn" class="btn btn-primary btn-sm" style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>;">Tambah Kondisi</button>
        </div>

        <hr>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages") ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Fungsi untuk memuat opsi jawaban pertanyaan
    function loadConditionalValueInput(questionSelector, initialValue = null) {
        const questionId = questionSelector.val();
        const valueContainer = questionSelector.closest('.condition-row').find('.value-input-container');

        if (!questionId) {
            valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Value" class="form-control" value="${initialValue || ''}" required>`);
            return;
        }

        $.ajax({
            url: "<?= base_url('admin/questionnaire/pages/getQuestionOptions') ?>",
            type: 'GET',
            data: { question_id: questionId },
            dataType: 'json',
            success: function(response) {
                console.log('AJAX Success:', response); // Debug
                let inputHtml = '';
                if (response.type === 'select' && response.options && response.options.length > 0) {
                    inputHtml = '<select name="condition_value[]" class="form-control" required>';
                    response.options.forEach(function(option) {
                        const isSelected = initialValue !== null && String(initialValue) === String(option.id) ? 'selected' : '';
                        inputHtml += `<option value="${option.id}" ${isSelected}>${option.option_text}</option>`;
                    });
                    inputHtml += '</select>';
                } else {
                    inputHtml = `<input type="text" name="condition_value[]" placeholder="Value" class="form-control" value="${initialValue || ''}" required>`;
                }
                valueContainer.html(inputHtml);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error, xhr.responseText);
                valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Error loading options" class="form-control" value="${initialValue || ''}" required>`);
            }
        });
    }

    // Event handler saat pertanyaan berubah
    $(document).on('change', '.question-selector', function() {
        loadConditionalValueInput($(this), null);
    });

    // Event handler untuk tombol "Tambah Kondisi"
    $('#add-condition-btn').on('click', function() {
        const templateRow = `
            <div class="condition-row d-flex align-items-center gap-2 mb-2">
                <select name="condition_question_id[]" class="question-selector form-control">
                    <option value="">Pilih Pertanyaan</option>
                    <?php foreach ($questions as $q): ?>
                        <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="operator[]" class="form-control" style="width: auto;">
                    <?php foreach ($operators as $key => $label): ?>
                        <option value="<?= $key ?>"><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="value-input-container w-100">
                    <input type="text" name="condition_value[]" placeholder="Value" class="form-control" required>
                </span>
                <button type="button" class="remove-condition-btn btn btn-danger btn-sm">Hapus</button>
            </div>
        `;
        $('#conditional-container').append(templateRow);
        loadConditionalValueInput($('.condition-row:last .question-selector'));
    });

    // Event handler untuk tombol "Hapus"
    $(document).on('click', '.remove-condition-btn', function() {
        if ($('.condition-row').length > 1) {
            $(this).closest('.condition-row').remove();
        } else {
            const row = $(this).closest('.condition-row');
            row.find('.question-selector').val('');
            row.find('select[name="operator[]"]').val('is');
            row.find('.value-input-container').html('<input type="text" name="condition_value[]" placeholder="Value" class="form-control" required>');
            row.find('.remove-condition-btn').hide();
        }
    });

    // Inisialisasi awal saat halaman dimuat
    $('#conditional_logic').on('change', function() {
        $('#conditional-form').toggle(this.checked);
        if (this.checked) {
            $('.condition-row').first().show();
            $('#add-condition-btn').show();
            $('.condition-row').first().find('.remove-condition-btn').hide();
        } else {
            $('.condition-row:not(:first)').remove();
            $('.condition-row').first().hide();
            $('#add-condition-btn').hide();
        }
    }).trigger('change');

    // Inisialisasi untuk existing conditions
    <?php if (!empty($conditionalLogic)): ?>
        $('.condition-row').each(function(index) {
            const condition = <?= json_encode($conditionalLogic) ?>[index];
            if (condition) {
                $(this).find('.question-selector').val(condition.question_id);
                $(this).find('select[name="operator[]"]').val(condition.operator);
                loadConditionalValueInput($(this).find('.question-selector'), condition.value);
                $(this).find('.remove-condition-btn').show();
            }
        });
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

 <link rel="stylesheet" href="<?= base_url('css/questioner/page/edit.css') ?>">
<div class="bg-white rounded-xl shadow-md p-8 w-full mx-auto">
    <div class="-mx-8 mb-6 border-b border-gray-300 pb-3 px-8">
        <div class="flex items-center">
            <img src="/images/logo.png" alt="Tracer Study" class="h-12 mr-3">
            <h2 class="text-xl font-semibold">Edit Halaman Kuesioner</h2>
        </div>
    </div>

    <form action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page['id']}/update") ?>" method="post" class="space-y-5">
        <?= csrf_field() ?>

        <!-- Judul Halaman -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Judul Halaman <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   id="title"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                   value="<?= esc($page['page_title']) ?>" 
                   required>
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi
            </label>
            <textarea name="description" 
                      id="description"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical" 
                      rows="4"><?= esc($page['page_description']) ?></textarea>
        </div>

        <!-- Urutan -->
        <div>
            <label for="order_no" class="block text-sm font-medium text-gray-700 mb-2">
                Urutan <span class="text-red-500">*</span>
            </label>
            <input type="number" 
                   name="order_no" 
                   id="order_no"
                   class="w-24 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                   value="<?= esc($page['order_no']) ?>" 
                   min="1" 
                   required>
            <p class="text-gray-500 text-xs mt-1">Urutan halaman dalam kuesioner</p>
        </div>

        <!-- Conditional Logic -->
        <div>
            <label class="flex items-center">
                <input type="checkbox" 
                       name="conditional_logic" 
                       id="conditional_logic" 
                       value="1" 
                       <?= !empty($conditionalLogic) ? 'checked' : '' ?>
                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Conditional Logic</span>
            </label>

            <div id="conditional-form" style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>;" class="mt-4">
                <div class="flex items-center mb-3 text-sm text-gray-700">
                    <span class="mr-2">Show this page if</span>
                    <select name="logic_type" 
                            class="px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm mr-2" 
                            style="width: auto;">
                        <option value="any" <?= $logicType === 'any' ? 'selected' : '' ?>>Any</option>
                        <option value="all" <?= $logicType === 'all' ? 'selected' : '' ?>>All</option>
                    </select>
                    <span>of this/these following match:</span>
                </div>

                <div id="conditional-container" class="mb-4">
                    <?php if (!empty($conditionalLogic)): ?>
                        <?php foreach ($conditionalLogic as $index => $condition): ?>
                            <div class="condition-row grid grid-cols-4 gap-2 mb-3 p-3 bg-gray-50 rounded-md border items-center min-h-[56px]" role="group" aria-label="Conditional Logic Row">
                                <select name="condition_question_id[]" 
                                        class="question-selector w-full truncate text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" data-tooltip="true" aria-describedby="tooltip"
                                        <?= !empty($conditionalLogic) ? 'required' : '' ?>>
                                    <option value="">Pilih Pertanyaan</option>
                                    <?php foreach ($questions as $q): ?>
                                        <option value="<?= esc($q['id']) ?>" 
                                                <?= isset($condition['field']) && (string)$q['id'] === (string)$condition['field'] ? 'selected' : '' ?>>
                                            <?= esc($q['question_text']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="operator[]" 
                                        class="w-full text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Operator"
                                        <?= !empty($conditionalLogic) ? 'required' : '' ?>>
                                    <?php foreach ($operators as $key => $label): ?>
                                        <option value="<?= esc($key) ?>" <?= isset($condition['operator']) && $key === $condition['operator'] ? 'selected' : '' ?>>
                                            <?= esc($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="value-input-container">
                                    <input type="text" 
                                           name="condition_value[]" 
                                           placeholder="Value" 
                                           class="w-full min-w-[200px] text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Value"
                                           value="<?= isset($condition['value']) ? esc($condition['value']) : '' ?>" 
                                           <?= !empty($conditionalLogic) ? 'required' : '' ?>>
                                </span>
                                <button type="button" 
                                        class="remove-condition-btn w-full text-sm bg-red-500 text-white rounded-md p-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500" aria-label="Remove Condition">
                                    Hapus
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="condition-row grid grid-cols-4 gap-2 mb-3 p-3 bg-gray-50 rounded-md border items-center min-h-[56px]" role="group" aria-label="Conditional Logic Row" style="display:none;">
                            <select name="condition_question_id[]" 
                                    class="question-selector w-full truncate text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" data-tooltip="true" aria-describedby="tooltip">
                                <option value="">Pilih Pertanyaan</option>
                                <?php foreach ($questions as $q): ?>
                                    <option value="<?= esc($q['id']) ?>"><?= esc($q['question_text']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="operator[]" 
                                    class="w-full text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Operator">
                                <?php foreach ($operators as $key => $label): ?>
                                    <option value="<?= esc($key) ?>"><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="value-input-container">
                                <input type="text" 
                                       name="condition_value[]" 
                                       placeholder="Value" 
                                       class="w-full min-w-[200px] text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Value">
                            </span>
                            <button type="button" 
                                    class="remove-condition-btn w-full text-sm bg-red-500 text-white rounded-md p-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500" aria-label="Remove Condition"
                                    style="display:none;">
                                Hapus
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" 
                        id="add-condition-btn" 
                        style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>; background-color: #3b82f6; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                        onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.25)'"
                        onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    Tambah Kondisi
                </button>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
            <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages") ?>" 
               style="background-color: #fbbf24; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; text-decoration: none; display: inline-block; cursor: pointer; transition: all 0.2s ease;"
               onmouseover="this.style.backgroundColor='#f59e0b'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(251, 191, 36, 0.25)'"
               onmouseout="this.style.backgroundColor='#fbbf24'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                Batal
            </a>
            <button type="submit" 
                    style="background-color: #3b82f6; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.25)'"
                    onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                Update
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadConditionalValueInput(questionSelector, initialValue = null) {
        const questionId = questionSelector.val();
        const valueContainer = questionSelector.closest('.condition-row').find('.value-input-container');

        if (!questionId) {
            valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Value" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" value="${initialValue || ''}" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`);
            return;
        }

        $.ajax({
            url: "<?= base_url('admin/questionnaire/pages/getQuestionOptions') ?>",
            type: 'GET',
            data: { question_id: questionId },
            dataType: 'json',
            success: function(response) {
                console.log('AJAX Success:', response);
                let inputHtml = '';
                if (response.type === 'select' && response.options && response.options.length > 0) {
                    inputHtml = `<select name="condition_value[]" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`;
                    response.options.forEach(function(option) {
                        const isSelected = initialValue !== null && String(initialValue) === String(option.id) ? 'selected' : '';
                        inputHtml += `<option value="${option.id}" ${isSelected}>${option.option_text}</option>`;
                    });
                    inputHtml += `</select>`;
                } else {
                    inputHtml = `<input type="text" name="condition_value[]" placeholder="Value" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" value="${initialValue || ''}" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`;
                }
                valueContainer.html(inputHtml);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error, xhr.responseText);
                valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Error loading options" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" value="${initialValue || ''}" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`);
            }
        });
    }

    $('#conditional_logic').on('change', function() {
        if (this.checked) {
            $('#conditional-form').slideDown(300, function() {
                $('.condition-row').show();
                $('#add-condition-btn').show();
                $('.condition-row').each(function() {
                    $(this).find('.remove-condition-btn').show();
                });
                $('.condition-row').find('input[name="condition_value[]"], select[name="condition_value[]"]').prop('required', true);
                $('.condition-row').find('select[name="condition_question_id[]"]').prop('required', true);
                $('.condition-row').find('select[name="operator[]"]').prop('required', true);
            });
        } else {
            $('#conditional-form').slideUp(300, function() {
                $('.condition-row:not(:first)').remove();
                $('.condition-row').first().hide();
                $('#add-condition-btn').hide();
                $('.condition-row').find('input[name="condition_value[]"], select[name="condition_value[]"]').prop('required', false);
                $('.condition-row').find('select[name="condition_question_id[]"]').prop('required', false);
                $('.condition-row').find('select[name="operator[]"]').prop('required', false);
            });
        }
    }).trigger('change');

    $('#add-condition-btn').on('click', function() {
        const templateRow = `
            <div class="condition-row grid grid-cols-4 gap-2 mb-3 p-3 bg-gray-50 rounded-md border items-center min-h-[56px]" role="group" aria-label="Conditional Logic Row">
                <select name="condition_question_id[]" class="question-selector w-full truncate text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" data-tooltip="true" aria-describedby="tooltip" required>
                    <option value="">Pilih Pertanyaan</option>
                    <?php foreach ($questions as $q): ?>
                        <option value="<?= esc($q['id']) ?>"><?= esc($q['question_text']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="operator[]" class="px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" style="width: auto;" required>
                    <?php foreach ($operators as $key => $label): ?>
                        <option value="<?= esc($key) ?>"><?= esc($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="value-input-container flex-1">
                    <input type="text" name="condition_value[]" placeholder="Value" class="w-full min-w-[200px] text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Value" required>
                </span>
                <button type="button" class="remove-condition-btn w-full text-sm bg-red-500 text-white rounded-md p-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500" aria-label="Remove Condition">
                    Hapus
                </button>
            </div>
        `;
        $('#conditional-container').append(templateRow);
        loadConditionalValueInput($('.condition-row:last .question-selector'), null);
    });

    $(document).on('click', '.remove-condition-btn', function() {
        if ($('.condition-row').length > 1) {
            $(this).closest('.condition-row').remove();
        } else {
            const row = $(this).closest('.condition-row');
            row.find('.question-selector').val('');
            row.find('select[name="operator[]"]').val('is');
            row.find('.value-input-container').html(`<input type="text" name="condition_value[]" placeholder="Value" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`);
            row.find('.remove-condition-btn').hide();
        }
    });

    $(document).on('change', '.question-selector', function() {
        const initialValue = $(this).closest('.condition-row').find('input[name="condition_value[]"], select[name="condition_value[]"]').val();
        loadConditionalValueInput($(this), initialValue);
    });

    $('.condition-row').each(function(index) {
        const conditions = <?= json_encode($conditionalLogic) ?>;
        const condition = conditions[index];
        if (condition && condition.field) {
            $(this).find('.question-selector').val(condition.field);
            $(this).find('select[name="operator[]"]').val(condition.operator);
            loadConditionalValueInput($(this).find('.question-selector'), condition.value);
            $(this).find('.remove-condition-btn').show();
        }
    });

    $('form').on('submit', function(e) {
        if (!$('#conditional_logic').is(':checked')) {
            $('.condition-row').find('input[name="condition_value[]"], select[name="condition_value[]"]').prop('required', false);
            $('.condition-row').find('select[name="condition_question_id[]"]').prop('required', false);
            $('.condition-row').find('select[name="operator[]"]').prop('required', false);
        }
    });
});
</script>

<?= $this->endSection() ?>
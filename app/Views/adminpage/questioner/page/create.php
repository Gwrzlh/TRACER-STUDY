<!-- desain create page -->
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
 <link rel="stylesheet" href="<?= base_url('css/questioner/page/tambah.css') ?>">
<div class="bg-white rounded-xl shadow-md p-8 w-full mx-auto">
    <!-- Header + Divider -->
    <div class="-mx-8 mb-6 border-b border-gray-300 pb-3 px-8">
        <div class="flex items-center">
            <img src="/images/logo.png" alt="Tracer Study" class="h-12 mr-3">
            <h2 class="text-xl font-semibold">Tambah Halaman Kuesioner</h2>
        </div>
    </div>

    <form action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/store") ?>" method="post" class="space-y-5">
        <?= csrf_field() ?>

        <!-- Judul Halaman -->
        <div>
            <label class="block font-medium mb-1">Judul Halaman</label>
            <input type="text" name="title" required
                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block font-medium mb-1">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300 focus:outline-none"></textarea>
        </div>

        <!-- Urutan -->
        <div>
            <label class="block font-medium mb-1">Urutan</label>
            <input type="number" name="order_no" value="<?= old('order_no', $pageOrderNo) ?>" min="1" required
                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

       <!-- Conditional Logic -->
       <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="conditional_logic" 
                           id="conditional_logic" 
                           value="1" 
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Conditional Logic</span>
                </label>
            </div>

            <div id="conditional-form" style="display: none;">
                <div class="flex items-center mb-2 text-sm text-gray-700">
                    <span class="mr-2">Show this section if</span>
                    <select name="logic_type" 
                            class="px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                            style="width: auto;">
                        <option value="any">Any</option>
                        <option value="all" selected>All</option>
                    </select>
                    <span class="ml-2">of this/these following match:</span>
                </div>

                <div id="conditional-container" class="mb-3">
                    <div class="condition-row grid grid-cols-4 gap-2 mb-3 p-3 bg-gray-50 rounded-md border items-center min-h-[56px]" role="group" aria-label="Conditional Logic Row" style="display:none;">
                        <select name="condition_question_id[]" 
                                class="question-selector w-full truncate text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" data-tooltip="true" aria-describedby="tooltip">
                            <option value="">Pilih Pertanyaan</option>
                            <?php foreach ($questions as $q): ?>
                                <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="operator[]" 
                                class="px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                style="width: auto;">
                            <?php foreach ($operators as $key => $label): ?>
                                <option value="<?= $key ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="flex-1 value-input-container">
                            <input type="text" 
                                   name="condition_value[]" 
                                   placeholder="Value" 
                                   class="w-full min-w-[200px] text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Value">
                        </div>
                        <button type="button" 
                                class="remove-condition-btn w-full text-sm bg-red-500 text-white rounded-md p-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500" aria-label="Remove Condition"
                                style="display:none;">
                            Hapus
                        </button>
                    </div>
                </div>
                <button type="button" id="add-condition-btn" 
                        style="display: none; background-color: #3b82f6; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                        onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.25)'"
                        onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    Tambah Kondisi
                </button>
            </div>

        <!-- Tombol -->
        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Simpan</button>
            <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages") ?>"
                class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg">Batal</a>
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
                valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Value" class="form-control" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`);
                return;
            }

            $.ajax({
                url: "<?= base_url('admin/questionnaire/pages/getQuestionOptions') ?>",
                type: 'GET',
                data: { question_id: questionId },
                dataType: 'json',
                success: function(response) {
                    let inputHtml = '';
                    if (response.type === 'select' && response.options && response.options.length > 0) {
                        inputHtml = `<select name="condition_value[]" class="form-control" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`;
                        response.options.forEach(function(option) {
                            const isSelected = initialValue !== null && String(initialValue) === String(option.id) ? 'selected' : '';
                            inputHtml += `<option value="${option.id}" ${isSelected}>${option.option_text}</option>`;
                        });
                        inputHtml += '</select>';
                    } else {
                        inputHtml = `<input type="text" name="condition_value[]" placeholder="Value" class="form-control" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`;
                    }
                    valueContainer.html(inputHtml);
                },
                error: function() {
                    valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Error loading options" class="form-control" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`);
                }
            });
        }

        $('#conditional_logic').on('change', function() {
            if (this.checked) {
                $('#conditional-form').slideDown(300, function() {
                    $('.condition-row').first().show();
                    $('#add-condition-btn').show();
                    $('.condition-row').first().find('.remove-condition-btn').hide();
                    $('.condition-row').find('input, select').prop('required', true);
                });
            } else {
                $('#conditional-form').slideUp(300, function() {
                    $('.condition-row:not(:first)').remove();
                    $('.condition-row').first().hide();
                    $('#add-condition-btn').hide();
                    $('.condition-row').find('input, select').prop('required', false);
                });
            }
        }).trigger('change');

        $('#add-condition-btn').on('click', function() {
            const templateRow = `
                <div class="condition-row grid grid-cols-4 gap-2 mb-3 p-3 bg-gray-50 rounded-md border items-center min-h-[56px]" role="group" aria-label="Conditional Logic Row">
                    <select name="condition_question_id[]" class="question-selector w-full truncate text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" data-tooltip="true" aria-describedby="tooltip" required>
                        <option value="">Pilih Pertanyaan</option>
                        <?php foreach ($questions as $q): ?>
                            <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="operator[]" class="form-control" style="width: auto;" required>
                        <?php foreach ($operators as $key => $label): ?>
                            <option value="<?= $key ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="value-input-container w-100">
                        <input type="text" name="condition_value[]" placeholder="Value" class="w-full min-w-[200px] text-sm bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Value"required>
                    </span>
                    <button type="button" class="remove-condition-btn btn btn-danger btn-sm">Hapus</button>
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
                row.find('.value-input-container').html(`<input type="text" name="condition_value[]" placeholder="Value" class="form-control" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`);
                row.find('.remove-condition-btn').hide();
            }
        });

        $(document).on('change', '.question-selector', function() {
            loadConditionalValueInput($(this), null);
        });

        $('form').on('submit', function() {
            if (!$('#conditional_logic').is(':checked')) {
                $('.condition-row').find('input, select').prop('required', false);
            }
        });
    });
</script>

<?= $this->endSection() ?>
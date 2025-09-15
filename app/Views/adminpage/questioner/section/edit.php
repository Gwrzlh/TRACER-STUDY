<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="bg-white rounded-xl shadow-md p-8 w-full mx-auto">
    <!-- Header + Divider -->
    <div class="-mx-8 mb-6 border-b border-gray-300 pb-3 px-8">
        <div class="flex items-center">
            <img src="/images/logo.png" alt="Tracer Study" class="h-12 mr-3">
            <h2 class="text-xl font-semibold">Edit Section: <?= esc($section['section_title']) ?></h2>
        </div>
        <p class="text-sm text-gray-600 mt-1">Halaman: <?= esc($page['page_title']) ?></p>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
            <ul class="text-sm text-red-600 space-y-1">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/update") ?>" class="space-y-5">
        <?= csrf_field() ?>

        <!-- Judul Section -->
        <div>
            <label for="section_title" class="block text-sm font-medium text-gray-700 mb-2">
                Judul Section <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                   id="section_title" 
                   name="section_title" 
                   value="<?= old('section_title', $section['section_title']) ?>" 
                   required>
        </div>

        <!-- Deskripsi Section -->
        <div>
            <label for="section_description" class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi Section
            </label>
            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical" 
                      id="section_description" 
                      name="section_description" 
                      rows="4"><?= old('section_description', $section['section_description']) ?></textarea>
        </div>

        <!-- Urutan -->
        <div>
            <label for="order_no" class="block text-sm font-medium text-gray-700 mb-2">
                Urutan
            </label>
            <input type="number" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                   id="order_no" 
                   name="order_no" 
                   value="<?= old('order_no', $section['order_no']) ?>" 
                   min="1" 
                   required>
        </div>

        <!-- Pengaturan Tampilan -->
        <div class="space-y-3">
            <h3 class="text-sm font-medium text-gray-700">Pengaturan Tampilan</h3>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" 
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                           id="show_section_title" 
                           name="show_section_title" 
                           value="1" 
                           <?= old('show_section_title', $section['show_section_title']) ? 'checked' : '' ?>>
                    <span class="ml-2 text-sm text-gray-700">Tampilkan Judul Section</span>
                </label>
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" 
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                           id="show_section_description" 
                           name="show_section_description" 
                           value="1" 
                           <?= old('show_section_description', $section['show_section_description']) ? 'checked' : '' ?>>
                    <span class="ml-2 text-sm text-gray-700">Tampilkan Deskripsi</span>
                </label>
            </div>
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

            <div id="conditional-form" style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>;" class="mt-3">
                <div class="flex items-center gap-2 mb-3 text-sm text-gray-600">
                    <span>Show this section if</span>
                    <select name="logic_type" class="px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" style="width: auto;">
                        <option value="any" <?= isset($conditionalLogic['logic_type']) && $conditionalLogic['logic_type'] === 'any' ? 'selected' : '' ?>>Any</option>
                        <option value="all" <?= !isset($conditionalLogic['logic_type']) || $conditionalLogic['logic_type'] === 'all' ? 'selected' : '' ?>>All</option>
                    </select>
                    <span>of this/these following match:</span>
                </div>

                <div id="conditional-container" class="space-y-3">
                    <?php if (!empty($conditionalLogic)): ?>
                        <?php foreach ($conditionalLogic as $condition): ?>
                            <div class="condition-row flex items-center gap-2 p-3 bg-gray-50 rounded-md border">
                                <select name="condition_question_id[]" class="question-selector flex-1 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Pilih Pertanyaan</option>
                                    <?php foreach ($questions as $q): ?>
                                        <option value="<?= $q['id'] ?>" <?= $q['id'] == $condition['question_id'] ? 'selected' : '' ?>><?= esc($q['question_text']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="operator[]" class="px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" style="width: auto;">
                                    <?php foreach ($operators as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= $key == $condition['operator'] ? 'selected' : '' ?>><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="value-input-container flex-1">
                                    <input type="text" 
                                           name="condition_value[]" 
                                           placeholder="Value" 
                                           class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                           value="<?= esc($condition['value']) ?>" 
                                           required>
                                </span>
                                <button type="button" 
                                        class="remove-condition-btn px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600 transition-colors font-medium">
                                    Hapus
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="condition-row flex items-center gap-2 p-3 bg-gray-50 rounded-md border" style="display:none;">
                            <select name="condition_question_id[]" class="question-selector flex-1 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Pilih Pertanyaan</option>
                                <?php foreach ($questions as $q): ?>
                                    <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="operator[]" class="px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" style="width: auto;">
                                <?php foreach ($operators as $key => $label): ?>
                                    <option value="<?= $key ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="value-input-container flex-1">
                                <input type="text" 
                                       name="condition_value[]" 
                                       placeholder="Value" 
                                       class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </span>
                            <button type="button" 
                                    class="remove-condition-btn px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600 transition-colors font-medium" 
                                    style="display:none;">
                                Hapus
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                
                <button type="button" 
                        id="add-condition-btn" 
                        style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>; background-color: #3b82f6; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease; margin-top: 0.75rem;"
                        onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.25)'"
                        onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    Tambah Kondisi
                </button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="p-4 bg-blue-50 rounded-md border border-blue-200">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Quick Actions</h3>
            <div class="flex gap-2">
                <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions") ?>"
                   style="background-color: #06b6d4; color: #fff; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; display: inline-block;"
                   onmouseover="this.style.backgroundColor='#0891b2'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(6, 182, 212, 0.25)'"
                   onmouseout="this.style.backgroundColor='#06b6d4'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    Kelola Pertanyaan
                </a>
                <button type="button" 
                        onclick="duplicateSection()"
                        style="background-color: #6b7280; color: #fff; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                        onmouseover="this.style.backgroundColor='#4b5563'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(107, 114, 128, 0.25)'"
                        onmouseout="this.style.backgroundColor='#6b7280'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    Duplikasi Section
                </button>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
            <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>"
               onclick="deleteSection()"
                style="background-color: #ef4444; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                onmouseover="this.style.backgroundColor='#dc2626'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(239, 68, 68, 0.25)'"
                onmouseout="this.style.backgroundColor='#ef4444'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                Kembali
            </a>
            
            <div class="flex gap-3">
                <button type="submit" 
                        style="background-color: #3b82f6; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.25)'"
                    onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    Update Section
                </button>
                <button type="button" 
                    style="background-color: #fbbf24; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; text-decoration: none; cursor: pointer; transition: all 0.2s ease; display: inline-block;"
                    onmouseover="this.style.backgroundColor='#f59e0b'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(251, 191, 36, 0.25)'"
                    onmouseout="this.style.backgroundColor='#fbbf24'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    Hapus Section
                </button>
            </div>
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
                valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Value" class="form-control" value="${initialValue || ''}" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`);
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
                        inputHtml = `<select name="condition_value[]" class="form-control" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`;
                        response.options.forEach(function(option) {
                            const isSelected = initialValue !== null && String(initialValue) === String(option.id) ? 'selected' : '';
                            inputHtml += `<option value="${option.id}" ${isSelected}>${option.option_text}</option>`;
                        });
                        inputHtml += '</select>';
                    } else {
                        inputHtml = `<input type="text" name="condition_value[]" placeholder="Value" class="form-control" value="${initialValue || ''}" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`;
                    }
                    valueContainer.html(inputHtml);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error, xhr.responseText);
                    valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Error loading options" class="form-control" value="${initialValue || ''}" ${$('#conditional_logic').is(':checked') ? 'required' : ''}>`);
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
                    // Add required attribute to condition inputs
                    $('.condition-row').find('input[name="condition_value[]"], select[name="condition_value[]"]').prop('required', true);
                    $('.condition-row').find('select[name="condition_question_id[]"]').prop('required', true);
                    $('.condition-row').find('select[name="operator[]"]').prop('required', true);
                });
            } else {
                $('#conditional-form').slideUp(300, function() {
                    $('.condition-row:not(:first)').remove();
                    $('.condition-row').first().hide();
                    $('#add-condition-btn').hide();
                    // Remove required attribute from condition inputs
                    $('.condition-row').find('input[name="condition_value[]"], select[name="condition_value[]"]').prop('required', false);
                    $('.condition-row').find('select[name="condition_question_id[]"]').prop('required', false);
                    $('.condition-row').find('select[name="operator[]"]').prop('required', false);
                });
            }
        }).trigger('change');

        $('#add-condition-btn').on('click', function() {
            const templateRow = `
                <div class="condition-row d-flex align-items-center gap-2 mb-2">
                    <select name="condition_question_id[]" class="question-selector form-control" required>
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
                        <input type="text" name="condition_value[]" placeholder="Value" class="form-control" required>
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
            const initialValue = $(this).closest('.condition-row').find('input[name="condition_value[]"], select[name="condition_value[]"]').val();
            loadConditionalValueInput($(this), initialValue);
        });

        // Handle pre-populated conditions
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

        // Prevent form submission issues with hidden required fields
        $('form').on('submit', function(e) {
            if (!$('#conditional_logic').is(':checked')) {
                $('.condition-row').find('input[name="condition_value[]"], select[name="condition_value[]"]').prop('required', false);
                $('.condition-row').find('select[name="condition_question_id[]"]').prop('required', false);
                $('.condition-row').find('select[name="operator[]"]').prop('required', false);
            }
        });

        function deleteSection() {
            if (confirm('Yakin ingin menghapus section ini? Semua pertanyaan di dalam section ini juga akan terhapus!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/delete") ?>';
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= csrf_token() ?>';
                csrfInput.value = '<?= csrf_hash() ?>';
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function duplicateSection() {
            if (confirm('Duplikasi section ini? Section baru akan dibuat dengan nama "Copy of <?= esc($section['section_title']) ?>"')) {
                $.ajax({
                    url: '<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/duplicate") ?>',
                    type: 'POST',
                    data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>';
                        } else {
                            alert('Gagal menduplikasi section: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Duplicate Error:', status, error, xhr.responseText);
                        alert('Terjadi kesalahan saat menduplikasi section.');
                    }
                });
            }
        }
    });
</script>
</div>

<style>
.card-header.bg-warning { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%) !important; }
.btn-success { background: linear-gradient(135deg, #28a745 0%, #218838 100%); border: none; }
.btn-success:hover { background: linear-gradient(135deg, #218838 0%, #1e7e34 100%); transform: translateY(-1px); transition: all 0.2s ease; }
.form-control:focus, .form-select:focus { border-color: #f6d365; box-shadow: 0 0 0 0.2rem rgba(246, 211, 101, 0.25); }
</style>

<?= $this->endSection() ?>
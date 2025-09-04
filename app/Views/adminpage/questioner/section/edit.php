<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('admin/questionnaire') ?>">Daftar Kuesioner</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages") ?>"><?= esc($questionnaire['title']) ?></a></li>
            <li class="breadcrumb-item"><a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>"><?= esc($page['page_title']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: <?= esc($section['section_title']) ?></li>
        </ol>
    </nav>

    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">
                <i class="fas fa-edit me-2"></i>Edit Section: <?= esc($section['section_title']) ?>
            </h4>
            <p class="mb-0"><small>Halaman: <?= esc($page['page_title']) ?></small></p>
        </div>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post" action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/update") ?>">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="section_title" class="form-label">
                                <i class="fas fa-heading me-2"></i>Judul Section <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="section_title" name="section_title" 
                                   value="<?= old('section_title', $section['section_title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="section_description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Deskripsi Section
                            </label>
                            <textarea class="form-control" id="section_description" name="section_description" 
                                      rows="4"><?= old('section_description', $section['section_description']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="conditional_logic">
                                <input type="checkbox" name="conditional_logic" id="conditional_logic" value="1" 
                                       <?= !empty($conditionalLogic) ? 'checked' : '' ?>>
                                <strong>Aktifkan Conditional Logic</strong>
                            </label>
                        </div>

                        <div id="conditional-form" style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>;">
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">Show this section if</span>
                                <select name="logic_type" class="form-control me-2" style="width: auto;">
                                    <option value="any" <?= isset($conditionalLogic['logic_type']) && $conditionalLogic['logic_type'] === 'any' ? 'selected' : '' ?>>Any</option>
                                    <option value="all" <?= !isset($conditionalLogic['logic_type']) || $conditionalLogic['logic_type'] === 'all' ? 'selected' : '' ?>>All</option>
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
                                            <input type="text" name="condition_value[]" placeholder="Value" class="form-control" >
                                        </span>
                                        <button type="button" class="remove-condition-btn btn btn-danger btn-sm" style="display:none;">Hapus</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" id="add-condition-btn" class="btn btn-primary btn-sm" style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>;">Tambah Kondisi</button>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Pengaturan Tampilan</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="show_section_title" 
                                               name="show_section_title" value="1" <?= old('show_section_title', $section['show_section_title']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="show_section_title">
                                            <i class="fas fa-eye me-1"></i>Tampilkan Judul Section
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="show_section_description" 
                                               name="show_section_description" value="1" <?= old('show_section_description', $section['show_section_description']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="show_section_description">
                                            <i class="fas fa-align-left me-1"></i>Tampilkan Deskripsi
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="order_no" class="form-label">
                                        <i class="fas fa-sort-numeric-up me-2"></i>Urutan
                                    </label>
                                    <input type="number" class="form-control" id="order_no" name="order_no" 
                                           value="<?= old('order_no', $section['order_no']) ?>" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-info bg-opacity-10 border-info">
                            <div class="card-header bg-info bg-opacity-25">
                                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions") ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-cogs me-2"></i>Kelola Pertanyaan
                                    </a>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="duplicateSection()">
                                        <i class="fas fa-copy me-2"></i>Duplikasi Section
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>" 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <div>
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-check me-2"></i>Update Section
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteSection()">
                            <i class="fas fa-trash me-2"></i>Hapus Section
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
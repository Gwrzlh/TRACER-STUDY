<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<div class="container mt-4">

    <div class="bg-white rounded-xl shadow-md p-8 w-full mx-auto">
        <!-- Header + Divider -->
        <div class="-mx-8 mb-6 border-b border-gray-300 pb-3 px-8">
            <div class="flex items-center">
                <img src="/images/logo.png" alt="Tracer Study" class="h-12 mr-3">
                <h2 class="text-xl font-semibold">Tambah Section Baru</h2>
            </div>
            <p class="text-gray-500 text-sm mt-1">Halaman: <?= esc($page['page_title']) ?></p>
        </div>

        <form method="post" action="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections/store") ?>" class="space-y-5">
            <?= csrf_field() ?>

            <!-- Judul Section -->
            <div class="mb-4">
                <label for="section_title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Section <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    id="section_title"
                    name="section_title"
                    value="<?= old('section_title') ?>"
                    placeholder="Masukkan judul section..."
                    required>
                <p class="text-gray-500 text-xs mt-1">Contoh: "Data Pribadi", "Informasi Pekerjaan", dll.</p>
            </div>

            <!-- Deskripsi Section -->
            <div class="mb-4">
                <label for="section_description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Section
                </label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical"
                    id="section_description"
                    name="section_description"
                    rows="4"
                    placeholder="Jelaskan tujuan dan isi dari section ini..."><?= old('section_description') ?></textarea>
                <p class="text-gray-500 text-xs mt-1">Deskripsi opsional untuk menjelaskan maksud section ini kepada responden.</p>
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
                    <div class="condition-row flex items-center gap-2 mb-2" style="display:none;">
                        <select name="condition_question_id[]"
                            class="question-selector flex-1 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
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
                                class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <button type="button"
                            class="remove-condition-btn px-2 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600 transition-colors"
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

            <!-- Pengaturan Tampilan -->
            <div class="bg-gray-50 p-4 rounded-lg border">
                <h6 class="font-semibold mb-3">Pengaturan Tampilan</h6>

                <div class="space-y-3">
                    <div class="flex items-center">
                        <input class="form-check-input mr-2" type="checkbox" id="show_section_title"
                            name="show_section_title" value="1" <?= old('show_section_title', 1) ? 'checked' : '' ?>>
                        <label for="show_section_title">Tampilkan Judul Section</label>
                    </div>
                    <p class="text-gray-500 text-sm ml-6">Judul section akan terlihat oleh responden</p>

                    <div class="flex items-center">
                        <input class="form-check-input mr-2" type="checkbox" id="show_section_description"
                            name="show_section_description" value="1" <?= old('show_section_description', 1) ? 'checked' : '' ?>>
                        <label for="show_section_description">Tampilkan Deskripsi</label>
                    </div>
                    <p class="text-gray-500 text-sm ml-6">Deskripsi section akan terlihat oleh responden</p>

                    <div>
                        <label for="order_no" class="block font-medium mb-1">Urutan</label>
                        <input type="number" class="form-control w-24 rounded-lg" id="order_no" name="order_no"
                            value="<?= old('order_no', $next_order) ?>" min="1" required>
                        <p class="text-gray-500 text-sm mt-1">Urutan section dalam halaman</p>
                    </div>
                </div>
            </div>

            <!-- Informasi -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h6 class="font-semibold mb-2">Informasi</h6>
                <ul class="list-disc ml-5 text-sm text-gray-600">
                    <li>Menambahkan pertanyaan ke section ini</li>
                    <li>Mengatur conditional logic</li>
                    <li>Mengurutkan ulang section</li>
                </ul>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-between pt-4">
                <a href="<?= base_url("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections") ?>"
                    style="background-color: #ef4444; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; text-decoration: none; display: inline-block; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.backgroundColor='#dc2626'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(239, 68, 68, 0.25)'"
                    onmouseout="this.style.backgroundColor='#ef4444'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    Kembali
                </a>
                <div class="flex gap-2">
                    <button type="reset"
                        style="background-color: #fbbf24; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                        onmouseover="this.style.backgroundColor='#f59e0b'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(251, 191, 36, 0.25)'"
                        onmouseout="this.style.backgroundColor='#fbbf24'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        Reset
                    </button>
                    <button type="submit"
                        style="background-color: #3b82f6; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                        onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.25)'"
                        onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        Simpan Section
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Script jQuery -->
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
                    url: "<?= base_url('kaprodi/kuesioner/pages/getQuestionOptions') ?>",
                    type: 'GET',
                    data: {
                        question_id: questionId
                    },
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
                loadConditionalValueInput($(this), null);
            });

            $('form').on('submit', function() {
                if (!$('#conditional_logic').is(':checked')) {
                    $('.condition-row').find('input, select').prop('required', false);
                }
            });
        });
    </script>
</div>

<style>
    .card-header.bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6c5ce7 100%);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>

<?= $this->endSection() ?>
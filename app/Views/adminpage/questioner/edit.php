<!-- desain edit kuesioner -->
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-md p-8 w-full mx-auto">
    <!-- Header + Divider -->
    <div class="-mx-8 mb-6 border-b border-gray-300 pb-3 px-8">
        <div class="flex items-center">
            <img src="/images/logo.png" alt="Tracer Study" class="h-12 mr-3">
            <h2 class="text-xl font-semibold">Edit Kuesioner</h2>
        </div>
    </div>

    <form action="<?= base_url('/admin/questionnaire/' . $questionnaire['id'] . '/update/') ?>" method="post" class="space-y-5">
        
        <!-- Judul Kuesioner -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Judul Kuesioner <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   id="title"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                   value="<?= esc($questionnaire['title']) ?>" 
                   required>
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                Deskripsi
            </label>
            <textarea name="deskripsi" 
                      id="deskripsi"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical" 
                      rows="4"><?= esc($questionnaire['deskripsi']) ?></textarea>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Status
            </label>
            <select name="is_active" 
                    id="status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                <option value="active" <?= $questionnaire['is_active'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="draft" <?= $questionnaire['is_active'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="inactive" <?= $questionnaire['is_active'] == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
            </select>
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
                <span class="ml-2 text-sm font-medium text-gray-700">Conditional Logic</span>
            </label>

            <div id="conditional-container" class="mt-3">
                <?php if (!empty($conditionalLogic)): ?>
                    <?php foreach ($conditionalLogic as $i => $condition): ?>
                        <div class="condition-row flex items-center gap-2 mb-3 p-3 bg-gray-50 rounded-md border">
                            <select name="field_name[]" 
                                    class="field-selector flex-1 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <?php foreach ($fields as $f): ?>
                                    <option value="<?= $f ?>" <?= $f == $condition['field'] ? 'selected' : '' ?>><?= ucwords(str_replace('_', ' ', $f)) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="operator[]" 
                                    class="px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                    style="width: auto;">
                                <?php foreach ($operators as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $key == $condition['operator'] ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="value-input-container flex-1">
                                <?php
                                $input_type = 'text';
                                $options = [];
                                $value = $condition['value'];

                                if (in_array($condition['field'], ['academic_faculty', 'academic_program', 'academic_year', 'academic_graduate_year', 'city', 'group_id', 'jenis_kel'])) {
                                    $input_type = 'select';
                                    if ($condition['field'] == 'academic_faculty') {
                                        $model = new \App\Models\Jurusan();
                                        $options = $model->select('id, nama_jurusan as name')->findAll();
                                    } elseif ($condition['field'] == 'academic_program') {
                                        $model = new \App\Models\Prodi();
                                        $options = $model->select('id, nama_prodi as name')->findAll();
                                    } elseif ($condition['field'] == 'city') {
                                        $model = new \App\Models\Cities();
                                        $options = $model->select('id, name')->findAll();
                                    } elseif ($condition['field'] == 'group_id') {
                                        $model = new \App\Models\Roles();
                                        $options = $model->select('id, nama as name')->findAll();
                                    } elseif ($condition['field'] == 'jenis_kel') {
                                        $options = [['id' => 'L', 'name' => 'Laki-laki'], ['id' => 'P', 'name' => 'Perempuan']];
                                    } elseif ($condition['field'] == 'academic_year' || $condition['field'] == 'academic_graduate_year') {
                                        $start_year = date('Y') - 15;
                                        $end_year = date('Y');
                                        $options = [];
                                        for ($y = $end_year; $y >= $start_year; $y--) {
                                            $options[] = ['id' => (string)$y, 'name' => (string)$y];
                                        }
                                    }
                                }

                                if ($input_type == 'select' && !empty($options)): ?>
                                    <select name="value[]" 
                                            class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <?php foreach ($options as $opt): ?>
                                            <option value="<?= $opt['id'] ?>" <?= $opt['id'] == $value ? 'selected' : '' ?>>
                                                <?= $opt['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <input type="text" 
                                           name="value[]" 
                                           placeholder="Value" 
                                           value="<?= esc($value) ?>"
                                           class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <?php endif; ?>
                            </span>
                            <button type="button" 
                                    class="remove-condition-btn px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600 transition-colors font-medium">
                                Hapus
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="condition-row flex items-center gap-2 mb-3 p-3 bg-gray-50 rounded-md border" style="display:none;">
                        <select name="field_name[]" 
                                class="field-selector flex-1 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <?php foreach ($fields as $f): ?>
                                <option value="<?= $f ?>"><?= ucwords(str_replace('_', ' ', $f)) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="operator[]" 
                                class="px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                style="width: auto;">
                            <?php foreach ($operators as $key => $label): ?>
                                <option value="<?= $key ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="value-input-container flex-1">
                            <input type="text" 
                                   name="value[]" 
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

        <!-- Tombol Aksi -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
            <button type="button" 
                    onclick="window.history.back();"
                    style="background-color: #fbbf24; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.backgroundColor='#f59e0b'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(251, 191, 36, 0.25)'"
                    onmouseout="this.style.backgroundColor='#fbbf24'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                Batal
            </button>
            <button type="submit" 
                    style="background-color: #3b82f6; color: #fff; padding: 0.625rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.25)'"
                    onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Fungsi untuk memuat opsi berdasarkan field yang dipilih
    function loadOptions(fieldSelector, currentValue = '') {
        const selectedField = fieldSelector.val();
        const valueContainer = fieldSelector.closest('.condition-row').find('.value-input-container');

        if (!selectedField) {
            valueContainer.html('<input type="text" name="value[]" placeholder="Value" value="' + currentValue + '" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">');
            return;
        }

        $.ajax({
            url: "<?= base_url('/admin/get-conditional-options') ?>",
            type: 'GET',
            data: {
                field: selectedField
            },
            dataType: 'json',
            success: function(response) {
                let inputHtml = '';
                if (response.type === 'select' && response.options && response.options.length > 0) {
                    inputHtml = '<select name="value[]" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">';
                    $.each(response.options, function(index, option) {
                        // Set selected jika option.id sesuai dengan currentValue
                        const isSelected = option.id == currentValue ? 'selected' : '';
                        inputHtml += `<option value="${option.id}" ${isSelected}>${option.name}</option>`;
                    });
                    inputHtml += '</select>';
                } else {
                    inputHtml = '<input type="text" name="value[]" placeholder="Value" value="' + currentValue + '" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">';
                }
                valueContainer.html(inputHtml);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
                valueContainer.html('<input type="text" name="value[]" placeholder="Error loading data" value="' + currentValue + '" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">');
            }
        });
    }

    // Event handler untuk perubahan pada field selector
    $(document).on('change', '.field-selector', function() {
        // Ambil value saat ini dari input/dropdown (jika ada)
        const currentValue = $(this).closest('.condition-row').find('[name="value[]"]').val() || '';
        loadOptions($(this), currentValue);
    });

    // Event handler untuk checkbox Conditional Logic
    $('#conditional_logic').on('change', function() {
        if ($(this).is(':checked')) {
            $('.condition-row').first().show();
            $('#add-condition-btn').show();
        } else {
            $('.condition-row').hide();
            $('#add-condition-btn').hide();
            $('.condition-row:not(:first)').remove();
        }
    });

    // Event handler untuk tombol "Tambah Kondisi"
    $('#add-condition-btn').on('click', function() {
        const firstRow = $('.condition-row').first();
        const newRow = firstRow.clone();

        // Reset nilai-nilai di baris baru
        newRow.find('select').val(firstRow.find('.field-selector').val());
        newRow.find('.value-input-container').html('<input type="text" name="value[]" placeholder="Value" class="w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">');

        // Tampilkan tombol "Hapus" pada baris baru
        newRow.find('.remove-condition-btn').show();

        // Tambahkan baris baru ke container
        $('#conditional-container').append(newRow);

        // Panggil loadOptions untuk baris baru (tanpa currentValue karena baru)
        loadOptions(newRow.find('.field-selector'));
    });

    // Event handler untuk tombol "Hapus"
    $(document).on('click', '.remove-condition-btn', function() {
        if ($('.condition-row').length > 1) {
            $(this).closest('.condition-row').remove();
        }
    });

    // Inisialisasi pada halaman edit: tampilkan tombol "Hapus" dan load opsi untuk setiap baris
    if ($('#conditional_logic').is(':checked')) {
        $('.condition-row').each(function() {
            if ($('.condition-row').length > 1) {
                $(this).find('.remove-condition-btn').show();
            }
            // Ambil value dari database (tersimpan di input/dropdown saat ini)
            const currentValue = $(this).find('[name="value[]"]').val() || '';
            // Panggil loadOptions dengan value dari database
            loadOptions($(this).find('.field-selector'), currentValue);
        });
    }
});
</script>
<?= $this->endSection() ?>
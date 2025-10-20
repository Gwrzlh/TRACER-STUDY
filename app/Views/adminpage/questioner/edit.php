```html
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/questioner/tambah.css') ?>">

<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">
    <!-- Header + Divider -->
    <div class="flex items-center mb-4">
        <img src="/images/logo.png" alt="Tracer Study" class="h-12 mr-3">
        <h2 class="text-xl font-semibold">Edit Kuesioner: <?= esc($questionnaire['title']) ?></h2>
    </div>
    <hr class="mb-6 border-gray-300">

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
            <ul class="text-sm text-red-600 space-y-1">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/admin/questionnaire/' . $questionnaire['id'] . '/update') ?>" method="post" class="space-y-5">
        <?= csrf_field() ?>

        <!-- Judul Kuesioner -->
        <div>
            <label for="title" class="block font-medium text-gray-700 mb-1">Judul Kuesioner <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-400" value="<?= old('title', esc($questionnaire['title'])) ?>" required>
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="deskripsi" class="block font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-400" rows="4"><?= old('deskripsi', esc($questionnaire['deskripsi'])) ?></textarea>
        </div>

        <!-- Pengumuman -->
        <div>
            <label for="announcement" class="block font-medium text-gray-700 mb-1">Pengumuman (Ditampilkan di Akhir Kuesioner)</label>
            <textarea class="announcement-editor w-full border rounded-lg p-2 focus:ring focus:border-blue-400" id="announcement" name="announcement" rows="4"><?= old('announcement', $questionnaire['announcement']) ?></textarea>
            <p class="text-gray-500 text-xs mt-1">Masukkan teks pengumuman yang akan ditampilkan setelah kuesioner selesai. Gunakan editor untuk memformat teks.</p>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block font-medium text-gray-700 mb-1">Status</label>
            <select name="is_active" id="status" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-400">
                <option value="active" <?= old('is_active', $questionnaire['is_active']) == 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="draft" <?= old('is_active', $questionnaire['is_active']) == 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="inactive" <?= old('is_active', $questionnaire['is_active']) == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
            </select>
        </div>

        <!-- Conditional Logic -->
      <div>
            <label class="flex items-center">
                <input type="checkbox" name="conditional_logic" id="conditional_logic" value="1" class="mr-2" <?= !empty($conditionalLogic) ? 'checked' : '' ?>>
                <span class="text-sm font-medium text-gray-700">Conditional Logic</span>
            </label>

            <div id="conditional-container" class="mt-3 space-y-3">
                <!-- Template untuk baris baru -->
                <div class="condition-row hidden flex items-center gap-3 p-3 bg-gray-50 rounded-md border">
                    <select name="field_name[]" class="field-selector border rounded-lg p-2 flex-1">
                        <option value="">Pilih Field</option>
                        <?php foreach ($fields as $actual_field => $label): ?>
                            <option value="<?= esc($actual_field) ?>"><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="operator[]" class="border rounded-lg p-2" style="width: auto;">
                        <?php foreach ($operators as $key => $label): ?>
                            <option value="<?= esc($key) ?>"><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="value-input-container flex-1">
                        <input type="text" name="value[]" placeholder="Value" class="w-full border rounded-lg p-2">
                    </span>
                    <button type="button" class="remove-condition-btn hidden bg-red-500 text-white px-3 py-1 rounded-lg text-sm">
                        Hapus
                    </button>
                </div>

                <!-- Tampilkan conditional logic yang sudah ada -->
                <?php if (!empty($conditionalLogic)): ?>
                    <?php foreach ($conditionalLogic as $i => $condition): ?>
                        <div class="condition-row flex items-center gap-3 p-3 bg-gray-50 rounded-md border">
                            <select name="field_name[]" class="field-selector border rounded-lg p-2 flex-1">
                                <option value="">Pilih Field</option>
                                <?php foreach ($fields as $actual_field => $label): ?>
                                    <option value="<?= esc($actual_field) ?>" <?= $condition['field'] == $actual_field ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select name="operator[]" class="border rounded-lg p-2" style="width: auto;">
                                <?php foreach ($operators as $key => $label): ?>
                                    <option value="<?= esc($key) ?>" <?= $condition['operator'] == $key ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="value-input-container flex-1">
                                <?php
                                $input_type = 'text';
                                $options = [];
                                $value = $condition['value'];

                                // Sesuaikan dengan nama field dari $user_fields
                                if (in_array($condition['field'], ['id_jurusan', 'id_prodi', 'angkatan', 'tahun_kelulusan', 'id_provinsi', 'role_id', 'jenisKelamin'])) {
                                    $input_type = 'select';
                                    if ($condition['field'] == 'id_jurusan') {
                                        $model = new \App\Models\Jurusan();
                                        $options = $model->select('id, nama_jurusan as name')->findAll();
                                    } elseif ($condition['field'] == 'id_prodi') {
                                        $model = new \App\Models\Prodi();
                                        $options = $model->select('id, nama_prodi as name')->findAll();
                                    } elseif ($condition['field'] == 'id_provinsi') {
                                        $model = new \App\Models\Provincies(); // Sesuaikan dengan model yang benar
                                        $options = $model->select('id, name')->findAll();
                                    } elseif ($condition['field'] == 'role_id') {
                                        $model = new \App\Models\Roles();
                                        $options = $model->select('id, nama as name')->findAll();
                                    } elseif ($condition['field'] == 'jenisKelamin') {
                                        $options = [['id' => 'Laki-Laki', 'name' => 'Laki-Laki'], ['id' => 'Perempuan', 'name' => 'Perempuan']];
                                    } elseif ($condition['field'] == 'angkatan' || $condition['field'] == 'tahun_kelulusan') {
                                        $start_year = date('Y') - 15;
                                        $end_year = date('Y');
                                        $options = [];
                                        for ($y = $end_year; $y >= $start_year; $y--) {
                                            $options[] = ['id' => (string)$y, 'name' => (string)$y];
                                        }
                                    }
                                }

                                if ($input_type == 'select' && !empty($options)): ?>
                                    <select name="value[]" class="w-full border rounded-lg p-2">
                                        <?php foreach ($options as $opt): ?>
                                            <option value="<?= esc($opt['id']) ?>" <?= $opt['id'] == $value ? 'selected' : '' ?>>
                                                <?= esc($opt['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <input type="text" name="value[]" placeholder="Value" value="<?= esc($value) ?>" class="w-full border rounded-lg p-2">
                                <?php endif; ?>
                            </span>
                            <button type="button" class="remove-condition-btn bg-red-500 text-white px-3 py-1 rounded-lg text-sm">
                                Hapus
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="button" id="add-condition-btn" class="hidden mt-2 bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm" style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>;">
                Tambah Kondisi
            </button>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end gap-3 pt-4">
            <a href="<?= base_url('/admin/questionnaire') ?>" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?= base_url('tinymce/tinymce.min.js'); ?>"></script>
<script>
    $(document).ready(function() {
        // Fungsi untuk memuat opsi berdasarkan field yang dipilih
        function loadOptions(fieldSelector, currentValue = '') {
            const selectedField = fieldSelector.val();
            const valueContainer = fieldSelector.closest('.condition-row').find('.value-input-container');

            if (!selectedField) {
                valueContainer.html('<input type="text" name="value[]" placeholder="Value" value="' + currentValue + '" class="w-full border rounded-lg p-2">');
                return;
            }

            $.ajax({
                url: "<?= base_url('/admin/get-conditional-options') ?>", // Perbaikan URL
                type: 'GET',
                data: {
                    field: selectedField
                },
                dataType: 'json',
                success: function(response) {
                    let inputHtml = '';
                    if (response.type === 'select' && response.options && response.options.length > 0) {
                        inputHtml = '<select name="value[]" class="w-full border rounded-lg p-2">';
                        $.each(response.options, function(index, option) {
                            const isSelected = option.id == currentValue ? 'selected' : '';
                            inputHtml += `<option value="${option.id}" ${isSelected}>${option.name}</option>`;
                        });
                        inputHtml += '</select>';
                    } else {
                        inputHtml = '<input type="text" name="value[]" placeholder="Value" value="' + currentValue + '" class="w-full border rounded-lg p-2">';
                    }
                    valueContainer.html(inputHtml);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ' - ' + error + ' - URL: <?= base_url('/admin/questionnaire/getConditionalOptions') ?>');
                    valueContainer.html('<input type="text" name="value[]" placeholder="Error loading options - Cek console browser" value="' + currentValue + '" class="w-full border rounded-lg p-2">');
                }
            });
        }

        // Event handler untuk perubahan pada field selector
        $(document).on('change', '.field-selector', function() {
            const currentValue = $(this).closest('.condition-row').find('[name="value[]"]').val() || '';
            loadOptions($(this), currentValue);
        });

        // Event handler untuk checkbox Conditional Logic
        $('#conditional_logic').on('change', function() {
            if ($(this).is(':checked')) {
                $('.condition-row').first().show();
                $('#add-condition-btn').show();
                // Trigger loadOptions untuk baris pertama jika field sudah dipilih
                const firstSelector = $('.condition-row').first().find('.field-selector');
                if (firstSelector.val()) {
                    loadOptions(firstSelector);
                }
            } else {
                $('.condition-row').hide();
                $('#add-condition-btn').hide();
                $('.condition-row:not(:first)').remove();
            }
        });

        // Event handler untuk tombol "Tambah Kondisi"
        $('#add-condition-btn').on('click', function() {
            const fields = <?php echo json_encode($fields); ?>;
            const operators = <?php echo json_encode($operators); ?>;

            let fieldOptions = '<option value="">Pilih Field</option>';
            for (let actual_field in fields) {
                fieldOptions += `<option value="${actual_field}">${fields[actual_field]}</option>`;
            }

            let operatorOptions = '';
            for (let key in operators) {
                operatorOptions += `<option value="${key}">${operators[key]}</option>`;
            }

            const newRow = `
                <div class="condition-row flex items-center gap-3 p-3 bg-gray-50 rounded-md border">
                    <select name="field_name[]" class="field-selector border rounded-lg p-2 flex-1">
                        ${fieldOptions}
                    </select>
                    <select name="operator[]" class="border rounded-lg p-2" style="width: auto;">
                        ${operatorOptions}
                    </select>
                    <span class="value-input-container flex-1">
                        <input type="text" name="value[]" placeholder="Value" class="w-full border rounded-lg p-2">
                    </span>
                    <button type="button" class="remove-condition-btn bg-red-500 text-white px-3 py-1 rounded-lg text-sm">
                        Hapus
                    </button>
                </div>`;

            $('#conditional-container').append(newRow);
            loadOptions($('.condition-row').last().find('.field-selector'));
        });

        // Event handler untuk tombol "Hapus"
        $(document).on('click', '.remove-condition-btn', function() {
            if ($('.condition-row').length > 1) {
                $(this).closest('.condition-row').remove();
            }
        });

        // Inisialisasi pada halaman edit
        if ($('#conditional_logic').is(':checked')) {
            $('.condition-row').each(function() {
                if ($('.condition-row').length > 1) {
                    $(this).find('.remove-condition-btn').show();
                }
                const currentValue = $(this).find('[name="value[]"]').val() || '';
                loadOptions($(this).find('.field-selector'), currentValue);
            });
        }

        // Inisialisasi TinyMCE untuk announcement
        tinymce.init({
            selector: 'textarea.announcement-editor',
            height: 400,
            menubar: false,
            plugins: 'lists link image table code fullscreen',
            toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | fullscreen code',
            content_style: 'body { font-family:"Figtree", sans-serif; font-size:16px; line-height:1.6 }',
            license_key: 'gpl'
        });
    });
</script>
<?= $this->endSection() ?>
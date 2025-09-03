<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= base_url('css/questioner/edit.css') ?>">
</head>
<body>
    <h2>Edit Kuesioner</h2>
<form action="<?= base_url('/admin/questionnaire/update/' . $questionnaire['id']) ?>" method="post">
        <div>
            <label>Judul Kuesioner</label><br>
            <input type="text" name="title" value="<?= esc($questionnaire['title']) ?>" required>
        </div>
        <div>
            <label>Deskripsi</label><br>
            <textarea name="deskripsi"><?= esc($questionnaire['deskripsi']) ?></textarea>
        </div>
        <div>
            <label for="status">Status :</label>
            <select name="status" id="status">
                <option value="active" <?= $questionnaire['is_active'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="draft" <?= $questionnaire['is_active'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="inactive" <?= $questionnaire['is_active'] == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
            </select>
        </div>
        <div>
            <label for="conditional_logic">
                Conditional Logic <input type="checkbox" name="conditional_logic" id="conditional_logic" value="1" <?= !empty($conditionalLogic) ? 'checked' : '' ?>>
            </label>

            <div id="conditional-container">
                <?php if (!empty($conditionalLogic)): ?>
                    <?php foreach ($conditionalLogic as $i => $condition): ?>
                        <div class="condition-row" style="margin-top:10px; display:flex; align-items:center; gap:10px;">
                            <select name="field_name[]" class="field-selector">
                                <?php foreach ($fields as $f): ?>
                                    <option value="<?= $f ?>" <?= $f == $condition['field'] ? 'selected' : '' ?>><?= ucwords(str_replace('_',' ',$f)) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="operator[]">
                                <?php foreach ($operators as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $key == $condition['operator'] ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="value-input-container">
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
                                    <select name="value[]">
                                        <?php foreach($options as $opt): ?>
                                            <option value="<?= $opt['id'] ?>" <?= $opt['id'] == $value ? 'selected' : '' ?>>
                                                <?= $opt['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <input type="text" name="value[]" placeholder="Value" value="<?= esc($value) ?>">
                                <?php endif; ?>
                            </span>
                            <button type="button" class="remove-condition-btn">Hapus</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="condition-row" style="margin-top:10px; display:flex; align-items:center; gap:10px; display:none;">
                        <select name="field_name[]" class="field-selector">
                            <?php foreach ($fields as $f): ?>
                                <option value="<?= $f ?>"><?= ucwords(str_replace('_',' ',$f)) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="operator[]">
                            <?php foreach ($operators as $key => $label): ?>
                                <option value="<?= $key ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="value-input-container">
                            <input type="text" name="value[]" placeholder="Value">
                        </span>
                        <button type="button" class="remove-condition-btn" style="display:none;">Hapus</button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-condition-btn" style="display: <?= !empty($conditionalLogic) ? 'block' : 'none' ?>;">Tambah Kondisi</button>
        </div>
        <button type="submit">Simpan Perubahan</button>
    </form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Fungsi untuk memuat opsi berdasarkan field yang dipilih
    function loadOptions(fieldSelector) {
        const selectedField = fieldSelector.val();
        const valueContainer = fieldSelector.closest('.condition-row').find('.value-input-container');

        if (!selectedField) {
            valueContainer.html('<input type="text" name="value[]" placeholder="Value">');
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
                    inputHtml = '<select name="value[]">';
                    $.each(response.options, function(index, option) {
                        inputHtml += `<option value="${option.id}">${option.name}</option>`;
                    });
                    inputHtml += '</select>';
                } else {
                    inputHtml = '<input type="text" name="value[]" placeholder="Value">';
                }
                valueContainer.html(inputHtml);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
                valueContainer.html('<input type="text" name="value[]" placeholder="Error loading data">');
            }
        });
    }

    // Event handler untuk perubahan pada field selector
    $(document).on('change', '.field-selector', function() {
        loadOptions($(this));
    });

    // Event handler untuk checkbox Conditional Logic
    $('#conditional_logic').on('change', function() {
        if ($(this).is(':checked')) {
            $('.condition-row').first().show();
            $('#add-condition-btn').show();
        } else {
            $('.condition-row').hide();
            $('#add-condition-btn').hide();
            // Hapus baris tambahan agar form kembali bersih
            $('.condition-row:not(:first)').remove();
        }
    });

    // Event handler untuk tombol "Tambah Kondisi"
    $('#add-condition-btn').on('click', function() {
        const firstRow = $('.condition-row').first();
        const newRow = firstRow.clone();

        // Reset nilai-nilai di baris baru
        newRow.find('select').val(firstRow.find('.field-selector').val());
        newRow.find('.value-input-container').html('<input type="text" name="value[]" placeholder="Value">');

        // Tampilkan tombol "Hapus" pada baris baru
        newRow.find('.remove-condition-btn').show();

        // Tambahkan baris baru ke container
        $('#conditional-container').append(newRow);

        // Panggil fungsi loadOptions untuk baris baru
        loadOptions(newRow.find('.field-selector'));
    });
    
    // Event handler untuk tombol "Hapus"
    $(document).on('click', '.remove-condition-btn', function() {
        if ($('.condition-row').length > 1) {
            $(this).closest('.condition-row').remove();
        }
    });
    
    // Inisialisasi pada halaman edit: tampilkan tombol "Hapus" untuk setiap baris
    if ($('#conditional_logic').is(':checked')) {
        $('.condition-row').each(function() {
            if ($('.condition-row').length > 1) {
                $(this).find('.remove-condition-btn').show();
            }
        });
    }

    // Jalankan loadOptions untuk baris pertama saat halaman dimuat
    loadOptions($('.field-selector').first());
});
</script>
</body>
</html>
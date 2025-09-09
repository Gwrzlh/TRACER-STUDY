
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/questioner/tambah.css') ?>">

<h2>Buat Kuesioner Baru</h2>
<form action="<?= base_url('/admin/questionnaire/store') ?>" method="post" class="form-kuesioner">

    <div>
        <label>Judul Kuesioner</label><br>
        <input type="text" name="title" required>
    </div>
    <div>
        <label>Deskripsi</label><br>
        <textarea name="deskripsi"></textarea>
    </div>
    <div>
        <label for="status">Status :</label>
        <select name="is_active" id="status">
            <option value="active">Aktif</option>
            <option value="draft">Draft</option>
            <option value="inactive">Tidak Aktif</option>
        </select>
    </div>
    <div>
        <label for="conditional_logic">
            Conditional Logic <input type="checkbox" name="conditional_logic" id="conditional_logic" value="1">
        </label>

        <div id="conditional-container">
            <div class="condition-row" style="margin-top:10px; display:flex; align-items:center; gap:10px; display:none;">
                <select name="field_name[]" class="field-selector">
                    <?php foreach ($fields as $f): ?>
                        <option value="<?= $f ?>"><?= ucwords(str_replace('_', ' ', $f)) ?></option>
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
        </div>
        <button type="button" id="add-condition-btn" style="display:none;">Tambah Kondisi</button>
    </div>
    <button type="submit">Simpan</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
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
                error: function() {
                    valueContainer.html('<input type="text" name="value[]" placeholder="Error loading data">');
                }
            });
        }

        // field berubah
        $(document).on('change', '.field-selector', function() {
            loadOptions($(this));
        });

        // aktifkan conditional logic
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

        // tambah kondisi
        $('#add-condition-btn').on('click', function() {
            const firstRow = $('.condition-row').first();
            const newRow = firstRow.clone();

            newRow.find('select').val('');
            newRow.find('.value-input-container').html('<input type="text" name="value[]" placeholder="Value">');
            newRow.find('.remove-condition-btn').show();

            $('#conditional-container').append(newRow);
        });

        // hapus kondisi
        $(document).on('click', '.remove-condition-btn', function() {
            if ($('.condition-row').length > 1) {
                $(this).closest('.condition-row').remove();
            }
        });
    });
</script>

<?= $this->endSection() ?>
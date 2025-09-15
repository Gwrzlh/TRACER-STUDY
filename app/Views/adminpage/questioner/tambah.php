<!-- desain tambah kuesioner -->
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

<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">
    <div class="flex items-center mb-4">
        <img src="/images/logo.png" alt="Tracer Study" class="h-12 mr-3">
        <h2 class="text-xl font-semibold">Buat Kuesioner Baru</h2>
    </div>
    <hr class="mb-6 border-gray-300">   
  <form action="<?= base_url('/admin/questionnaire/store') ?>" method="post" class="space-y-5">

      <!-- Judul -->
      <div>
          <label class="block font-medium text-gray-700 mb-1">Judul Kuesioner</label>
          <input type="text" name="title" required
                 class="w-full border rounded-lg p-2 focus:ring focus:border-blue-400">
      </div>

      <!-- Deskripsi -->
      <div>
          <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
          <textarea name="deskripsi" rows="4"
                    class="w-full border rounded-lg p-2 focus:ring focus:border-blue-400"></textarea>
      </div>

      <!-- Status -->
      <div>
          <label class="block font-medium text-gray-700 mb-1">Status</label>
          <select name="is_active" id="status"
                  class="w-full border rounded-lg p-2 focus:ring focus:border-blue-400">
              <option value="active">Aktif</option>
              <option value="draft">Draft</option>
              <option value="inactive">Tidak Aktif</option>
          </select>
      </div>

      <!-- Conditional Logic -->
      <div>
          <label for="conditional_logic" class="block font-medium text-gray-700">
              <input type="checkbox" name="conditional_logic" id="conditional_logic" value="1" class="mr-2">
              Conditional Logic
          </label>

          <div id="conditional-container" class="mt-3 space-y-3">
              <div class="condition-row hidden flex items-center gap-3">
                  <select name="field_name[]" class="field-selector border rounded-lg p-2">
                      <?php foreach ($fields as $f): ?>
                          <option value="<?= $f ?>"><?= ucwords(str_replace('_', ' ', $f)) ?></option>
                      <?php endforeach; ?>
                  </select>

                  <select name="operator[]" class="border rounded-lg p-2">
                      <?php foreach ($operators as $key => $label): ?>
                          <option value="<?= $key ?>"><?= $label ?></option>
                      <?php endforeach; ?>
                  </select>

                  <span class="value-input-container">
                      <input type="text" name="value[]" placeholder="Value"
                             class="border rounded-lg p-2">
                  </span>

                  <button type="button" class="remove-condition-btn hidden bg-red-500 text-white px-3 py-1 rounded-lg text-sm">
                      Hapus
                  </button>
              </div>
          </div>

          <button type="button" id="add-condition-btn"
                  class="hidden mt-2 bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm">
              Tambah Kondisi
          </button>
      </div>

      <!-- Tombol -->
      <div class="flex gap-3 pt-4">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
              Simpan
          </button>
          <a href="<?= base_url('/admin/questionnaire') ?>"
             class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
              Batal
          </a>
      </div>
  </form>
</div>

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
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
            <input type="number" name="order_no" value="1" min="1" required
                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
        </div>

        <!-- Conditional Logic Checkbox -->
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="conditional_logic" id="conditional_logic" value="1"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2">Aktifkan Conditional Logic</span>
            </label>
        </div>

        <!-- Conditional Logic Container (hidden by default) -->
        <div id="conditional-form" class="hidden border rounded-lg p-4 bg-gray-50">
            <div class="flex items-center gap-2 mb-3">
                <span>Show this page if</span>
                <select name="logic_type" class="border rounded px-2 py-1">
                    <option value="any">Any</option>
                    <option value="all">All</option>
                </select>
                <span>of these conditions match:</span>
            </div>

            <div id="conditional-container" class="space-y-2">
                <div class="flex items-center gap-2">
                    <select name="condition_question_id[]" class="border rounded px-2 py-1">
                        <option value="">Pilih Pertanyaan</option>
                        <?php foreach ($questions as $q): ?>
                            <option value="<?= $q['id'] ?>"><?= esc($q['question_text']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="operator[]" class="border rounded px-2 py-1">
                        <?php foreach ($operators as $key => $label): ?>
                            <option value="<?= $key ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text" name="condition_value[]" placeholder="Value"
                        class="border rounded px-2 py-1 flex-1">

                    <button type="button"
                        class="remove-condition-btn bg-red-500 text-white px-2 py-1 rounded hidden">Hapus</button>
                </div>
            </div>

            <button type="button" id="add-condition-btn" class="mt-3 bg-blue-500 text-white px-3 py-1 rounded">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Fungsi untuk memuat opsi jawaban pertanyaan
    function loadConditionalValueInput(questionSelector, initialValue = null) {
        const questionId = questionSelector.val();
        const valueContainer = questionSelector.closest('.condition-row').find('.value-input-container');

        // Jika tidak ada pertanyaan yang dipilih, kembalikan input teks default
        if (!questionId) {
            valueContainer.html('<input type="text" name="condition_value[]" placeholder="Value" class="form-control" required>');
            return;
        }

        $.ajax({
            url: "<?= base_url('admin/questionnaire/pages/getQuestionOptions') ?>", // Ganti dengan rute yang benar
            type: 'GET',
            data: { question_id: questionId },
            dataType: 'json',
            success: function(response) {
                console.log('AJAX SUCECSS LAAH', response);
                if (response.type === 'select' && response.options.length > 0) {
                    let selectHtml = `<select name="condition_value[]" class="form-control" required>`;
                    response.options.forEach(function(option) {
                        const isSelected = (initialValue !== null && initialValue == option.id);
                        selectHtml += `<option value="${option.id}" ${isSelected ? 'selected' : ''}>${option.option_text}</option>`;
                    });
                    selectHtml += `</select>`;
                    valueContainer.html(selectHtml);
                } else {
                    // Jika pertanyaan tidak memiliki opsi (misal: text, number), tampilkan input teks
                    valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Value" class="form-control" value="${initialValue || ''}" required>`);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error, xhr.responseText); // Perbaiki debug
                valueContainer.html(`<input type="text" name="condition_value[]" placeholder="Error loading options" class="form-control" value="${initialValue || ''}" required>`);
            }
        });
    }

    // Event handler saat pertanyaan berubah
    $(document).on('change', '.question-selector', function() {
        loadConditionalValueInput($(this));
    });

    // Event handler saat tombol "Tambah Kondisi" diklik
    $('#add-condition-btn').on('click', function() {
        const templateRow = `
            <div class="condition-row d-flex align-items-center gap-2 mb-2">
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
                    <input type="text" name="condition_value[]" placeholder="Value" class="form-control" required>
                </span>
                <button type="button" class="remove-condition-btn btn btn-danger btn-sm">Hapus</button>
            </div>
        `;
        $('#conditional-container').append(templateRow);
    });

    // Event handler untuk tombol "Hapus"
    $(document).on('click', '.remove-condition-btn', function() {
        if ($('.condition-row').length > 1) {
            $(this).closest('.condition-row').remove();
        } else {
            // Jika hanya satu baris, reset
            $(this).closest('.condition-row').find('.question-selector').val('');
            $(this).closest('.condition-row').find('.value-input-container').html('<input type="text" name="condition_value[]" placeholder="Value" class="form-control">');
            $(this).closest('.condition-row').find('.remove-condition-btn').hide();
        }
    });

    // Inisialisasi awal saat halaman dimuat
    $('#conditional_logic').on('change', function() {
        $('#conditional-form').toggle(this.checked);
    }).trigger('change');

    // Inisialisasi baris conditional yang sudah ada (untuk halaman edit)
    <?php if (isset($conditionalLogic['conditions'])): ?>
        <?php foreach ($conditionalLogic['conditions'] as $index => $condition): ?>
            // Untuk baris pertama
            if (<?= $index ?> === 0) {
                $('.condition-row:eq(0) .question-selector').val('<?= $condition['question_id'] ?>');
                $('.condition-row:eq(0) select[name="operator[]"]').val('<?= $condition['operator'] ?>');
                loadConditionalValueInput($('.condition-row:eq(0) .question-selector'), '<?= $condition['value'] ?>');
                $('.condition-row:eq(0) .remove-condition-btn').show();
            } else {
                // Untuk baris selanjutnya, tambahkan baris baru
                const newRow = $('.condition-row:eq(0)').clone();
                newRow.find('.question-selector').val('<?= $condition['question_id'] ?>');
                newRow.find('select[name="operator[]"]').val('<?= $condition['operator'] ?>');
                newRow.find('.remove-condition-btn').show();
                
                const valueContainer = newRow.find('.value-input-container');
                valueContainer.html('<input type="text" name="condition_value[]" placeholder="Value" class="form-control" value="<?= $condition['value'] ?>">');
                $('#conditional-container').append(newRow);
                loadConditionalValueInput(newRow.find('.question-selector'), '<?= $condition['value'] ?>');
            }
        <?php endforeach; ?>
    <?php endif; ?>

});
</script>

<?= $this->endSection() ?>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
 <link rel="stylesheet" href="<?= base_url('css/questioner/page/tambah.css') ?>">
<div class="container mt-4">
    <h2>Tambah Halaman Kuesioner</h2>

    <form action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/store") ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Judul Halaman</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="order_no" class="form-control" value="1" min="1" required>
        </div>


       
<div class="mb-3">
    <label for="conditional_logic">
        <input type="checkbox" name="conditional_logic" id="conditional_logic" value="1">
        **Aktifkan Conditional Logic**
    </label>
</div>

<div id="conditional-form" style="display:none;">
    <div class="d-flex align-items-center mb-2">
        <span class="me-2">Show this page if</span>
        <select name="logic_type" class="form-control me-2" style="width: auto;">
            <option value="any">Any</option>
            <option value="all">All</option>
        </select>
        <span>of this/these following match:</span>
    </div>

    <div id="conditional-container" class="mb-3">
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
                <input type="text" name="condition_value[]" placeholder="Value" class="form-control">
            </span>
            <button type="button" class="remove-condition-btn btn btn-danger btn-sm" style="display:none;">Hapus</button>
        </div>
    </div>
    
    <button type="button" id="add-condition-btn" class="btn btn-primary btn-sm">Tambah Kondisi</button>
</div>

        <hr>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages") ?>" class="btn btn-secondary">Batal</a>
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
            error: function() {
                // Jika terjadi error, kembalikan input teks default
                valueContainer.html('<input type="text" name="condition_value[]" placeholder="Value" class="form-control" required>');
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
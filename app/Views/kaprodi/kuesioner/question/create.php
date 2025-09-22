<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pertanyaan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h4>Tambah Pertanyaan - Section Title</h4>
        <form id="questionForm" method="post">
            <!-- Text Pertanyaan -->
            <div class="mb-3">
                <label class="form-label">Teks Pertanyaan</label>
                <textarea name="question_text" class="form-control" rows="3" required></textarea>
            </div>

            <!-- Tipe Pertanyaan -->
            <div class="mb-3">
                <label class="form-label">Jenis Pertanyaan</label>
                <select name="question_type" id="question_type" class="form-select" required>
                    <option value="text">Text Pendek</option>
                    <option value="textarea">Text Panjang</option>
                    <option value="radio">Pilihan Tunggal</option>
                    <option value="checkbox">Pilihan Ganda</option>
                    <option value="dropdown">Dropdown</option>
                    <option value="number">Angka</option>
                    <option value="date">Tanggal</option>
                    <option value="email">Email</option>
                    <option value="file">Upload File</option>
                    <option value="rating">Rating</option>
                    <option value="matrix">Matriks</option>
                </select>
            </div>

            <!-- Opsi Jawaban -->
            <div class="mb-3" id="options-container" style="display:none;">
                <label class="form-label">Opsi Jawaban</label>
                <div id="options-list">
                    <div class="input-group mb-2">
                        <input type="text" name="options[]" class="form-control" placeholder="Opsi 1">
                        <button type="button" class="btn btn-danger remove-option">&times;</button>
                    </div>
                </div>
                <button type="button" id="add-option" class="btn btn-sm btn-primary">+ Tambah Opsi</button>
            </div>

            <!-- Pertanyaan Wajib -->
            <div class="mb-3 form-check">
                <input type="hidden" name="is_required" value="0">
                <input type="checkbox" name="is_required" value="1" id="is_required" class="form-check-input">
                <label class="form-check-label" for="is_required">Pertanyaan Wajib</label>
            </div>

            <!-- Urutan -->
            <div class="mb-3">
                <label class="form-label">Urutan Pertanyaan</label>
                <input type="number" name="order_no" class="form-control" value="1" min="1" required>
            </div>

            <!-- Conditional Logic - IMPROVED VERSION -->
            <div class="mb-3 p-3 border rounded">
                <div class="form-check mb-2">
                    <input type="checkbox" id="enable_conditional" class="form-check-input">
                    <label class="form-check-label" for="enable_conditional">
                        <strong>Aktifkan Conditional Logic</strong>
                    </label>
                </div>

                <div id="conditional-container" style="display: none;">
                    <small class="text-muted mb-2 d-block">Pertanyaan ini hanya akan ditampilkan jika kondisi terpenuhi</small>

                    <!-- Parent Question Selection -->
                    <div class="mb-2">
                        <label class="form-label">Berdasarkan Pertanyaan:</label>
                        <select name="parent_question_id" id="parent_question_id" class="form-select">
                            <option value="">-- Pilih Pertanyaan --</option>
                            <!-- Sample data - dalam implementasi, ini dari PHP loop -->
                            <option value="1">Apakah Anda sudah menikah?</option>
                            <option value="2">Berapa usia Anda?</option>
                            <option value="3">Status pekerjaan Anda?</option>
                        </select>
                    </div>

                    <!-- Condition Value Selection - Dynamic -->
                    <div class="mb-2" id="condition-value-container" style="display: none;">
                        <label class="form-label">Jika Jawabannya:</label>
                        <select name="condition_value" id="condition_value" class="form-select">
                            <option value="">-- Pilih Jawaban --</option>
                        </select>
                    </div>

                    <!-- Manual Input (fallback) -->
                    <div class="mb-2" id="manual-condition-container" style="display: none;">
                        <label class="form-label">Atau masukkan nilai manual:</label>
                        <input type="text" name="manual_condition_value" id="manual_condition_value" class="form-control" placeholder="Contoh: Ya, Sudah, 25, dsb">
                    </div>

                    <small class="text-muted">
                        💡 Pertanyaan ini hanya akan muncul ketika jawaban sesuai dengan kondisi di atas
                    </small>
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" onclick="history.back()">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan Pertanyaan</button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('question_type');
            const optionsContainer = document.getElementById('options-container');
            const addOptionBtn = document.getElementById('add-option');
            const optionsList = document.getElementById('options-list');

            // Conditional Logic Elements
            const enableConditional = document.getElementById('enable_conditional');
            const conditionalContainer = document.getElementById('conditional-container');
            const parentQuestionSelect = document.getElementById('parent_question_id');
            const conditionValueContainer = document.getElementById('condition-value-container');
            const conditionValueSelect = document.getElementById('condition_value');
            const manualConditionContainer = document.getElementById('manual-condition-container');
            const manualConditionInput = document.getElementById('manual_condition_value');

            // Sample question options data (dalam implementasi nyata, ini dari AJAX)
            const questionOptions = {
                1: [{
                        value: 'ya',
                        text: 'Ya'
                    },
                    {
                        value: 'tidak',
                        text: 'Tidak'
                    }
                ],
                2: [{
                        value: 'dibawah_25',
                        text: 'Di bawah 25 tahun'
                    },
                    {
                        value: '25_35',
                        text: '25-35 tahun'
                    },
                    {
                        value: 'diatas_35',
                        text: 'Di atas 35 tahun'
                    }
                ],
                3: [{
                        value: 'pegawai',
                        text: 'Pegawai'
                    },
                    {
                        value: 'wiraswasta',
                        text: 'Wiraswasta'
                    },
                    {
                        value: 'mahasiswa',
                        text: 'Mahasiswa'
                    },
                    {
                        value: 'tidak_bekerja',
                        text: 'Tidak Bekerja'
                    }
                ]
            };

            // Toggle options based on question type
            function toggleOptions() {
                const type = typeSelect.value;
                if (['radio', 'checkbox', 'dropdown'].includes(type)) {
                    optionsContainer.style.display = 'block';
                } else {
                    optionsContainer.style.display = 'none';
                }
            }

            // Add new option
            addOptionBtn.addEventListener('click', function() {
                const optionCount = optionsList.children.length + 1;
                const optionHtml = `
            <div class="input-group mb-2">
                <input type="text" name="options[]" class="form-control" placeholder="Opsi ${optionCount}">
                <button type="button" class="btn btn-danger remove-option">&times;</button>
            </div>`;
                optionsList.insertAdjacentHTML('beforeend', optionHtml);
            });

            // Remove option
            optionsList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-option')) {
                    e.target.closest('.input-group').remove();
                }
            });

            // Toggle conditional logic
            enableConditional.addEventListener('change', function() {
                if (this.checked) {
                    conditionalContainer.style.display = 'block';
                } else {
                    conditionalContainer.style.display = 'none';
                    // Reset form
                    parentQuestionSelect.value = '';
                    conditionValueSelect.value = '';
                    manualConditionInput.value = '';
                    conditionValueContainer.style.display = 'none';
                    manualConditionContainer.style.display = 'none';
                }
            });

            // Load options when parent question changes
            parentQuestionSelect.addEventListener('change', function() {
                const questionId = this.value;

                if (questionId) {
                    // Clear previous options
                    conditionValueSelect.innerHTML = '<option value="">-- Pilih Jawaban --</option>';

                    // Check if we have options for this question
                    if (questionOptions[questionId]) {
                        // Populate options
                        questionOptions[questionId].forEach(option => {
                            const optionElement = new Option(option.text, option.value);
                            conditionValueSelect.add(optionElement);
                        });

                        conditionValueContainer.style.display = 'block';
                        manualConditionContainer.style.display = 'block';
                    } else {
                        // No predefined options, show manual input only
                        conditionValueContainer.style.display = 'none';
                        manualConditionContainer.style.display = 'block';
                    }
                } else {
                    conditionValueContainer.style.display = 'none';
                    manualConditionContainer.style.display = 'none';
                }
            });

            // Initialize
            typeSelect.addEventListener('change', toggleOptions);
            toggleOptions();

            // Form submission
            document.getElementById('questionForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Validation
                const questionText = document.querySelector('textarea[name="question_text"]').value.trim();
                const questionType = typeSelect.value;

                if (!questionText) {
                    alert('Teks pertanyaan wajib diisi!');
                    return;
                }

                // Check if options needed
                if (['radio', 'checkbox', 'dropdown'].includes(questionType)) {
                    const options = document.querySelectorAll('input[name="options[]"]');
                    let hasValidOption = false;

                    options.forEach(option => {
                        if (option.value.trim()) hasValidOption = true;
                    });

                    if (!hasValidOption) {
                        alert('Minimal satu opsi jawaban harus diisi untuk jenis pertanyaan ini!');
                        return;
                    }
                }

                // Process conditional logic
                if (enableConditional.checked && parentQuestionSelect.value) {
                    const conditionValue = conditionValueSelect.value || manualConditionInput.value;
                    if (!conditionValue) {
                        alert('Nilai kondisi harus diisi jika conditional logic diaktifkan!');
                        return;
                    }
                }

                alert('Form validation passed! Ready to submit to server.');
                // In real implementation: this.submit();
            });

            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('=== FORM DEBUG ===');

                // Cek question type
                const questionType = document.getElementById('question_type').value;
                console.log('Question Type:', questionType);

                // Cek options
                const optionInputs = document.querySelectorAll('input[name="options[]"]');
                console.log('Option Inputs Found:', optionInputs.length);

                const optionValues = [];
                optionInputs.forEach((input, index) => {
                    console.log(`Option ${index + 1}:`, input.value);
                    optionValues.push(input.value);
                });

                console.log('All Options:', optionValues);

                // Cek apakah form data akan dikirim
                const formData = new FormData(this);
                console.log('Form Data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }

                // Jangan submit dulu, biar bisa lihat console
                e.preventDefault();
                alert('Check console for debug info. Remove preventDefault to actually submit.');
            });
        });
    </script>
</body>

</html>
<div class="container mt-4">
    <h2>Edit Kontak</h2>

    <form action="/admin/kontak/update/<?= $kontak['id'] ?>" method="post">
        <div class="mb-3">
            <label>Kategori</label>
            <select id="kategori" name="kategori" class="form-select" required onchange="showAccounts()">
                <option value="">-- Pilih Kategori --</option>
                <option value="Wakil Direktur" <?= $kontak['kategori'] == 'Wakil Direktur' ? 'selected' : '' ?>>Wakil Direktur</option>
                <option value="Tim Tracer" <?= $kontak['kategori'] == 'Tim Tracer' ? 'selected' : '' ?>>Tim Tracer</option>
                <option value="Surveyor" <?= $kontak['kategori'] == 'Surveyor' ? 'selected' : '' ?>>Surveyor</option>
            </select>
        </div>

        <div class="mb-3">
            <label id="labelNama">Nama / NIM</label>
            <select name="id_account" id="id_account" class="form-select" required>
                <option value="">-- Pilih --</option>
            </select>
        </div>

        <div id="previewData" style="border:1px solid #ccc;padding:10px;display:none;">
            <h5>Preview Data</h5>
            <div id="previewContent"></div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/admin/kontak" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    const wakilDirektur = <?= json_encode($wakilDirektur) ?>;
    const teamTracer = <?= json_encode($teamTracer) ?>;
    const surveyors = <?= json_encode($surveyors) ?>;

    const kategoriSelect = document.getElementById('kategori');
    const idAccountSelect = document.getElementById('id_account');
    const previewDiv = document.getElementById('previewData');
    const previewContent = document.getElementById('previewContent');
    const labelNama = document.getElementById('labelNama');

    function showAccounts(selectedId = null) {
        const kategori = kategoriSelect.value;
        let list = [];
        if (kategori === 'Wakil Direktur') list = wakilDirektur;
        else if (kategori === 'Tim Tracer') list = teamTracer;
        else if (kategori === 'Surveyor') list = surveyors;

        idAccountSelect.innerHTML = '<option value="">-- Pilih --</option>';
        previewDiv.style.display = 'none';
        previewContent.innerHTML = '';

        list.forEach(acc => {
            const label = kategori === 'Surveyor' ? acc.nim : acc.nama_lengkap;
            const option = document.createElement('option');
            option.value = acc.id_account;
            option.text = label;
            option.setAttribute('data-item', JSON.stringify(acc));
            if (selectedId && selectedId == acc.id_account) option.selected = true;
            idAccountSelect.appendChild(option);
        });

        if (selectedId) {
            const selectedOption = idAccountSelect.querySelector('option[selected]');
            if (selectedOption) {
                const data = JSON.parse(selectedOption.getAttribute('data-item'));
                let html = '<table border="1" cellpadding="5">';
                for (const key in data) html += `<tr><td><strong>${key}</strong></td><td>${data[key]}</td></tr>`;
                html += '</table>';
                previewDiv.style.display = 'block';
                previewContent.innerHTML = html;
            }
        }
    }

    kategoriSelect.addEventListener('change', function() {
        labelNama.textContent = this.value === 'Surveyor' ? 'NIM' : 'Nama';
        showAccounts();
    });

    idAccountSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (!selectedOption.value) {
            previewDiv.style.display = 'none';
            return;
        }
        const data = JSON.parse(selectedOption.getAttribute('data-item'));
        let html = '<table border="1" cellpadding="5">';
        for (const key in data) html += `<tr><td><strong>${key}</strong></td><td>${data[key]}</td></tr>`;
        html += '</table>';
        previewDiv.style.display = 'block';
        previewContent.innerHTML = html;
    });

    // Load awal
    showAccounts('<?= $kontak['id_account'] ?>');
</script>
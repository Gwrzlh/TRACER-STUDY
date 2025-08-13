<div class="container mt-4">
    <h2>Tambah Kontak</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="/admin/kontak/store" method="post">
        <div class="mb-3">
            <label>Kategori</label>
            <select id="kategori" name="kategori" class="form-select" required onchange="showAccounts()">
                <option value="">-- Pilih Kategori --</option>
                <option value="Wakil Direktur">Wakil Direktur</option>
                <option value="Tim Tracer">Tim Tracer</option>
                <option value="Surveyor">Surveyor</option>
            </select>
        </div>

        <div class="mb-3">
            <label id="labelNama">Nama/NIM</label>
            <select name="id_account" id="id_account" class="form-select" required>
                <option value="">-- Pilih Nama --</option>
            </select>
        </div>

        <div id="previewData" style="border:1px solid #ccc;padding:10px;display:none;">
            <h5>Preview Data</h5>
            <div id="previewContent"></div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/admin/kontak" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    const wakilDirektur = <?= json_encode($wakilDirektur) ?>;
    const teamTracer = <?= json_encode($teamTracer) ?>;
    const surveyors = <?= json_encode($surveyors) ?>;

    function showAccounts() {
        const kategori = document.getElementById('kategori').value;
        const select = document.getElementById('id_account');
        const previewDiv = document.getElementById('previewData');
        const previewContent = document.getElementById('previewContent');

        select.innerHTML = '<option value="">-- Pilih --</option>';
        previewDiv.style.display = 'none';
        previewContent.innerHTML = '';

        let list = [];
        if (kategori === 'Wakil Direktur') list = wakilDirektur;
        else if (kategori === 'Tim Tracer') list = teamTracer;
        else if (kategori === 'Surveyor') list = surveyors;

        list.forEach(acc => {
            const label = kategori === 'Surveyor' ? acc.nim : acc.nama_lengkap;
            const option = document.createElement('option');
            option.value = acc.id_account;
            option.text = label;
            option.setAttribute('data-item', JSON.stringify(acc));
            select.appendChild(option);
        });
    }

    document.getElementById('id_account').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (!selected.value) {
            document.getElementById('previewData').style.display = 'none';
            return;
        }
        const data = JSON.parse(selected.getAttribute('data-item'));
        let html = '<table border="1" cellpadding="5">';
        for (const key in data) {
            html += `<tr><td><strong>${key}</strong></td><td>${data[key]}</td></tr>`;
        }
        html += '</table>';
        document.getElementById('previewData').style.display = 'block';
        document.getElementById('previewContent').innerHTML = html;
    });
</script>
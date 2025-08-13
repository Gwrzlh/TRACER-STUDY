<h2>Tambah Kontak</h2>

<form action="/admin/kontak/store" method="post">
    <label>Kategori</label>
    <select name="kategori" id="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="Wakil Direktur">Wakil Direktur</option>
        <option value="Team Tracer">Team Tracer</option>
        <option value="Surveyor">Surveyor</option>
    </select>

    <br><br>
    <label>Nama</label>
    <select name="id_account" id="namaDropdown" required>
        <option value="">-- Pilih Nama --</option>
    </select>

    <br><br>
    <button type="submit">Simpan</button>
</form>

<script>
    const dataWakilDirektur = <?= json_encode($wakilDirektur) ?>;
    const dataTeamTracer = <?= json_encode($teamTracer) ?>;
    const dataSurveyor = <?= json_encode($surveyors) ?>;

    document.getElementById('kategori').addEventListener('change', function() {
        const kategori = this.value;
        const dropdown = document.getElementById('namaDropdown');
        dropdown.innerHTML = '<option value="">-- Pilih Nama --</option>';

        let data = [];
        if (kategori === 'Wakil Direktur') {
            data = dataWakilDirektur;
        } else if (kategori === 'Team Tracer') {
            data = dataTeamTracer;
        } else if (kategori === 'Surveyor') {
            data = dataSurveyor;
        }

        data.forEach(item => {
            dropdown.innerHTML += `<option value="${item.id}">${item.nama_lengkap}</option>`;
        });
    });
</script>
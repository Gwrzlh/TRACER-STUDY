<h2>Edit Kontak</h2>

<form action="/admin/kontak/update/<?= $kontak['id'] ?>" method="post">
    <label>Kategori</label>
    <select name="kategori" id="kategori" required>
        <option value="Wakil Direktur" <?= $kontak['kategori'] == 'Wakil Direktur' ? 'selected' : '' ?>>Wakil Direktur</option>
        <option value="Team Tracer" <?= $kontak['kategori'] == 'Team Tracer' ? 'selected' : '' ?>>Team Tracer</option>
        <option value="Surveyor" <?= $kontak['kategori'] == 'Surveyor' ? 'selected' : '' ?>>Surveyor</option>
    </select>

    <br><br>
    <label>Nama</label>
    <select name="id_account" id="namaDropdown" required>
        <option value="">-- Pilih Nama --</option>
    </select>

    <br><br>
    <button type="submit">Update</button>
</form>

<script>
    const dataWakilDirektur = <?= json_encode($wakilDirektur) ?>;
    const dataTeamTracer = <?= json_encode($teamTracer) ?>;
    const dataSurveyor = <?= json_encode($surveyors) ?>;

    function populateDropdown(kategori, selectedId) {
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
            dropdown.innerHTML += `<option value="${item.id}" ${item.id == selectedId ? 'selected' : ''}>${item.nama_lengkap}</option>`;
        });
    }

    document.getElementById('kategori').addEventListener('change', function() {
        populateDropdown(this.value, '<?= $kontak['id_account'] ?>');
    });

    // Load awal dropdown sesuai data kontak
    populateDropdown('<?= $kontak['kategori'] ?>', '<?= $kontak['id_account'] ?>');
</script>
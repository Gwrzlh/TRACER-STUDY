<div class="container mt-4">
    <h2>Edit Kontak</h2>

    <form action="<?= base_url('admin/kontak/update/' . $kontak['id']) ?>" method="post">
        <?= csrf_field() ?>

        <!-- Pilih Kategori -->
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select name="kategori" id="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Wakil Direktur" <?= $kontak['kategori'] == 'Wakil Direktur' ? 'selected' : '' ?>>Wakil Direktur</option>
                <option value="Tim Tracer" <?= $kontak['kategori'] == 'Tim Tracer' ? 'selected' : '' ?>>Tim Tracer</option>
                <option value="Surveyor" <?= $kontak['kategori'] == 'Surveyor' ? 'selected' : '' ?>>Surveyor</option>
            </select>
        </div>

        <!-- Pilih Account -->
        <div class="mb-3">
            <label for="id_account" class="form-label">Nama / NIM</label>
            <select name="id_account" id="id_account" class="form-control" required>
                <option value="">-- Pilih Nama --</option>
                <?php foreach ($accounts as $acc): ?>
                    <option value="<?= $acc['id_account'] ?>" data-item='<?= json_encode($acc) ?>'
                        <?= $kontak['id_account'] == $acc['id_account'] ? 'selected' : '' ?>>
                        <?= $acc['nama_lengkap'] ?? $acc['nim'] ?? '-' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Preview Data Lengkap -->
        <div class="mb-3" id="preview" style="display: none;">
            <h5>Preview Data Lengkap</h5>
            <table class="table table-bordered" id="previewTable"></table>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= base_url('admin/kontak') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategori');
        const accountSelect = document.getElementById('id_account');
        const previewDiv = document.getElementById('preview');
        const previewTable = document.getElementById('previewTable');
        let currentId = '<?= $kontak['id_account'] ?>';

        function updatePreview() {
            const selectedOption = accountSelect.options[accountSelect.selectedIndex];
            previewTable.innerHTML = '';
            if (selectedOption && selectedOption.value) {
                const data = JSON.parse(selectedOption.getAttribute('data-item'));
                let html = '';
                for (const key in data) {
                    html += `<tr><td><strong>${key}</strong></td><td>${data[key] ?? '-'}</td></tr>`;
                }
                previewTable.innerHTML = html;
                previewDiv.style.display = 'block';
            } else {
                previewDiv.style.display = 'none';
            }
        }

        // Preview awal
        updatePreview();

        // Saat kategori berubah
        kategoriSelect.addEventListener('change', function() {
            const kategori = this.value;
            accountSelect.innerHTML = '<option value="">-- Pilih Nama --</option>';
            previewDiv.style.display = 'none';

            if (!kategori) return;

            fetch(`<?= base_url('admin/kontak/getAccountsByKategori') ?>/${kategori}`)
                .then(res => res.json())
                .then(data => {
                    let foundCurrent = false;
                    data.forEach(acc => {
                        const option = document.createElement('option');
                        option.value = acc.id_account;
                        option.textContent = acc.nama_lengkap ?? acc.nim ?? '-';
                        option.setAttribute('data-item', JSON.stringify(acc));

                        if (acc.id_account == currentId) {
                            option.selected = true;
                            foundCurrent = true;
                        }

                        accountSelect.appendChild(option);
                    });

                    // Jika data lama tidak ada di list baru, tambahkan
                    if (!foundCurrent && currentId) {
                        const oldOption = document.createElement('option');
                        oldOption.value = currentId;
                        oldOption.textContent = selectedOption.textContent;
                        oldOption.setAttribute('data-item', selectedOption.getAttribute('data-item'));
                        oldOption.selected = true;
                        accountSelect.appendChild(oldOption);
                    }

                    updatePreview();
                })
                .catch(err => console.error(err));
        });

        accountSelect.addEventListener('change', updatePreview);
    });
</script>
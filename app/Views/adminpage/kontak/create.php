    <div class="container mt-4">
        <h2>Tambah Kontak</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="/admin/kontak/store" method="post">
            <div class="mb-3">
                <label>Kategori</label>
                <select id="kategori" name="kategori" class="form-select" required onchange="loadAccounts()">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Wakil Direktur">Wakil Direktur</option>
                    <option value="Tim Tracer">Tim Tracer</option>
                    <option value="Surveyor">Surveyor</option>
                </select>
            </div>

            <div id="accountList" style="display:none;">
                <h5>Daftar Kontak</h5>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Pilih</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Detail Lainnya</th>
                        </tr>
                    </thead>
                    <tbody id="accountTableBody">
                        <!-- data akan diisi via AJAX -->
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            <a href="/admin/kontak" class="btn btn-secondary mt-3">Batal</a>
        </form>
    </div>

    <script>
        function loadAccounts() {
            const kategori = document.getElementById('kategori').value;
            const tableBody = document.getElementById('accountTableBody');
            const accountListDiv = document.getElementById('accountList');

            tableBody.innerHTML = '';
            accountListDiv.style.display = 'none';

            if (!kategori) return;

            fetch(`/admin/kontak/getByKategori/${encodeURIComponent(kategori)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="4" class="text-center">Tidak ada data tersedia</td></tr>`;
                    } else {
                        data.forEach(acc => {
                            const tr = document.createElement('tr');

                            // Checkbox
                            const tdCheck = document.createElement('td');
                            tdCheck.innerHTML = `<input type="checkbox" name="id_account[]" value="${acc.id_account}">`;
                            tr.appendChild(tdCheck);

                            // Nama Lengkap
                            const tdNama = document.createElement('td');
                            tdNama.textContent = acc.nama_lengkap || acc.nim || '-';
                            tr.appendChild(tdNama);

                            // Email
                            const tdEmail = document.createElement('td');
                            tdEmail.textContent = acc.email || '-';
                            tr.appendChild(tdEmail);

                            // Detail lainnya (preview semua field)
                            const tdDetail = document.createElement('td');
                            let detailHtml = '<ul style="margin:0;padding-left:16px;">';
                            for (const key in acc) {
                                if (key !== 'id_account' && key !== 'nama_lengkap' && key !== 'email') {
                                    detailHtml += `<li><strong>${key}</strong>: ${acc[key] ?? '-'}</li>`;
                                }
                            }
                            detailHtml += '</ul>';
                            tdDetail.innerHTML = detailHtml;
                            tr.appendChild(tdDetail);

                            tableBody.appendChild(tr);
                        });
                    }

                    accountListDiv.style.display = 'block';
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal memuat data.');
                });
        }
    </script>
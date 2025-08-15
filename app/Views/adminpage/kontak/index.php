<div class="container mt-4">
    <h2 class="mb-4">Daftar Kontak - Wakil Direktur & Tim Tracer</h2>
    <a href="/admin/kontak/create" class="btn btn-primary mb-3">Tambah Kontak</a>
    <a href="/admin/kontak/deleteKategori/Wakil%20Direktur" class="btn btn-danger mb-3" onclick="return confirm('Hapus semua Wakil Direktur?')">Hapus Semua Wakil Direktur</a>
    <a href="/admin/kontak/deleteKategori/Tim%20Tracer" class="btn btn-danger mb-3" onclick="return confirm('Hapus semua Tim Tracer?')">Hapus Semua Tim Tracer</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach (array_merge($wakilDirektur, $teamTracer) as $k): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($k['kategori']) ?></td>
                    <td><?= esc($k['nama_lengkap']) ?></td>
                    <td><?= esc($k['email']) ?></td>
                    <td>
                        <a href="/admin/kontak/edit/<?= $k['kontak_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/admin/kontak/delete/<?= $k['kontak_id'] ?>" onclick="return confirm('Hapus kontak ini?')" class="btn btn-sm btn-danger">Hapus</a>
                        <button type="button" class="btn btn-sm btn-info" onclick='showPreview(<?= json_encode($k) ?>)'>Preview</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($wakilDirektur) && empty($teamTracer)): ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada data Wakil Direktur atau Tim Tracer</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2 class="mt-5 mb-3">Daftar Kontak - Surveyor</h2>
    <a href="/admin/kontak/deleteKategori/Surveyor" class="btn btn-danger mb-3" onclick="return confirm('Hapus semua Surveyor?')">Hapus Semua Surveyor</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Prodi</th>
                <th>Nama Lengkap</th>
                <th>NIM</th>
                <th>Email</th>
                <th>WA</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($surveyors as $s): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($s['nama_prodi'] ?? '-') ?></td>
                    <td><?= esc($s['nama_lengkap'] ?? '-') ?></td>
                    <td><?= esc($s['nim'] ?? '-') ?></td>
                    <td><?= esc($s['email'] ?? '-') ?></td>
                    <td><?= esc($s['notlp'] ?? '-') ?></td>
                    <td>
                        <a href="/admin/kontak/edit/<?= $s['kontak_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/admin/kontak/delete/<?= $s['kontak_id'] ?>" onclick="return confirm('Hapus kontak ini?')" class="btn btn-sm btn-danger">Hapus</a>
                        <button type="button" class="btn btn-sm btn-info" onclick='showPreview(<?= json_encode($s) ?>)'>Preview</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($surveyors)): ?>
                <tr>
                    <td colspan="7" class="text-center">Belum ada data Surveyor</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Detail Kontak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showPreview(data) {
        let html = '<table class="table table-bordered">';
        for (const key in data) {
            html += `<tr><td><strong>${key}</strong></td><td>${data[key] ?? '-'}</td></tr>`;
        }
        html += '</table>';
        document.getElementById('previewContent').innerHTML = html;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }
</script>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Kontak</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('css/kontak.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="p-4">

    <div class="container">
        <h2 class="mb-4">Manajemen Kontak</h2>

        <!-- Pilih kategori & search -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="searchForm" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Pilih Kategori</label>
                        <select name="kategori" id="kategori" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Surveyor">Surveyor</option>
                            <option value="Tim Tracer">Tim Tracer</option>
                            <option value="Wakil Direktur">Wakil Direktur</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cari</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan NIM / Nama" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-cari w-100">Cari</button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Hasil Pencarian -->
        <div id="searchResult" class="mb-4" style="display:none;">
            <div class="card">
                <div class="card-header">Hasil Pencarian</div>
                <div class="card-body">
                    <form id="addForm" method="post" action="<?= site_url('admin/kontak/store-multiple') ?>">
                        <input type="hidden" name="kategori" id="addKategori">
                        <table class="table table-bordered">
                            <thead>
                                <tr id="searchCols">
                                    <!-- Kolom akan diisi JS sesuai kategori -->
                                </tr>
                            </thead>
                            <tbody id="resultBody"></tbody>
                        </table>
                        <button type="submit" class="btn btn-success">Tambah ke Kontak</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Kontak per kategori -->
        <div class="row">
            <?php
            $categories = [
                'Surveyor' => ['data' => $surveyors, 'cols' => ['Nama', 'NIM', 'No.Telp', 'Email', 'Prodi', 'Jurusan', 'Tahun Lulus'], 'fields' => ['nama_lengkap', 'nim', 'notlp', 'email', 'nama_prodi', 'nama_jurusan', 'tahun_kelulusan']],
                'Tim Tracer' => ['data' => $teamTracer, 'cols' => ['Nama', 'Email'], 'fields' => ['nama_lengkap', 'email']],
                'Wakil Direktur' => ['data' => $wakilDirektur, 'cols' => ['Nama', 'Email'], 'fields' => ['nama_lengkap', 'email']],
            ];

            foreach ($categories as $title => $cat):
            ?>
                <div class="col-md-<?= ($title == 'Surveyor') ? '12' : '6' ?> mb-4">
                    <div class="card">
                        <div class="card-header"><?= $title ?></div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <?php foreach ($cat['cols'] as $col): ?>
                                            <th><?= $col ?></th>
                                        <?php endforeach; ?>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cat['data'] as $row): ?>
                                        <tr>
                                            <?php foreach ($cat['fields'] as $field): ?>
                                                <td><?= $row[$field] ?? '-' ?></td>
                                            <?php endforeach; ?>
                                            <td>
    <form method="post" action="<?= site_url('admin/kontak/delete/' . $row['kontak_id']) ?>" class="deleteForm">
        <button type="button" class="btn btn-cari w-200 btnDelete">Hapus</button>
    </form>
</td>


                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- Script Ajax -->
    <script>
        $(function() {
            const colMap = {
                'Surveyor': ['Pilih', 'Nama', 'NIM', 'Email', 'No.Telp', 'Prodi', 'Jurusan', 'Tahun Lulus'],
                'Tim Tracer': ['Pilih', 'Nama', 'Email'],
                'Wakil Direktur': ['Pilih', 'Nama', 'Email', 'No.Telp']
            };

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                let kategori = $('#kategori').val();
                let keyword = $('#keyword').val();

                $.get("<?= site_url('admin/kontak/search') ?>", {
                    kategori,
                    keyword
                }, function(res) {
                    if (!res || res.length === 0) {
                        alert("Data tidak ditemukan!");
                        $('#searchResult').hide();
                        return;
                    }

                    // Set kolom tabel sesuai kategori
                    let th = '';
                    colMap[kategori].forEach(c => {
                        th += `<th>${c}</th>`;
                    });
                    $('#searchCols').html(th);

                    // Set isi tbody
                    let html = '';
                    res.forEach(r => {
                        html += '<tr>';
                        html += `<td><input type="checkbox" name="id_account[]" value="${r.id_account}"></td>`;
                        html += `<td>${r.nama_lengkap ?? '-'}</td>`;
                        if (kategori === 'Surveyor') {
                            html += `<td>${r.nim ?? '-'}</td>`;
                            html += `<td>${r.email ?? '-'}</td>`;
                            html += `<td>${r.notlp ?? '-'}</td>`;
                            html += `<td>${r.nama_prodi ?? '-'}</td>`;
                            html += `<td>${r.nama_jurusan ?? '-'}</td>`;
                            html += `<td>${r.tahun_kelulusan ?? '-'}</td>`;
                        } else if (kategori === 'Tim Tracer') {
                            html += `<td>${r.email ?? '-'}</td>`;
                        } else if (kategori === 'Wakil Direktur') {
                            html += `<td>${r.email ?? '-'}</td>`;
                            html += `<td>${r.notlp ?? '-'}</td>`;
                        }
                        html += '</tr>';
                    });
                    $('#resultBody').html(html);
                    $('#addKategori').val(kategori);
                    $('#searchResult').show();
                }, 'json');
            });
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '.btnDelete', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            icon: 'warning',
            title: 'Yakin hapus?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

</body>

</html>
<?= $this->endSection() ?>

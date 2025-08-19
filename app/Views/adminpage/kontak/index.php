<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Kontak</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Hasil pencarian -->
        <div id="searchResult" class="mb-4" style="display:none;">
            <div class="card">
                <div class="card-header">Hasil Pencarian</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody id="resultBody"></tbody>
                    </table>
                    <form id="addForm" method="post" action="<?= site_url('admin/kontak/store') ?>">
                        <input type="hidden" name="kategori" id="addKategori">
                        <input type="hidden" name="id_account" id="addIdAccount">
                        <button type="submit" class="btn btn-success">Tambah ke Kontak</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Kontak per kategori -->
        <div class="row">
            <!-- Surveyor -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">Surveyor</div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>No.Telp</th>
                                    <th>Email</th>
                                    <th>Prodi</th>
                                    <th>Jurusan</th>
                                    <th>Tahun Lulus</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($surveyors as $s): ?>
                                    <tr>
                                        <td><?= $s['nama_lengkap'] ?></td>
                                        <td><?= $s['nim'] ?></td>
                                        <td><?= $s['notlp'] ?></td>
                                        <td><?= $s['email'] ?></td>
                                        <td><?= $s['nama_prodi'] ?></td>
                                        <td><?= $s['nama_jurusan'] ?></td>
                                        <td><?= $s['tahun_kelulusan'] ?></td>
                                        <td>
                                            <form method="post" action="<?= site_url('admin/kontak/delete/' . $s['kontak_id']) ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tim Tracer -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-warning">Tim Tracer</div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teamTracer as $t): ?>
                                    <tr>
                                        <td><?= $t['nama_lengkap'] ?></td>
                                        <td><?= $t['email'] ?></td>
                                        <td>
                                            <form method="post" action="<?= site_url('admin/kontak/delete/' . $t['kontak_id']) ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Wakil Direktur -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">Wakil Direktur</div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($wakilDirektur as $w): ?>
                                    <tr>
                                        <td><?= $w['nama_lengkap'] ?></td>
                                        <td><?= $w['email'] ?></td>
                                        <td>
                                            <form method="post" action="<?= site_url('admin/kontak/delete/' . $w['kontak_id']) ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Ajax -->
    <script>
        $(function() {
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                let kategori = $('#kategori').val();
                let keyword = $('#keyword').val();

                $.get("<?= site_url('admin/kontak/search') ?>", {
                    kategori,
                    keyword
                }, function(res) {
                    if ($.isEmptyObject(res)) {
                        alert("Data tidak ditemukan!");
                        $('#searchResult').hide();
                    } else {
                        let html = "";
                        for (let key in res) {
                            html += "<tr><th>" + key + "</th><td>" + res[key] + "</td></tr>";
                        }
                        $('#resultBody').html(html);
                        $('#addKategori').val(kategori);
                        $('#addIdAccount').val(res.id_account);
                        $('#searchResult').show();
                    }
                }, 'json');
            });
        });
    </script>

</body>

</html>
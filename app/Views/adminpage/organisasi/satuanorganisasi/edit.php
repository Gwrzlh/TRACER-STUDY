<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/create.css') ?>">

<div class="card shadow-sm">
    <div class="card-header">
        <img src="/images/logo.png" alt="Tracer Study" class="logo">
        <h4>Edit Satuan Organisasi</h4>
    </div>

    <div class="card-body">
        <form action="/satuanorganisasi/update/<?= $satuan['id'] ?>" method="post">
            <?= csrf_field() ?>

            <!-- Jurusan -->
            <div class="mb-3">
                <label for="id_jurusan" class="form-label required">Jurusan</label>
                <select name="id_jurusan" id="id_jurusan" class="form-control" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <?php foreach ($jurusan as $j): ?>
                        <option value="<?= esc($j['id']) ?>" 
                            <?= ($satuan['id_jurusan'] == $j['id']) ? 'selected' : '' ?>>
                            <?= esc($j['nama_jurusan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Prodi (otomatis tampil semua sesuai jurusan) -->
            <div class="mb-3">
                <label class="form-label required">Prodi</label>
                <div id="prodi_list" class="border rounded p-2 bg-light"></div>
                <!-- hidden input untuk kirim semua prodi -->
                <div id="prodi_hidden"></div>
            </div>

            <!-- Nama Satuan -->
            <div class="mb-3">
                <label for="nama_satuan" class="form-label required">Nama Satuan</label>
                <input type="text" name="nama_satuan" id="nama_satuan" 
                       value="<?= esc($satuan['nama_satuan']) ?>" class="form-control" required>
            </div>

            <!-- Singkatan -->
            <div class="mb-3">
                <label for="nama_singkatan" class="form-label required">Singkatan</label>
                <input type="text" name="nama_singkatan" id="nama_singkatan" 
                       value="<?= esc($satuan['nama_singkatan']) ?>" class="form-control" required>
            </div>

            <!-- Slug -->
            <div class="mb-3">
                <label for="nama_slug" class="form-label required">Slug</label>
                <input type="text" name="nama_slug" id="nama_slug" 
                       value="<?= esc($satuan['nama_slug']) ?>" class="form-control" required>
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control"><?= esc($satuan['deskripsi']) ?></textarea>
            </div>

            <!-- Tipe Organisasi -->
            <div class="mb-3">
                <label for="id_tipe" class="form-label required">Tipe Organisasi</label>
                <select name="id_tipe" id="id_tipe" class="form-control" required>
                    <option value="">-- Pilih Tipe Organisasi --</option>
                    <?php foreach ($tipe as $t): ?>
                        <option value="<?= esc($t['id']) ?>" 
                            <?= ($satuan['id_tipe'] == $t['id']) ? 'selected' : '' ?>>
                            <?= esc($t['nama_tipe']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tombol -->
            <div class="mt-3">
                <button type="submit" class="btn-primary-custom">Update</button>
                <a href="/satuanorganisasi" class="btn-warning-custom">Batal</a>
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function loadProdi(jurusanId) {
        $('#prodi_list').empty();
        $('#prodi_hidden').empty();

        if (jurusanId) {
            $.getJSON("<?= base_url('satuanorganisasi/getProdiByJurusan') ?>/" + jurusanId, function(data) {
                if (data && data.length > 0) {
                    data.forEach(p => {
                        // tampilkan daftar prodi
                        $('#prodi_list').append(`<div>${p.nama_prodi}</div>`);

                        // simpan hidden input
                        $('#prodi_hidden').append(
                            `<input type="hidden" name="prodi_ids[]" value="${p.id}">`
                        );
                    });
                }
            });
        }
    }

    $(document).ready(function() {
        loadProdi($('#id_jurusan').val());

        $('#id_jurusan').change(function() {
            loadProdi($(this).val());
        });
    });
</script>

<?= $this->endSection() ?>

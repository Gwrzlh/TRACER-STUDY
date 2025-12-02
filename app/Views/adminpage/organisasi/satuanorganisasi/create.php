```php
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/create.css') ?>">

<div class="card shadow-sm">
    <div class="card-header d-flex align-items-center">
        <img src="/images/logo.png" alt="Tracer Study" class="logo me-2">
        <h4 class="mb-0">Tambah Satuan Organisasi</h4>
    </div>

    <div class="card-body">
        <form action="<?= base_url('satuanorganisasi/store') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Jurusan -->
            <div class="mb-3">
                <label for="id_jurusan" class="form-label required">Jurusan</label>
                <select name="id_jurusan" id="id_jurusan" class="form-control" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <?php foreach ($jurusan as $j): ?>
                        <option value="<?= esc($j['id']) ?>"><?= esc($j['nama_jurusan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

         <div class="mb-3">
    <label for="id_prodi" class="form-label required">Prodi</label>
   <select name="id_prodi[]" id="id_prodi" class="form-control" multiple size="6" required>

        <option value="">-- Pilih Prodi --</option>
    </select>
</div>

            <!-- Nama Satuan -->
            <div class="mb-3">
                <label for="nama_satuan" class="form-label required">Nama Satuan</label>
                <input type="text" name="nama_satuan" id="nama_satuan" class="form-control" required>
            </div>

            <!-- Singkatan -->
            <div class="mb-3">
                <label for="nama_singkatan" class="form-label required">Singkatan</label>
                <input type="text" name="nama_singkatan" id="nama_singkatan" class="form-control" required>
            </div>

            <!-- Slug -->
            <div class="mb-3">
                <label for="nama_slug" class="form-label required">Slug</label>
                <input type="text" name="nama_slug" id="nama_slug" class="form-control" required>
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
            </div>

            <!-- Tipe Organisasi -->
            <div class="mb-3">
                <label for="id_tipe" class="form-label required">Tipe Organisasi</label>
                <select name="id_tipe" id="id_tipe" class="form-control" required>
                    <option value="">-- Pilih Tipe Organisasi --</option>
                    <?php foreach ($tipe as $t): ?>
                        <option value="<?= esc($t['id']) ?>"><?= esc($t['nama_tipe']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        <!-- Tombol -->
<div class="form-actions">
    <button type="submit" class="btn-custom save-btn">Simpan</button>
    <a href="<?= base_url('satuanorganisasi') ?>" class="btn-custom cancel-btn">Batal</a>
</div>

        </form>
    </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Slug & singkatan otomatis
$('#nama_satuan').on('input', function() {
    const nama = $(this).val();
    const slug = nama.toLowerCase()
                     .replace(/\s+/g, '-')
                     .replace(/[^a-z0-9\-]/g, '');
    $('#nama_slug').val(slug);

    let singkatan = '';
    nama.split(' ').forEach(w => {
        if (w.length > 0) singkatan += w[0].toUpperCase();
    });
    $('#nama_singkatan').val(singkatan);
});

// Ketika jurusan dipilih, ambil daftar prodi
$('#id_jurusan').change(function() {
    const jurusanId = $(this).val();
    $('#id_prodi').empty().append('<option value="">-- Pilih Prodi --</option>');

    if (jurusanId) {
        $.getJSON("<?= base_url('satuanorganisasi/getProdi') ?>/"  + jurusanId, function(data) {
            if (data && data.length > 0) {
                data.forEach(function(prodi) {
                    $('#id_prodi').append(
                        $('<option>', {
                            value: prodi.id,
                            text: prodi.nama_prodi
                        })
                    );
                });
            } else {
                $('#id_prodi').append('<option value="">Tidak ada prodi</option>');
            }
        }).fail(function() {
            $('#id_prodi').append('<option value="">Gagal memuat data</option>');
        });
    } else {
        $('#id_prodi').append('<option value="">Pilih Jurusan dahulu</option>');
    }
});


</script>

<?= $this->endSection() ?>

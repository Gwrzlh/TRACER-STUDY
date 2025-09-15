<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/organisasi/create.css') ?>">

<div class="card shadow-sm">
    <div class="card-header">
        <img src="/images/logo.png" alt="Tracer Study" class="logo">
        <h4>Tambah Satuan Organisasi</h4>
    </div>

    <div class="card-body">
        <form action="/satuanorganisasi/store" method="post">
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

            <!-- Prodi (hanya tampil sesuai jurusan) -->
            <div class="mb-3">
                <label class="form-label">Prodi</label>
                <div id="prodi_list" class="border rounded p-2 bg-light">
                    <em>Pilih jurusan terlebih dahulu</em>
                </div>
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
            <div class="mt-3">
                <button type="submit" class="btn-primary-custom">Simpan</button>
                <a href="/satuanorganisasi" class="btn-warning-custom">Batal</a>
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

// Tampilkan Prodi sesuai Jurusan dan buat hidden input otomatis
$('#id_jurusan').change(function() {
    const jurusanId = $(this).val();
    $('#prodi_list').html('<em>Memuat data...</em>');

    if (jurusanId) {
        $.getJSON("<?= base_url('satuanorganisasi/getProdiByJurusan') ?>/" + jurusanId, function(data) {
            if (data && data.length > 0) {
                let html = '<ul>';
                data.forEach(p => {
                    html += `<li>${p.nama_prodi}</li>`;
                    // hidden input supaya ikut ke form
                    html += `<input type="hidden" name="prodi_ids[]" value="${p.id}">`;
                });
                html += '</ul>';
                $('#prodi_list').html(html);
            } else {
                $('#prodi_list').html('<em>Tidak ada prodi untuk jurusan ini</em>');
            }
        });
    } else {
        $('#prodi_list').html('<em>Pilih jurusan terlebih dahulu</em>');
    }
});

</script>

<?= $this->endSection() ?>

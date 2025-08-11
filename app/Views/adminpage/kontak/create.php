<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<h1 class="text-xl font-bold mb-4">Tambah Kontak</h1>

<form action="<?= base_url('admin/kontak/store') ?>" method="post">
    <div class="mb-4">
        <label class="block font-medium mb-1">Nama</label>
        <input type="text" name="nama" class="border rounded w-full p-2">
    </div>

    <div class="mb-4">
        <label class="block font-medium mb-1">Posisi</label>
        <input type="text" name="posisi" class="border rounded w-full p-2">
    </div>

    <div class="mb-4">
        <label class="block font-medium mb-1">Kualifikasi</label>
        <input type="text" name="kualifikasi" class="border rounded w-full p-2">
    </div>

    <div class="mb-4">
        <label class="block font-medium mb-1">Tipe Kontak</label>
        <select name="tipe_kontak" id="tipe_kontak" class="border rounded w-full p-2" onchange="toggleFields()">
            <option value="">-- Pilih Tipe --</option>
            <option value="surveyor">Surveyor</option>
            <option value="coordinator">Koordinator</option>
            <option value="team">Tim</option>
            <option value="directorate">Direktorat</option>
            <option value="address">Alamat</option>
        </select>
    </div>

    <!-- Kontak -->
    <div class="mb-4">
        <label class="block font-medium mb-1">Kontak</label>
        <textarea name="kontak" rows="3" class="border rounded w-full p-2"></textarea>
    </div>

    <!-- Prodi -->
    <div id="prodi_section" class="mb-4 hidden">
        <label class="block font-medium mb-1">Program Studi</label>
        <select name="id_prodi" class="border rounded w-full p-2">
            <option value="">-- Pilih Prodi --</option>
            <?php foreach ($prodiList as $p): ?>
                <option value="<?= $p['id'] ?>"><?= esc($p['nama_prodi']) ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <!-- Jurusan -->
    <div id="jurusan_section" class="mb-4 hidden">
        <label class="block font-medium mb-1">Jurusan</label>
        <select name="id_jurusan" class="border rounded w-full p-2">
            <option value="">-- Pilih Jurusan --</option>
            <?php foreach ($jurusanList as $j): ?>
                <option value="<?= $j['id'] ?>"><?= esc($j['nama_jurusan']) ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="mb-4">
        <label class="block font-medium mb-1">Urutan</label>
        <input type="number" name="urutan" class="border rounded w-full p-2">
    </div>

    <div class="mb-4">
        <label class="block font-medium mb-1">Status Aktif</label>
        <select name="aktif" class="border rounded w-full p-2">
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
    </div>

    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</form>

<script>
    function toggleFields() {
        const tipe = document.getElementById('tipe_kontak').value;
        document.getElementById('prodi_section').classList.add('hidden');
        document.getElementById('jurusan_section').classList.add('hidden');

        if (tipe === 'surveyor') {
            document.getElementById('prodi_section').classList.remove('hidden');
        } else if (tipe === 'coordinator') {
            document.getElementById('jurusan_section').classList.remove('hidden');
        }
    }

    window.onload = toggleFields;
</script>

<?= $this->endSection() ?>
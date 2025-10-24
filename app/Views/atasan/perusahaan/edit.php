<!-- app/Views/atasan/perusahaan/edit.php -->
<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <a href="<?= base_url('atasan/perusahaan') ?>" class="btn btn-secondary mb-3">⬅️ Kembali</a>
    <h3 class="mb-3">✏️ Edit Data Perusahaan</h3>

    <form action="<?= base_url('atasan/perusahaan/update/' . $perusahaan['id']) ?>" method="post">
        <?= csrf_field() ?>

        <!-- NAMA PERUSAHAAN -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" class="form-control"
                   value="<?= esc($perusahaan['nama_perusahaan']) ?>" required>
        </div>

        <!-- ALAMAT -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Alamat 1</label>
            <textarea name="alamat1" class="form-control" rows="2"><?= esc($perusahaan['alamat1']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Alamat 2</label>
            <textarea name="alamat2" class="form-control" rows="2"><?= esc($perusahaan['alamat2']) ?></textarea>
        </div>

        <!-- PROVINSI & KOTA -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Provinsi</label>
                <select name="id_provinsi" class="form-control" id="provinsiDropdown" required>
                    <option value="">-- Pilih Provinsi --</option>
                    <?php foreach ($provinces as $prov): ?>
                        <option value="<?= $prov['id'] ?>"
                            <?= $prov['id'] == $perusahaan['id_provinsi'] ? 'selected' : '' ?>>
                            <?= esc($prov['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Kota</label>
                <select name="id_kota" class="form-control" id="kotaDropdown" required>
                    <option value="">-- Pilih Kota --</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= $city['id'] ?>"
                            <?= $city['id'] == $perusahaan['id_kota'] ? 'selected' : '' ?>>
                            <?= esc($city['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- KODE POS & TELP -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Kode Pos</label>
            <input type="text" name="kodepos" class="form-control" value="<?= esc($perusahaan['kodepos']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Nomor Telepon</label>
            <input type="text" name="noTlp" class="form-control" value="<?= esc($perusahaan['noTlp']) ?>">
        </div>

        <button type="submit" class="btn btn-primary mt-2">💾 Simpan Perubahan</button>
    </form>
</div>

<!-- ✅ SCRIPT: Update dropdown kota secara dinamis -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const provinsiDropdown = document.getElementById('provinsiDropdown');
    const kotaDropdown = document.getElementById('kotaDropdown');

    provinsiDropdown.addEventListener('change', function() {
        const provinceId = this.value;

        kotaDropdown.innerHTML = '<option value="">Memuat data kota...</option>';
        kotaDropdown.disabled = true;

        fetch(`<?= base_url('atasan/perusahaan/getCitiesByProvince/') ?>${provinceId}`)
            .then(response => response.json())
            .then(data => {
                kotaDropdown.innerHTML = '<option value="">-- Pilih Kota --</option>';

                if (data.error) {
                    alert(data.error);
                    return;
                }

                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    kotaDropdown.appendChild(option);
                });

                kotaDropdown.disabled = false;
            })
            .catch(err => {
                kotaDropdown.innerHTML = '<option value="">Gagal memuat kota</option>';
                kotaDropdown.disabled = false;
                console.error('Error saat mengambil data kota:', err);
            });
    });
});

</script>

<?= $this->endSection() ?>

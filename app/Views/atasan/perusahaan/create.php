<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <a href="<?= base_url('atasan/perusahaan') ?>" class="btn btn-secondary mb-3">⬅️ Kembali</a>
    <h3 class="mb-3">➕ Tambah Data Perusahaan</h3>

    <form action="<?= base_url('atasan/perusahaan/simpan') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" class="form-control" placeholder="Masukkan nama perusahaan" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat 1</label>
            <textarea name="alamat1" class="form-control" rows="2" placeholder="Alamat utama perusahaan"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat 2</label>
            <textarea name="alamat2" class="form-control" rows="2" placeholder="Alamat tambahan (jika ada)"></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Provinsi</label>
                <select name="id_provinsi" class="form-control" id="provinsiDropdown" required>
                    <option value="" disabled selected>-- Pilih Provinsi --</option>
                    <?php foreach ($provinces as $prov): ?>
                        <option value="<?= $prov['id'] ?>"><?= esc($prov['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Kota</label>
                <select name="id_kota" class="form-control" id="kotaDropdown" required>
                    <option value="" disabled selected>-- Pilih Kota --</option>
                </select>
            </div>
        </div>

       <div class="mb-3">
    <label class="form-label">Kode Pos</label>
    <input 
        type="text" 
        name="kodepos" 
        class="form-control" 
        placeholder="Masukkan kode pos"
        maxlength="5" 
        pattern="\d{5}"
        oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,5)" 
        title="Masukkan kode Pos"
        required
    >
</div>


      <div class="mb-3">
    <label class="form-label">Nomor Telepon</label>
    <input 
        type="text" 
        name="noTlp" 
        class="form-control" 
        placeholder="Masukkan nomor telepon"
        maxlength="15"
        pattern="[0-9]+"
        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
        title="Masukkan nomor telepon"
        required
    >
</div>


        <button type="submit" class="btn btn-success">💾 Simpan Data</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinsiDropdown = document.getElementById('provinsiDropdown');
    const kotaDropdown = document.getElementById('kotaDropdown');

    provinsiDropdown.addEventListener('change', async function () {
        const provinsiId = this.value;

        // UI sementara
        kotaDropdown.innerHTML = '<option value="">Memuat kota...</option>';
        kotaDropdown.disabled = true;

        // URL: pastikan base_url() menghasilkan path yang benar
        const url = '<?= base_url("atasan/perusahaan/getCitiesByProvince") ?>/' + provinsiId;
        console.debug('Fetch cities from:', url);

        try {
            const res = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } });

            // Log status
            console.debug('Response status:', res.status);

            // Jika response bukan OK, ambil pesan error dari body bila ada
            if (!res.ok) {
                let errBody = {};
                try { errBody = await res.json(); } catch(e) { errBody = { error: 'Tidak bisa mengurai respon server' }; }
                console.error('Server error:', errBody);
                kotaDropdown.innerHTML = `<option value="">Gagal memuat kota: ${errBody.error || res.statusText}</option>`;
                kotaDropdown.disabled = false;
                return;
            }

            const data = await res.json();
            console.debug('Cities payload:', data);

            // Kosongkan dan isi
            kotaDropdown.innerHTML = '<option value="">-- Pilih Kota --</option>';

            if (!Array.isArray(data) || data.length === 0) {
                kotaDropdown.innerHTML = '<option value="">(Belum ada kota untuk provinsi ini)</option>';
                kotaDropdown.disabled = false;
                return;
            }

            data.forEach(city => {
                // beberapa tabel punya nama kolom 'name' atau 'city_name' — fallback aman
                const id = city.id ?? city.ID ?? city.city_id;
                const name = city.name ?? city.city_name ?? city.kota ?? 'Nama kota';
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = name;
                kotaDropdown.appendChild(opt);
            });

            kotaDropdown.disabled = false;
        } catch (err) {
            console.error('Fetch failed:', err);
            kotaDropdown.innerHTML = '<option value="">Gagal memuat kota (periksa console)</option>';
            kotaDropdown.disabled = false;
        }
    });
});
</script>


<?= $this->endSection() ?>

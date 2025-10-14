<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/pengaturan_situs.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "<?= session()->getFlashdata('success') ?>",
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

   
<!-- tabs -->
<div class="tab-container">
    <a href="<?= base_url('pengaturan-situs') ?>" 
       class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-situs') ? 'active' : '' ?>">
        Pengaturan Admin
    </a>
    <a href="<?= base_url('pengaturan-alumni') ?>" 
       class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-alumni') ? 'active' : '' ?>">
        Pengaturan Alumni
    </a>
      <a href="<?= base_url('pengaturan-kaprodi') ?>" 
           class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-kaprodi') ? 'active' : '' ?>">
            Pengaturan Kaprodi
        </a>
      <a href="<?= base_url('pengaturan-atasan') ?>" 
           class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-atasan') ? 'active' : '' ?>">
           Pengaturan Atasan
      </a>
      
    <a href="<?= base_url('pengaturan-jabatanlainya') ?>" 
       class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-jabatanlainya') ? 'active' : '' ?>">
        Pengaturan Jabatan Lainnya
    </a>
</div>

    <form action="<?= base_url('pengaturan-atasan/save') ?>" method="post">
        <div class="card">
            <div class="card-header">
                <h5>Pengaturan Tombol Logout (Atasan)</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="atasan_logout_button_text" class="form-label">Teks Tombol Logout</label>
                    <input type="text" name="atasan_logout_button_text" id="atasan_logout_button_text"
                           value="<?= esc($settings['atasan_logout_button_text'] ?? 'Logout') ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="atasan_logout_button_color" class="form-label">Warna Tombol Logout</label>
                    <input type="color" name="atasan_logout_button_color" id="atasan_logout_button_color"
                           value="<?= esc($settings['atasan_logout_button_color'] ?? '#dc3545') ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="atasan_logout_button_text_color" class="form-label">Warna Teks Tombol</label>
                    <input type="color" name="atasan_logout_button_text_color" id="atasan_logout_button_text_color"
                           value="<?= esc($settings['atasan_logout_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label class="form-label">Preview Tombol Logout</label><br>
                    <button type="button" id="atasanLogoutPreview"
                            style="background-color: <?= esc($settings['atasan_logout_button_color'] ?? '#dc3545') ?>;
                                   color: <?= esc($settings['atasan_logout_button_text_color'] ?? '#ffffff') ?>;
                                   border-radius: 8px;
                                   padding: 8px 16px;
                                   border: none;">
                        <?= esc($settings['atasan_logout_button_text'] ?? 'Logout') ?>
                    </button>
                </div>
            </div>
        </div>
<!-- Card Pengaturan Kuesioner -->
<div class="card">
  <div class="card-header">
    <h5>Pengaturan Kuesioner</h5>
  </div>
  <div class="card-body p-4">
    <div class="mb-3">
      <label for="atasan_kuesioner_pagination_limit" class="form-label">Jumlah Data per Halaman</label>
      <input type="number" min="1" max="100"
             name="atasan_kuesioner_pagination_limit"
             id="atasan_kuesioner_pagination_limit"
             value="<?= esc($settings['atasan_kuesioner_pagination_limit'] ?? 10) ?>"
             class="form-control" style="width: 150px;">
      <small class="text-muted">
        Atur berapa banyak data kuesioner yang ditampilkan per halaman di menu “Kuesioner”.
      </small>
    </div>
  </div>
</div>
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success">💾 Simpan Pengaturan</button>
        </div>
    </form>
</div>

<script>
    const logoutBtn = {
        text: document.getElementById("atasan_logout_button_text"),
        color: document.getElementById("atasan_logout_button_color"),
        textColor: document.getElementById("atasan_logout_button_text_color"),
        preview: document.getElementById("atasanLogoutPreview")
    };

    function updateLogoutPreview() {
        logoutBtn.preview.innerText = logoutBtn.text.value;
        logoutBtn.preview.style.backgroundColor = logoutBtn.color.value;
        logoutBtn.preview.style.color = logoutBtn.textColor.value;
    }

    [logoutBtn.text, logoutBtn.color, logoutBtn.textColor].forEach(el => el.addEventListener("input", updateLogoutPreview));
</script>

<?= $this->endSection() ?>

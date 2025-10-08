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
</div>

    <!-- Form Pengaturan Logout -->
    <form action="<?= base_url('pengaturan-kaprodi/save') ?>" method="post">
        <div class="card mt-3">
            <div class="card-header">
                <h5>Pengaturan Tombol Dashboard - Logout Kaprodi</h5>
            </div>
            <div class="card-body p-4">
                
                <div class="mb-3">
                    <label for="kaprodi_logout_button_text" class="form-label">Teks Tombol</label>
                    <input type="text" name="kaprodi_logout_button_text" id="kaprodi_logout_button_text"
                           value="<?= esc($settings['kaprodi_logout_button_text'] ?? 'Logout') ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="kaprodi_logout_button_color" class="form-label">Warna Background</label>
                    <input type="color" name="kaprodi_logout_button_color" id="kaprodi_logout_button_color"
                           value="<?= esc($settings['kaprodi_logout_button_color'] ?? '#dc2626') ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="kaprodi_logout_button_text_color" class="form-label">Warna Teks</label>
                    <input type="color" name="kaprodi_logout_button_text_color" id="kaprodi_logout_button_text_color"
                           value="<?= esc($settings['kaprodi_logout_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="kaprodi_logout_button_hover_color" class="form-label">Warna Hover</label>
                    <input type="color" name="kaprodi_logout_button_hover_color" id="kaprodi_logout_button_hover_color"
                           value="<?= esc($settings['kaprodi_logout_button_hover_color'] ?? '#b91c1c') ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label class="form-label">Preview Tombol Logout Kaprodi</label><br>
                    <button type="button" id="kaprodiLogoutPreview"
                            style="background-color: <?= esc($settings['kaprodi_logout_button_color'] ?? '#dc2626') ?>;
                                   color: <?= esc($settings['kaprodi_logout_button_text_color'] ?? '#ffffff') ?>;
                                   border: none;
                                   padding: 8px 16px;
                                   border-radius: 8px;
                                   font-weight: 600;
                                   cursor: pointer;
                                   transition: 0.2s;">
                        <?= esc($settings['kaprodi_logout_button_text'] ?? 'Logout') ?> 
                    </button>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success">💾 Simpan Pengaturan Kaprodi</button>
        </div>
    </form>
</div>

<script>
    // Preview Tombol Logout Kaprodi
    const kaprodiLogoutBtn = {
        text: document.getElementById("kaprodi_logout_button_text"),
        color: document.getElementById("kaprodi_logout_button_color"),
        textColor: document.getElementById("kaprodi_logout_button_text_color"),
        hover: document.getElementById("kaprodi_logout_button_hover_color"),
        preview: document.getElementById("kaprodiLogoutPreview")
    };

    function updateKaprodiLogoutBtn() {
        kaprodiLogoutBtn.preview.innerText = kaprodiLogoutBtn.text.value + " →";
        kaprodiLogoutBtn.preview.style.backgroundColor = kaprodiLogoutBtn.color.value;
        kaprodiLogoutBtn.preview.style.color = kaprodiLogoutBtn.textColor.value;
    }

    [kaprodiLogoutBtn.text, kaprodiLogoutBtn.color, kaprodiLogoutBtn.textColor].forEach(el =>
        el.addEventListener("input", updateKaprodiLogoutBtn)
    );

    kaprodiLogoutBtn.preview.addEventListener("mouseover", () =>
        kaprodiLogoutBtn.preview.style.backgroundColor = kaprodiLogoutBtn.hover.value
    );
    kaprodiLogoutBtn.preview.addEventListener("mouseout", updateKaprodiLogoutBtn);
</script>

<?= $this->endSection() ?>

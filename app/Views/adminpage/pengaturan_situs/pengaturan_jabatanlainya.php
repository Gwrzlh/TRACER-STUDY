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
    <form action="<?= base_url('pengaturan-jabatanlainnya/save') ?>" method="post">
        <div class="card">
            <div class="card-header">
                <h5>Pengaturan Tombol Logout - Jabatan Lainnya</h5>
            </div>
            <div class="card-body p-4">

                <div class="mb-3">
                    <label for="jabatanlainnya_logout_button_text" class="form-label">Teks Tombol Logout</label>
                    <input type="text" name="jabatanlainnya_logout_button_text" id="jabatanlainnya_logout_button_text"
                           value="<?= esc($settings['jabatanlainnya_logout_button_text'] ?? 'Logout') ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="jabatanlainnya_logout_button_color" class="form-label">Warna Background</label>
                    <input type="color" name="jabatanlainnya_logout_button_color" id="jabatanlainnya_logout_button_color"
                           value="<?= esc($settings['jabatanlainnya_logout_button_color'] ?? '#ef4444') ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="jabatanlainnya_logout_button_text_color" class="form-label">Warna Teks</label>
                    <input type="color" name="jabatanlainnya_logout_button_text_color" id="jabatanlainnya_logout_button_text_color"
                           value="<?= esc($settings['jabatanlainnya_logout_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="jabatanlainnya_logout_button_hover_color" class="form-label">Warna Hover</label>
                    <input type="color" name="jabatanlainnya_logout_button_hover_color" id="jabatanlainnya_logout_button_hover_color"
                           value="<?= esc($settings['jabatanlainnya_logout_button_hover_color'] ?? '#b91c1c') ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label class="form-label">Preview Tombol Logout</label><br>
                    <button type="button" id="jabatanlainnyaLogoutPreview"
                            style="background-color: <?= esc($settings['jabatanlainnya_logout_button_color'] ?? '#ef4444') ?>;
                                   color: <?= esc($settings['jabatanlainnya_logout_button_text_color'] ?? '#ffffff') ?>;
                                   border: none;
                                   padding: 8px 16px;
                                   border-radius: 8px;
                                   font-weight: 600;
                                   cursor: pointer;
                                   transition: 0.2s;">
                        <?= esc($settings['jabatanlainnya_logout_button_text'] ?? 'Logout') ?> →
                    </button>
                </div>

            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success">💾 Simpan Pengaturan Jabatan Lainnya</button>
        </div>
    </form>
</div>

<script>
    // Preview Logout - Jabatan Lainnya
    const jlBtn = {
        text: document.getElementById("jabatanlainnya_logout_button_text"),
        color: document.getElementById("jabatanlainnya_logout_button_color"),
        textColor: document.getElementById("jabatanlainnya_logout_button_text_color"),
        hover: document.getElementById("jabatanlainnya_logout_button_hover_color"),
        preview: document.getElementById("jabatanlainnyaLogoutPreview")
    };

    function updateJLBtn() {
        jlBtn.preview.innerText = jlBtn.text.value + " →";
        jlBtn.preview.style.backgroundColor = jlBtn.color.value;
        jlBtn.preview.style.color = jlBtn.textColor.value;
    }

    [jlBtn.text, jlBtn.color, jlBtn.textColor].forEach(el => el.addEventListener("input", updateJLBtn));
    jlBtn.preview.addEventListener("mouseover", () => jlBtn.preview.style.backgroundColor = jlBtn.hover.value);
    jlBtn.preview.addEventListener("mouseout", updateJLBtn);
</script>

<?= $this->endSection() ?>

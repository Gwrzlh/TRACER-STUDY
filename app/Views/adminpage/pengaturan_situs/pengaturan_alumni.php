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
        <a href="<?= base_url('pengaturan-situs') ?>" class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-situs') ? 'active' : '' ?>">
            Pengaturan Admin
        </a>
        <a href="<?= base_url('pengaturan-alumni') ?>" class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-alumni') ? 'active' : '' ?>">
            Pengaturan Alumni
        </a>
    </div>

    <form action="<?= base_url('pengaturan-alumni/save') ?>" method="post">
        <div class="card">
            <div class="card-header">
                <h5>Pengaturan Tombol Dashboard</h5>
            </div>
            <div class="card-body p-4">

                <!-- Profil -->
                <h5 class="mb-3">Tombol Dashboard - Lihat Profil</h5>
                <div class="mb-3">
                    <label for="dashboard_profil_button_text" class="form-label">Teks Tombol</label>
                    <input type="text" name="dashboard_profil_button_text" id="dashboard_profil_button_text"
                           value="<?= esc($settings['dashboard_profil_button_text'] ?? 'Lihat Profil') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="dashboard_profil_button_color" class="form-label">Warna Tombol</label>
                    <input type="color" name="dashboard_profil_button_color" id="dashboard_profil_button_color"
                           value="<?= esc($settings['dashboard_profil_button_color'] ?? '#0d6efd') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label for="dashboard_profil_button_text_color" class="form-label">Warna Teks</label>
                    <input type="color" name="dashboard_profil_button_text_color" id="dashboard_profil_button_text_color"
                           value="<?= esc($settings['dashboard_profil_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label for="dashboard_profil_button_hover_color" class="form-label">Warna Hover</label>
                    <input type="color" name="dashboard_profil_button_hover_color" id="dashboard_profil_button_hover_color"
                           value="<?= esc($settings['dashboard_profil_button_hover_color'] ?? '#0b5ed7') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Preview Tombol Profil</label><br>
                    <button type="button" id="profilPreview" class="preview-btn"
                            style="background-color: <?= esc($settings['dashboard_profil_button_color'] ?? '#0d6efd') ?>;
                                   color: <?= esc($settings['dashboard_profil_button_text_color'] ?? '#ffffff') ?>;">
                        <?= esc($settings['dashboard_profil_button_text'] ?? 'Lihat Profil') ?>
                    </button>
                </div>

                <hr class="my-4">

                <!-- Kuesioner -->
                <h5 class="mb-3">Tombol Dashboard - Isi Kuesioner</h5>
                <div class="mb-3">
                    <label for="dashboard_kuesioner_button_text" class="form-label">Teks Tombol</label>
                    <input type="text" name="dashboard_kuesioner_button_text" id="dashboard_kuesioner_button_text"
                           value="<?= esc($settings['dashboard_kuesioner_button_text'] ?? 'Isi Kuesioner') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="dashboard_kuesioner_button_color" class="form-label">Warna Tombol</label>
                    <input type="color" name="dashboard_kuesioner_button_color" id="dashboard_kuesioner_button_color"
                           value="<?= esc($settings['dashboard_kuesioner_button_color'] ?? '#198754') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label for="dashboard_kuesioner_button_text_color" class="form-label">Warna Teks</label>
                    <input type="color" name="dashboard_kuesioner_button_text_color" id="dashboard_kuesioner_button_text_color"
                           value="<?= esc($settings['dashboard_kuesioner_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label for="dashboard_kuesioner_button_hover_color" class="form-label">Warna Hover</label>
                    <input type="color" name="dashboard_kuesioner_button_hover_color" id="dashboard_kuesioner_button_hover_color"
                           value="<?= esc($settings['dashboard_kuesioner_button_hover_color'] ?? '#157347') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Preview Tombol Kuesioner</label><br>
                    <button type="button" id="kuesionerPreview" class="preview-btn"
                            style="background-color: <?= esc($settings['dashboard_kuesioner_button_color'] ?? '#198754') ?>;
                                   color: <?= esc($settings['dashboard_kuesioner_button_text_color'] ?? '#ffffff') ?>;">
                        <?= esc($settings['dashboard_kuesioner_button_text'] ?? 'Isi Kuesioner') ?>
                    </button>
                </div>

                <hr class="my-4">

                <!-- Logout -->
                <h5 class="mb-3">Tombol Dashboard - Logout</h5>
                <div class="mb-3">
                  <label for="dashboard_logout_button_text" class="form-label">Teks Tombol</label>
                  <input type="text" name="dashboard_logout_button_text" id="dashboard_logout_button_text"
                         value="<?= esc($settings['dashboard_logout_button_text'] ?? 'Logout') ?>" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="dashboard_logout_button_color" class="form-label">Warna Background</label>
                  <input type="color" name="dashboard_logout_button_color" id="dashboard_logout_button_color"
                         value="<?= esc($settings['dashboard_logout_button_color'] ?? '#dc2626') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                  <label for="dashboard_logout_button_text_color" class="form-label">Warna Teks</label>
                  <input type="color" name="dashboard_logout_button_text_color" id="dashboard_logout_button_text_color"
                         value="<?= esc($settings['dashboard_logout_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                  <label for="dashboard_logout_button_hover_color" class="form-label">Warna Hover</label>
                  <input type="color" name="dashboard_logout_button_hover_color" id="dashboard_logout_button_hover_color"
                         value="<?= esc($settings['dashboard_logout_button_hover_color'] ?? '#b91c1c') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                  <label class="form-label">Preview Tombol Logout</label><br>
                  <button type="button" id="logoutPreview"
                          style="background-color: <?= esc($settings['dashboard_logout_button_color'] ?? '#dc2626') ?>;
                                 color: <?= esc($settings['dashboard_logout_button_text_color'] ?? '#ffffff') ?>;
                                 border: none;
                                 padding: 8px 16px;
                                 border-radius: 8px;
                                 font-weight: 600;
                                 cursor: pointer;
                                 transition: 0.2s;">
                    <?= esc($settings['dashboard_logout_button_text'] ?? 'Logout') ?> →
                  </button>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success">💾 Simpan Pengaturan Alumni</button>
        </div>
    </form>
</div>

<script>
    // Preview Profil
    const profilBtn = {
      text: document.getElementById("dashboard_profil_button_text"),
      color: document.getElementById("dashboard_profil_button_color"),
      textColor: document.getElementById("dashboard_profil_button_text_color"),
      hover: document.getElementById("dashboard_profil_button_hover_color"),
      preview: document.getElementById("profilPreview")
    };
    function updateProfilBtn() {
      profilBtn.preview.innerText = profilBtn.text.value;
      profilBtn.preview.style.backgroundColor = profilBtn.color.value;
      profilBtn.preview.style.color = profilBtn.textColor.value;
    }
    [profilBtn.text, profilBtn.color, profilBtn.textColor].forEach(el => el.addEventListener("input", updateProfilBtn));
    profilBtn.preview.addEventListener("mouseover", () => profilBtn.preview.style.backgroundColor = profilBtn.hover.value);
    profilBtn.preview.addEventListener("mouseout", updateProfilBtn);

    // Preview Kuesioner
    const kuesBtn = {
      text: document.getElementById("dashboard_kuesioner_button_text"),
      color: document.getElementById("dashboard_kuesioner_button_color"),
      textColor: document.getElementById("dashboard_kuesioner_button_text_color"),
      hover: document.getElementById("dashboard_kuesioner_button_hover_color"),
      preview: document.getElementById("kuesionerPreview")
    };
    function updateKuesBtn() {
      kuesBtn.preview.innerText = kuesBtn.text.value;
      kuesBtn.preview.style.backgroundColor = kuesBtn.color.value;
      kuesBtn.preview.style.color = kuesBtn.textColor.value;
    }
    [kuesBtn.text, kuesBtn.color, kuesBtn.textColor].forEach(el => el.addEventListener("input", updateKuesBtn));
    kuesBtn.preview.addEventListener("mouseover", () => kuesBtn.preview.style.backgroundColor = kuesBtn.hover.value);
    kuesBtn.preview.addEventListener("mouseout", updateKuesBtn);

    // Preview Logout
    const logoutBtn = {
      text: document.getElementById("dashboard_logout_button_text"),
      color: document.getElementById("dashboard_logout_button_color"),
      textColor: document.getElementById("dashboard_logout_button_text_color"),
      hover: document.getElementById("dashboard_logout_button_hover_color"),
      preview: document.getElementById("logoutPreview")
    };
    function updateLogoutBtn() {
      logoutBtn.preview.innerText = logoutBtn.text.value + " →";
      logoutBtn.preview.style.backgroundColor = logoutBtn.color.value;
      logoutBtn.preview.style.color = logoutBtn.textColor.value;
    }
    [logoutBtn.text, logoutBtn.color, logoutBtn.textColor].forEach(el => el.addEventListener("input", updateLogoutBtn));
    logoutBtn.preview.addEventListener("mouseover", () => logoutBtn.preview.style.backgroundColor = logoutBtn.hover.value);
    logoutBtn.preview.addEventListener("mouseout", updateLogoutBtn);
</script>

<?= $this->endSection() ?>

<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/pengaturan_situs.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid mt-4">
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

    <form action="<?= base_url('pengaturan-jabatanlainnya/save') ?>" method="post">
        <!-- MAIN CARD dengan Navbar di Header -->
        <div class="main-card">
            <div class="main-card-header">
                <h5 class="header-title">Pengaturan Sistem</h5>
                <!-- Tabs di dalam header -->
                <div class="tab-container-inline">
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
                    <a href="<?= base_url('pengaturan-jabatanlainnya') ?>" 
                       class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-jabatanlainnya') ? 'active' : '' ?>">
                        Pengaturan Jabatan Lainnya
                    </a>
                </div>
            </div>
            
            <div class="main-card-body">
                
                <!-- Card: Logout -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h6 class="settings-card-title">Pengaturan Tombol Logout - Jabatan Lainnya</h6>
                    </div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="jabatanlainnya_logout_button_text" class="form-label">Teks Tombol Logout</label>
                                <input type="text" name="jabatanlainnya_logout_button_text" id="jabatanlainnya_logout_button_text"
                                       value="<?= esc($settings['jabatanlainnya_logout_button_text'] ?? 'Logout') ?>" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label for="jabatanlainnya_logout_button_color" class="form-label">Warna Background</label>
                                <div class="color-input-group">
                                    <input type="color" name="jabatanlainnya_logout_button_color" id="jabatanlainnya_logout_button_color"
                                           value="<?= esc($settings['jabatanlainnya_logout_button_color'] ?? '#ef4444') ?>" class="form-control-color">
                                    <button type="button" class="btn-reset" id="resetJabatanLainnyaLogout">Reset</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="jabatanlainnya_logout_button_text_color" class="form-label">Warna Teks</label>
                                <input type="color" name="jabatanlainnya_logout_button_text_color" id="jabatanlainnya_logout_button_text_color"
                                       value="<?= esc($settings['jabatanlainnya_logout_button_text_color'] ?? '#ffffff') ?>" class="form-control-color">
                            </div>
                            <div class="col-md-4">
                                <label for="jabatanlainnya_logout_button_hover_color" class="form-label">Warna Hover</label>
                                <input type="color" name="jabatanlainnya_logout_button_hover_color" id="jabatanlainnya_logout_button_hover_color"
                                       value="<?= esc($settings['jabatanlainnya_logout_button_hover_color'] ?? '#b91c1c') ?>" class="form-control-color">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Preview Tombol Logout</label><br>
                            <button type="button" id="jabatanlainnyaLogoutPreview"
                                    style="background-color: <?= esc($settings['jabatanlainnya_logout_button_color'] ?? '#ef4444') ?>;
                                           color: <?= esc($settings['jabatanlainnya_logout_button_text_color'] ?? '#ffffff') ?>;
                                           padding: 8px 16px;
                                           border-radius: 8px;
                                           font-weight: 600;
                                           border: none;">
                                <?= esc($settings['jabatanlainnya_logout_button_text'] ?? 'Logout') ?> →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card: AMI -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h6 class="settings-card-title">Pengaturan Tombol AMI - Jabatan Lainnya</h6>
                    </div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="jabatanlainnya_ami_button_text" class="form-label">Teks Tombol AMI</label>
                                <input type="text" name="jabatanlainnya_ami_button_text" id="jabatanlainnya_ami_button_text"
                                       value="<?= esc($settings['jabatanlainnya_ami_button_text'] ?? 'AMI') ?>" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label for="jabatanlainnya_ami_button_color" class="form-label">Warna Background</label>
                                <div class="color-input-group">
                                    <input type="color" name="jabatanlainnya_ami_button_color" id="jabatanlainnya_ami_button_color"
                                           value="<?= esc($settings['jabatanlainnya_ami_button_color'] ?? '#2563eb') ?>" class="form-control-color">
                                    <button type="button" class="btn-reset" id="resetJabatanLainnyaAmi">Reset</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="jabatanlainnya_ami_button_text_color" class="form-label">Warna Teks</label>
                                <input type="color" name="jabatanlainnya_ami_button_text_color" id="jabatanlainnya_ami_button_text_color"
                                       value="<?= esc($settings['jabatanlainnya_ami_button_text_color'] ?? '#ffffff') ?>" class="form-control-color">
                            </div>
                            <div class="col-md-4">
                                <label for="jabatanlainnya_ami_button_hover_color" class="form-label">Warna Hover</label>
                                <input type="color" name="jabatanlainnya_ami_button_hover_color" id="jabatanlainnya_ami_button_hover_color"
                                       value="<?= esc($settings['jabatanlainnya_ami_button_hover_color'] ?? '#1d4ed8') ?>" class="form-control-color">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Preview Tombol AMI</label><br>
                            <button type="button" id="jabatanlainnyaAmiPreview"
                                    style="background-color: <?= esc($settings['jabatanlainnya_ami_button_color'] ?? '#2563eb') ?>;
                                           color: <?= esc($settings['jabatanlainnya_ami_button_text_color'] ?? '#ffffff') ?>;
                                           padding: 8px 16px;
                                           border-radius: 8px;
                                           font-weight: 600;
                                           border: none;">
                                <?= esc($settings['jabatanlainnya_ami_button_text'] ?? 'AMI') ?>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card: Akreditasi -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h6 class="settings-card-title">Pengaturan Tombol Akreditasi - Jabatan Lainnya</h6>
                    </div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="jabatanlainnya_akreditasi_button_text" class="form-label">Teks Tombol Akreditasi</label>
                                <input type="text" name="jabatanlainnya_akreditasi_button_text" id="jabatanlainnya_akreditasi_button_text"
                                       value="<?= esc($settings['jabatanlainnya_akreditasi_button_text'] ?? 'Akreditasi') ?>" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label for="jabatanlainnya_akreditasi_button_color" class="form-label">Warna Background</label>
                                <div class="color-input-group">
                                    <input type="color" name="jabatanlainnya_akreditasi_button_color" id="jabatanlainnya_akreditasi_button_color"
                                           value="<?= esc($settings['jabatanlainnya_akreditasi_button_color'] ?? '#059669') ?>" class="form-control-color">
                                    <button type="button" class="btn-reset" id="resetJabatanLainnyaAkreditasi">Reset</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="jabatanlainnya_akreditasi_button_text_color" class="form-label">Warna Teks</label>
                                <input type="color" name="jabatanlainnya_akreditasi_button_text_color" id="jabatanlainnya_akreditasi_button_text_color"
                                       value="<?= esc($settings['jabatanlainnya_akreditasi_button_text_color'] ?? '#ffffff') ?>" class="form-control-color">
                            </div>
                            <div class="col-md-4">
                                <label for="jabatanlainnya_akreditasi_button_hover_color" class="form-label">Warna Hover</label>
                                <input type="color" name="jabatanlainnya_akreditasi_button_hover_color" id="jabatanlainnya_akreditasi_button_hover_color"
                                       value="<?= esc($settings['jabatanlainnya_akreditasi_button_hover_color'] ?? '#047857') ?>" class="form-control-color">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Preview Tombol Akreditasi</label><br>
                            <button type="button" id="jabatanlainnyaAkreditasiPreview"
                                    style="background-color: <?= esc($settings['jabatanlainnya_akreditasi_button_color'] ?? '#059669') ?>;
                                           color: <?= esc($settings['jabatanlainnya_akreditasi_button_text_color'] ?? '#ffffff') ?>;
                                           padding: 8px 16px;
                                           border-radius: 8px;
                                           font-weight: 600;
                                           border: none;">
                                <?= esc($settings['jabatanlainnya_akreditasi_button_text'] ?? 'Akreditasi') ?>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Card Footer -->
            <div class="main-card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Semua Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Helper Alert
    function showResetAlert(nama) {
        Swal.fire({
            icon: 'success',
            title: 'Reset Berhasil',
            text: 'Tombol ' + nama + ' berhasil direset ke default!',
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Fungsi setup preview tombol
    function setupPreview(prefix, previewId, resetId, defaults) {
        const btn = {
            text: document.getElementById(`${prefix}_button_text`),
            color: document.getElementById(`${prefix}_button_color`),
            textColor: document.getElementById(`${prefix}_button_text_color`),
            hover: document.getElementById(`${prefix}_button_hover_color`),
            preview: document.getElementById(previewId),
            reset: document.getElementById(resetId)
        };

        function updatePreview() {
            btn.preview.innerText = btn.text.value + (prefix.includes('logout') ? ' →' : '');
            btn.preview.style.backgroundColor = btn.color.value;
            btn.preview.style.color = btn.textColor.value;
        }

        // Update langsung ketika input berubah
        [btn.text, btn.color, btn.textColor].forEach(el => {
            el.addEventListener('input', updatePreview);
        });

        // Efek hover
        btn.preview.addEventListener('mouseover', () => {
            btn.preview.style.backgroundColor = btn.hover.value;
        });
        btn.preview.addEventListener('mouseout', updatePreview);

        // Reset button
        btn.reset.addEventListener('click', () => {
            btn.text.value = defaults.text;
            btn.color.value = defaults.color;
            btn.textColor.value = defaults.textColor;
            btn.hover.value = defaults.hover;
            updatePreview();
            showResetAlert(defaults.name);
        });

        // Inisialisasi awal
        updatePreview();
    }

    // 🔧 Inisialisasi preview untuk ketiga tombol
    document.addEventListener("DOMContentLoaded", function() {
        setupPreview(
            "jabatanlainnya_logout", 
            "jabatanlainnyaLogoutPreview",
            "resetJabatanLainnyaLogout",
            { text: "Logout", color: "#ef4444", textColor: "#ffffff", hover: "#b91c1c", name: "Logout Jabatan Lainnya" }
        );

        setupPreview(
            "jabatanlainnya_ami", 
            "jabatanlainnyaAmiPreview",
            "resetJabatanLainnyaAmi",
            { text: "AMI", color: "#2563eb", textColor: "#ffffff", hover: "#1d4ed8", name: "AMI" }
        );

        setupPreview(
            "jabatanlainnya_akreditasi", 
            "jabatanlainnyaAkreditasiPreview",
            "resetJabatanLainnyaAkreditasi",
            { text: "Akreditasi", color: "#059669", textColor: "#ffffff", hover: "#047857", name: "Akreditasi" }
        );
    });
</script>

<?= $this->endSection() ?>
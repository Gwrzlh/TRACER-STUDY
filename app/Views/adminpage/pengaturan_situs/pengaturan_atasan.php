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

    <form action="<?= base_url('pengaturan-atasan/save') ?>" method="post">
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
                        <h6 class="settings-card-title">Pengaturan Tombol Logout (Atasan)</h6>
                    </div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="atasan_logout_button_text" class="form-label">Teks Tombol Logout</label>
                                <input type="text" name="atasan_logout_button_text" id="atasan_logout_button_text"
                                       value="<?= esc($settings['atasan_logout_button_text'] ?? 'Logout') ?>" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label for="atasan_logout_button_color" class="form-label">Warna Tombol Logout</label>
                                <div class="color-input-group">
                                    <input type="color" name="atasan_logout_button_color" id="atasan_logout_button_color"
                                           value="<?= esc($settings['atasan_logout_button_color'] ?? '#dc3545') ?>" class="form-control-color">
                                    <button type="button" class="btn-reset" id="resetAtasanLogout">Reset</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="atasan_logout_button_text_color" class="form-label">Warna Teks Tombol</label>
                                <input type="color" name="atasan_logout_button_text_color" id="atasan_logout_button_text_color"
                                       value="<?= esc($settings['atasan_logout_button_text_color'] ?? '#ffffff') ?>" class="form-control-color">
                            </div>
                        </div>
                        <div class="mt-3">
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

                <!-- Card: Pengaturan Kuesioner -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h6 class="settings-card-title">Pengaturan Kuesioner</h6>
                    </div>
                    <div class="settings-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="atasan_kuesioner_pagination_limit" class="form-label">Jumlah Data per Halaman</label>
                                <input type="number" min="1" max="100"
                                       name="atasan_kuesioner_pagination_limit"
                                       id="atasan_kuesioner_pagination_limit"
                                       value="<?= esc($settings['atasan_kuesioner_pagination_limit'] ?? 10) ?>"
                                       class="form-control" style="width: 150px;">
                                <small class="text-muted">
                                    Atur berapa banyak data kuesioner yang ditampilkan per halaman di menu "Kuesioner".
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Card Footer -->
            <div class="main-card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Pengaturan
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

    document.addEventListener("DOMContentLoaded", function() {
        const logoutBtn = {
            text: document.getElementById("atasan_logout_button_text"),
            color: document.getElementById("atasan_logout_button_color"),
            textColor: document.getElementById("atasan_logout_button_text_color"),
            preview: document.getElementById("atasanLogoutPreview"),
            reset: document.getElementById("resetAtasanLogout")
        };

        function updateLogoutPreview() {
            logoutBtn.preview.innerText = logoutBtn.text.value;
            logoutBtn.preview.style.backgroundColor = logoutBtn.color.value;
            logoutBtn.preview.style.color = logoutBtn.textColor.value;
        }

        [logoutBtn.text, logoutBtn.color, logoutBtn.textColor].forEach(el => el.addEventListener("input", updateLogoutPreview));
        
        logoutBtn.reset.addEventListener("click", () => {
            logoutBtn.text.value = "Logout";
            logoutBtn.color.value = "#dc3545";
            logoutBtn.textColor.value = "#ffffff";
            updateLogoutPreview();
            showResetAlert("Logout Atasan");
        });
    });
</script>

<?= $this->endSection() ?>
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

    <!-- Tabs -->
    <div class="tab-container">
        <a href="<?= base_url('pengaturan-situs') ?>" class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-situs') ? 'active' : '' ?>">Pengaturan Admin</a>
        <a href="<?= base_url('pengaturan-alumni') ?>" class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-alumni') ? 'active' : '' ?>">Pengaturan Alumni</a>
        <a href="<?= base_url('pengaturan-kaprodi') ?>" class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-kaprodi') ? 'active' : '' ?>">Pengaturan Kaprodi</a>
        <a href="<?= base_url('pengaturan-atasan') ?>" class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-atasan') ? 'active' : '' ?>">Pengaturan Atasan</a>
        <a href="<?= base_url('pengaturan-jabatanlainnya') ?>" class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-jabatanlainnya') ? 'active' : '' ?>">Pengaturan Jabatan Lainnya</a>
    </div>

    <form action="<?= base_url('pengaturan-jabatanlainnya/save') ?>" method="post">
        <!-- Pengaturan Logout -->
        <div class="card mb-4">
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
                    <label class="form-label">Warna Background</label>
                    <input type="color" name="jabatanlainnya_logout_button_color"
                        value="<?= esc($settings['jabatanlainnya_logout_button_color'] ?? '#ef4444') ?>"
                        class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna Teks</label>
                    <input type="color" name="jabatanlainnya_logout_button_text_color"
                        value="<?= esc($settings['jabatanlainnya_logout_button_text_color'] ?? '#ffffff') ?>"
                        class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna Hover</label>
                    <input type="color" name="jabatanlainnya_logout_button_hover_color"
                        value="<?= esc($settings['jabatanlainnya_logout_button_hover_color'] ?? '#b91c1c') ?>"
                        class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Preview</label><br>
                    <button type="button" id="jabatanlainnyaLogoutPreview"
                        style="background-color: <?= esc($settings['jabatanlainnya_logout_button_color'] ?? '#ef4444') ?>;
                               color: <?= esc($settings['jabatanlainnya_logout_button_text_color'] ?? '#ffffff') ?>;
                               padding: 8px 16px;
                               border-radius: 8px;
                               font-weight: 600;">
                        <?= esc($settings['jabatanlainnya_logout_button_text'] ?? 'Logout') ?> →
                    </button>
                </div>
            </div>
        </div>

        <!-- Pengaturan Tombol AMI -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Pengaturan Tombol AMI - Jabatan Lainnya</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="jabatanlainnya_ami_button_text" class="form-label">Teks Tombol AMI</label>
                    <input type="text" name="jabatanlainnya_ami_button_text" id="jabatanlainnya_ami_button_text"
                        value="<?= esc($settings['jabatanlainnya_ami_button_text'] ?? 'AMI') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna Background</label>
                    <input type="color" name="jabatanlainnya_ami_button_color"
                        value="<?= esc($settings['jabatanlainnya_ami_button_color'] ?? '#2563eb') ?>"
                        class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna Teks</label>
                    <input type="color" name="jabatanlainnya_ami_button_text_color"
                        value="<?= esc($settings['jabatanlainnya_ami_button_text_color'] ?? '#ffffff') ?>"
                        class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna Hover</label>
                    <input type="color" name="jabatanlainnya_ami_button_hover_color"
                        value="<?= esc($settings['jabatanlainnya_ami_button_hover_color'] ?? '#1d4ed8') ?>"
                        class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Preview</label><br>
                    <button type="button" id="jabatanlainnyaAmiPreview"
                        style="background-color: <?= esc($settings['jabatanlainnya_ami_button_color'] ?? '#2563eb') ?>;
                               color: <?= esc($settings['jabatanlainnya_ami_button_text_color'] ?? '#ffffff') ?>;
                               padding: 8px 16px;
                               border-radius: 8px;
                               font-weight: 600;">
                        <?= esc($settings['jabatanlainnya_ami_button_text'] ?? 'AMI') ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Pengaturan Tombol Akreditasi -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Pengaturan Tombol Akreditasi - Jabatan Lainnya</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="jabatanlainnya_akreditasi_button_text" class="form-label">Teks Tombol Akreditasi</label>
                    <input type="text" name="jabatanlainnya_akreditasi_button_text" id="jabatanlainnya_akreditasi_button_text"
                        value="<?= esc($settings['jabatanlainnya_akreditasi_button_text'] ?? 'Akreditasi') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna Background</label>
                    <input type="color" name="jabatanlainnya_akreditasi_button_color"
                        value="<?= esc($settings['jabatanlainnya_akreditasi_button_color'] ?? '#059669') ?>"
                        class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna Teks</label>
                    <input type="color" name="jabatanlainnya_akreditasi_button_text_color"
                        value="<?= esc($settings['jabatanlainnya_akreditasi_button_text_color'] ?? '#ffffff') ?>"
                        class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna Hover</label>
                    <input type="color" name="jabatanlainnya_akreditasi_button_hover_color"
                        value="<?= esc($settings['jabatanlainnya_akreditasi_button_hover_color'] ?? '#047857') ?>"
                        class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Preview</label><br>
                    <button type="button" id="jabatanlainnyaAkreditasiPreview"
                        style="background-color: <?= esc($settings['jabatanlainnya_akreditasi_button_color'] ?? '#059669') ?>;
                               color: <?= esc($settings['jabatanlainnya_akreditasi_button_text_color'] ?? '#ffffff') ?>;
                               padding: 8px 16px;
                               border-radius: 8px;
                               font-weight: 600;">
                        <?= esc($settings['jabatanlainnya_akreditasi_button_text'] ?? 'Akreditasi') ?>
                    </button>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success">💾 Simpan Semua Pengaturan</button>
        </div>
    </form>
</div>

<script>
    // Fungsi setup preview tombol
    function setupPreview(prefix, previewId) {
        const btn = {
            text: document.getElementById(`${prefix}_button_text`),
            color: document.querySelector(`[name="${prefix}_button_color"]`),
            textColor: document.querySelector(`[name="${prefix}_button_text_color"]`),
            hover: document.querySelector(`[name="${prefix}_button_hover_color"]`),
            preview: document.getElementById(previewId)
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

        // Inisialisasi awal
        updatePreview();
    }

    // 🔧 Inisialisasi preview untuk ketiga tombol
    setupPreview("jabatanlainnya_logout", "jabatanlainnyaLogoutPreview");
    setupPreview("jabatanlainnya_ami", "jabatanlainnyaAmiPreview");
    setupPreview("jabatanlainnya_akreditasi", "jabatanlainnyaAkreditasiPreview");
</script>


<?= $this->endSection() ?>

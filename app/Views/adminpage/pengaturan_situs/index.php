<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ====== Container Style ====== */
.container {
    max-width: 100%;
    width: 100%;
    padding-left: 0;
    padding-right: 0;
}

/* ====== Card Style ====== */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background: #fff;
    overflow: hidden;
    width: 100%;
}

.card-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: #fff;
    padding: 18px 22px;
}

.card-header h5 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 600;
}

/* ====== Form Style ====== */
.form-label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: #333;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #ccc;
    padding: 10px 14px;
    transition: all 0.2s ease-in-out;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 6px rgba(0,123,255,0.25);
}

/* ====== Button Preview Style ====== */
#previewButton,
#loginPreview,
#orgPreview,
#logoutPreview,
#importPreview {
    border-radius: 8px;
    padding: 10px 22px;
    font-size: 1.1rem;
    font-weight: 600;
    border: none;
    transition: all 0.2s ease-in-out;
}

/* ====== Simpan Button ====== */
.btn-success {
    border-radius: 10px;
    padding: 10px 22px;
    font-weight: 600;
    transition: all 0.2s ease-in-out;
}
.btn-success:hover {
    background: #218838 !important;
    transform: translateY(-1px);
}

/* ====== Reset Button ====== */
.btn-reset {
    padding: 4px 10px;
    font-size: 0.85rem;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background-color: #f3f4f6;
    color: #374151;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}
.btn-reset:hover {
    background-color: #e5e7eb;
    border-color: #9ca3af;
}
</style>

<div class="container mt-4">

    <?php if(session()->getFlashdata('success')): ?>
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

    <!-- ================= Pengguna ================= -->
    <div class="card">
        <div class="card-header">
            <h5>Edit Menu Pengguna </h5>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('pengaturan-situs/save') ?>" method="post">
                <div class="mb-3">
                    <label for="pengguna_button_text" class="form-label">Teks Tombol Pengguna</label>
                    <input type="text" name="pengguna_button_text" id="pengguna_button_text"
                           value="<?= esc($settings['pengguna_button_text']) ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="pengguna_button_color" class="form-label">Warna Tombol</label>
                    <div class="d-flex gap-2">
                        <input type="color" name="pengguna_button_color" id="pengguna_button_color"
                               value="<?= esc($settings['pengguna_button_color']) ?>" 
                               class="form-control form-control-color">
                        <button type="button" class="btn-reset" id="resetPengguna">Reset</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pengguna_button_text_color" class="form-label">Warna Teks Tombol</label>
                    <input type="color" name="pengguna_button_text_color" id="pengguna_button_text_color"
                           value="<?= esc($settings['pengguna_button_text_color']) ?>" 
                           class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="pengguna_button_hover_color" class="form-label">Warna Hover Tombol</label>
                    <input type="color" name="pengguna_button_hover_color" id="pengguna_button_hover_color"
                           value="<?= esc($settings['pengguna_button_hover_color']) ?>" 
                           class="form-control form-control-color">
                </div>

                <!-- Preview -->
                <div class="mb-3">
                    <label class="form-label">Preview Tombol</label><br>
                    <button type="button" id="previewButton"
                            style="background-color: <?= esc($settings['pengguna_button_color']) ?>;
                                   color: <?= esc($settings['pengguna_button_text_color']) ?>;">
                        <?= esc($settings['pengguna_button_text']) ?>
                    </button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">💾 Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= Import ================= -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Pengaturan Tombol Import Akun</h5>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('pengaturan-situs/save') ?>" method="post">

                <div class="mb-3">
                    <label for="import_button_text" class="form-label">Teks Tombol</label>
                    <input type="text" name="import_button_text" id="import_button_text"
                           value="<?= esc($settings['import_button_text'] ?? 'Import Akun') ?>" 
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label for="import_button_color" class="form-label">Warna Tombol</label>
                    <div class="d-flex gap-2">
                        <input type="color" name="import_button_color" id="import_button_color"
                               value="<?= esc($settings['import_button_color'] ?? '#22c55e') ?>" 
                               class="form-control form-control-color">
                        <button type="button" class="btn-reset" id="resetImport">Reset</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="import_button_text_color" class="form-label">Warna Teks Tombol</label>
                    <input type="color" name="import_button_text_color" id="import_button_text_color"
                           value="<?= esc($settings['import_button_text_color'] ?? '#ffffff') ?>" 
                           class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="import_button_hover_color" class="form-label">Warna Hover Tombol</label>
                    <input type="color" name="import_button_hover_color" id="import_button_hover_color"
                           value="<?= esc($settings['import_button_hover_color'] ?? '#16a34a') ?>" 
                           class="form-control form-control-color">
                </div>

                <!-- Preview -->
                <div class="mb-3">
                    <label class="form-label">Preview Tombol Import Akun</label><br>
                    <button type="button" id="importPreview"
                            style="background-color: <?= esc($settings['import_button_color'] ?? '#22c55e') ?>;
                                   color: <?= esc($settings['import_button_text_color'] ?? '#ffffff') ?>;">
                        <?= esc($settings['import_button_text'] ?? 'Import Akun') ?>
                    </button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">💾 Simpan Tombol Import</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= Organisasi ================= -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Pengaturan Tombol Satuan Organisasi</h5>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('pengaturan-situs/save') ?>" method="post">
                <div class="mb-3">
                    <label for="org_button_text" class="form-label">Teks Tombol</label>
                    <input type="text" name="org_button_text" id="org_button_text"
                           value="<?= esc($settings['org_button_text']) ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="org_button_color" class="form-label">Warna Tombol</label>
                    <input type="color" name="org_button_color" id="org_button_color"
                           value="<?= esc($settings['org_button_color']) ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="org_button_text_color" class="form-label">Warna Teks Tombol</label>
                    <input type="color" name="org_button_text_color" id="org_button_text_color"
                           value="<?= esc($settings['org_button_text_color']) ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="org_button_hover_color" class="form-label">Warna Hover Tombol</label>
                    <input type="color" name="org_button_hover_color" id="org_button_hover_color"
                           value="<?= esc($settings['org_button_hover_color']) ?>" class="form-control form-control-color">
                </div>

                <!-- Preview -->
                <div class="mb-3">
                    <label class="form-label">Preview Tombol Satuan Organisasi</label><br>
                    <button type="button" id="orgPreview"
                            style="background-color: <?= esc($settings['org_button_color']) ?>;
                                   color: <?= esc($settings['org_button_text_color']) ?>;">
                        <?= esc($settings['org_button_text']) ?>
                    </button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">💾 Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= Login ================= -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Pengaturan Tombol Login</h5>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('pengaturan-situs/save') ?>" method="post">
                <div class="mb-3">
                    <label for="login_button_text" class="form-label">Teks Tombol Login</label>
                    <input type="text" name="login_button_text" id="login_button_text"
                           value="<?= esc($settings['login_button_text']) ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="login_button_color" class="form-label">Warna Tombol</label>
                    <input type="color" name="login_button_color" id="login_button_color"
                           value="<?= esc($settings['login_button_color']) ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="login_button_text_color" class="form-label">Warna Teks Tombol</label>
                    <input type="color" name="login_button_text_color" id="login_button_text_color"
                           value="<?= esc($settings['login_button_text_color']) ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="login_button_hover_color" class="form-label">Warna Hover Tombol</label>
                    <input type="color" name="login_button_hover_color" id="login_button_hover_color"
                           value="<?= esc($settings['login_button_hover_color']) ?>" class="form-control form-control-color">
                </div>

                <!-- Preview -->
                <div class="mb-3">
                    <label class="form-label">Preview Tombol Login</label><br>
                    <button type="button" id="loginPreview"
                            style="background-color: <?= esc($settings['login_button_color']) ?>;
                                   color: <?= esc($settings['login_button_text_color']) ?>;">
                        <?= esc($settings['login_button_text']) ?>
                    </button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">💾 Simpan Tombol Login</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= Logout ================= -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Pengaturan Tombol Logout</h5>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('pengaturan-situs/save') ?>" method="post">
                <div class="mb-3">
                    <label for="logout_button_text" class="form-label">Teks Tombol Logout</label>
                    <input type="text" name="logout_button_text" id="logout_button_text"
                           value="<?= esc($settings['logout_button_text']) ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="logout_button_color" class="form-label">Warna Tombol</label>
                    <input type="color" name="logout_button_color" id="logout_button_color"
                           value="<?= esc($settings['logout_button_color']) ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="logout_button_text_color" class="form-label">Warna Teks Tombol</label>
                    <input type="color" name="logout_button_text_color" id="logout_button_text_color"
                           value="<?= esc($settings['logout_button_text_color']) ?>" class="form-control form-control-color">
                </div>

                <div class="mb-3">
                    <label for="logout_button_hover_color" class="form-label">Warna Hover Tombol</label>
                    <input type="color" name="logout_button_hover_color" id="logout_button_hover_color"
                           value="<?= esc($settings['logout_button_hover_color']) ?>" class="form-control form-control-color">
                </div>

                <!-- Preview -->
                <div class="mb-3">
                    <label class="form-label">Preview Tombol Logout</label><br>
                    <button type="button" id="logoutPreview"
                            style="background-color: <?= esc($settings['logout_button_color']) ?>;
                                   color: <?= esc($settings['logout_button_text_color']) ?>;">
                        <?= esc($settings['logout_button_text']) ?>
                    </button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">💾 Simpan Tombol Logout</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ====== Helper Alert ======
function showResetAlert(nama) {
    Swal.fire({
        icon: 'success',
        title: 'Reset Berhasil',
        text: 'Tombol ' + nama + ' berhasil direset ke default!',
        timer: 2000,
        showConfirmButton: false
    });
}

// ====== Preview + Reset ======
// Pengguna
const pengguna = {
    text: document.getElementById("pengguna_button_text"),
    color: document.getElementById("pengguna_button_color"),
    textColor: document.getElementById("pengguna_button_text_color"),
    hover: document.getElementById("pengguna_button_hover_color"),
    preview: document.getElementById("previewButton"),
    reset: document.getElementById("resetPengguna")
};
function updatePengguna() {
    pengguna.preview.innerText = pengguna.text.value;
    pengguna.preview.style.backgroundColor = pengguna.color.value;
    pengguna.preview.style.color = pengguna.textColor.value;
}
[pengguna.text, pengguna.color, pengguna.textColor].forEach(el => el.addEventListener("input", updatePengguna));
pengguna.preview.addEventListener("mouseover", () => pengguna.preview.style.backgroundColor = pengguna.hover.value);
pengguna.preview.addEventListener("mouseout", updatePengguna);
pengguna.reset.addEventListener("click", () => {
    pengguna.text.value = "Pengguna";
    pengguna.color.value = "#0d6efd";
    pengguna.textColor.value = "#ffffff";
    pengguna.hover.value = "#0b5ed7";
    updatePengguna();
    showResetAlert("Pengguna");
});

// Import
const imp = {
    text: document.getElementById("import_button_text"),
    color: document.getElementById("import_button_color"),
    textColor: document.getElementById("import_button_text_color"),
    hover: document.getElementById("import_button_hover_color"),
    preview: document.getElementById("importPreview"),
    reset: document.getElementById("resetImport")
};
function updateImport() {
    imp.preview.innerText = imp.text.value;
    imp.preview.style.backgroundColor = imp.color.value;
    imp.preview.style.color = imp.textColor.value;
}
[imp.text, imp.color, imp.textColor].forEach(el => el.addEventListener("input", updateImport));
imp.preview.addEventListener("mouseover", () => imp.preview.style.backgroundColor = imp.hover.value);
imp.preview.addEventListener("mouseout", updateImport);
imp.reset.addEventListener("click", () => {
    imp.text.value = "Import Akun";
    imp.color.value = "#22c55e";
    imp.textColor.value = "#ffffff";
    imp.hover.value = "#16a34a";
    updateImport();
    showResetAlert("Import Akun");
});

// Organisasi
const org = {
    text: document.getElementById("org_button_text"),
    color: document.getElementById("org_button_color"),
    textColor: document.getElementById("org_button_text_color"),
    hover: document.getElementById("org_button_hover_color"),
    preview: document.getElementById("orgPreview")
};
function updateOrg() {
    org.preview.innerText = org.text.value;
    org.preview.style.backgroundColor = org.color.value;
    org.preview.style.color = org.textColor.value;
}
[org.text, org.color, org.textColor].forEach(el => el.addEventListener("input", updateOrg));
org.preview.addEventListener("mouseover", () => org.preview.style.backgroundColor = org.hover.value);
org.preview.addEventListener("mouseout", updateOrg);

// Login
const login = {
    text: document.getElementById("login_button_text"),
    color: document.getElementById("login_button_color"),
    textColor: document.getElementById("login_button_text_color"),
    hover: document.getElementById("login_button_hover_color"),
    preview: document.getElementById("loginPreview")
};
function updateLogin() {
    login.preview.innerText = login.text.value;
    login.preview.style.backgroundColor = login.color.value;
    login.preview.style.color = login.textColor.value;
}
[login.text, login.color, login.textColor].forEach(el => el.addEventListener("input", updateLogin));
login.preview.addEventListener("mouseover", () => login.preview.style.backgroundColor = login.hover.value);
login.preview.addEventListener("mouseout", updateLogin);

// Logout
const logout = {
    text: document.getElementById("logout_button_text"),
    color: document.getElementById("logout_button_color"),
    textColor: document.getElementById("logout_button_text_color"),
    hover: document.getElementById("logout_button_hover_color"),
    preview: document.getElementById("logoutPreview")
};
function updateLogout() {
    logout.preview.innerText = logout.text.value;
    logout.preview.style.backgroundColor = logout.color.value;
    logout.preview.style.color = logout.textColor.value;
}
[logout.text, logout.color, logout.textColor].forEach(el => el.addEventListener("input", updateLogout));
logout.preview.addEventListener("mouseover", () => logout.preview.style.backgroundColor = logout.hover.value);
logout.preview.addEventListener("mouseout", updateLogout);
</script>

<?= $this->endSection() ?>

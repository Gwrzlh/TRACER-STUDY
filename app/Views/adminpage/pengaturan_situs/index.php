<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/pengaturan_situs.css') ?>">

<!-- SweetAlert2 -->
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
      
    <a href="<?= base_url('pengaturan-jabatanlainnya') ?>" 
       class="tab-link <?= (service('uri')->getSegment(1) == 'pengaturan-jabatanlainnya') ? 'active' : '' ?>">
        Pengaturan Jabatan Lainnya
    </a>
</div>

    <form action="<?= base_url('pengaturan-situs/save') ?>" method="post">
        <!-- Pengguna -->
<div class="card">
    <div class="card-header">
        <h5>Edit Menu Pengguna</h5>
    </div>
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="pengguna_button_text" class="form-label">Teks Tombol Pengguna</label>
            <input type="text" name="pengguna_button_text" id="pengguna_button_text"
                   value="<?= esc($settings['pengguna_button_text']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="pengguna_button_color" class="form-label">Warna Tombol</label>
            <div class="d-flex gap-2">
                <input type="color" name="pengguna_button_color" id="pengguna_button_color"
                       value="<?= esc($settings['pengguna_button_color']) ?>" class="form-control form-control-color">
                <button type="button" class="btn-reset" id="resetPengguna">Reset</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="pengguna_button_text_color" class="form-label">Warna Teks Tombol</label>
            <input type="color" name="pengguna_button_text_color" id="pengguna_button_text_color"
                   value="<?= esc($settings['pengguna_button_text_color']) ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label for="pengguna_button_hover_color" class="form-label">Warna Hover Tombol</label>
            <input type="color" name="pengguna_button_hover_color" id="pengguna_button_hover_color"
                   value="<?= esc($settings['pengguna_button_hover_color']) ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label class="form-label">Preview Tombol</label><br>
            <button type="button" id="previewButton"
                    style="background-color: <?= esc($settings['pengguna_button_color']) ?>;
                           color: <?= esc($settings['pengguna_button_text_color']) ?>;">
                <?= esc($settings['pengguna_button_text']) ?>
            </button>
        </div>

        <!-- Tambahan: Setting jumlah data per halaman -->
        <div class="mb-3">
            <label for="pengguna_perpage_default" class="form-label">Default Tampilkan Per Halaman</label>
            <input type="number" 
                   name="pengguna_perpage_default" 
                   id="pengguna_perpage_default"
                   value="<?= esc($settings['pengguna_perpage_default'] ?? 10) ?>" 
                   class="form-control" 
                   min="1">
        </div>
    </div>
</div>

        <!-- Import -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Pengaturan Tombol Import Akun</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="import_button_text" class="form-label">Teks Tombol</label>
                    <input type="text" name="import_button_text" id="import_button_text"
                           value="<?= esc($settings['import_button_text'] ?? 'Import Akun') ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="import_button_color" class="form-label">Warna Tombol</label>
                    <div class="d-flex gap-2">
                        <input type="color" name="import_button_color" id="import_button_color"
                               value="<?= esc($settings['import_button_color'] ?? '#22c55e') ?>" class="form-control form-control-color">
                        <button type="button" class="btn-reset" id="resetImport">Reset</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="import_button_text_color" class="form-label">Warna Teks Tombol</label>
                    <input type="color" name="import_button_text_color" id="import_button_text_color"
                           value="<?= esc($settings['import_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label for="import_button_hover_color" class="form-label">Warna Hover Tombol</label>
                    <input type="color" name="import_button_hover_color" id="import_button_hover_color"
                           value="<?= esc($settings['import_button_hover_color'] ?? '#16a34a') ?>" class="form-control form-control-color">
                </div>
                <div class="mb-3">
                    <label class="form-label">Preview Tombol Import Akun</label><br>
                    <button type="button" id="importPreview"
                            style="background-color: <?= esc($settings['import_button_color'] ?? '#22c55e') ?>;
                                   color: <?= esc($settings['import_button_text_color'] ?? '#ffffff') ?>;">
                        <?= esc($settings['import_button_text'] ?? 'Import Akun') ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Organisasi -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Pengaturan Satuan Organisasi</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="org_button_text" class="form-label">Teks Tombol</label>
                    <input type="text" name="org_button_text" id="org_button_text"
                           value="<?= esc($settings['org_button_text']) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="org_button_color" class="form-label">Warna Tombol</label>
                    <div class="d-flex gap-2">
                        <input type="color" name="org_button_color" id="org_button_color"
                               value="<?= esc($settings['org_button_color']) ?>" class="form-control form-control-color">
                        <button type="button" class="btn-reset" id="resetOrg">Reset</button>
                    </div>
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
                <div class="mb-3">
                    <label class="form-label">Preview Tombol Satuan Organisasi</label><br>
                    <button type="button" id="orgPreview"
                            style="background-color: <?= esc($settings['org_button_color']) ?>;
                                   color: <?= esc($settings['org_button_text_color']) ?>;">
                        <?= esc($settings['org_button_text']) ?>
                    </button>
                </div>
                <!-- Tambahan: Setting jumlah data per halaman Satuan Organisasi -->
<div class="mb-3">
    <label for="org_perpage_default" class="form-label">Data yang di tampilkan di satuan organisasi/jurusan/prodi </label>
    <input type="number" 
           name="org_perpage_default" 
           id="org_perpage_default"
           value="<?= esc($settings['org_perpage_default'] ?? 10) ?>" 
           class="form-control" 
           min="1">
</div>

            </div>
        </div>

       
<!-- Mulai Survey -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Pengaturan Tombol Mulai Survey</h5>
    </div>
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="survey_button_text" class="form-label">Teks Tombol</label>
            <input type="text" name="survey_button_text" id="survey_button_text"
                   value="<?= esc($settings['survey_button_text'] ?? 'Mulai Survey') ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="survey_button_color" class="form-label">Warna Tombol</label>
            <div class="d-flex gap-2">
                <input type="color" name="survey_button_color" id="survey_button_color"
                       value="<?= esc($settings['survey_button_color'] ?? '#ef4444') ?>" class="form-control form-control-color">
                <button type="button" class="btn-reset" id="resetSurvey">Reset</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="survey_button_text_color" class="form-label">Warna Teks Tombol</label>
            <input type="color" name="survey_button_text_color" id="survey_button_text_color"
                   value="<?= esc($settings['survey_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label for="survey_button_hover_color" class="form-label">Warna Hover Tombol</label>
            <input type="color" name="survey_button_hover_color" id="survey_button_hover_color"
                   value="<?= esc($settings['survey_button_hover_color'] ?? '#dc2626') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label class="form-label">Preview Tombol Mulai Survey</label><br>
            <button type="button" id="surveyPreview"
                    style="background-color: <?= esc($settings['survey_button_color'] ?? '#ef4444') ?>;
                           color: <?= esc($settings['survey_button_text_color'] ?? '#ffffff') ?>;
                           padding: 8px 20px;
                           border-radius: 6px;
                           border: none;
                           font-weight: 600;
                           transition: all 0.3s ease;">
                <?= esc($settings['survey_button_text'] ?? 'Mulai Survey') ?>
            </button>
        </div>
    </div>
</div>

        <!-- Logout -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Pengaturan Tombol Logout</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="logout_button_text" class="form-label">Teks Tombol Logout</label>
                    <input type="text" name="logout_button_text" id="logout_button_text"
                           value="<?= esc($settings['logout_button_text']) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="logout_button_color" class="form-label">Warna Tombol</label>
                    <div class="d-flex gap-2">
                        <input type="color" name="logout_button_color" id="logout_button_color"
                               value="<?= esc($settings['logout_button_color']) ?>" class="form-control form-control-color">
                        <button type="button" class="btn-reset" id="resetLogout">Reset</button>
                    </div>
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
                <div class="mb-3">
                    <label class="form-label">Preview Tombol Logout</label><br>
                    <button type="button" id="logoutPreview"
                            style="background-color: <?= esc($settings['logout_button_color']) ?>;
                                   color: <?= esc($settings['logout_button_text_color']) ?>;">
                        <?= esc($settings['logout_button_text']) ?>
                    </button>
                </div>
            </div>
        </div>
<!-- Filter & Reset -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Pengaturan logactivities</h5>
    </div>
    <div class="card-body p-4">
      <!-- Tombol Filter -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Pengaturan Tombol Filter</h5>
    </div>
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="filter_button_text" class="form-label">Teks Tombol</label>
            <input type="text" name="filter_button_text" id="filter_button_text"
                   value="<?= esc($settings['filter_button_text'] ?? 'Filter') ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="filter_button_color" class="form-label">Warna Tombol</label>
            <div class="d-flex gap-2">
                <input type="color" name="filter_button_color" id="filter_button_color"
                       value="<?= esc($settings['filter_button_color'] ?? '#0d6efd') ?>" class="form-control form-control-color">
                <button type="button" class="btn-reset" id="resetFilter">Reset</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="filter_button_text_color" class="form-label">Warna Teks Tombol</label>
            <input type="color" name="filter_button_text_color" id="filter_button_text_color"
                   value="<?= esc($settings['filter_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label for="filter_button_hover_color" class="form-label">Warna Hover Tombol</label>
            <input type="color" name="filter_button_hover_color" id="filter_button_hover_color"
                   value="<?= esc($settings['filter_button_hover_color'] ?? '#0b5ed7') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label class="form-label">Preview Tombol Filter</label><br>
            <button type="button" id="filterPreview"
                    style="background-color: <?= esc($settings['filter_button_color'] ?? '#0d6efd') ?>;
                           color: <?= esc($settings['filter_button_text_color'] ?? '#ffffff') ?>;
                           border: none; padding: 8px 16px; border-radius: 6px;">
                <?= esc($settings['filter_button_text'] ?? 'Filter') ?>
            </button>
        </div>
    </div>
</div>

<!-- Tombol Reset -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Pengaturan Tombol Reset</h5>
    </div>
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="reset_button_text" class="form-label">Teks Tombol</label>
            <input type="text" name="reset_button_text" id="reset_button_text"
                   value="<?= esc($settings['reset_button_text'] ?? 'Reset') ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="reset_button_color" class="form-label">Warna Tombol</label>
            <div class="d-flex gap-2">
                <input type="color" name="reset_button_color" id="reset_button_color"
                       value="<?= esc($settings['reset_button_color'] ?? '#6c757d') ?>" class="form-control form-control-color">
                <button type="button" class="btn-reset" id="resetReset">Reset</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="reset_button_text_color" class="form-label">Warna Teks Tombol</label>
            <input type="color" name="reset_button_text_color" id="reset_button_text_color"
                   value="<?= esc($settings['reset_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label for="reset_button_hover_color" class="form-label">Warna Hover Tombol</label>
            <input type="color" name="reset_button_hover_color" id="reset_button_hover_color"
                   value="<?= esc($settings['reset_button_hover_color'] ?? '#5c636a') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label class="form-label">Preview Tombol Reset</label><br>
            <button type="button" id="resetPreviewBtn"
                    style="background-color: <?= esc($settings['reset_button_color'] ?? '#6c757d') ?>;
                           color: <?= esc($settings['reset_button_text_color'] ?? '#ffffff') ?>;
                           border: none; padding: 8px 16px; border-radius: 6px;">
                <?= esc($settings['reset_button_text'] ?? 'Reset') ?>
            </button>
        </div>
    </div>
</div>

<!-- Tombol Export CSV -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Pengaturan Tombol Export CSV</h5>
    </div>
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="export_button_text" class="form-label">Teks Tombol</label>
            <input type="text" name="export_button_text" id="export_button_text"
                   value="<?= esc($settings['export_button_text'] ?? 'Export CSV') ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="export_button_color" class="form-label">Warna Tombol</label>
            <div class="d-flex gap-2">
                <input type="color" name="export_button_color" id="export_button_color"
                       value="<?= esc($settings['export_button_color'] ?? '#198754') ?>" class="form-control form-control-color">
                <button type="button" class="btn-reset" id="resetExport">Reset</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="export_button_text_color" class="form-label">Warna Teks Tombol</label>
            <input type="color" name="export_button_text_color" id="export_button_text_color"
                   value="<?= esc($settings['export_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label for="export_button_hover_color" class="form-label">Warna Hover Tombol</label>
            <input type="color" name="export_button_hover_color" id="export_button_hover_color"
                   value="<?= esc($settings['export_button_hover_color'] ?? '#157347') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label class="form-label">Preview Tombol Export CSV</label><br>
            <button type="button" id="exportPreview"
                    style="background-color: <?= esc($settings['export_button_color'] ?? '#198754') ?>;
                           color: <?= esc($settings['export_button_text_color'] ?? '#ffffff') ?>;
                           border: none; padding: 8px 16px; border-radius: 6px;">
                <?= esc($settings['export_button_text'] ?? 'Export CSV') ?>
            </button>
        </div>
    </div>
</div>
        <div class="mb-3">
            <label for="log_perpage_default" class="form-label">Default Tampilkan Per Halaman (Log Activities)</label>
            <input type="number" 
                   name="log_perpage_default" 
                   id="log_perpage_default"
                   value="<?= esc($settings['log_perpage_default'] ?? 10) ?>" 
                   class="form-control" 
                   min="1">
        </div>
    </div>
</div>
<!-- Tombol Tambah -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Pengaturan Tombol Tambah</h5>
    </div>
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="tambah_button_text" class="form-label">Teks Tombol</label>
            <input type="text" name="tambah_button_text" id="tambah_button_text"
                   value="<?= esc($settings['tambah_button_text'] ?? 'Tambah Data') ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="tambah_button_color" class="form-label">Warna Tombol</label>
            <div class="d-flex gap-2">
                <input type="color" name="tambah_button_color" id="tambah_button_color"
                       value="<?= esc($settings['tambah_button_color'] ?? '#0d6efd') ?>" class="form-control form-control-color">
                <button type="button" class="btn-reset" id="resetTambah">Reset</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="tambah_button_text_color" class="form-label">Warna Teks Tombol</label>
            <input type="color" name="tambah_button_text_color" id="tambah_button_text_color"
                   value="<?= esc($settings['tambah_button_text_color'] ?? '#ffffff') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label for="tambah_button_hover_color" class="form-label">Warna Hover Tombol</label>
            <input type="color" name="tambah_button_hover_color" id="tambah_button_hover_color"
                   value="<?= esc($settings['tambah_button_hover_color'] ?? '#0b5ed7') ?>" class="form-control form-control-color">
        </div>
        <div class="mb-3">
            <label class="form-label">Preview Tombol Tambah</label><br>
            <button type="button" id="tambahPreview"
                    style="background-color: <?= esc($settings['tambah_button_color'] ?? '#0d6efd') ?>;
                           color: <?= esc($settings['tambah_button_text_color'] ?? '#ffffff') ?>;
                           border: none; padding: 8px 16px; border-radius: 6px;">
                <?= esc($settings['tambah_button_text'] ?? 'Tambah Data') ?>
            </button>
        </div>
    </div>
</div>
<!-- Pengaturan Jumlah Data Tipe Organisasi -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Pengaturan Jumlah Data Tipe Organisasi</h5>
    </div>
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="tipe_organisasi_per_page" class="form-label">Jumlah Data per Halaman</label>
            <input type="number" name="tipe_organisasi_per_page" id="tipe_organisasi_per_page"
                   value="<?= esc($settings['tipe_organisasi_per_page'] ?? 1) ?>" 
                   class="form-control" min="1" max="100">
            <small class="text-muted">Atur berapa banyak data Tipe Organisasi yang tampil di satu halaman.</small>
        </div>
    </div>
</div>


        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success">💾 Simpan Semua Pengaturan</button>
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
            preview: document.getElementById("orgPreview"),
            reset: document.getElementById("resetOrg")
        };
        function updateOrg() {
            org.preview.innerText = org.text.value;
            org.preview.style.backgroundColor = org.color.value;
            org.preview.style.color = org.textColor.value;
        }
        [org.text, org.color, org.textColor].forEach(el => el.addEventListener("input", updateOrg));
        org.preview.addEventListener("mouseover", () => org.preview.style.backgroundColor = org.hover.value);
        org.preview.addEventListener("mouseout", updateOrg);
        org.reset.addEventListener("click", () => {
            org.text.value = "Satuan Organisasi";
            org.color.value = "#6b7280";
            org.textColor.value = "#ffffff";
            org.hover.value = "#4b5563";
            updateOrg();
            showResetAlert("Satuan Organisasi");
        });

        // Login
        const login = {
            text: document.getElementById("login_button_text"),
            color: document.getElementById("login_button_color"),
            textColor: document.getElementById("login_button_text_color"),
            hover: document.getElementById("login_button_hover_color"),
            preview: document.getElementById("loginPreview"),
            reset: document.getElementById("resetLogin")
        };
        function updateLogin() {
            login.preview.innerText = login.text.value;
            login.preview.style.backgroundColor = login.color.value;
            login.preview.style.color = login.textColor.value;
        }
        [login.text, login.color, login.textColor].forEach(el => el.addEventListener("input", updateLogin));
        login.preview.addEventListener("mouseover", () => login.preview.style.backgroundColor = login.hover.value);
        login.preview.addEventListener("mouseout", updateLogin);
        login.reset.addEventListener("click", () => {
            login.text.value = "Login";
            login.color.value = "#dc3545";
            login.textColor.value = "#ffffff";
            login.hover.value = "#bb2d3b";
            updateLogin();
            showResetAlert("Login");
        });
// Mulai Survey
const survey = {
    text: document.getElementById("survey_button_text"),
    color: document.getElementById("survey_button_color"),
    textColor: document.getElementById("survey_button_text_color"),
    hover: document.getElementById("survey_button_hover_color"),
    preview: document.getElementById("surveyPreview"),
    reset: document.getElementById("resetSurvey")
};

function updateSurvey() {
    survey.preview.innerText = survey.text.value;
    survey.preview.style.backgroundColor = survey.color.value;
    survey.preview.style.color = survey.textColor.value;
}

[survey.text, survey.color, survey.textColor].forEach(el => 
    el.addEventListener("input", updateSurvey)
);

survey.preview.addEventListener("mouseover", () => 
    survey.preview.style.backgroundColor = survey.hover.value
);

survey.preview.addEventListener("mouseout", updateSurvey);

survey.reset.addEventListener("click", () => {
    survey.text.value = "Mulai Survey";
    survey.color.value = "#ef4444";
    survey.textColor.value = "#ffffff";
    survey.hover.value = "#dc2626";
    updateSurvey();
    showResetAlert("Mulai Survey");
});

        // Logout
        const logout = {
            text: document.getElementById("logout_button_text"),
            color: document.getElementById("logout_button_color"),
            textColor: document.getElementById("logout_button_text_color"),
            hover: document.getElementById("logout_button_hover_color"),
            preview: document.getElementById("logoutPreview"),
            reset: document.getElementById("resetLogout")
        };
        function updateLogout() {
            logout.preview.innerText = logout.text.value;
            logout.preview.style.backgroundColor = logout.color.value;
            logout.preview.style.color = logout.textColor.value;
        }
        [logout.text, logout.color, logout.textColor].forEach(el => el.addEventListener("input", updateLogout));
        logout.preview.addEventListener("mouseover", () => logout.preview.style.backgroundColor = logout.hover.value);
        logout.preview.addEventListener("mouseout", updateLogout);
        logout.reset.addEventListener("click", () => {
            logout.text.value = "Logout";
            logout.color.value = "#6c757d";
            logout.textColor.value = "#ffffff";
            logout.hover.value = "#5c636a";
            updateLogout();
            showResetAlert("Logout");
        });
    });

    // Filter
const filterBtn = {
    text: document.getElementById("filter_button_text"),
    color: document.getElementById("filter_button_color"),
    textColor: document.getElementById("filter_button_text_color"),
    hover: document.getElementById("filter_button_hover_color"),
    preview: document.getElementById("filterPreview"),
    reset: document.getElementById("resetFilter")
};
function updateFilter() {
    filterBtn.preview.innerText = filterBtn.text.value;
    filterBtn.preview.style.backgroundColor = filterBtn.color.value;
    filterBtn.preview.style.color = filterBtn.textColor.value;
}
[filterBtn.text, filterBtn.color, filterBtn.textColor].forEach(el => el.addEventListener("input", updateFilter));
filterBtn.preview.addEventListener("mouseover", () => filterBtn.preview.style.backgroundColor = filterBtn.hover.value);
filterBtn.preview.addEventListener("mouseout", updateFilter);
filterBtn.reset.addEventListener("click", () => {
    filterBtn.text.value = "Filter";
    filterBtn.color.value = "#0d6efd";
    filterBtn.textColor.value = "#ffffff";
    filterBtn.hover.value = "#0b5ed7";
    updateFilter();
    showResetAlert("Filter");
});

// Reset
const resetBtn = {
    text: document.getElementById("reset_button_text"),
    color: document.getElementById("reset_button_color"),
    textColor: document.getElementById("reset_button_text_color"),
    hover: document.getElementById("reset_button_hover_color"),
    preview: document.getElementById("resetPreviewBtn"),
    reset: document.getElementById("resetReset")
};
function updateReset() {
    resetBtn.preview.innerText = resetBtn.text.value;
    resetBtn.preview.style.backgroundColor = resetBtn.color.value;
    resetBtn.preview.style.color = resetBtn.textColor.value;
}
[resetBtn.text, resetBtn.color, resetBtn.textColor].forEach(el => el.addEventListener("input", updateReset));
resetBtn.preview.addEventListener("mouseover", () => resetBtn.preview.style.backgroundColor = resetBtn.hover.value);
resetBtn.preview.addEventListener("mouseout", updateReset);
resetBtn.reset.addEventListener("click", () => {
    resetBtn.text.value = "Reset";
    resetBtn.color.value = "#6c757d";
    resetBtn.textColor.value = "#ffffff";
    resetBtn.hover.value = "#5c636a";
    updateReset();
    showResetAlert("Reset");
});
// Export CSV
const exportBtn = {
    text: document.getElementById("export_button_text"),
    color: document.getElementById("export_button_color"),
    textColor: document.getElementById("export_button_text_color"),
    hover: document.getElementById("export_button_hover_color"),
    preview: document.getElementById("exportPreview"),
    reset: document.getElementById("resetExport")
};
function updateExport() {
    exportBtn.preview.innerText = exportBtn.text.value;
    exportBtn.preview.style.backgroundColor = exportBtn.color.value;
    exportBtn.preview.style.color = exportBtn.textColor.value;
}
[exportBtn.text, exportBtn.color, exportBtn.textColor].forEach(el => el.addEventListener("input", updateExport));
exportBtn.preview.addEventListener("mouseover", () => exportBtn.preview.style.backgroundColor = exportBtn.hover.value);
exportBtn.preview.addEventListener("mouseout", updateExport);
exportBtn.reset.addEventListener("click", () => {
    exportBtn.text.value = "Export CSV";
    exportBtn.color.value = "#198754";
    exportBtn.textColor.value = "#ffffff";
    exportBtn.hover.value = "#157347";
    updateExport();
    showResetAlert("Export CSV");
});
// Tombol Tambah
const tambahBtn = {
    text: document.getElementById("tambah_button_text"),
    color: document.getElementById("tambah_button_color"),
    textColor: document.getElementById("tambah_button_text_color"),
    hover: document.getElementById("tambah_button_hover_color"),
    preview: document.getElementById("tambahPreview"),
    reset: document.getElementById("resetTambah")
};

function updateTambah() {
    tambahBtn.preview.innerText = tambahBtn.text.value;
    tambahBtn.preview.style.backgroundColor = tambahBtn.color.value;
    tambahBtn.preview.style.color = tambahBtn.textColor.value;
}
[tambahBtn.text, tambahBtn.color, tambahBtn.textColor].forEach(el => el.addEventListener("input", updateTambah));
tambahBtn.preview.addEventListener("mouseover", () => tambahBtn.preview.style.backgroundColor = tambahBtn.hover.value);
tambahBtn.preview.addEventListener("mouseout", updateTambah);
tambahBtn.reset.addEventListener("click", () => {
    tambahBtn.text.value = "Tambah Data";
    tambahBtn.color.value = "#0d6efd";
    tambahBtn.textColor.value = "#ffffff";
    tambahBtn.hover.value = "#0b5ed7";
    updateTambah();
    showResetAlert("Tambah");
});

</script>

<?= $this->endSection() ?>
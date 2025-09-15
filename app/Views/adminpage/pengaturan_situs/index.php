<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
/* ====== Container Style ====== */
.container {
    max-width: 100%;   /* biar full */
    width: 100%;
    padding-left: 0;   /* optional, biar lebih rapat */
    padding-right: 0;  /* optional */
}


/* ====== Card Style ====== */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background: #fff;
    overflow: hidden;
    width: 100%;   /* pastikan full */
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

/* ====== Button Style ====== */
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

#previewButton {
    border-radius: 8px;
    padding: 10px 22px;
    font-weight: 600;
    border: none;
    transition: all 0.2s ease-in-out;
}
#loginPreview {
    border-radius: 8px;
    padding: 10px 22px;  /* lebih lebar */
    font-size: 1.2rem;    /* lebih besar tulisannya */
    font-weight: 600;
    border: none;
    transition: all 0.2s ease-in-out;
}

#orgPreview {
    border-radius: 8px;
    padding: 10px 22px;  /* lebih lebar */
    font-size: 1.2rem;    /* lebih besar tulisannya */
    font-weight: 600;
    border: none;
    transition: all 0.2s ease-in-out;
}

#logoutPreview {
    border-radius: 8px;
    padding: 10px 22px;  /* lebih lebar */
    font-size: 1.2rem;    /* lebih besar tulisannya */
    font-weight: 600;
    border: none;
    transition: all 0.2s ease-in-out;
}

</style>

<div class="container mt-4">

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5>Edit Menu Pengguna </h5>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('pengaturan-situs/save') ?>" method="post">

                <div class="mb-3">
                    <label for="pengguna_button_text" class="form-label">Teks Tombol Pengguna</label>
                    <input type="text" 
                           name="pengguna_button_text" 
                           id="pengguna_button_text" 
                           value="<?= esc($settings['pengguna_button_text']) ?>" 
                           class="form-control">
                </div>

                <div class="mb-3">
    <label for="pengguna_button_color" class="form-label">Warna Tombol</label>
    <div class="d-flex gap-2">
        <input type="color" 
               name="pengguna_button_color" 
               id="pengguna_button_color" 
               value="<?= esc($settings['pengguna_button_color']) ?>" 
               class="form-control form-control-color">
       <button type="button" class="btn-reset" id="resetPenggunaColor">
            Reset
        </button>
    </div>
</div>

                <div class="mb-3">
                    <label for="pengguna_button_text_color" class="form-label">Warna Teks Tombol</label>
                    <input type="color" 
                           name="pengguna_button_text_color" 
                           id="pengguna_button_text_color" 
                           value="<?= esc($settings['pengguna_button_text_color']) ?>" 
                           class="form-control form-control-color" 
                           title="Pilih warna teks tombol">
                </div>

                <div class="mb-3">
                    <label for="pengguna_button_hover_color" class="form-label">Warna Hover Tombol</label>
                    <input type="color" 
                           name="pengguna_button_hover_color" 
                           id="pengguna_button_hover_color" 
                           value="<?= esc($settings['pengguna_button_hover_color']) ?>" 
                           class="form-control form-control-color" 
                           title="Pilih warna hover tombol">
                </div>

                <!-- Preview Tombol -->
                <div class="mb-3">
                    <label class="form-label">Preview Tombol</label><br>
                    <button type="button" 
                            id="previewButton" 
                            style="background-color: <?= esc($settings['pengguna_button_color']) ?>;
                                   color: <?= esc($settings['pengguna_button_text_color']) ?>;">
                        <?= esc($settings['pengguna_button_text']) ?>
                    </button>
                </div>

                <div class="mb-3">
                    <label for="pengguna_perpage_default" class="form-label">Default Tampilkan Per Halaman</label>
                    <input type="number" 
                           name="pengguna_perpage_default" 
                           id="pengguna_perpage_default"
                           value="<?= esc($settings['pengguna_perpage_default']) ?>" 
                           class="form-control" 
                           min="1">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">💾 Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
                  <button type="button" class="btn-reset" id="resetImportColor">Reset</button>
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

          <div class="text-end">
              <button type="submit" class="btn btn-success">💾 Simpan Tombol Import</button>
          </div>
      </form>
  </div>
</div>

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
                             color: <?= esc($settings['org_button_text_color']) ?>;
                             padding: 14px 28px; font-size: 1.1rem; font-weight:600;">
                  <?= esc($settings['org_button_text']) ?>
              </button>
          </div>

          <div class="text-end">
              <button type="submit" class="btn btn-success">💾 Simpan Pengaturan</button>
          </div>
      </form>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header">
      <h5>Pengaturan Tombol Login</h5>
  </div>
  <div class="card-body p-4">

      <!-- FORM LOGIN -->
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
<div class="card mt-4">
  <div class="card-header">
      <h5>Pengaturan Tombol logout</h5>
  </div>
  <div class="card-body p-4">

      <!-- FORM logout -->
      <form action="<?= base_url('pengaturan-situs/save') ?>" method="post">

          <div class="mb-3">
              <label for="logout_button_text" class="form-label">Teks Tombol logout</label>
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
              <label class="form-label">Preview Tombol logout</label><br>
              <button type="button" id="logoutPreview"
                      style="background-color: <?= esc($settings['logout_button_color']) ?>;
                             color: <?= esc($settings['logout_button_text_color']) ?>;">
                  <?= esc($settings['logout_button_text']) ?>
              </button>
          </div>

          <div class="text-end">
              <button type="submit" class="btn btn-success">💾 Simpan Tombol logout</button>
          </div>
      </form>
  </div>
</div>




<style>
.btn-reset {
    padding: 4px 10px;
    font-size: 0.85rem;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background-color: #f3f4f6; /* abu muda */
    color: #374151; /* abu gelap */
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}

.btn-reset:hover {
    background-color: #e5e7eb; /* abu sedikit lebih gelap */
    border-color: #9ca3af;
}
</style>



<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnText = document.getElementById("pengguna_button_text");
    const btnColor = document.getElementById("pengguna_button_color");
    const btnTextColor = document.getElementById("pengguna_button_text_color");
    const btnHover = document.getElementById("pengguna_button_hover_color");
    const preview = document.getElementById("previewButton");

    // Update preview real-time
    function updatePreview() {
        preview.innerText = btnText.value;
        preview.style.backgroundColor = btnColor.value;
        preview.style.color = btnTextColor.value;
    }

    btnText.addEventListener("input", updatePreview);
    btnColor.addEventListener("input", updatePreview);
    btnTextColor.addEventListener("input", updatePreview);

    // Hover effect
    preview.addEventListener("mouseover", () => {
        preview.style.backgroundColor = btnHover.value;
    });
    preview.addEventListener("mouseout", updatePreview);
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // --- Preview Pengguna ---
    const btnText = document.getElementById("pengguna_button_text");
    const btnColor = document.getElementById("pengguna_button_color");
    const btnTextColor = document.getElementById("pengguna_button_text_color");
    const btnHover = document.getElementById("pengguna_button_hover_color");
    const preview = document.getElementById("previewButton");

    function updatePreview() {
        preview.innerText = btnText.value;
        preview.style.backgroundColor = btnColor.value;
        preview.style.color = btnTextColor.value;
    }

    btnText.addEventListener("input", updatePreview);
    btnColor.addEventListener("input", updatePreview);
    btnTextColor.addEventListener("input", updatePreview);

    preview.addEventListener("mouseover", () => {
        preview.style.backgroundColor = btnHover.value;
    });
    preview.addEventListener("mouseout", updatePreview);
// Warna default bawaan CSS .btn-add
const defaultPengguna = {
    bg: "#3b82f6",       // biru default
    text: "#ffffff",     // putih
    hover: "#2563eb"     // biru lebih gelap
};

document.getElementById("resetPenggunaColor").addEventListener("click", () => {
    btnColor.value = defaultPengguna.bg;
    updatePreview();
});

document.getElementById("resetPenggunaTextColor").addEventListener("click", () => {
    btnTextColor.value = defaultPengguna.text;
    updatePreview();
});

document.getElementById("resetPenggunaHover").addEventListener("click", () => {
    btnHover.value = defaultPengguna.hover;
    updatePreview();
});


    // --- Preview Login ---
    const loginText = document.getElementById("login_button_text");
    const loginColor = document.getElementById("login_button_color");
    const loginTextColor = document.getElementById("login_button_text_color");
    const loginHover = document.getElementById("login_button_hover_color");
    const loginPreview = document.getElementById("loginPreview");

    function updateLoginPreview() {
        loginPreview.innerText = loginText.value;
        loginPreview.style.backgroundColor = loginColor.value;
        loginPreview.style.color = loginTextColor.value;
    }

    loginText.addEventListener("input", updateLoginPreview);
    loginColor.addEventListener("input", updateLoginPreview);
    loginTextColor.addEventListener("input", updateLoginPreview);

    loginPreview.addEventListener("mouseover", () => {
        loginPreview.style.backgroundColor = loginHover.value;
    });
    loginPreview.addEventListener("mouseout", updateLoginPreview);
});
</script>


<script>
    // --- Preview Satuan Organisasi ---
const orgText = document.getElementById("org_button_text");
const orgColor = document.getElementById("org_button_color");
const orgTextColor = document.getElementById("org_button_text_color");
const orgHover = document.getElementById("org_button_hover_color");
const orgPreview = document.getElementById("orgPreview");

function updateOrgPreview() {
    orgPreview.innerText = orgText.value;
    orgPreview.style.backgroundColor = orgColor.value;
    orgPreview.style.color = orgTextColor.value;
}

orgText.addEventListener("input", updateOrgPreview);
orgColor.addEventListener("input", updateOrgPreview);
orgTextColor.addEventListener("input", updateOrgPreview);

orgPreview.addEventListener("mouseover", () => {
    orgPreview.style.backgroundColor = orgHover.value;
});
orgPreview.addEventListener("mouseout", updateOrgPreview);

</script>

<script>
    // --- Preview Satuan Organisasi ---
const logoutText = document.getElementById("logout_button_text");
const logoutColor = document.getElementById("logout_button_color");
const logoutTextColor = document.getElementById("logout_button_text_color");
const logoutHover = document.getElementById("logout_button_hover_color");
const logoutPreview = document.getElementById("logoutPreview");

function updatelogoutPreview() {
    logoutPreview.innerText = logoutText.value;
    logoutPreview.style.backgroundColor = logoutColor.value;
    logoutPreview.style.color = logoutTextColor.value;
}

logoutText.addEventListener("input", updatelogoutPreview);
logoutColor.addEventListener("input", updatelogoutPreview);
logoutTextColor.addEventListener("input", updatelogoutPreview);

logoutPreview.addEventListener("mouseover", () => {
    logoutPreview.style.backgroundColor = logoutHover.value;
});
logoutPreview.addEventListener("mouseout", updatelogoutPreview);

</script>

<?= $this->endSection() ?>
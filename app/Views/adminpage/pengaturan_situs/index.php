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
                    <input type="color" 
                           name="pengguna_button_color" 
                           id="pengguna_button_color" 
                           value="<?= esc($settings['pengguna_button_color']) ?>" 
                           class="form-control form-control-color" 
                           title="Pilih warna tombol">
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

<?= $this->endSection() ?>

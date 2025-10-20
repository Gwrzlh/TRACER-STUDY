<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
/* ===============================
   Import Akun Page Styles
================================ */
.import-page {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Heading */
.import-page h2 {
    font-size: 28px;
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 25px;
    padding-left: 10px;
    border-left: 5px solid #3b82f6;
}

/* ===============================
   Card
================================ */
.import-page .import-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.import-page .import-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

/* ===============================
   Form Input
================================ */
.import-page .form-label {
    font-weight: 600;
    color: #374151;
}

.import-page .form-control[type="file"] {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 18px;
    background: #f8fafc;
    transition: all 0.3s ease;
}

.import-page .form-control[type="file"]:hover {
    border-color: #3b82f6;
    background: #f1f5f9;
}

.import-page .form-control[type="file"]:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
}

/* ===============================
   Buttons
================================ */
.import-page .btn-success {
    background: linear-gradient(135deg, #16a34a, #15803d);
    border: none;
    border-radius: 10px;
    padding: 12px 22px;
    font-weight: 600;
    transition: all 0.2s ease-in-out;
}

.import-page .btn-success:hover {
    background: linear-gradient(135deg, #3b82f6, #3b82f6);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(22,163,74,0.4);
}

.import-page .btn-secondary {
    background: #64748b;
    border: none;
    border-radius: 10px;
    padding: 12px 22px;
    font-weight: 600;
    transition: all 0.2s ease-in-out;
}

.import-page .btn-secondary:hover {
    background: #475569;
}

/* ✅ Tombol Download Template — Lebih Menonjol */
#import-page #download-template,
.import-page #download-template {
    background: linear-gradient(135deg, #2563eb, #16a34a);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 14px 24px;
    font-weight: 700;
    font-size: 15px;
    box-shadow: 0 5px 14px rgba(37,99,235,0.3);
    transition: all 0.25s ease-in-out;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

#import-page #download-template:hover,
.import-page #download-template:hover {
    background: linear-gradient(135deg, #1e3a8a, #15803d);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37,99,235,0.4);
    color: #fff;
}

/* ===============================
   Alerts
================================ */
.import-page .alert {
    border-radius: 12px;
    padding: 15px 20px;
    font-weight: 500;
    margin-bottom: 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.import-page .alert-success {
    background: #dcfce7;
    color: #166534;
    border-left: 5px solid #22c55e;
}

.import-page .alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border-left: 5px solid #ef4444;
}

.import-page .alert-warning {
    background: #fef3c7;
    color: #92400e;
    border-left: 5px solid #f59e0b;
}

/* ===============================
   Role Requirements Hint
================================ */
#import-page #role-requirements,
.import-page #role-requirements {
    display: block;
    margin-top: 8px;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 500;
    color: #000000ff;
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    border-radius: 8px;
    line-height: 1.6;
    transition: all 0.3s ease;
}

#import-page #role-requirements:empty,
.import-page #role-requirements:empty {
    display: none;
}

/* Indikator Wajib & Opsional */
.indikator {
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 14px;
    font-weight: 600;
}
.indikator span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.bullet-merah {
    width: 12px;
    height: 12px;
    background: #ef4444;
    border-radius: 50%;
}
.bullet-kuning {
    width: 12px;
    height: 12px;
    background: #facc15;
    border-radius: 50%;
}
</style>

<div class="container mt-4 import-page">
    <h2>Import Akun</h2>

    <div class="import-card">
        <!-- ✅ Area Alert Dinamis -->
        <div id="alert-container"></div>

        <!-- ✅ Alerts dari session -->
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- ✅ Form Import -->
        <form action="<?= base_url('/admin/pengguna/import') ?>" method="post" enctype="multipart/form-data" class="mt-3">
            
            <!-- Pilih Role -->
            <div class="mb-3">
                <label for="role" class="form-label">Pilih Role</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="">Pilih Role </option>
                    <option value="alumni">Alumni</option>
                    <option value="admin">Admin</option>
                    <option value="perusahaan">Perusahaan</option>
                    <option value="kaprodi">Kaprodi</option>
                    <option value="atasan">Atasan</option>
                    <option value="jabatan lainnya">Jabatan Lainnya</option>
                </select>

                <!-- Keterangan wajib -->
                <small id="role-requirements" class="form-text text-muted mt-2"></small>

                <!-- Indikator warna -->
                <div class="indikator">
                    <span><span class="bullet-merah"></span> Wajib</span>
                    <span><span class="bullet-kuning"></span> Opsional</span>
                </div>

                <!-- Tombol Download Template -->
                <a id="download-template" href="#" class="btn mt-3 d-none" target="_blank">
                    <i class="fas fa-file-excel"></i> Download Template Excel
                </a>
            </div>

            <!-- Pilih File -->
            <div class="mb-3">
                <label for="file" class="form-label">Pilih File (xls, xlsx, csv)</label>
                <input type="file" name="file" id="file" class="form-control" accept=".xls,.xlsx,.csv" required>
            </div>

            <!-- Tombol -->
            <button type="submit" class="btn btn-success">
                <i class="fas fa-file-import"></i> Import
            </button>
            <a href="<?= base_url('/admin/pengguna') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </form>
    </div>
</div>

<script>
    const roleSelect = document.getElementById("role");
    const requirements = document.getElementById("role-requirements");
    const templateBtn = document.getElementById("download-template");
    const alertContainer = document.getElementById("alert-container");

    const messages = {
        "alumni": "🔴 WAJIB: Email, Password, NIM, Nama Lengkap, Jurusan, Prodi, Angkatan, Tahun Kelulusan, IPK, No. Telepon<br>🟡 OPSIONAL: Alamat, Kode Pos, Provinsi, Kota",
        "admin": "🔴 WAJIB: Email, Password, Nama Lengkap",
        "perusahaan": "🔴 WAJIB: Email, Password, Nama Perusahaan, Alamat, No. Telepon",
        "kaprodi": "🔴 WAJIB: Email, Password, Nama Lengkap, Jurusan, Prodi, No. Telepon",
        "atasan": "🔴 WAJIB: Email, Password, Nama Lengkap, Jabatan, No. Telepon",
        "jabatan lainnya": "🔴 WAJIB: Email, Password, Nama Lengkap, Jurusan, Prodi, Jabatan, No. Telepon"
    };

    const templates = {
        "alumni": "<?= base_url('templates/alumni_template.xlsx') ?>",
        "admin": "<?= base_url('templates/admin_template.xlsx') ?>",
        "perusahaan": "<?= base_url('templates/perusahaan_template.xlsx') ?>",
        "kaprodi": "<?= base_url('templates/kaprodi_template.xlsx') ?>",
        "atasan": "<?= base_url('templates/atasan_template.xlsx') ?>",
        "jabatan lainnya": "<?= base_url('templates/jabatan_lainnya_template.xlsx') ?>"
    };

    roleSelect.addEventListener("change", function () {
        const role = this.value;
        requirements.innerHTML = messages[role] || "";
        
        // Update tombol template
        if (templates[role]) {
            templateBtn.href = templates[role];
            templateBtn.classList.remove("d-none");
        } else {
            templateBtn.classList.add("d-none");
        }
    });

    // 🚨 Jika klik download tanpa pilih role
    templateBtn.addEventListener("click", function (e) {
        if (!roleSelect.value) {
            e.preventDefault();
            showAlert("⚠️ Pilih role terlebih dahulu sebelum mengunduh template!", "warning");
        }
    });

    function showAlert(message, type = "warning") {
        const alert = document.createElement("div");
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertContainer.innerHTML = "";
        alertContainer.appendChild(alert);
        setTimeout(() => alert.remove(), 4000);
    }
</script>

<?= $this->endSection() ?>

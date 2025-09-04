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
    line-height: 1.5;
    transition: all 0.3s ease;
}

#import-page #role-requirements:empty,
.import-page #role-requirements:empty {
    display: none; /* Sembunyikan kalau kosong */
}


</style>

<div class="container mt-4 import-page">
    <h2>Import Akun</h2>

    <div class="import-card">
        <!-- ✅ Alerts -->
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
                    <option value="">-- Pilih Role --</option>
                    <option value="alumni">Alumni</option>
                    <option value="admin">Admin</option>
                    <option value="perusahaan">Perusahaan</option>
                    <option value="kaprodi">Kaprodi</option>
                    <option value="atasan">Atasan</option>
                    <option value="jabatan lainnya">Jabatan Lainnya</option>
                </select>
                <!-- Keterangan wajib -->
                <small id="role-requirements" class="form-text text-muted mt-2"></small>
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

    const messages = {
        "alumni": "Wajib diisi: Email, Password, NIM, Nama Lengkap, Jurusan, Prodi, Angkatan, Tahun Kelulusan, IPK, No. Telepon",
        "admin": "Wajib diisi: Email, Password, Nama Lengkap",
        "perusahaan": "Wajib diisi: Email, Password, Nama Perusahaan, Alamat, No. Telepon",
        "kaprodi": "Wajib diisi: Email, Password, Nama Lengkap, Jurusan, Prodi, No. Telepon",
        "atasan": "Wajib diisi: Email, Password, Nama Lengkap, Jabatan, No. Telepon",
        "jabatan lainnya": "Wajib diisi: Email, Password, Nama Lengkap, Jurusan, Prodi, Jabatan, No. Telepon"
    };

    roleSelect.addEventListener("change", function () {
        requirements.textContent = messages[this.value] || "";
    });
</script>

<?= $this->endSection() ?>

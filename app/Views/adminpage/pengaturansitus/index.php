<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
    /* Container utama */
    .settings-wrapper {
        background: linear-gradient(to bottom right, #ebf4ff, #ffffff, #dbeafe);
        min-height: 100vh;
        padding: 40px 20px;
    }

    .settings-card {
        max-width: 900px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        padding: 40px;
        border: 1px solid #e5e7eb;
        transition: transform 0.2s ease-in-out;
    }
    .settings-card:hover {
        transform: translateY(-3px);
    }

    .section-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1e3a8a;
        margin-bottom: 25px;
        text-align: left;
        border-left: 5px solid #2563eb;
        padding-left: 12px;
    }

    .alert-success {
        background: #ecfdf5;
        border: 1px solid #10b981;
        color: #065f46;
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 25px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #d1d5db;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.25s ease;
        background: #f9fafb;
    }
    .form-control:focus {
        border-color: #2563eb;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        outline: none;
    }

    .btn-primary {
        background: linear-gradient(to right, #2563eb, #1d4ed8);
        color: white;
        padding: 14px 36px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 4px 15px rgba(37,99,235,0.25);
    }
    .btn-primary:hover {
        background: linear-gradient(to right, #1e40af, #1d4ed8);
        transform: translateY(-2px);
    }
</style>

<div class="settings-wrapper">
    <div class="settings-card">
        <h1 class="section-title">
            <i class="fas fa-cog"></i> Pengaturan Situs
        </h1>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/pengaturan-situs/simpan') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Pilihan Warna Tema -->
            <div class="form-group">
                <label class="form-label" for="theme">Warna Tema</label>
                <select name="theme" id="theme" class="form-control">
                    <option value="light" <?= ($theme == 'light') ? 'selected' : '' ?>>Terang</option>
                    <option value="dark" <?= ($theme == 'dark') ? 'selected' : '' ?>>Gelap</option>
                    <option value="blue" <?= ($theme == 'blue') ? 'selected' : '' ?>>Biru</option>
                    <option value="green" <?= ($theme == 'green') ? 'selected' : '' ?>>Hijau</option>
                    <option value="custom" <?= ($theme == 'custom') ? 'selected' : '' ?>>Custom</option>
                </select>
            </div>

            <!-- Jumlah Data per Halaman -->
            <div class="form-group">
                <label class="form-label" for="per_page">Tampilan Data per Halaman</label>
                <select name="per_page" id="per_page" class="form-control">
                    <option value="5" <?= ($per_page == 5) ? 'selected' : '' ?>>5</option>
                    <option value="10" <?= ($per_page == 10) ? 'selected' : '' ?>>10</option>
                    <option value="20" <?= ($per_page == 20) ? 'selected' : '' ?>>20</option>
                    <option value="50" <?= ($per_page == 50) ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= ($per_page == 100) ? 'selected' : '' ?>>100</option>
                </select>
            </div>

            <!-- Pilihan Tampilan Data -->
            <div class="form-group">
                <label class="form-label" for="view_mode">Tampilan Data</label>
                <select name="view_mode" id="view_mode" class="form-control">
                    <option value="table" <?= ($view_mode == 'table') ? 'selected' : '' ?>>Tabel</option>
                    <option value="grid" <?= ($view_mode == 'grid') ? 'selected' : '' ?>>Grid</option>
                    <option value="compact" <?= ($view_mode == 'compact') ? 'selected' : '' ?>>Ringkas</option>
                </select>
            </div>

            <div style="text-align:center; margin-top:30px;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
            </div>      
        </form>
    </div>
</div>

<?= $this->endSection() ?>

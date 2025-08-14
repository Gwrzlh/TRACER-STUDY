<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
    .settings-container {
        max-width: 800px;
        margin: auto;
    }
    .settings-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        border: 1px solid #e0e4e8;
    }
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 0.95rem;
    }
    .btn-primary {
        background-color: orange;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-primary:hover {
        background-color: #e6850e;
    }
</style>

<div class="settings-container">
    <div class="settings-card">
        <h1 class="section-title">
            <i class="fas fa-paint-brush"></i> Pengaturan Tampilan
        </h1>

        <?php if(session()->getFlashdata('success')): ?>
            <div style="background:#d4edda;padding:10px;border-radius:8px;margin-bottom:15px;">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/pengaturan-situs/simpan') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Pilihan Warna Tema -->
            <div class="form-group" style="margin-bottom:20px;">
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
            <div class="form-group" style="margin-bottom:20px;">
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
            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" for="view_mode">Mode Tampilan Data</label>
                <select name="view_mode" id="view_mode" class="form-control">
                    <option value="table" <?= ($view_mode == 'table') ? 'selected' : '' ?>>Tabel</option>
                    <option value="grid" <?= ($view_mode == 'grid') ? 'selected' : '' ?>>Grid</option>
                    <option value="compact" <?= ($view_mode == 'compact') ? 'selected' : '' ?>>Ringkas</option>
                </select>
            </div>

            <div style="text-align:center;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

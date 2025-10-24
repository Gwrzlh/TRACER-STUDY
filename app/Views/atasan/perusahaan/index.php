<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-3 fw-bold text-primary">🏢 Perusahaan Saya</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (!empty($perusahaan)): ?>
        <div class="card border-0 shadow-lg rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h4 class="fw-bold text-dark mb-0"><?= esc($perusahaan['nama_perusahaan']) ?></h4>
                <span class="badge bg-primary-subtle text-primary px-3 py-2 fs-6">Aktif</span>
            </div>

            <div class="text-secondary mb-3">
                <p class="mb-1"><strong>Alamat 1:</strong> <?= esc($perusahaan['alamat1'] ?? '-') ?></p>
                <?php if (!empty($perusahaan['alamat2'])): ?>
                    <p class="mb-1"><strong>Alamat 2:</strong> <?= esc($perusahaan['alamat2']) ?></p>
                <?php endif; ?>
                <p class="mb-1"><strong>Kota:</strong> <?= esc($perusahaan['kota'] ?? '-') ?></p>
                <p class="mb-1"><strong>Provinsi:</strong> <?= esc($perusahaan['provinsi'] ?? '-') ?></p>
                <p class="mb-1"><strong>Kode Pos:</strong> <?= esc($perusahaan['kodepos'] ?? '-') ?></p>
                <p class="mb-0"><strong>Telepon:</strong> <?= esc($perusahaan['noTlp'] ?? '-') ?></p>
            </div>

            <hr>

            <div class="d-flex flex-wrap gap-2 mt-2">
                <a href="<?= base_url('atasan/perusahaan/edit/' . $perusahaan['id']) ?>" 
                   class="btn btn-warning btn-sm px-3 shadow-sm">
                    ✏️ Edit Perusahaan
                </a>

                <a href="<?= base_url('atasan/perusahaan/detail/' . $perusahaan['id']) ?>" 
                   class="btn btn-info btn-sm px-3 shadow-sm">
                    👁️ Lihat Alumni
                </a>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-info text-center p-5 mt-5 rounded-4 shadow-sm">
            <h4 class="fw-semibold text-primary mb-2">Belum Ada Perusahaan Terhubung</h4>
            <p class="mb-3">Akun Anda belum memiliki perusahaan yang terhubung.<br>
               Silakan hubungi admin untuk menambahkan data perusahaan Anda.</p>
            <i class="text-muted">Data perusahaan akan muncul otomatis di sini setelah disetujui admin.</i>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

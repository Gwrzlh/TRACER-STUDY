<?= $this->extend('layout/sidebar'); ?>
<?= $this->section('content'); ?>

<link href="<?= base_url('css/respon/akreditasi.css') ?>" rel="stylesheet">

<div class="flex-1 overflow-y-auto bg-gray-50">
    <div class="max-w-7xl mx-auto px-8 py-8">

        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav mb-6">
            <a href="<?= base_url('admin/respon') ?>" class="breadcrumb-item <?= (uri_string() == 'admin/respon') ? 'active' : '' ?>">
                <svg class="breadcrumb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Respon</span>
            </a>
            <span class="breadcrumb-separator">›</span>
            <a href="<?= base_url('admin/respon/ami') ?>" class="breadcrumb-item <?= (uri_string() == 'admin/respon/ami') ? 'active' : '' ?>">
                <svg class="breadcrumb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>AMI</span>
            </a>
            <span class="breadcrumb-separator">›</span>
            <a href="<?= base_url('admin/respon/akreditasi') ?>" class="breadcrumb-item <?= (uri_string() == 'admin/respon/akreditasi') ? 'active' : '' ?>">
                <svg class="breadcrumb-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0h2a2 2 0 012 2h2a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6z" />
                </svg>
                <span>Akreditasi</span>
            </a>
        </div>

        <!-- Header -->
        <div class="header-section mb-8">
            <h1 class="header-title">Data Akreditasi</h1>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div class="card-header">
                <h2 class="card-title">DAFTAR PERTANYAAN AKREDITASI</h2>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <?php if (empty($pertanyaan)) : ?>
                    <div class="empty-state">
                        <div class="empty-icon">📊</div>
                        <p class="empty-text">Belum ada pertanyaan untuk Akreditasi.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($pertanyaan as $q) : ?>
                        <div class="question-item">
                            <!-- Question Header -->
                            <div class="question-header">
                                <div class="question-info">
                                    <h3 class="question-text"><?= esc($q['teks']); ?></h3>
                                    <span class="badge-akreditasi">Akreditasi</span>
                                </div>
                                <a href="<?= base_url('admin/respon/remove_from_accreditation/' . $q['id']); ?>"
                                   class="btn-hapus">
                                     Hapus
                                </a>
                            </div>

                            <!-- Answer Table -->
                            <div class="table-container">
                                <table class="answer-table">
                                    <thead>
                                        <tr>
                                            <th class="col-opsi">OPSI JAWABAN</th>
                                            <th class="col-jumlah">JUMLAH</th>
                                            <th class="col-detail">DETAIL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($q['jawaban'] as $a) : ?>
                                            <tr class="answer-row">
                                                <td class="opsi-cell"><?= esc($a['opsi']); ?></td>
                                                <td class="jumlah-cell">
                                                    <span class="jumlah-badge"><?= esc($a['jumlah']); ?></span>
                                                </td>
                                                <td class="detail-cell">
                                                    <a href="<?= base_url('admin/respon/akreditasi/detail/' . urlencode($a['opsi'])); ?>"
                                                       class="btn-detail">
                                                        Lihat Alumni
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 🔥 Konfirmasi SweetAlert2 untuk tombol hapus
    document.querySelectorAll('.btn-hapus').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');

            Swal.fire({
                title: 'Hapus pertanyaan ini?',
                text: 'Data pertanyaan akan dihapus dari Akreditasi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

    // ✅ Tampilkan SweetAlert2 jika ada flashdata success
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success') ?>',
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>

    // ❌ Tampilkan SweetAlert2 jika ada flashdata error
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= session()->getFlashdata('error') ?>',
        });
    <?php endif; ?>
});
</script>

<?= $this->endSection(); ?>

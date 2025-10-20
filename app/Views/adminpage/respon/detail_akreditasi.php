<?= $this->extend('layout/sidebar'); ?>
<?= $this->section('content'); ?>

<link href="<?= base_url('css/respon/detail_akreditasi.css') ?>" rel="stylesheet">

<div class="flex-1 overflow-y-auto bg-gray-50">
    <div class="max-w-7xl mx-auto px-8 py-8">

        <!-- Header Section -->
        <div class="header-container mb-8">
            <div class="header-top">
                <div class="header-left">
                    <h1 class="page-title"> Detail Alumni - <span class="badge-akreditasi-title">Akreditasi</span></h1>
                </div>
                <a href="<?= base_url('admin/respon/akreditasi'); ?>" class="btn-kembali">
                     Kembali
                </a>
            </div>
        </div>

        <?php if (empty($alumni)) : ?>
            <!-- Empty State -->
            <div class="empty-card">
                <div class="empty-state">
                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="empty-text">Tidak ada alumni yang termasuk dalam data Akreditasi.</p>
                </div>
            </div>
        <?php else : ?>
            <!-- Filter Card -->
            <div class="filter-card mb-6">
                <form method="get" class="filter-form">
                    <div class="filter-grid">
                        <div class="filter-group">
                            <select name="jurusan" class="filter-input">
                                <option value="">Semua Jurusan</option>
                                <?php foreach ($jurusanList as $j): ?>
                                    <option value="<?= esc($j['id']); ?>" <?= ($filterJurusan == $j['id']) ? 'selected' : ''; ?>>
                                        <?= esc($j['nama_jurusan']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <select name="prodi" class="filter-input">
                                <option value="">Semua Prodi</option>
                                <?php foreach ($prodiList as $p): ?>
                                    <option value="<?= esc($p['id']); ?>" <?= ($filterProdi == $p['id']) ? 'selected' : ''; ?>>
                                        <?= esc($p['nama_prodi']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <input type="number" name="angkatan" class="filter-input" placeholder="Angkatan"
                                value="<?= esc($filterAngkatan); ?>">
                        </div>

                        <div class="filter-group">
                            <button type="submit" class="btn-filter">
                                 Filter
                            </button>
                        </div>
                    </div>

                    <div class="filter-actions">
                        <a href="<?= base_url('admin/respon/akreditasi/pdf/' . urlencode($opsi)); ?>" target="_blank" class="btn-export">
                            📄 Export PDF
                        </a>
                    </div>
                </form>
            </div>

            <!-- Data Table Card -->
            <div class="table-card">
                <!-- Card Header -->
                <div class="card-header">
                    <h2 class="card-title">DAFTAR ALUMNI</h2>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="col-no">No</th>
                                    <th>Nama Lengkap</th>
                                    <th class="col-nim">NIM</th>
                                    <th>Jurusan</th>
                                    <th>Prodi</th>
                                    <th class="col-center">Angkatan</th>
                                    <th class="col-center">Tahun Kelulusan</th>
                                    <th class="col-center">IPK</th>
                                    <th>Alamat</th>
                                    <th class="col-center">Jenis Kelamin</th>
                                    <th>No. Telp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($alumni as $a): ?>
                                    <tr>
                                        <td class="col-no"><?= $no++; ?></td>
                                        <td class="nama-cell"><?= esc($a['nama_lengkap']); ?></td>
                                        <td class="nim-cell"><?= esc($a['nim']); ?></td>
                                        <td><?= esc($a['nama_jurusan']); ?></td>
                                        <td><?= esc($a['nama_prodi']); ?></td>
                                        <td class="col-center"><?= esc($a['angkatan']); ?></td>
                                        <td class="col-center"><?= esc($a['tahun_kelulusan']); ?></td>
                                        <td class="col-center">
                                            <span class="ipk-badge"><?= esc($a['ipk']); ?></span>
                                        </td>
                                        <td><?= esc($a['alamat']); ?></td>
                                        <td class="col-center"><?= esc($a['jenisKelamin']); ?></td>
                                        <td><?= esc($a['notlp']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection(); ?>
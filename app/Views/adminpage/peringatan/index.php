<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/kirim_peringatan.css') ?>">

<div class="container">
  <!-- Page Header -->
  <div class="page-header">
    <div class="header-content">
      <div class="header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
      </div>
      <div class="header-text">
        <h2 class="header-title">Peringatan Penilaian Atasan</h2>
        <p class="header-subtitle">Daftar atasan yang belum menilai alumni mereka</p>
      </div>
    </div>
    <form action="<?= base_url('admin/kirim-peringatan-penilaian') ?>" method="post">
      <?= csrf_field() ?>
      <button type="submit" class="btn-send-all">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="22" y1="2" x2="11" y2="13"></line>
          <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
        </svg>
        Kirim Semua
      </button>
    </form>
  </div>

  <!-- Alert Success -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
      </svg>
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <!-- Empty State -->
  <?php if (empty($peringatan)): ?>
    <div class="empty-state-card">
      <div class="empty-state-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <h4 class="empty-state-title">Semua Sudah Dinilai</h4>
      <p class="empty-state-text">Semua atasan sudah menilai alumni mereka. Tidak ada peringatan yang perlu dikirim.</p>
    </div>
  <?php else: ?>

    <!-- List Peringatan -->
    <div class="peringatan-list">
      <?php foreach ($peringatan as $p): ?>
        <div class="peringatan-card">
          <!-- Card Header -->
          <div class="card-header">
            <div class="atasan-info">
              <div class="atasan-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </div>
              <div class="atasan-details">
                <h5 class="atasan-name"><?= esc($p['atasan']['nama_atasan']) ?></h5>
                <p class="atasan-email"><?= esc($p['atasan']['email']) ?></p>
              </div>
            </div>
            <form action="<?= base_url('admin/kirim-peringatan-penilaian') ?>" method="post" class="form-inline">
              <?= csrf_field() ?>
              <input type="hidden" name="id_atasan" value="<?= $p['atasan']['id_account'] ?>">
              <button type="submit" class="btn-send">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="22" y1="2" x2="11" y2="13"></line>
                  <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                Kirim Sekarang
              </button>
            </form>
          </div>

          <!-- Card Body -->
          <div class="card-body">
            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 11l3 3L22 4"></path>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
              </svg>
              Alumni yang belum dinilai
            </div>
            <ul class="alumni-list">
              <?php foreach ($p['alumni'] as $a): ?>
                <li class="alumni-item">
                  <div class="alumni-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                  </div>
                  <div class="alumni-info">
                    <span class="alumni-name"><?= esc($a['nama_lengkap']) ?></span>
                    <span class="alumni-meta"><?= esc($a['nim']) ?> • <?= esc($a['nama_prodi']) ?></span>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
  /* 🎨 Custom styling */
  .page-header {
    background: linear-gradient(90deg, #0d6efd, #3b82f6);
    color: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
  }

  .card {
    border: none;
    border-radius: 12px;
    transition: all 0.3s ease;
  }

  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
  }

  .card-header {
    border-top-left-radius: 12px !important;
    border-top-right-radius: 12px !important;
    font-weight: 600;
    background: linear-gradient(90deg, #0d6efd, #007bff);
  }

  .btn-light.btn-sm {
    background: white;
    border: none;
    color: #0d6efd;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .btn-light.btn-sm:hover {
    background: #e7f1ff;
    color: #0a58ca;
  }

  .btn-primary.mb-3 {
    background: linear-gradient(90deg, #3b82f6, #2563eb);
    border: none;
    border-radius: 10px;
    padding: 10px 18px;
    font-weight: 600;
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    transition: all 0.3s ease;
  }

  .btn-primary.mb-3:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(37, 99, 235, 0.4);
  }

  ul {
    margin-top: 8px;
    margin-bottom: 0;
  }

  ul li {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 6px;
    margin-bottom: 6px;
    font-size: 0.95rem;
    border-left: 4px solid #3b82f6;
  }

  .alert-info {
    background: #e3f2fd;
    color: #0d47a1;
    font-weight: 500;
    border: none;
    border-radius: 10px;
  }

  .alert-success {
    border-radius: 10px;
  }
</style>

<div class="container mt-4">
  <!-- 🏷️ Header -->
  <div class="page-header d-flex justify-content-between align-items-center">
    <div>
      <h3 class="fw-bold mb-0"><i class="fa-solid fa-bell me-2"></i>Peringatan Penilaian Atasan</h3>
      <p class="text-light mb-0">Daftar atasan yang belum menilai alumni mereka.</p>
    </div>
    <form action="<?= base_url('admin/kirim-peringatan-penilaian') ?>" method="post">
      <?= csrf_field() ?>
      <button type="submit" class="btn btn-light text-primary fw-semibold">
        <i class="fa-solid fa-paper-plane me-1"></i> Kirim Semua
      </button>
    </form>
  </div>

  <!-- 🧾 Alert -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success mt-3">
      <i class="fa fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <?php if (empty($peringatan)): ?>
    <div class="alert alert-info text-center mt-3">
      🎉 Semua atasan sudah menilai alumni mereka. Tidak ada peringatan yang perlu dikirim.
    </div>
  <?php else: ?>

    <!-- 📋 List atasan -->
    <?php foreach ($peringatan as $p): ?>
      <div class="card mb-4 shadow-sm">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
          <div>
            👔 <b><?= esc($p['atasan']['nama_atasan']) ?></b><br>
            <small class="text-light"><?= esc($p['atasan']['email']) ?></small>
          </div>
          <form action="<?= base_url('admin/kirim-peringatan-penilaian') ?>" method="post" class="d-inline">
            <?= csrf_field() ?>
            <input type="hidden" name="id_atasan" value="<?= $p['atasan']['id_account'] ?>">
            <button type="submit" class="btn btn-light btn-sm">
              <i class="fa-solid fa-bell me-1"></i> Kirim Sekarang
            </button>
          </form>
        </div>

        <div class="card-body">
          <h6 class="text-secondary fw-bold mb-2">📋 Alumni yang belum dinilai:</h6>
          <ul>
            <?php foreach ($p['alumni'] as $a): ?>
              <li>
                <b><?= esc($a['nama_lengkap']) ?></b> 
                <small class="text-muted"> (<?= esc($a['nim']) ?> • <?= esc($a['nama_prodi']) ?>)</small>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// --- Konfirmasi "Kirim Semua" ---
document.querySelector('form[action="<?= base_url('admin/kirim-peringatan-penilaian') ?>"]:not(.d-inline)')?.addEventListener('submit', function(e) {
  e.preventDefault();

  Swal.fire({
    title: 'Kirim Semua Peringatan?',
    text: 'Semua atasan yang belum menilai akan menerima email peringatan.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, Kirim Semua',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#2563eb',
    cancelButtonColor: '#6b7280'
  }).then((result) => {
    if (result.isConfirmed) {
      this.submit();
    }
  });
});

// --- Konfirmasi "Kirim Sekarang" per atasan ---
document.querySelectorAll('form.d-inline').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const namaAtasan = this.closest('.card').querySelector('.card-header b')?.textContent.trim() || 'atasan ini';

    Swal.fire({
      title: `Kirim Peringatan ke ${namaAtasan}?`,
      text: 'Email akan dikirim ke atasan tersebut.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Kirim Sekarang',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#2563eb',
      cancelButtonColor: '#6b7280'
    }).then((result) => {
      if (result.isConfirmed) {
        this.submit();
      }
    });
  });
});

// --- Alert sukses otomatis setelah redirect ---
<?php if (session()->getFlashdata('success')): ?>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '<?= esc(session()->getFlashdata('success')) ?>',
  timer: 2000,
  showConfirmButton: false
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>

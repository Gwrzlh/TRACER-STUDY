<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/atasan/alumni/index.css') ?>">

<div class="container">
  <!-- Page Header -->
  <div class="page-header">
    <div class="header-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
        <circle cx="8.5" cy="7" r="4"></circle>
        <polyline points="17 11 19 13 23 9"></polyline>
      </svg>
    </div>
    <h2 class="header-title">Penilaian Kuesioner Alumni</h2>
  </div>

  <!-- Tabel Data -->
  <div class="table-wrapper">
    <table class="data-table">
      <thead>
        <tr>
          <th>Nama</th>
          <th>NIM</th>
          <th>Prodi</th>
          <th>Kuesioner Selesai</th>
          <th>Status</th>
          <th>Nilai Rata-rata</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($alumni)): ?>
          <tr>
            <td colspan="7" class="text-center empty-state">Belum ada data alumni.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($alumni as $a): ?>
          <tr>
            <td class="font-medium"><?= esc($a['nama_lengkap']) ?></td>
            <td><?= esc($a['nim']) ?></td>
            <td><?= esc($a['nama_prodi'] ?? '-') ?></td>

            <!-- Jumlah Kuesioner -->
            <td>
              <?php if ($a['total_responses'] > 0): ?>
                <span class="badge badge-success"><?= $a['total_responses'] ?> selesai</span>
              <?php else: ?>
                <span class="badge badge-secondary">Belum ada</span>
              <?php endif; ?>
            </td>

            <!-- Status -->
            <td>
              <?php if ($a['total_responses'] == 0): ?>
                <span class="badge badge-danger">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                  </svg>
                  Belum Isi Kuesioner
                </span>
              <?php elseif ($a['rata_rata']): ?>
                <span class="badge badge-success">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
                  Sudah Dinilai
                </span>
              <?php else: ?>
                <span class="badge badge-warning">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                  </svg>
                  Belum Dinilai
                </span>
              <?php endif; ?>
            </td>

            <!-- Nilai Rata-rata -->
            <td>
              <?php if ($a['rata_rata']):
                $stars = round($a['rata_rata']);
              ?>
                <div class="rating-display">
                  <div class="stars">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                      <svg class="star-icon <?= $i <= $stars ? 'active' : '' ?>" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="<?= $i <= $stars ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                      </svg>
                    <?php endfor; ?>
                  </div>
                  <div class="rating-text"><?= number_format($a['rata_rata'],1) ?>/5</div>
                </div>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>

            <!-- Tombol Nilai -->
            <td class="text-center">
              <?php if ($a['total_responses'] > 0): ?>
                <button class="btn-action btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#nilaiModal<?= $a['id'] ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                  </svg>
                  Nilai
                </button>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ==================== MODAL PENILAIAN ==================== -->
<?php foreach ($alumni as $a): ?>
<?php if ($a['total_responses'] > 0): ?>
<div class="modal fade" id="nilaiModal<?= $a['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="<?= base_url('atasan/simpan-penilaian/'.$a['id']) ?>">
        <?= csrf_field() ?>
        <div class="modal-header">
          <div class="modal-title-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            <h5 class="modal-title">Penilaian: <?= esc($a['nama_lengkap']) ?></h5>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <?php
          $aspek = [
            'kelengkapan' => 'Kelengkapan Jawaban',
            'kejelasan'   => 'Kejelasan & Relevansi',
            'konsistensi' => 'Konsistensi Jawaban',
            'refleksi'    => 'Kualitas Refleksi',
          ];
          ?>

          <?php foreach ($aspek as $field => $label): ?>
          <div class="rating-group">
            <label class="rating-label"><?= $label ?></label>
            <div class="rating-stars" data-field="<?= $field ?>">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <svg class="star-input <?= ($a[$field] ?? 0) >= $i ? 'active' : '' ?>" data-value="<?= $i ?>" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="<?= ($a[$field] ?? 0) >= $i ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
              <?php endfor; ?>
              <input type="hidden" name="<?= $field ?>" value="<?= esc($a[$field] ?? 0) ?>">
            </div>
          </div>
          <?php endforeach; ?>

          <div class="form-group">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan atau feedback..."><?= esc($a['catatan'] ?? '') ?></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
<?php endforeach; ?>

<!-- SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Rating stars click handler
document.querySelectorAll('.rating-stars').forEach(rating => {
  const stars = rating.querySelectorAll('.star-input');
  const input = rating.querySelector('input');
  
  stars.forEach(star => {
    star.addEventListener('click', () => {
      const val = parseInt(star.dataset.value);
      input.value = val;
      
      stars.forEach(s => {
        const sVal = parseInt(s.dataset.value);
        s.classList.toggle('active', sVal <= val);
        s.setAttribute('fill', sVal <= val ? 'currentColor' : 'none');
      });
    });
  });
});

// SweetAlert notifications
<?php if (session()->getFlashdata('success')): ?>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '<?= session()->getFlashdata('success') ?>',
  confirmButtonColor: '#3b82f6',
  confirmButtonText: 'OK'
});
<?php elseif (session()->getFlashdata('error')): ?>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: '<?= session()->getFlashdata('error') ?>',
  confirmButtonColor: '#3b82f6',
  confirmButtonText: 'OK'
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>
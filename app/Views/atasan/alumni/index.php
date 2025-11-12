<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<style>
:root {
  --primary-color: #0d47a1;
  --accent-color: #1e40af;
  --light-bg: #f9fafb;
  --border-radius: 12px;
}
.table-container {
  background: #fff;
  border-radius: var(--border-radius);
  padding: 24px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}
.star {
  font-size: 1.4rem;
  color: #ddd;
  cursor: pointer;
  transition: color 0.2s;
}
.star.active {
  color: #ffc107;
}
</style>

<div class="container mt-4">
  <h3 class="fw-bold text-primary mb-4">📊 Penilaian Kuesioner Alumni</h3>

  <div class="table-container">
    <table class="table table-hover align-middle text-center">
      <thead class="table-primary">
        <tr>
          <th>Nama</th>
          <th>NIM</th>
          <th>Prodi</th>
          <th>Kuesioner Selesai</th>
          <th>Status</th>
          <th>Nilai Rata-rata</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($alumni as $a): ?>
        <tr>
          <td><?= esc($a['nama_lengkap']) ?></td>
          <td><?= esc($a['nim']) ?></td>
          <td><?= esc($a['nama_prodi'] ?? '-') ?></td>

          <!-- Jumlah Kuesioner -->
          <td>
            <?php if ($a['total_responses'] > 0): ?>
              <span class="badge bg-success"><?= $a['total_responses'] ?> selesai</span>
            <?php else: ?>
              <span class="badge bg-secondary">Belum ada</span>
            <?php endif; ?>
          </td>

          <!-- Status -->
          <td>
            <?php if ($a['total_responses'] == 0): ?>
              <span class="badge bg-secondary">⛔ Belum Isi Kuesioner</span>
            <?php elseif ($a['rata_rata']): ?>
              <span class="badge bg-success">✅ Sudah Dinilai</span>
            <?php else: ?>
              <span class="badge bg-warning text-dark">❌ Belum Dinilai</span>
            <?php endif; ?>
          </td>

          <!-- Nilai Rata-rata -->
          <td>
            <?php if ($a['rata_rata']):
              $stars = round($a['rata_rata']);
            ?>
              <div class="text-warning fs-5">
                <?= str_repeat('⭐', $stars) . str_repeat('☆', 5 - $stars) ?>
                <div class="small text-muted"><?= number_format($a['rata_rata'],1) ?>/5</div>
              </div>
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
          </td>

          <!-- Tombol Nilai -->
          <td>
            <?php if ($a['total_responses'] > 0): ?>
              <button class="btn btn-sm btn-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#nilaiModal<?= $a['id'] ?>">
                📝 Nilai
              </button>
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ==================== MODAL PENILAIAN ==================== -->
<?php foreach ($alumni as $a): ?>
<?php if ($a['total_responses'] > 0): // Modal hanya untuk alumni yang bisa dinilai ?>
<div class="modal fade" id="nilaiModal<?= $a['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="<?= base_url('atasan/simpan-penilaian/'.$a['id']) ?>">
        <?= csrf_field() ?>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">📝 Penilaian: <?= esc($a['nama_lengkap']) ?></h5>
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
          <div class="mb-3 text-center">
            <label class="form-label fw-bold d-block"><?= $label ?></label>
            <div class="rating" data-field="<?= $field ?>">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star <?= ($a[$field] ?? 0) >= $i ? 'active' : '' ?>" data-value="<?= $i ?>">★</span>
              <?php endfor; ?>
              <input type="hidden" name="<?= $field ?>" value="<?= esc($a[$field] ?? 0) ?>">
            </div>
          </div>
          <?php endforeach; ?>

          <label class="form-label fw-bold mt-3">Catatan</label>
          <textarea name="catatan" class="form-control" rows="3"><?= esc($a['catatan'] ?? '') ?></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">💾 Simpan</button>
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
document.querySelectorAll('.rating').forEach(rating => {
  const stars = rating.querySelectorAll('.star');
  const input = rating.querySelector('input');
  stars.forEach(star => {
    star.addEventListener('click', () => {
      const val = parseInt(star.dataset.value);
      input.value = val;
      stars.forEach(s => s.classList.toggle('active', s.dataset.value <= val));
    });
  });
});

<?php if (session()->getFlashdata('success')): ?>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '<?= session()->getFlashdata('success') ?>',
  confirmButtonColor: '#0d47a1'
});
<?php elseif (session()->getFlashdata('error')): ?>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: '<?= session()->getFlashdata('error') ?>',
  confirmButtonColor: '#0d47a1'
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>

<!-- desain daftar pengguna -->
<?= $this->extend('layout/sidebar'); ?>
<?= $this->section('content'); ?>

<!-- External CSS and JS -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="<?= base_url('css/pengguna/index.css') ?>" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('js/pengguna.js') ?>"></script>

<!-- Main Content -->
<div class="pengguna-page">
  <div class="page-wrapper">
    <div class="page-container">
      <h2 class="page-title">Daftar Pengguna</h2>

      <!-- Top Controls -->
      <div class="top-controls d-flex justify-content-between align-items-center">
        <!-- Search Form -->
        <div class="controls-container">
          <form method="get" action="<?= base_url('admin/pengguna') ?>">
            <?php if (!empty($roleId)): ?>
              <input type="hidden" name="role" value="<?= esc($roleId) ?>">
            <?php endif; ?>
            <div class="search-wrapper">
              <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" 
                     placeholder="Cari nama pengguna..." class="search-input">
              <button type="submit" class="search-btn">
                <i class="fas fa-search"></i> Search
              </button>
            </div>
          </form>
        </div>

        <!-- Buttons -->
        <div class="button-container d-flex gap-2">
          <a href="<?= base_url('admin/pengguna/tambahPengguna') ?>"
             class="btn-add"
             style="
                background-color: <?= get_setting('pengguna_button_color', '#3b82f6') ?>;
                color: <?= get_setting('pengguna_button_text_color', '#ffffff') ?>;
             "
             onmouseover="this.style.backgroundColor='<?= get_setting('pengguna_button_hover_color', '#2563eb') ?>'"
             onmouseout="this.style.backgroundColor='<?= get_setting('pengguna_button_color', '#3b82f6') ?>'">
            <i class="fas fa-user-plus"></i>
            <?= get_setting('pengguna_button_text', 'Tambah Pengguna') ?>
          </a>

          <a href="<?= base_url('admin/pengguna/import') ?>"
             class="btn-import"
             style="
                background-color: <?= get_setting('import_button_color', '#22c55e') ?>;
                color: <?= get_setting('import_button_text_color', '#ffffff') ?>;
             "
             onmouseover="this.style.backgroundColor='<?= get_setting('import_button_hover_color', '#16a34a') ?>'"
             onmouseout="this.style.backgroundColor='<?= get_setting('import_button_color', '#22c55e') ?>'">
            <i class="fas fa-file-import"></i>
            <?= get_setting('import_button_text', 'Import Akun') ?>
          </a>
        </div>
      </div>

      <!-- Table / Data -->
      <?php if (!empty($accounts)): ?>
        <div class="table-container mt-3">
          <div class="table-wrapper">
            <table class="user-table">
              <thead>
                <tr>
                  <th><input type="checkbox" class="table-checkbox"></th>
                  <th>Pengguna</th>
                  <th>Status</th>
                  <th>Email</th>
                  <th>Group</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $no = 1 + (($perPage ?? 10) * (($currentPage ?? 1) - 1));
                foreach ($accounts as $acc): ?>
                  <tr>
                    <td><input type="checkbox" class="table-checkbox"></td>
                    <td class="user-info">
                      <?php if (!empty($acc['foto'])): ?>
                        <div class="user-avatar">
                          <img src="<?= base_url('uploads/foto_admin/' . esc($acc['foto'])) ?>" 
                               alt="<?= esc($acc['username']) ?>" class="avatar-img">
                        </div>
                      <?php else: ?>
                        <div class="user-avatar" data-initial="<?= strtoupper(substr($acc['username'], 0, 1)) ?>">
                          <span><?= strtoupper(substr($acc['username'], 0, 1)) ?></span>
                        </div>
                      <?php endif; ?>

                      <div class="user-details">
                        <div class="user-name"><?= esc($acc['username']) ?></div>
                        <div class="user-email"><?= esc($acc['email']) ?></div>
                      </div>
                    </td>
                    <td>
                      <span class="status-badge <?= (strtolower($acc['status']) == 'active' || strtolower($acc['status']) == 'aktif' || $acc['status'] == '1') ? 'status-active' : 'status-inactive' ?>">
                        <?= (strtolower($acc['status']) == 'active' || strtolower($acc['status']) == 'aktif' || $acc['status'] == '1') ? 'Active' : 'Inactive' ?>
                      </span>
                    </td>
                    <td class="email-cell"><?= esc($acc['email']) ?></td>
                    <td><span class="group-badge"><?= esc($acc['nama_role'] ?? 'No Role') ?></span></td>
                    <td class="action-cell">
                      <div class="action-buttons">
                        <a href="<?= base_url('/admin/pengguna/editPengguna/' . $acc['id']) ?>" 
                           class="btn-action btn-edit" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?= base_url('/admin/pengguna/delete/' . $acc['id']) ?>" 
                              method="post" style="display: inline;" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                          <?= csrf_field() ?>
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="submit" class="btn-action btn-delete" title="Delete">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                        <button class="btn-action btn-more" title="More options">
                          <i class="fas fa-ellipsis-v"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <?php if (!empty($pagerLinks)): ?>
            <div class="pagination-wrapper mt-3">
              <?= $pagerLinks ?>
            </div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state text-center mt-5">
          <i class="fas fa-users fa-3x mb-3"></i>
          <h3>Belum ada data pengguna</h3>
          <p>Silakan tambah pengguna baru untuk memulai.</p>
          <a href="<?= base_url('admin/pengguna/tambahPengguna') ?>" class="btn-add">
            <i class="fas fa-user-plus"></i> Tambah Pengguna
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?= $this->endSection(); ?>

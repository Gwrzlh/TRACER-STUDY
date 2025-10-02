<!-- desain navbar section -->
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="/css/questioner/section/index.css">
<div class="container mt-4">
  
<!-- Navbar -->
    <nav class="navbar navbar-light bg-white border-bottom shadow-sm mb-3">
        <div class="container-fluid px-3">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/questionnaire') ?>">Daftar Kuesioner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/questionnaire/14/pages') ?>">Halaman Kuesioner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/questionnaire/3/pages/30/sections') ?>">Kuesioner Section</a>
                </li>
            </ul>
        </div>
    </nav>

   <!-- Header Section -->
<div class="pengguna-page">
  <div class="page-wrapper">
    <div class="page-container">
      <h2 class="page-title">📑 Sunting Kuesioner Section</h2>

      <!-- Info Card -->
      <div class="top-controls">
        <div class="controls-container">
          <div class="info-box">
            <div class="info-value"><?= count($sections) ?></div>
            <div class="info-label">Total Sections</div>
          </div>
        </div>

        <!-- Button Container -->
        <div class="button-container">
          <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/create") ?>" 
             class="btn-add">
            <i class="fas fa-plus"></i> Tambah Section
          </a>
        </div>
      </div>

      <!-- Flash Messages -->
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          <?= session()->getFlashdata('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <?= session()->getFlashdata('error') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <!-- Sections Table -->
      <?php if (empty($sections)): ?>
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada section</h5>
            <p class="text-muted">Mulai dengan menambahkan section pertama untuk halaman ini.</p>
            <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/create") ?>" 
               class="btn-add">
              <i class="fas fa-plus"></i> Tambah Section Pertama
            </a>
          </div>
        </div>
      <?php else: ?>
        <!-- ✅ Table Container sama dengan daftar halaman -->
        <div class="table-container">
          <div class="table-wrapper">
            <table class="user-table">
              <thead>
                <tr>
                  <th>Section ID</th>
                  <th>Section Name</th>
                  <th>Description</th>
                  <th>Conditional Logic</th>
                  <th>Num of Question</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($sections as $section): ?>
                  <tr>
                    <td>
                      <span class="badge bg-primary"><?= $section['id'] ?></span>
                    </td>
                    <td>
                      <div class="questionnaire-title"><?= esc($section['section_title']) ?></div>
                      <small class="text-muted">
                        <i class="fas fa-eye me-1"></i>Show Title: <?= $section['show_section_title'] ? 'Yes' : 'No' ?>
                        <i class="fas fa-align-left ms-2 me-1"></i>Show Desc: <?= $section['show_section_description'] ? 'Yes' : 'No' ?>
                      </small>
                    </td>
                    <td>
                      <div class="questionnaire-description" title="<?= esc($section['section_description']) ?>">
                        <?= esc($section['section_description']) ?>
                      </div>
                    </td>
                    <td>
                       
                      <?php if ($section['conditional_logic'] != null ): ?>
                        <span class="status-badge status-active">Active</span>
                      <?php else: ?>
                        <span class="status-badge status-inactive">Inactive</span>
                      <?php endif; ?>
                    
                    </td>
                    <td>
                      <span class="status-badge status-active"><?= $section['question_count'] ?? 0 ?></span>
                    </td>
                    <td class="action-cell">
                      <div class="action-buttons">
                        <!-- Move Up -->
                        <button class="btn-action btn-edit move-up-btn" title="Move Up" data-section-id="<?= $section['id'] ?>">
                          <i class="fas fa-arrow-up"></i>
                        </button>
                        <!-- Move Down -->
                        <button class="btn-action btn-edit move-down-btn" title="Move Down" data-section-id="<?= $section['id'] ?>">
                          <i class="fas fa-arrow-down"></i>
                        </button>
                        <!-- Manage Questions -->
                        <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/questions") ?>" 
                           class="btn-action btn-edit" title="Manage Questions">
                          <i class="fas fa-cogs"></i>
                        </a>
                        <!-- Edit -->
                        <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/edit") ?>" 
                           class="btn-action btn-edit" title="Edit">
                          <i class="fas fa-edit"></i>
                        </a>
                        <!-- Delete -->
                        <form action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/delete") ?>" 
                              method="post" style="display:inline-block;" 
                              onsubmit="return confirm('Yakin ingin menghapus section ini dan semua pertanyaannya?');">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn-action btn-delete" title="Delete">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('.move-up-btn').on('click', function() {
        const sectionId = $(this).data('section-id');
        console.log('Move Up: section_id=' + sectionId); // Debug
        $.ajax({
            url: '<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>/' + sectionId + '/moveUp',
            type: 'POST',
            data: {
                section_id: sectionId,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                console.log('Move Up Response:', response);
                if (response.success) {
                    window.location.reload();
                } else {
                    alert('Gagal memindahkan section: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Move Up Error:', status, error, xhr.responseText);
                alert('Terjadi kesalahan saat memindahkan section: ' + xhr.responseText);
            }
        });
    });

    $('.move-down-btn').on('click', function() {
        const sectionId = $(this).data('section-id');
        console.log('Move Down: section_id=' + sectionId); // Debug
        $.ajax({
            url: '<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>/' + sectionId + '/moveDown',
            type: 'POST',
            data: {
                section_id: sectionId,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                console.log('Move Down Response:', response);
                if (response.success) {
                    window.location.reload();
                } else {
                    alert('Gagal memindahkan section: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Move Down Error:', status, error, xhr.responseText);
                alert('Terjadi kesalahan saat memindahkan section: ' + xhr.responseText);
            }
        });
    });
</script>

<?= $this->endSection() ?>
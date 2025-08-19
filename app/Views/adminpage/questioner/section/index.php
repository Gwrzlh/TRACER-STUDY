<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('admin/questionnaire') ?>">Daftar Kuesioner</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages") ?>"><?= esc($questionnaire['title']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($page['page_title']) ?></li>
        </ol>
    </nav>

    <!-- Header Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">
                        <i class="fas fa-layer-group me-2"></i>
                        Sunting Kuesioner Section
                    </h4>
                    <p class="mb-0">
                        <small>Halaman: <?= esc($page['page_title']) ?></small><br>
                        <small><?= esc($page['page_description']) ?></small>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-white bg-opacity-25 rounded p-3">
                        <div class="h4 mb-0 text-white"><?= count($sections) ?></div>
                        <small class="text-white opacity-75">Total Sections</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Button -->
    <div class="mb-3">
        <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/create") ?>" 
           class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Section
        </a>
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
                   class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Section Pertama
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="80">Section ID</th>
                                <th>Section Name</th>
                                <th>Description</th>
                                <th width="120">Conditional Logic</th>
                                <th width="120">Num of Question</th>
                                <th width="200">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sections as $section): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary"><?= $section['id'] ?></span>
                                    </td>
                                    <td>
                                        <strong><?= esc($section['section_title']) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>Show Title: <?= $section['show_section_title'] ? 'Yes' : 'No' ?>
                                            <i class="fas fa-align-left ms-2 me-1"></i>Show Desc: <?= $section['show_section_description'] ? 'Yes' : 'No' ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="section-description" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" 
                                             title="<?= esc($section['section_description']) ?>">
                                            <?= esc($section['section_description']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">none</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info fs-6"><?= $section['question_count'] ?? 0 ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- Move Up/Down buttons -->
                                            <button class="btn btn-sm btn-secondary" title="Move Up">
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Move Down">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                            
                                            <!-- Manage Questions -->
                                            <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/questions") ?>" 
                                               class="btn btn-sm btn-info" title="Manage Questions">
                                                <i class="fas fa-cogs"></i>  manage questions
                                            </a>
                                            
                                            <!-- Edit -->
                                            <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/edit") ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i> edit section
                                            </a>
                                            
                                            <!-- Delete -->
                                            <form action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/delete") ?>" 
                                                  method="post" style="display:inline-block;" 
                                                  onsubmit="return confirm('Yakin ingin menghapus section ini dan semua pertanyaannya?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i> delete section
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
        </div>
    <?php endif; ?>
</div>

<style>
.section-description {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.card-header.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6c5ce7 100%);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}
</style>

<?= $this->endSection() ?>
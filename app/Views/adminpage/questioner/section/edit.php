<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('admin/questionnaire') ?>">Daftar Kuesioner</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages") ?>"><?= esc($questionnaire['title']) ?></a></li>
            <li class="breadcrumb-item"><a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>"><?= esc($page['page_title']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: <?= esc($section['section_title']) ?></li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">
                <i class="fas fa-edit me-2"></i>
                Edit Section: <?= esc($section['section_title']) ?>
            </h4>
            <p class="mb-0"><small>Halaman: <?= esc($page['page_title']) ?></small></p>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form method="post" action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/update") ?>">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Section Title -->
                        <div class="mb-3">
                            <label for="section_title" class="form-label">
                                <i class="fas fa-heading me-2"></i>Judul Section
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="section_title" 
                                   name="section_title" 
                                   value="<?= old('section_title', $section['section_title']) ?>"
                                   required>
                        </div>

                        <!-- Section Description -->
                        <div class="mb-3">
                            <label for="section_description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Deskripsi Section
                            </label>
                            <textarea class="form-control" 
                                      id="section_description" 
                                      name="section_description" 
                                      rows="4"><?= old('section_description', $section['section_description']) ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Display Options -->
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-cog me-2"></i>Pengaturan Tampilan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="show_section_title" name="show_section_title" value="1" 
                                               <?= old('show_section_title', $section['show_section_title']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="show_section_title">
                                            <i class="fas fa-eye me-1"></i>Tampilkan Judul Section
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="show_section_description" name="show_section_description" value="1" 
                                               <?= old('show_section_description', $section['show_section_description']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="show_section_description">
                                            <i class="fas fa-align-left me-1"></i>Tampilkan Deskripsi
                                        </label>
                                    </div>
                                </div>

                                <!-- Order Number -->
                                <div class="mb-3">
                                    <label for="order_no" class="form-label">
                                        <i class="fas fa-sort-numeric-up me-2"></i>Urutan
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="order_no" 
                                           name="order_no" 
                                           value="<?= old('order_no', $section['order_no']) ?>" 
                                           min="1" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card bg-info bg-opacity-10 border-info">
                            <div class="card-header bg-info bg-opacity-25">
                                <h6 class="mb-0">
                                    <i class="fas fa-bolt me-2"></i>Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions") ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-cogs me-2"></i>Kelola Pertanyaan
                                    </a>
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="fas fa-code-branch me-2"></i>Atur Conditional Logic
                                    </a>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="duplicateSection()">
                                        <i class="fas fa-copy me-2"></i>Duplikasi Section
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>" 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <div>
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-check me-2"></i>Update Section
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteSection()">
                            <i class="fas fa-trash me-2"></i>Hapus Section
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card-header.bg-warning {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%) !important;
}

.btn-success {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #7ce8a0 0%, #85c9f2 100%);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}
</style>

<script>
function deleteSection() {
    if (confirm('Yakin ingin menghapus section ini? Semua pertanyaan di dalam section ini juga akan terhapus!')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/delete") ?>';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function duplicateSection() {
    if (confirm('Duplikasi section ini? Section baru akan dibuat dengan nama "Copy of <?= esc($section['section_title']) ?>"')) {
        // Implementasi duplikasi
        alert('Fitur duplikasi akan segera tersedia!');
    }
}
</script>

<?= $this->endSection() ?>
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
            <li class="breadcrumb-item active" aria-current="page">Tambah Section</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>
                Tambah Section Baru
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
            <form method="post" action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/store") ?>">
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
                                   value="<?= old('section_title') ?>"
                                   placeholder="Masukkan judul section..."
                                   required>
                            <div class="form-text">Contoh: "Data Pribadi", "Informasi Pekerjaan", dll.</div>
                        </div>

                        <!-- Section Description -->
                        <div class="mb-3">
                            <label for="section_description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Deskripsi Section
                            </label>
                            <textarea class="form-control" 
                                      id="section_description" 
                                      name="section_description" 
                                      rows="4" 
                                      placeholder="Jelaskan tujuan dan isi dari section ini..."><?= old('section_description') ?></textarea>
                            <div class="form-text">Deskripsi opsional untuk menjelaskan maksud section ini kepada responden.</div>
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
                                               <?= old('show_section_title', 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="show_section_title">
                                            <i class="fas fa-eye me-1"></i>Tampilkan Judul Section
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Judul section akan terlihat oleh responden</small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="show_section_description" name="show_section_description" value="1" 
                                               <?= old('show_section_description', 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="show_section_description">
                                            <i class="fas fa-align-left me-1"></i>Tampilkan Deskripsi
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Deskripsi section akan terlihat oleh responden</small>
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
                                           value="<?= old('order_no', $next_order) ?>" 
                                           min="1" 
                                           required>
                                    <div class="form-text">Urutan section dalam halaman</div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="card bg-info bg-opacity-10 border-info">
                            <div class="card-header bg-info bg-opacity-25">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Informasi
                                </h6>
                            </div>
                            <div class="card-body">
                                <small class="text-muted">
                                    Setelah section dibuat, Anda dapat:
                                    <ul class="mt-2 mb-0">
                                        <li>Menambahkan pertanyaan ke section ini</li>
                                        <li>Mengatur conditional logic</li>
                                        <li>Mengurutkan ulang section</li>
                                    </ul>
                                </small>
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
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-save me-2"></i>Simpan Section
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>

<?= $this->endSection() ?>
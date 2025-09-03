<?php

use App\Models\Prodi; ?>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<!-- Link to external CSS -->
<link href="<?= base_url('assets/css/organisasi/edit.css') ?>" rel="stylesheet">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2" style="height: 60px;">
                    <h2 class="mb-0">Edit Satuan Organisasi</h2>
                </div>
                <div class="card-body">
                    <!-- Display validation errors -->
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Display success/error messages -->
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>

                    <form action="/satuanorganisasi/update/<?= $satuan['id'] ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_jurusan" class="form-label required">Jurusan</label>
                                    <select name="id_jurusan" id="id_jurusan" class="form-control form-select" required>
                                        <option value="">-- Pilih Jurusan --</option>
                                        <?php foreach ($jurusan as $j): ?>
                                            <option value="<?= esc($j['id']) ?>" <?= $satuan['id_jurusan'] == $j['id'] ? 'selected' : '' ?>><?= esc($j['nama_jurusan']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_prodi" class="form-label required">Prodi</label>
                                    <select name="id_prodi" id="id_prodi" class="form-control form-select" required>
                                        <option value="">-- Pilih Prodi --</option>
                                        <?php foreach ((new Prodi())->where('id_jurusan', $satuan['id_jurusan'])->findAll() as $p): ?>
                                            <option value="<?= $p['id'] ?>" <?= $satuan['id_prodi'] == $p['id'] ? 'selected' : '' ?>><?= $p['nama_prodi'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nama_satuan" class="form-label required">Nama Satuan</label>
                            <input type="text" name="nama_satuan" id="nama_satuan" class="form-control" value="<?= esc($satuan['nama_satuan']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_singkatan" class="form-label required">Singkatan</label>
                                    <input type="text" name="nama_singkatan" id="nama_singkatan" class="form-control" value="<?= esc($satuan['nama_singkatan']) ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_slug" class="form-label required">Slug</label>
                                    <input type="text" name="nama_slug" id="nama_slug" class="form-control" value="<?= esc($satuan['nama_slug']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control form-textarea"><?= esc($satuan['deskripsi']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="id_tipe" class="form-label required">Tipe Organisasi</label>
                            <select name="id_tipe" id="id_tipe" class="form-control form-select" required>
                                <option value="">-- Pilih Tipe Organisasi --</option>
                                <?php foreach ($tipe as $t): ?>
                                    <option value="<?= esc($t['id']) ?>" <?= $satuan['id_tipe'] == $t['id'] ? 'selected' : '' ?>><?= esc($t['nama_tipe']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="/satuanorganisasi" class="btn btn-warning">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Auto-generate slug and abbreviation from nama_satuan
    $('#nama_satuan').on('input', function() {
        const nama = $(this).val();
        const slug = nama.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '');
        $('#nama_slug').val(slug);
        let singkatan = '';
        nama.split(' ').forEach(w => {
            if (w.length > 0) singkatan += w[0].toUpperCase();
        });
        $('#nama_singkatan').val(singkatan);
    });

    // Load Prodi based on selected Jurusan
    $('#id_jurusan').change(function() {
        const jurusanId = $(this).val();
        $('#id_prodi').empty().append('<option value="">-- Pilih Prodi --</option>');
        if (jurusanId) {
            $.getJSON("<?= base_url('satuanorganisasi/getProdi') ?>/" + jurusanId, function(data) {
                $.each(data, function(k, v) {
                    $('#id_prodi').append('<option value="' + v.id + '">' + v.nama_prodi + '</option>');
                });
            });
        }
    });

    // Form validation
    $('form').on('submit', function(e) {
        var isValid = true;
        
        // Remove previous validation states
        $('.is-invalid').removeClass('is-invalid');
        
        // Validate required fields
        $('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            showAlert('error', 'Harap lengkapi semua field yang wajib diisi!');
            $('.is-invalid').first().focus();
        }
    });

    // Helper function to show alerts
    function showAlert(type, message) {
        $('.alert').not('.alert-danger, .alert-success').remove();
        
        var alertClass = 'alert-' + (type === 'error' ? 'danger' : type);
        var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                       message +
                       '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                       '</div>';
        
        $('.card-body form').prepend(alertHtml);
        
        setTimeout(function() {
            $('.alert').not('.alert-danger, .alert-success').fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Real-time validation feedback
    $('.form-control, .form-select').on('blur', function() {
        if ($(this).prop('required') && !$(this).val()) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // Remove validation classes on input
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid is-valid');
    });
});
</script>

<?= $this->endSection() ?>
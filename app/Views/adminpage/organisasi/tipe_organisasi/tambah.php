
    <?= $this->extend('layout/sidebar') ?>
    <?= $this->section('content') ?>

   <link rel="stylesheet" href="<?= base_url('css/organisasi/edittipe.css') ?>">


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2" style="height: 60px;">
                        <h4 class="mb-0">Tambah Tipe Organisasi</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('/admin/tipeorganisasi/insert') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="nama_tipe" class="form-label">Nama Tipe:</label>
                                <input type="text" class="form-control" id="nama_tipe" name="nama_tipe" required>
                            </div>

                            <div class="mb-3">
                                <label for="lavel" class="form-label">Level:</label>
                                <input type="number" class="form-control" id="lavel" name="lavel" required>
                                <small class="text-muted">Level organisasi dalam hierarki</small>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi:</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="group" class="form-label">Group (Role):</label>
                                <select class="form-select" id="group" name="group">
                                    <option value="" disabled selected>-- Pilih Role --</option>
                                    <?php foreach ($roles as $d): ?>
                                        <option value="<?= esc($d['id']) ?>"><?= esc($d['nama']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mt-4">
                               <button type="submit" class="btn-primary-custom">Simpan</button>
                            <a href="<?= base_url('/admin/tipeorganisasi') ?>" class="btn-warning-custom">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $this->endSection() ?>
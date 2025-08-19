<?php

use App\Models\Prodi; ?>
<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="page-container">
    <h2>Edit Satuan Organisasi</h2>

    <form action="/satuanorganisasi/update/<?= $satuan['id'] ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="id_jurusan" class="form-label required">Jurusan</label>
            <select name="id_jurusan" id="id_jurusan" class="form-control form-select" required>
                <option value="">-- Pilih Jurusan --</option>
                <?php foreach ($jurusan as $j): ?>
                    <option value="<?= esc($j['id']) ?>" <?= $satuan['id_jurusan'] == $j['id'] ? 'selected' : '' ?>><?= esc($j['nama_jurusan']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_prodi" class="form-label required">Prodi</label>
            <select name="id_prodi" id="id_prodi" class="form-control form-select" required>
                <option value="">-- Pilih Prodi --</option>
                <?php foreach ((new Prodi())->where('id_jurusan', $satuan['id_jurusan'])->findAll() as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $satuan['id_prodi'] == $p['id'] ? 'selected' : '' ?>><?= $p['nama_prodi'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nama_satuan" class="form-label required">Nama Satuan</label>
            <input type="text" name="nama_satuan" id="nama_satuan" class="form-control" value="<?= esc($satuan['nama_satuan']) ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_singkatan" class="form-label required">Singkatan</label>
            <input type="text" name="nama_singkatan" id="nama_singkatan" class="form-control" value="<?= esc($satuan['nama_singkatan']) ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_slug" class="form-label required">Slug</label>
            <input type="text" name="nama_slug" id="nama_slug" class="form-control" value="<?= esc($satuan['nama_slug']) ?>" required>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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
    </script>


    <?= $this->endSection() ?>
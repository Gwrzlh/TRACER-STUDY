<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/organisasi/tambahprodi.css') ?>">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header">
          <img src="/images/logo.png" alt="Tracer Study" class="logo mb-2">
          <h4 class="mb-0">Tambah Prodi</h4>
        </div>
        <div class="card-body">

          <!-- Error Messages -->
          <?php if (session('errors')): ?>
            <div class="alert alert-danger">
              <ul>
                <?php foreach (session('errors') as $error): ?>
                  <li><?= esc($error) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
          <?php endif; ?>

          <form action="<?= base_url('satuanorganisasi/prodi/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label for="nama_prodi" class="form-label">Nama Prodi:</label>
              <input type="text" class="form-control" id="nama_prodi" name="nama_prodi"
                     value="<?= old('nama_prodi') ?>" required>
            </div>

            <div class="mb-3">
              <label for="id_jurusan" class="form-label">Jurusan:</label>
              <select class="form-select" id="id_jurusan" name="id_jurusan" required>
                <option value="" disabled selected>-- Pilih Jurusan --</option>
                <?php foreach ($jurusan as $j): ?>
                  <option value="<?= $j['id'] ?>" <?= old('id_jurusan') == $j['id'] ? 'selected' : '' ?>>
                    <?= esc($j['nama_jurusan']) ?>
                  </option>
                <?php endforeach ?>
              </select>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn-primary-custom">Simpan</button>
              <a href="<?= base_url('satuanorganisasi/prodi') ?>" class="btn-warning-custom">Batal</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

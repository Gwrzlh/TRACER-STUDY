<?php $layout = 'layout/sidebar'; ?>
<?= $this->extend($layout) ?>
<?= $this->section('content') ?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="password-container">
  <h2>Ubah Password</h2>

  <form method="post" action="<?= base_url('admin/profil/update-password') ?>" class="password-form">
    <?= csrf_field() ?>
    <div class="form-group">
      <label>Password Lama</label>
      <input type="password" name="old_password" required>
    </div>
    <div class="form-group">
      <label>Password Baru</label>
      <input type="password" name="new_password" required>
    </div>
    <div class="form-group">
      <label>Konfirmasi Password Baru</label>
      <input type="password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn-save">Simpan</button>
  </form>
</div>

<!-- SweetAlert Notifikasi -->
<?php if (session()->getFlashdata('success')): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '<?= session()->getFlashdata('success') ?>',
  confirmButtonColor: '#3085d6',
  timer: 2000,
  timerProgressBar: true
}).then(() => {
  window.location.href = "<?= base_url('admin/profil') ?>"; // redirect ke halaman profil
});
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Oops...',
  text: '<?= session()->getFlashdata('error') ?>',
  confirmButtonColor: '#d33'
});
</script>
<?php endif; ?>

<!-- Styling -->
<style>
.password-container {
  max-width: 500px;
  margin: 30px auto;
  padding: 20px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.password-container h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.password-form .form-group {
  margin-bottom: 15px;
}

.password-form label {
  display: block;
  margin-bottom: 6px;
  font-weight: bold;
  color: #444;
}

.password-form input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.btn-save {
  display: block;
  width: 100%;
  padding: 10px;
  background: #007bff;
  border: none;
  border-radius: 6px;
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-save:hover {
  background: #0056b3;
}
</style>

<?= $this->endSection() ?>
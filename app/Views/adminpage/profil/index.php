<?php $layout = 'layout/sidebar'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/profil.css') ?>">

<!-- Profil Admin -->
<div class="profile-container">
  <div class="profile-header">
    <img src="<?= base_url('images/logo.png') ?>" alt="Tracer Study" class="logo">
    <h2>Profil Admin</h2>
  </div>

  <div class="profile-body">
    <!-- FOTO ADMIN -->
    <div class="profile-sidebar">
      <img id="fotoPreview"
        src="<?= !empty($admin['foto'])
                ? base_url('uploads/foto_admin/' . $admin['foto']) . '?t=' . time()
                : base_url('uploads/default.png') ?>"
        alt="Foto Profil">
      <p>Klik foto untuk mengganti</p>
    </div>

    <!-- INFO AKUN -->
    <div class="profile-details">
      <p><strong>Nama Lengkap :</strong> <span><?= esc($admin['nama_lengkap'] ?? $admin['full_name'] ?? '-') ?></span></p>
      <p><strong>Username :</strong> <span><?= esc($admin['username'] ?? '-') ?></span></p>
      <p><strong>Email :</strong> <span><?= esc($admin['email'] ?? '-') ?></span></p>

      <?php
        $hp = null;
        if (!empty($admin['no_hp'])) {
          $hp = $admin['no_hp'];
        } elseif (!empty($admin['phone'])) {
          $hp = $admin['phone'];
        } elseif (!empty($admin['hp'])) {
          $hp = $admin['hp'];
        }
        if (empty($hp)) {
          $id_for_dummy = isset($admin['id']) ? intval($admin['id']) : 0;
          $hp = '0812-' . str_pad($id_for_dummy, 3, '0', STR_PAD_LEFT) . '-000';
          $hp_note = true;
        } else {
          $hp_note = false;
        }
      ?>
      <p>
        <strong>Nomor Telepon / WhatsApp :</strong> <span><?= esc($hp) ?></span>
        <?php if ($hp_note): ?>
          <span class="dummy-note">(nomor dummy — belum ada data di DB)</span>
        <?php endif ?>
      </p>

      <p><strong>Status :</strong> <?= esc($admin['status'] ?? '-') ?></p>
      <p><strong>Role ID :</strong> <?= esc($admin['id_role'] ?? '-') ?></p>
      <p><strong>Dibuat :</strong> <?= esc($admin['created_at'] ?? '-') ?></p>
      <p><strong>Diupdate :</strong> <?= esc($admin['updated_at'] ?? '-') ?></p>

      <div class="profile-actions">
        <a href="<?= base_url('admin/profil/edit/' . session()->get('id_account')) ?>" class="btn-edit">Edit Profil</a>
        <a href="<?= base_url('admin/profil/ubah-password') ?>" class="btn-pass">Ubah Password</a>
      </div>
    </div>
  </div>
</div>

<!-- MODAL FOTO (Cropper.js) -->
<div id="modal" class="modal">
  <div class="modal-content">
    <h3>Ubah Foto Profil</h3>
    <input type="file" id="fileInput" accept="image/*">

    <div class="crop-container">
      <img id="cropImage" class="hidden" />
    </div>

    <div class="modal-actions">
      <button type="button" onclick="submitFoto()" class="btn-save">Simpan</button>
      <button type="button" onclick="window.closeModal()" class="btn-cancel">Batal</button>
    </div>
  </div>
</div>

<div id="toast" class="toast"></div>

<!-- Cropper.js -->
<link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal');
    const fotoPreview = document.getElementById('fotoPreview');
    const fileInput = document.getElementById('fileInput');
    const cropImage = document.getElementById('cropImage');
    const toast = document.getElementById('toast');
    let cropper = null;

    function openModal() {
      modal.classList.add('show');
      fileInput.value = '';
      cropImage.classList.add('hidden');
      if (cropper) { cropper.destroy(); cropper = null; }
    }

    window.closeModal = function() {
      modal.classList.remove('show');
    };

    modal.addEventListener('click', (e) => {
      if (e.target === modal) window.closeModal();
    });

    fileInput.addEventListener('change', () => {  
      const file = fileInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          cropImage.src = e.target.result;
          cropImage.classList.remove('hidden');
          if (cropper) cropper.destroy();
          cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
          });
          showToast('🔍 Silakan crop foto sesuai keinginan', 'blue');
        };
        reader.readAsDataURL(file);
      }
    });

    function showToast(msg, color = 'dark') {
      toast.textContent = msg;
      toast.className = `toast ${color} show`;
      setTimeout(() => { toast.classList.remove('show'); }, 3000);
    }

    window.submitFoto = function() {
      if (!cropper) {
        showToast('❌ Pilih foto dulu', 'red');
        return;
      }
      cropper.getCroppedCanvas({
        width: 400,
        height: 400
      }).toBlob(blob => {
        const formData = new FormData();
        formData.append('foto', blob, 'cropped.png');
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url("admin/profil/update-foto/" . session()->get("id_account")) ?>', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              showToast('✅ Foto berhasil disimpan', 'green');
              window.closeModal();
              fotoPreview.src = data.fotoUrl + '?t=' + new Date().getTime();
              const sidebarFoto = document.getElementById('sidebarFoto');
              if (sidebarFoto) sidebarFoto.src = data.fotoUrl + '?t=' + new Date().getTime();
            } else {
              showToast('❌ Gagal menyimpan: ' + (data.message || 'Error'), 'red');
            }
          })
          .catch(err => showToast('❌ Terjadi error: ' + err, 'red'));
      });
    };

    fotoPreview.addEventListener('click', openModal);
  });
</script>

<?= $this->endSection() ?>

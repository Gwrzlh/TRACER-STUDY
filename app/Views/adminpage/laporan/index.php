<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Laporan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen bg-gray-100 text-gray-800">

    <!-- Sidebar -->
    <?= view('layout/sidebar') ?>

<!-- Konten -->
<div class="flex-1 py-10 px-6 overflow-y-auto bg-gradient-to-br from-blue-50 via-white to-blue-100">
  <div class="max-w-7xl mx-auto">
    <!-- Logo -->
    <img src="/images/logo.png" alt="Logo POLBAN" class="logo-img mb-8 w-28" />

    <!-- Hero Section -->
    <div class="mb-10 flex items-center justify-between">
      <div>
        <h1 class="text-4xl font-extrabold text-gray-900">Kelola Laporan</h1>
        <p class="text-gray-600 mt-2">Manajemen data laporan tahunan dengan lebih mudah.</p>
      </div>
      <!-- Tombol Tambah Laporan -->
      <button 
        type="button" 
        id="add-laporan"
        class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl shadow hover:scale-105 transition"
      >
        + Tambah Laporan
      </button>
    </div>

    <!-- Card Container -->
    <div class="w-full bg-white shadow-xl rounded-2xl p-10 border border-gray-200">
      <!-- Notifikasi sukses -->
      <?php if (session()->getFlashdata('success')): ?>
          <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg font-medium shadow">
              ✅ <?= session()->getFlashdata('success') ?>
          </div>
      <?php endif; ?>

      <!-- Notifikasi error -->
      <?php if (session()->getFlashdata('error')): ?>
          <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg font-medium shadow">
              ⚠️ <?= session()->getFlashdata('error') ?>
          </div>
      <?php endif; ?>

      <!-- Form -->
      <form action="<?= base_url('admin/laporan/save') ?>" method="post" enctype="multipart/form-data" class="space-y-8" id="laporan-form">
        <?= csrf_field() ?>

        <div id="laporan-container" class="space-y-8">
          <?php for ($i = 1; $i <= 7; $i++): ?>
            <?php 
              $lap = $laporan[$i-1] ?? [];
              if (empty($lap['judul']) && empty($lap['isi']) && empty($lap['file_pdf']) && empty($lap['file_gambar'])) {
                continue;
              }
            ?>
            <div class="relative border-l-4 border-blue-600 rounded-2xl p-8 bg-gradient-to-br from-white to-blue-50 hover:shadow-lg transition laporan-item">
              <!-- Hidden id -->
              <input type="hidden" name="id[]" value="<?= $lap['id'] ?? '' ?>">
              <input type="hidden" name="urutan[]" value="<?= $i ?>">

              <!-- Tombol Delete -->
              <?php if(!empty($lap['id'])): ?>
              <button type="button" class="absolute top-4 right-4 px-4 py-1.5 bg-red-600 text-white text-sm font-semibold rounded-full hover:bg-red-700 transition delete-btn" 
                      data-id="<?= $lap['id'] ?>">
                Hapus
              </button>
              <?php endif; ?>

              <h3 class="text-xl font-bold text-blue-700 mb-6">📄 Laporan <?= $i ?></h3>

              <!-- Judul -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                <input 
                  type="text" 
                  name="judul[]" 
                  value="<?= $lap['judul'] ?? '' ?>"
                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2"
                >
              </div>

              <!-- Isi -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Isi</label>
                <textarea 
                  name="isi[]" 
                  class="isi-editor w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2"
                ><?= $lap['isi'] ?? '' ?></textarea>
              </div>

              <!-- File PDF -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">File PDF</label>
                <input 
                  type="file" 
                  name="file_pdf[]" 
                  class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-5 
                         file:rounded-full file:border-0 file:text-sm file:font-semibold 
                         file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100"
                >
                <?php if (!empty($lap['file_pdf'])): ?>
                  <p class="text-sm text-gray-600 mt-1">File lama: 
                    <span class="font-medium text-blue-600"><?= $lap['file_pdf'] ?></span>
                  </p>
                <?php endif; ?>
              </div>

              <!-- File Gambar -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                <input 
                  type="file" 
                  name="file_gambar[]" 
                  accept="image/*"
                  class="preview-gambar w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-5 
                         file:rounded-full file:border-0 file:text-sm file:font-semibold 
                         file:bg-green-50 file:text-green-600 hover:file:bg-green-100"
                  data-preview="preview-<?= $i ?>"
                >

                <!-- Preview Gambar -->
                <img id="preview-<?= $i ?>" 
                     src="<?= !empty($lap['file_gambar']) ? base_url('uploads/gambar/'.$lap['file_gambar']) : '' ?>" 
                     class="mt-3 w-36 h-36 object-cover rounded-xl border shadow <?= empty($lap['file_gambar']) ? 'hidden' : '' ?>">
              </div>
            </div>
          <?php endfor; ?>
        </div>

        <!-- Tombol Simpan -->
        <div class="text-right">
          <button 
            type="submit" 
            class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold rounded-xl shadow hover:scale-105 transition"
          >
            💾 Simpan Perubahan
          </button>
        </div>
      </form>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        <?= $pager->links('laporan', 'custom_pagination') ?>
    </div>
  </div>
</div>

<!-- Template Laporan Baru -->
<template id="laporan-template">
  <div class="relative border-l-4 border-blue-600 rounded-2xl p-8 bg-gradient-to-br from-white to-blue-50 hover:shadow-lg transition laporan-item">
    <input type="hidden" name="id[]" value="">
    <input type="hidden" name="urutan[]" value="">

    <button type="button" class="absolute top-4 right-4 px-4 py-1.5 bg-red-600 text-white text-sm font-semibold rounded-full hover:bg-red-700 transition remove-laporan">
      Hapus
    </button>

    <h3 class="text-xl font-bold text-blue-700 mb-6">📄 Laporan Baru</h3>

    <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
    <input type="text" name="judul[]" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2 mb-4">

    <label class="block text-sm font-medium text-gray-700 mb-2">Isi</label>
    <textarea name="isi[]" class="isi-editor w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2 mb-4"></textarea>

    <label class="block text-sm font-medium text-gray-700 mb-2">File PDF</label>
    <input type="file" name="file_pdf[]" class="w-full mb-2">

    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
    <input type="file" name="file_gambar[]" accept="image/*" class="preview-gambar w-full mb-2" data-preview="preview-new">

    <img id="preview-new" class="mt-3 w-36 h-36 object-cover rounded-xl border shadow hidden">
  </div>
</template>

<!-- Script Tambah & Hapus Slot -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const addBtn = document.getElementById('add-laporan');
  const container = document.getElementById('laporan-container');
  const template = document.getElementById('laporan-template').content;

  addBtn.addEventListener('click', () => {
    const clone = document.importNode(template, true);
    container.appendChild(clone);

    tinymce.remove('textarea.isi-editor');
    tinymce.init({
      selector: 'textarea.isi-editor',
      height: 250,
      menubar: false,
      plugins: 'lists link image table code fullscreen',
      toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | code fullscreen',
      license_key: 'gpl'
    });
  });

  container.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-laporan')) {
      e.target.closest('.laporan-item').remove();
    }
    if (e.target.classList.contains('delete-btn')) {
      const laporanItem = e.target.closest('.laporan-item');
      const id = e.target.getAttribute('data-id');

      Swal.fire({
        title: 'Yakin ingin menghapus laporan ini?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch("<?= base_url('admin/laporan/delete') ?>/" + id, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': "<?= csrf_hash() ?>",
              'Content-Type': 'application/json'
            }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              laporanItem.remove();
              Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: 'Laporan berhasil dihapus.',
                timer: 1500,
                showConfirmButton: false
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Gagal menghapus laporan.'
              });
            }
          })
          .catch(err => {
            console.error(err);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Terjadi kesalahan koneksi.'
            });
          });
        }
      });
    }
  });
</script>

<!-- TinyMCE Self-hosted -->
<script src="<?= base_url('tinymce/tinymce.min.js'); ?>"></script>
<script>
tinymce.init({
    selector: 'textarea.isi-editor',
    height: 400,
    menubar: false,
    plugins: 'lists link image table code fullscreen',
    toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | fullscreen code',
    content_style: 'body { font-family:"Figtree", sans-serif; font-size:16px; line-height:1.6 }',
    license_key: 'gpl'
});
</script>
</body>
</html>

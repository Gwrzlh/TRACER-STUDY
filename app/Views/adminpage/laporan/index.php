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
  <div class="max-w-6xl mx-auto">
    <!-- Logo -->
    <img src="/images/logo.png" alt="Logo POLBAN" class="logo-img mb-6" />

    <!-- Hero Section -->
    <div class="text-left mb-8 flex items-center justify-between">
      <h1 class="text-4xl font-extrabold text-gray-900">Kelola Laporan</h1>
      <!-- Tombol Tambah Laporan -->
      <button 
        type="button" 
        id="add-laporan"
        class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition"
      >
        + Tambah Laporan
      </button>
    </div>

    <!-- Card Container -->
    <div class="w-full bg-white shadow-2xl rounded-2xl p-8 border border-gray-200">
      <!-- Notifikasi sukses -->
      <?php if (session()->getFlashdata('success')): ?>
          <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg">
              <?= session()->getFlashdata('success') ?>
          </div>
      <?php endif; ?>

      <!-- Notifikasi error -->
      <?php if (session()->getFlashdata('error')): ?>
          <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
              <?= session()->getFlashdata('error') ?>
          </div>
      <?php endif; ?>

      <!-- Form -->
      <form action="<?= base_url('admin/laporan/save') ?>" method="post" enctype="multipart/form-data" class="space-y-8" id="laporan-form">
        <?= csrf_field() ?>

        <div id="laporan-container">
          <?php for ($i = 1; $i <= 7; $i++): ?>
            <?php 
              $lap = $laporan[$i-1] ?? [];
              if (empty($lap['judul']) && empty($lap['isi']) && empty($lap['file_pdf']) && empty($lap['file_gambar'])) {
                continue;
              }
            ?>
            <div class="relative border rounded-xl p-6 bg-gray-50 hover:shadow-md transition laporan-item mb-6">
              <!-- Tambahin hidden id biar jelas -->
              <?php if(!empty($lap['id'])): ?>
                <input type="hidden" name="id[]" value="<?= $lap['id'] ?>">
              <?php else: ?>
                <input type="hidden" name="id[]" value="">
              <?php endif; ?>

              <input type="hidden" name="urutan[]" value="<?= $i ?>">

              <!-- Tombol Delete -->
              <?php if(!empty($lap['id'])): ?>
              <button type="button" class="absolute top-4 right-4 px-3 py-1 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition delete-btn" 
                      data-id="<?= $lap['id'] ?>">
                Delete
              </button>
              <?php endif; ?>

              <h3 class="text-lg font-semibold text-blue-700 mb-4">Laporan <?= $i ?></h3>

              <!-- Judul -->
              <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
              <input 
                type="text" 
                name="judul[]" 
                value="<?= $lap['judul'] ?? '' ?>"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 mb-4"
              >

              <!-- Isi -->
              <label class="block text-sm font-medium text-gray-700 mb-1">Isi</label>
              <textarea 
                name="isi[]" 
                class="isi-editor w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 mb-4"
              ><?= $lap['isi'] ?? '' ?></textarea>

              <!-- File PDF -->
              <label class="block text-sm font-medium text-gray-700 mb-1">File PDF</label>
              <input 
                type="file" 
                name="file_pdf[]" 
                class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 
                       file:rounded-full file:border-0 file:text-sm file:font-semibold 
                       file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 mb-2"
              >
              <?php if (!empty($lap['file_pdf'])): ?>
                <p class="text-sm text-gray-600">File lama: 
                  <span class="font-medium text-blue-600"><?= $lap['file_pdf'] ?></span>
                </p>
              <?php endif; ?>

              <!-- File Gambar -->
              <label class="block text-sm font-medium text-gray-700 mt-4 mb-1">Gambar</label>
              <input 
                type="file" 
                name="file_gambar[]" 
                accept="image/*"
                class="preview-gambar w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 
                       file:rounded-full file:border-0 file:text-sm file:font-semibold 
                       file:bg-green-50 file:text-green-600 hover:file:bg-green-100 mb-2"
                data-preview="preview-<?= $i ?>"
              >

              <!-- Preview Gambar -->
              <img id="preview-<?= $i ?>" 
                   src="<?= !empty($lap['file_gambar']) ? base_url('uploads/gambar/'.$lap['file_gambar']) : '' ?>" 
                   class="mt-3 w-32 h-32 object-cover rounded-lg border <?= empty($lap['file_gambar']) ? 'hidden' : '' ?>">
            </div>
          <?php endfor; ?>
        </div>

        <!-- Tombol Simpan -->
        <div class="text-right">
          <button 
            type="submit" 
            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition"
          >
            Simpan
          </button>
        </div>
      </form>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        <?= $pager->links('laporan', 'custom_pagination') ?>
    </div>
  </div>
</div>



<!-- Template Laporan Baru -->
<template id="laporan-template">
  <div class="relative border rounded-xl p-6 bg-gray-50 hover:shadow-md transition laporan-item">
    <input type="hidden" name="id[]" value="">
    <input type="hidden" name="urutan[]" value="">

    <button type="button" class="absolute top-4 right-4 px-3 py-1 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition remove-laporan">
      Hapus
    </button>

    <h3 class="text-lg font-semibold text-blue-700 mb-4">Laporan Baru</h3>

    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
    <input type="text" name="judul[]" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 mb-4">

    <label class="block text-sm font-medium text-gray-700 mb-1">Isi</label>
    <textarea name="isi[]" class="isi-editor w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 mb-4"></textarea>

    <label class="block text-sm font-medium text-gray-700 mb-1">File PDF</label>
    <input type="file" name="file_pdf[]" class="w-full mb-2">

    <label class="block text-sm font-medium text-gray-700 mt-4 mb-1">Gambar</label>
    <input type="file" name="file_gambar[]" accept="image/*" class="preview-gambar w-full mb-2" data-preview="preview-new">

    <img id="preview-new" class="mt-3 w-32 h-32 object-cover rounded-lg border hidden">
  </div>
</template>


<!-- Script Tambah & Hapus Slot -->
<script>
  const addBtn = document.getElementById('add-laporan');
  const container = document.getElementById('laporan-container');
  const template = document.getElementById('laporan-template').content;

  addBtn.addEventListener('click', () => {
    const clone = document.importNode(template, true);
    container.appendChild(clone);

    // Hapus instance lama agar tidak dobel
    tinymce.remove('textarea.isi-editor');

    // Re-init TinyMCE di semua textarea isi-editor
    tinymce.init({
      selector: 'textarea.isi-editor',
      height: 250,
      menubar: false,
      plugins: 'lists link image table code fullscreen',
      toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | code fullscreen',
      license_key: 'gpl'
    });
  });

  // Event delegation untuk tombol hapus laporan baru
  container.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-laporan')) {
      e.target.closest('.laporan-item').remove();
    }
  });

  // Event delegation untuk tombol delete
  container.addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-btn')) {
      const laporanItem = e.target.closest('.laporan-item');
      const id = e.target.getAttribute('data-id');

      if (confirm('Yakin ingin menghapus laporan ini?')) {
        fetch("<?= base_url('admin/laporan/delete') ?>/" + id, {
          method: 'POST', // fix: CI4 route pakai POST
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
          } else {
            alert(data.message || 'Gagal menghapus laporan.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('Terjadi kesalahan koneksi.');
        });
      }
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

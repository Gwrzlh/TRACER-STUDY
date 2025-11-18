<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="flex-1 overflow-y-auto bg-gray-50">
  <div class="max-w-7xl mx-auto px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Kelola Laporan</h1>
        <p class="text-gray-600 mt-1 text-sm">Manajemen data laporan tahunan dengan lebih mudah.</p>
      </div>
      <!-- Tombol Tambah Laporan -->
      <button 
        type="button" 
        id="add-laporan"
        class="px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-all shadow-sm">
        + Tambah Laporan
      </button>
    </div>


    <!-- Notifikasi error -->
    <?php if (session()->getFlashdata('error')): ?>
      <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg font-medium">
        ⚠️ <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      
      <!-- Table Header -->
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h2 class="text-sm font-semibold text-gray-700">DAFTAR LAPORAN</h2>
      </div>

      <!-- Form Content -->
      <form action="<?= base_url('admin/laporan/save') ?>" method="post" enctype="multipart/form-data" id="laporan-form">
        <?= csrf_field() ?>

        <div id="laporan-container" class="divide-y divide-gray-100">

          <?php for ($i = 1; $i <= 7; $i++): ?>
            <?php 
              $lap = $laporan[$i-1] ?? [];
              if (empty($lap['judul']) && empty($lap['isi']) && empty($lap['file_pdf']) && empty($lap['file_gambar'])) {
                continue;
              }
            ?>

            <div class="laporan-item px-6 py-6 hover:bg-gray-50 transition-colors relative">
              <!-- Hidden fields -->
              <input type="hidden" name="id[]" value="<?= $lap['id'] ?? '' ?>">
              <input type="hidden" name="urutan[]" value="<?= $i ?>">

              <!-- Header dengan nomor dan tombol hapus -->
              <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">📄 Laporan <?= $i ?></h3>
                <?php if(!empty($lap['id'])): ?>
                <button type="button" 
                        class="delete-btn px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-all shadow-sm" 
                        data-id="<?= $lap['id'] ?>">
                  Hapus
                </button>
                <?php endif; ?>
              </div>

              <!-- Grid Layout untuk Form Fields -->
              <div class="space-y-5">
                
                <!-- Judul -->
                <div class="grid grid-cols-12 gap-4">
                  <div class="col-span-3 flex items-start pt-2">
                    <label class="font-medium text-gray-700 text-sm">Judul</label>
                  </div>
                  <div class="col-span-9">
                    <input 
                      type="text" 
                      name="judul[]" 
                      value="<?= $lap['judul'] ?? '' ?>"
                      class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                  </div>
                </div>

                <!-- Isi -->
                <div class="grid grid-cols-12 gap-4">
                  <div class="col-span-3 flex items-start pt-2">
                    <label class="font-medium text-gray-700 text-sm">Isi</label>
                  </div>
                  <div class="col-span-9">
                    <textarea 
                      name="isi[]" 
                      class="isi-editor w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"><?= $lap['isi'] ?? '' ?></textarea>
                  </div>
                </div>

                <!-- File PDF -->
                <div class="grid grid-cols-12 gap-4">
                  <div class="col-span-3 flex items-start pt-2">
                    <label class="font-medium text-gray-700 text-sm">File PDF</label>
                  </div>
                  <div class="col-span-9">
                    <input 
                      type="file" 
                      name="file_pdf[]" 
                      class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer
                             file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                             file:bg-blue-600 file:text-white file:text-sm hover:file:bg-blue-700 transition-all">
                    <?php if (!empty($lap['file_pdf'])): ?>
                      <p class="text-xs text-gray-600 mt-2">File saat ini: 
                        <span class="font-medium text-blue-600"><?= $lap['file_pdf'] ?></span>
                      </p>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- File Gambar -->
                <div class="grid grid-cols-12 gap-4">
                  <div class="col-span-3 flex items-start pt-2">
                    <label class="font-medium text-gray-700 text-sm">Gambar</label>
                  </div>
                  <div class="col-span-9">
                    <div class="flex items-start gap-4">
                      <img id="preview-<?= $i ?>" 
                           src="<?= !empty($lap['file_gambar']) ? base_url('uploads/gambar/'.$lap['file_gambar']) : 'https://via.placeholder.com/200x150/e5e7eb/6b7280?text=No+Image' ?>" 
                           class="w-48 h-36 object-cover rounded-lg border border-gray-300 flex-shrink-0 <?= empty($lap['file_gambar']) ? 'hidden' : '' ?>">
                      <div class="flex-1">
                        <input 
                          type="file" 
                          name="file_gambar[]" 
                          accept="image/*"
                          class="preview-gambar block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer
                                 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                 file:bg-green-600 file:text-white file:text-sm hover:file:bg-green-700 transition-all"
                          data-preview="preview-<?= $i ?>">
                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Max 2MB.</p>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          <?php endfor; ?>

        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
          <button type="button" onclick="window.history.back()" 
            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
            Batal
          </button>
          <button type="submit"
            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all">
             Simpan Perubahan
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
  <div class="laporan-item px-6 py-6 hover:bg-gray-50 transition-colors relative border-t border-gray-100">
    <input type="hidden" name="id[]" value="">
    <input type="hidden" name="urutan[]" value="">

    <!-- Header dengan nomor dan tombol hapus -->
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-lg font-bold text-gray-900">📄 Laporan Baru</h3>
      <button type="button" 
              class="remove-laporan px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-all shadow-sm">
        Hapus
      </button>
    </div>

    <!-- Grid Layout untuk Form Fields -->
    <div class="space-y-5">
      
      <!-- Judul -->
      <div class="grid grid-cols-12 gap-4">
        <div class="col-span-3 flex items-start pt-2">
          <label class="font-medium text-gray-700 text-sm">Judul</label>
        </div>
        <div class="col-span-9">
          <input 
            type="text" 
            name="judul[]"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
        </div>
      </div>

      <!-- Isi -->
      <div class="grid grid-cols-12 gap-4">
        <div class="col-span-3 flex items-start pt-2">
          <label class="font-medium text-gray-700 text-sm">Isi</label>
        </div>
        <div class="col-span-9">
          <textarea 
            name="isi[]" 
            class="isi-editor w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"></textarea>
        </div>
      </div>

      <!-- File PDF -->
      <div class="grid grid-cols-12 gap-4">
        <div class="col-span-3 flex items-start pt-2">
          <label class="font-medium text-gray-700 text-sm">File PDF</label>
        </div>
        <div class="col-span-9">
          <input 
            type="file" 
            name="file_pdf[]" 
            class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer
                   file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                   file:bg-blue-600 file:text-white file:text-sm hover:file:bg-blue-700 transition-all">
        </div>
      </div>

      <!-- File Gambar -->
      <div class="grid grid-cols-12 gap-4">
        <div class="col-span-3 flex items-start pt-2">
          <label class="font-medium text-gray-700 text-sm">Gambar</label>
        </div>
        <div class="col-span-9">
          <div class="flex items-start gap-4">
            <img id="preview-new" 
                 class="w-48 h-36 object-cover rounded-lg border border-gray-300 flex-shrink-0 hidden">
            <div class="flex-1">
              <input 
                type="file" 
                name="file_gambar[]" 
                accept="image/*"
                class="preview-gambar block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer
                       file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                       file:bg-green-600 file:text-white file:text-sm hover:file:bg-green-700 transition-all"
                data-preview="preview-new">
              <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Max 2MB.</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script Tambah & Hapus Slot -->
<script>
  const addBtn = document.getElementById('add-laporan');
  const container = document.getElementById('laporan-container');
  const template = document.getElementById('laporan-template').content;

  addBtn.addEventListener('click', () => {
    const clone = document.importNode(template, true);
    container.appendChild(clone);

    // Reinitialize TinyMCE
    tinymce.remove('textarea.isi-editor');
    tinymce.init({
      selector: 'textarea.isi-editor',
      height: 250,
      menubar: false,
      plugins: 'lists link image table code fullscreen',
      toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | fullscreen code',
      license_key: 'gpl',
      content_style: 'body { font-family:"Figtree", sans-serif; font-size:16px; line-height:1.6 }'
    });
  });

  // Remove laporan baru
  container.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-laporan')) {
      e.target.closest('.laporan-item').remove();
    }
  });

  // Delete laporan yang sudah ada
  container.addEventListener('click', function(e) {
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

  // Preview gambar saat upload
  container.addEventListener('change', function(e) {
    if (e.target.classList.contains('preview-gambar')) {
      const file = e.target.files[0];
      const previewId = e.target.getAttribute('data-preview');
      const preview = document.getElementById(previewId);
      
      if (file && preview) {
        const reader = new FileReader();
        reader.onload = function(event) {
          preview.src = event.target.result;
          preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
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
<?php if (session()->getFlashdata('success')): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: "<?= session()->getFlashdata('success') ?>",
  showConfirmButton: false,
  timer: 1800
});
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: "<?= session()->getFlashdata('error') ?>",
  showConfirmButton: true,
});
</script>
<?php endif; ?>

<?= $this->endSection() ?>
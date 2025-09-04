<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Welcome Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">

    <!-- Sidebar -->
    <?= view('layout/sidebar') ?>

<!-- Konten -->
<div class="flex-1 py-10 px-6 overflow-y-auto bg-gradient-to-br from-blue-50 via-white to-blue-100">
  <div class="max-w-6xl mx-auto">
<img src="/images/logo.png" alt="Logo POLBAN" class="logo-img" />
   <!-- Hero Section -->
<div class="text-left mb-12">
  <h1 class="text-4xl font-extrabold text-gray-900">Edit Landing Page</h1>
</div>

    <!-- Notifikasi -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="bg-blue-100 border-2 border-blue-500 text-blue-800 px-6 py-4 rounded-xl font-semibold mb-8">
        ✅ <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= base_url('/admin/welcome-page/update') ?>" method="post" enctype="multipart/form-data" class="space-y-12 relative">
      <input type="hidden" name="id" value="<?= esc($welcome['id']) ?>">

      <!-- Section Judul 1 -->
      <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Judul & Deskripsi Pertama</h2>

        <label class="block font-semibold text-gray-800">Judul 1</label>
        <input type="text" name="title_1" value="<?= esc($welcome['title_1']) ?>" required
          class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500">

        <label class="block font-semibold text-gray-800 mt-6">Deskripsi 1</label>
        <textarea id="desc_1_editor" name="desc_1" rows="4" required
          class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500"><?= esc($welcome['desc_1']) ?></textarea>
      </div>

      <!-- Section Judul 2 -->
      <div class="bg-white border-l-4 border-blue-500 rounded-2xl p-8 shadow-lg">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Judul & Deskripsi Kedua</h2>

        <label class="block font-semibold text-gray-800">Judul 2</label>
        <input type="text" name="title_2" value="<?= esc($welcome['title_2']) ?>" required
          class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500">

        <label class="block font-semibold text-gray-800 mt-6">Deskripsi 2</label>
        <textarea id="desc_2_editor" name="desc_2" rows="4" required
          class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500"><?= esc($welcome['desc_2']) ?></textarea>
      </div>

      <!-- Section Media -->
      <div class="bg-white border-l-4 border-blue-400 rounded-2xl p-8 shadow-lg">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Media</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Gambar -->
          <div>
            <label class="block font-semibold text-gray-800 mb-2">Gambar Saat Ini</label>
            <div class="bg-gray-100 p-4 rounded-xl border border-dashed border-blue-300 flex justify-center">
              <img src="<?= esc($welcome['image_path']) ?>" alt="Preview"
                   class="w-64 rounded-lg shadow hover:scale-105 transition-transform duration-300">
            </div>
            <input type="file" name="image"
              class="mt-4 block w-full text-sm text-gray-600 border border-blue-300 rounded-lg px-4 py-3 cursor-pointer
                     file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                     file:bg-blue-600 file:text-white hover:file:bg-blue-700">
          </div>

          <!-- YouTube -->
          <div>
            <label class="block font-semibold text-gray-800 mb-2">Link YouTube</label>
            <input type="text" name="youtube_url" value="<?= esc($welcome['youtube_url']) ?>" required
              class="w-full border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500">
            <p class="text-sm text-gray-500 mt-2">Gunakan embed URL (contoh: https://www.youtube.com/embed/xxxx)</p>
          </div>
        </div>
      </div>

      <!-- Tombol Floating -->
      <div class="sticky bottom-6 flex justify-end">
        <button type="submit"
          class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-extrabold px-10 py-4 rounded-full shadow-xl hover:scale-105 transition">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- TinyMCE Self-hosted -->
<script src="<?= base_url('tinymce/tinymce.min.js'); ?>"></script>
<script>
tinymce.init({
    selector: '#desc_1_editor, #desc_2_editor',
    license_key: 'gpl',
    height: 250,
    menubar: false,
    plugins: 'lists link image table code fullscreen',
    toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | fullscreen code',
    content_style: 'body { font-family:"Figtree", sans-serif; font-size:16px; line-height:1.6 }'
});
</script>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tentang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen bg-gray-100 text-gray-800">

    <!-- Sidebar -->
    <?= view('layout/sidebar') ?>

<!-- Konten -->
<div class="flex-1 py-10 px-6 overflow-y-auto bg-gradient-to-br from-blue-50 via-white to-blue-100">
  <div class="max-w-6xl mx-auto">
    <img src="/images/logo.png" alt="Logo POLBAN" class="logo-img" />

    <!-- Hero Section -->
    <div class="text-left mb-12">
      <h1 class="text-4xl font-extrabold text-gray-900">Edit Tentang</h1>
    </div>

    <!-- Notifikasi sukses -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="bg-blue-100 border-2 border-blue-500 text-blue-800 px-6 py-4 rounded-xl font-semibold mb-8">
        ✅ <?= esc(session()->getFlashdata('success')) ?>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= base_url('/admin/tentang/update') ?>" method="post" class="space-y-12 relative">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= esc($tentang['id']) ?>">

      <!-- Section Judul -->
      <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Judul Halaman</h2>

        <label for="judul" class="block font-semibold text-gray-800">Judul</label>
        <input type="text" id="judul" name="judul" value="<?= esc($tentang['judul']) ?>" required
          class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500">
      </div>

      <!-- Section Isi -->
      <div class="bg-white border-l-4 border-blue-500 rounded-2xl p-8 shadow-lg">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Isi Halaman</h2>

        <label for="isi-editor" class="block font-semibold text-gray-800">Isi</label>
        <textarea id="isi-editor" name="isi" rows="15" required
          class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500"><?= esc($tentang['isi']) ?></textarea>
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

<!-- TinyMCE JS (Self-hosted) -->
<script src="<?= base_url('tinymce/tinymce.min.js'); ?>"></script>
<script>
tinymce.init({
    selector: '#isi-editor',
    height: 400,
    menubar: false,
    plugins: 'lists link image table code fullscreen',
    toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | fullscreen code',
    content_style: 'body { font-family:"Figtree", sans-serif; font-size:16px; line-height:1.6 }',
    license_key: 'gpl' // ✅ supaya tidak minta API key
});
</script>

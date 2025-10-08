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

  <!-- Grid untuk 2 gambar sejajar -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Gambar Pertama -->
    <div>
      <label class="block font-semibold text-gray-800 mb-2">Gambar Kesatu</label>
      <div class="bg-gray-100 p-4 rounded-xl border border-dashed border-blue-300 flex justify-center">
        <img id="preview_image" src="<?= esc($welcome['image_path']) ?>" alt="Preview"
             class="w-64 rounded-lg shadow hover:scale-105 transition-transform duration-300">
      </div>
      <input type="file" name="image" id="image_input"
        class="mt-4 block w-full text-sm text-gray-600 border border-blue-300 rounded-lg px-4 py-3 cursor-pointer
               file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
               file:bg-blue-600 file:text-white hover:file:bg-blue-700">
      <button type="button" id="reset_image"
        class="mt-2 text-xs text-red-600 hover:text-red-800 underline">Reset Gambar</button>
    </div>

    <!-- Gambar Kedua -->
    <div>
      <label class="block font-semibold text-gray-800 mb-2">Gambar Kedua</label>
      <div class="bg-gray-100 p-4 rounded-xl border border-dashed border-blue-300 flex justify-center">
        <img id="preview_image_2" 
             src="<?= !empty($welcome['image_path_2']) ? esc($welcome['image_path_2']) : '/images/placeholder.png' ?>" 
             alt="Preview"
             class="w-64 rounded-lg shadow hover:scale-105 transition-transform duration-300">
      </div>
      <input type="file" name="image_2" id="image_input_2"
        class="mt-4 block w-full text-sm text-gray-600 border border-blue-300 rounded-lg px-4 py-3 cursor-pointer
               file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
               file:bg-blue-600 file:text-white hover:file:bg-blue-700">
      <button type="button" id="reset_image_2"
        class="mt-2 text-xs text-red-600 hover:text-red-800 underline">Reset Gambar</button>
    </div>
  </div>

   <!-- YouTube di bawah -->
  <div class="mt-8">
    <label class="block font-semibold text-gray-800 mb-2">Link YouTube</label>
    <input type="text" id="youtube_url" name="youtube_url" value="<?= esc($welcome['youtube_url']) ?>" required
      class="w-full border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500">
    <p class="text-sm text-gray-500 mt-2">Bisa pakai link biasa atau embed (contoh: https://www.youtube.com/watch?v=xxxx atau https://www.youtube.com/embed/xxxx)</p>

    <!-- Preview YouTube -->
    <div id="youtube_preview_container" class="mt-4 <?= empty($welcome['youtube_url']) ? 'hidden' : '' ?>">
      <iframe id="youtube_preview" 
              src="<?= esc($welcome['youtube_url']) ?>" 
              class="w-full h-64 rounded-xl shadow-md border border-blue-300" 
              frameborder="0" allowfullscreen></iframe>
    </div>
  </div>

  <!-- ✅ Tambahan: Upload Video File -->
  <div class="mt-10">
    <label class="block font-semibold text-gray-800 mb-2">Upload Video</label>
    <input type="file" id="video_file" name="video_file" accept="video/mp4,video/webm,video/ogg"
      class="block w-full text-sm text-gray-600 border border-blue-300 rounded-lg px-4 py-3 cursor-pointer
             file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
             file:bg-blue-600 file:text-white hover:file:bg-blue-700">
    <p class="text-sm text-gray-500 mt-2">Format didukung: MP4, WebM, OGG</p>

    <div id="video_preview_container" class="mt-4 <?= empty($welcome['video_path']) ? 'hidden' : '' ?>">
      <video id="video_preview" controls
             class="w-full h-64 rounded-xl shadow-md border border-blue-300">
        <?php if (!empty($welcome['video_path'])): ?>
          <source src="<?= esc($welcome['video_path']) ?>" type="video/mp4">
        <?php endif; ?>
        Browser Anda tidak mendukung pemutar video.
      </video>
    </div>
  </div>
</div>

<!-- Preview + Reset Script -->
<script>
function previewImage(inputId, previewId, resetId, defaultSrc) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);
  const resetBtn = document.getElementById(resetId);

  input.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(event) {
        preview.src = event.target.result;
      }
      reader.readAsDataURL(file);
    }
  });

  resetBtn.addEventListener('click', function() {
    input.value = "";
    preview.src = defaultSrc;
  });
}

previewImage('image_input', 'preview_image', 'reset_image', "<?= esc($welcome['image_path']) ?>");
previewImage('image_input_2', 'preview_image_2', 'reset_image_2', "<?= !empty($welcome['image_path_2']) ? esc($welcome['image_path_2']) : '/images/placeholder.png' ?>");

// === YouTube Preview ===
const youtubeInput = document.getElementById('youtube_url');
const youtubePreview = document.getElementById('youtube_preview');
const youtubeContainer = document.getElementById('youtube_preview_container');

function convertToEmbed(url) {
  const watchPattern = /(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/;
  const shortPattern = /(?:https?:\/\/)?youtu\.be\/([a-zA-Z0-9_-]+)/;
  if (watchPattern.test(url)) return url.replace(watchPattern, "https://www.youtube.com/embed/$1");
  if (shortPattern.test(url)) return url.replace(shortPattern, "https://www.youtube.com/embed/$1");
  return url;
}

youtubeInput.addEventListener('input', function() {
  let url = youtubeInput.value.trim();
  url = convertToEmbed(url);
  youtubeInput.value = url;
  if (url.includes("youtube.com/embed/")) {
    youtubePreview.src = url;
    youtubeContainer.classList.remove('hidden');
  } else {
    youtubeContainer.classList.add('hidden');
    youtubePreview.src = "";
  }
});

window.addEventListener('DOMContentLoaded', function() {
  let url = youtubeInput.value.trim();
  url = convertToEmbed(url);
  youtubeInput.value = url;
  if (url.includes("youtube.com/embed/")) {
    youtubePreview.src = url;
    youtubeContainer.classList.remove('hidden');
  } else {
    youtubeContainer.classList.add('hidden');
    youtubePreview.src = "";
  }
});

// === Preview Video File Tambahan ===
const videoInput = document.getElementById('video_file');
const videoContainer = document.getElementById('video_preview_container');
const videoPreview = document.getElementById('video_preview');

videoInput.addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const url = URL.createObjectURL(file);
    const source = videoPreview.querySelector('source');
    source.src = url;
    videoPreview.load();
    videoContainer.classList.remove('hidden');
  } else {
    videoContainer.classList.add('hidden');
  }
});
</script>


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
    selector: '#desc_1_editor, #desc_2_editor , #desc_3_editor',
    license_key: 'gpl',
    height: 250,
    menubar: false,
    plugins: 'lists link image table code fullscreen',
    toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | fullscreen code',
    content_style: 'body { font-family:"Figtree", sans-serif; font-size:16px; line-height:1.6 }'
});
</script>

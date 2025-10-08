<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="flex-1 overflow-y-auto bg-gray-50">
  <div class="max-w-7xl mx-auto px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Edit Landing Page</h1>
    </div>

    <!-- Notifikasi -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg mb-6 font-medium">
        ✅ <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      
      <!-- Table Header -->
      <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b border-gray-200 font-semibold text-sm text-gray-700">
        <div class="col-span-3">FIELD</div>
        <div class="col-span-9">KONTEN</div>
      </div>

      <!-- Form Content -->
      <form action="<?= base_url('/admin/welcome-page/update') ?>" method="post" enctype="multipart/form-data" id="welcomeForm" class="divide-y divide-gray-100">
        <input type="hidden" name="id" value="<?= esc($welcome['id']) ?>">

        <!-- Judul 1 -->
        <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
          <div class="col-span-3 flex items-start pt-2">
            <label class="font-medium text-gray-700">Judul 1</label>
          </div>
          <div class="col-span-9">
            <input type="text" name="title_1" value="<?= esc($welcome['title_1']) ?>" required
              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
          </div>
        </div>

        <!-- Deskripsi 1 -->
        <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
          <div class="col-span-3 flex items-start pt-2">
            <label class="font-medium text-gray-700">Deskripsi 1</label>
          </div>
          <div class="col-span-9">
            <textarea id="desc_1_editor" name="desc_1" rows="4" required
              class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"><?= esc($welcome['desc_1']) ?></textarea>
          </div>
        </div>

        <!-- Judul 2 -->
        <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
          <div class="col-span-3 flex items-start pt-2">
            <label class="font-medium text-gray-700">Judul 2</label>
          </div>
          <div class="col-span-9">
            <input type="text" name="title_2" value="<?= esc($welcome['title_2']) ?>" required
              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
          </div>
        </div>

        <!-- Deskripsi 2 -->
        <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
          <div class="col-span-3 flex items-start pt-2">
            <label class="font-medium text-gray-700">Deskripsi 2</label>
          </div>
          <div class="col-span-9">
            <textarea id="desc_2_editor" name="desc_2" rows="4" required
              class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"><?= esc($welcome['desc_2']) ?></textarea>
          </div>
        </div>

        <!-- Gambar 1 -->
        <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
          <div class="col-span-3 flex items-start pt-2">
            <label class="font-medium text-gray-700">Gambar 1</label>
          </div>
          <div class="col-span-9">
            <div class="flex items-start gap-4">
              <img id="preview_image" src="<?= esc($welcome['image_path']) ?>" alt="Preview"
                class="w-48 h-36 object-cover rounded-lg border border-gray-300 flex-shrink-0">
              <div class="flex-1">
                <input type="file" name="image" id="image_input" accept="image/*"
                  class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer
                         file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                         file:bg-blue-600 file:text-white file:text-sm hover:file:bg-blue-700 transition-all">
                <button type="button" id="reset_image"
                  class="mt-2 text-xs text-red-600 hover:text-red-800 underline">Reset ke default</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Gambar 2 -->
        <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
          <div class="col-span-3 flex items-start pt-2">
            <label class="font-medium text-gray-700">Gambar 2</label>
          </div>
          <div class="col-span-9">
            <div class="flex items-start gap-4">
              <img id="preview_image_2" 
                   src="<?= !empty($welcome['image_path_2']) ? esc($welcome['image_path_2']) : '/images/placeholder.png' ?>" 
                   alt="Preview"
                   class="w-48 h-36 object-cover rounded-lg border border-gray-300 flex-shrink-0">
              <div class="flex-1">
                <input type="file" name="image_2" id="image_input_2" accept="image/*"
                  class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer
                         file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                         file:bg-blue-600 file:text-white file:text-sm hover:file:bg-blue-700 transition-all">
                <button type="button" id="reset_image_2"
                  class="mt-2 text-xs text-red-600 hover:text-red-800 underline">Reset ke default</button>
              </div>
            </div>
          </div>
        </div>

        <!-- YouTube URL -->
        <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
          <div class="col-span-3 flex items-start pt-2">
            <label class="font-medium text-gray-700">Link YouTube</label>
          </div>
          <div class="col-span-9">
            <input type="text" id="youtube_url" name="youtube_url" value="<?= esc($welcome['youtube_url']) ?>" required
              placeholder="https://www.youtube.com/watch?v=xxxx"
              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            <p class="text-xs text-gray-500 mt-2">Bisa pakai link biasa atau embed (contoh: https://www.youtube.com/watch?v=xxxx atau https://www.youtube.com/embed/xxxx)</p>
            
            <!-- Preview YouTube -->
            <div id="youtube_preview_container" class="mt-4 <?= empty($welcome['youtube_url']) ? 'hidden' : '' ?>">
              <iframe id="youtube_preview" 
                      src="<?= esc($welcome['youtube_url']) ?>" 
                      class="w-full h-64 rounded-lg border border-gray-300" 
                      frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>

      </form>

      <!-- Action Buttons -->
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
        <button type="button" onclick="window.history.back()" 
          class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
          Batal
        </button>
        <button type="submit" form="welcomeForm"
          class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all">
          Simpan Perubahan
        </button>
      </div>

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

<!-- TinyMCE Self-hosted -->
<script src="<?= base_url('tinymce/tinymce.min.js'); ?>"></script>
<script>
tinymce.init({
    selector: '#desc_1_editor, #desc_2_editor, #desc_3_editor',
    license_key: 'gpl',
    height: 250,
    menubar: false,
    plugins: 'lists link image table code fullscreen',
    toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | fullscreen code',
    content_style: 'body { font-family:"Figtree", sans-serif; font-size:16px; line-height:1.6 }'
});
</script>

<?= $this->endSection() ?>
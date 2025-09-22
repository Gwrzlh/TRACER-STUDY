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

    <!-- Tabs Navigation -->
    <div class="mb-8 border-b border-gray-300 flex space-x-6">
      <button type="button" class="tab-btn py-3 px-6 font-bold text-blue-600 border-b-4 border-blue-600" data-target="tab-tentang">Landingpage Tentang</button>
      <button type="button" class="tab-btn py-3 px-6 font-bold text-gray-600 hover:text-blue-600" data-target="tab-sop">Landingpage SOP</button>
      <button type="button" class="tab-btn py-3 px-6 font-bold text-gray-600 hover:text-blue-600" data-target="tab-event">Landingpage Event</button>
      <button type="button" class="tab-btn py-3 px-6 font-bold text-gray-600 hover:text-blue-600" data-target="tab-history">History Event</button>
    </div>

    <!-- Form -->
    <form action="<?= base_url('/admin/tentang/update') ?>" method="post" enctype="multipart/form-data" class="relative">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= esc($tentang['id']) ?>">

      <!-- TAB 1: TENTANG -->
      <div id="tab-tentang" class="tab-content space-y-12">
        <!-- Section Judul 1 -->
        <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
          <h2 class="text-2xl font-bold text-blue-700 mb-6">Judul Halaman 1</h2>
          <label for="judul" class="block font-semibold text-gray-800">Judul</label>
          <input type="text" id="judul" name="judul" value="<?= esc($tentang['judul']) ?>" required
            class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500">
        </div>

        <!-- Section Isi 1 -->
        <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
          <h2 class="text-2xl font-bold text-blue-700 mb-6">Isi Halaman 1</h2>
          <label for="isi-editor" class="block font-semibold text-gray-800">Isi</label>
          <textarea id="isi-editor" name="isi" rows="15" required
            class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500"><?= esc($tentang['isi']) ?></textarea>
        </div>

        <!-- Upload Gambar 1 -->
        <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
          <h2 class="text-2xl font-bold text-blue-700 mb-6">Upload Gambar 1</h2>
          <label for="gambar" class="block font-semibold text-gray-800">Pilih Gambar</label>
          <input type="file" id="gambar" name="gambar" accept="image/*"
            class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-white">
          <p class="text-sm text-gray-500 mt-2">Format yang diperbolehkan: JPG, PNG. Max 2MB.</p>
          <div class="mt-4">
            <img id="preview-gambar" src="<?= !empty($tentang['gambar']) ? base_url('uploads/'.$tentang['gambar']) : '' ?>" 
                 alt="Preview Gambar" 
                 class="max-h-64 rounded-xl border border-blue-300 shadow-md <?= empty($tentang['gambar']) ? 'hidden' : '' ?>">
          </div>
          <button type="button" id="reset-gambar" 
            class="mt-3 text-red-600 hover:underline font-semibold <?= empty($tentang['gambar']) ? 'hidden' : '' ?>">
            Reset Gambar
          </button>
        </div>
      </div>

      <!-- TAB 2: SOP -->
      <div id="tab-sop" class="tab-content hidden space-y-12">
        <!-- Section Judul 2 -->
        <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
          <h2 class="text-2xl font-bold text-blue-700 mb-6">Judul Halaman 2</h2>
          <label for="judul2" class="block font-semibold text-gray-800">Judul</label>
          <input type="text" id="judul2" name="judul2" value="<?= esc($tentang['judul2'] ?? '') ?>" placeholder="Masukkan judul kedua..."
            class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500">
        </div>

        <!-- Section Isi 2 -->
        <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
          <h2 class="text-2xl font-bold text-blue-700 mb-6">Isi Halaman 2</h2>
          <label for="isi-editor2" class="block font-semibold text-gray-800">Isi</label>
          <textarea id="isi-editor2" name="isi2" rows="15"
            class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500"><?= esc($tentang['isi2'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- TAB 3: EVENT -->
      <div id="tab-event" class="tab-content hidden space-y-12">
        <!-- Section Judul 3 -->
        <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
          <h2 class="text-2xl font-bold text-blue-700 mb-6">Judul Halaman 3</h2>
          <label for="judul3" class="block font-semibold text-gray-800">Judul</label>
          <input type="text" id="judul3" name="judul3" value="<?= esc($tentang['judul3'] ?? '') ?>" placeholder="Masukkan judul ketiga..."
            class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500">
        </div>

        <!-- Section Isi 3 -->
        <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
          <h2 class="text-2xl font-bold text-blue-700 mb-6">Isi Halaman 3</h2>
          <label for="isi-editor3" class="block font-semibold text-gray-800">Isi</label>
          <textarea id="isi-editor3" name="isi3" rows="15"
            class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500"><?= esc($tentang['isi3'] ?? '') ?></textarea>
        </div>

        <!-- Upload Gambar 2 -->
        <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
          <h2 class="text-2xl font-bold text-blue-700 mb-6">Upload Gambar 2</h2>
          <label for="gambar2" class="block font-semibold text-gray-800">Pilih Gambar</label>
          <input type="file" id="gambar2" name="gambar2" accept="image/*"
            class="w-full mt-2 border border-blue-300 rounded-xl px-5 py-3 text-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-white">
          <p class="text-sm text-gray-500 mt-2">Format yang diperbolehkan: JPG, PNG. Max 2MB.</p>
          <div class="mt-4">
            <img id="preview-gambar2" src="<?= !empty($tentang['gambar2']) ? base_url('uploads/'.$tentang['gambar2']) : '' ?>" 
                 alt="Preview Gambar 2" 
                 class="max-h-64 rounded-xl border border-blue-300 shadow-md <?= empty($tentang['gambar2']) ? 'hidden' : '' ?>">
          </div>
          <button type="button" id="reset-gambar2" 
            class="mt-3 text-red-600 hover:underline font-semibold <?= empty($tentang['gambar2']) ? 'hidden' : '' ?>">
            Reset Gambar
          </button>
        </div>
      </div>

      <!-- TAB 4: HISTORY -->
      <div id="tab-history" class="tab-content hidden space-y-12">
        <!-- Section History Event -->
        <div class="bg-white border-l-4 border-blue-600 rounded-2xl p-8 shadow-lg">
          <h2 class="text-2xl font-bold text-blue-700 mb-6">History Event</h2>

          <?php if (!empty($historyEvents) && count($historyEvents) > 0): ?>
            <div class="space-y-6">
              <?php foreach ($historyEvents as $event): ?>
                <div class="border rounded-xl p-6 shadow-sm bg-gray-50">
                  <h3 class="text-xl font-bold text-gray-900 mb-2"><?= esc($event['judul3']) ?></h3>
                  <div class="prose max-w-none text-gray-700 mb-4">
                    <?= $event['isi3'] ?>
                  </div>
                  <?php if (!empty($event['gambar2'])): ?>
                    <img src="<?= base_url('uploads/'.$event['gambar2']) ?>" alt="Gambar Event"
                      class="max-h-64 rounded-xl border border-blue-300 shadow-md mb-4">
                  <?php endif; ?>
                  <p class="text-sm text-gray-500">📅 Tanggal: <?= esc($event['created_at'] ?? '-') ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="text-gray-600 italic">Belum ada history event yang tersimpan.</p>
          <?php endif; ?>
        </div>
      </div>

    <!-- Tombol Floating -->
    <div class="sticky bottom-6 flex justify-end mt-10"> 
      <button id="btn-simpan" type="submit" 
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
    selector: '#isi-editor, #isi-editor2, #isi-editor3',
    height: 400,
    menubar: false,
    plugins: 'lists link image table code fullscreen',
    toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | fullscreen code',
    content_style: 'body { font-family:"Figtree", sans-serif; font-size:16px; line-height:1.6 }',
    license_key: 'gpl'
});

// Tabs
const tabButtons = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');
const btnSimpan = document.getElementById('btn-simpan');

tabButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    tabButtons.forEach(b => {
      b.classList.remove('text-blue-600','border-blue-600','border-b-4');
      b.classList.add('text-gray-600');
    });
    btn.classList.add('text-blue-600','border-b-4','border-blue-600');

    tabContents.forEach(tab => tab.classList.add('hidden'));
    document.getElementById(btn.dataset.target).classList.remove('hidden');

    // 🔑 Sembunyikan tombol simpan jika tab = history
    if (btn.dataset.target === 'tab-history') {
      btnSimpan.classList.add('hidden');
    } else {
      btnSimpan.classList.remove('hidden');
    }
  });
});

// Preview & Reset Gambar 1
const inputGambar = document.getElementById('gambar');
const preview = document.getElementById('preview-gambar');
const resetBtn = document.getElementById('reset-gambar');
inputGambar.addEventListener('change', e => {
    const [file] = e.target.files;
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        resetBtn.classList.remove('hidden');
    }
});
resetBtn.addEventListener('click', () => {
    inputGambar.value = ''; 
    preview.src = '';       
    preview.classList.add('hidden'); 
    resetBtn.classList.add('hidden');
});

// Preview & Reset Gambar 2
const inputGambar2 = document.getElementById('gambar2');
const preview2 = document.getElementById('preview-gambar2');
const resetBtn2 = document.getElementById('reset-gambar2');
inputGambar2.addEventListener('change', e => {
    const [file] = e.target.files;
    if (file) {
        preview2.src = URL.createObjectURL(file);
        preview2.classList.remove('hidden');
        resetBtn2.classList.remove('hidden');
    }
});
resetBtn2.addEventListener('click', () => {
    inputGambar2.value = ''; 
    preview2.src = '';       
    preview2.classList.add('hidden'); 
    resetBtn2.classList.add('hidden');
});
</script>
</body>
</html>

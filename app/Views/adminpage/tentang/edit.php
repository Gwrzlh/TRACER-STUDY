<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="flex-1 overflow-y-auto bg-gray-50">
  <div class="max-w-7xl mx-auto px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Edit Tentang</h1>
    </div>

    <!-- Notifikasi sukses -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg mb-6 font-medium">
        ✅ <?= esc(session()->getFlashdata('success')) ?>
      </div>
    <?php endif; ?>

    <!-- Tabs Navigation -->
    <div class="mb-6 border-b border-gray-200">
      <nav class="flex space-x-8">
        <button type="button" class="tab-btn py-3 px-1 font-medium text-blue-600 border-b-2 border-blue-600" data-target="tab-tentang">
          Landingpage Tentang
        </button>
        <button type="button" class="tab-btn py-3 px-1 font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-target="tab-sop">
          Landingpage SOP
        </button>
        <button type="button" class="tab-btn py-3 px-1 font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-target="tab-event">
          Landingpage Event
        </button>
        <button type="button" class="tab-btn py-3 px-1 font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent" data-target="tab-history">
          History Event
        </button>
      </nav>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      
      <!-- Table Header -->
      <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b border-gray-200 font-semibold text-sm text-gray-700">
        <div class="col-span-3">FIELD</div>
        <div class="col-span-9">KONTEN</div>
      </div>

      <!-- Form Content -->
      <form action="<?= base_url('/admin/tentang/update') ?>" method="post" enctype="multipart/form-data" id="tentangForm">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= esc($tentang['id']) ?>">

        <!-- TAB 1: TENTANG -->
        <div id="tab-tentang" class="tab-content divide-y divide-gray-100">
          
          <!-- Judul -->
          <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
            <div class="col-span-3 flex items-start pt-2">
              <label for="judul" class="font-medium text-gray-700">Judul</label>
            </div>
            <div class="col-span-9">
              <input type="text" id="judul" name="judul" value="<?= esc($tentang['judul']) ?>" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            </div>
          </div>

          <!-- Isi -->
          <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
            <div class="col-span-3 flex items-start pt-2">
              <label for="isi-editor" class="font-medium text-gray-700">Isi</label>
            </div>
            <div class="col-span-9">
              <textarea id="isi-editor" name="isi" rows="15" required
                class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"><?= esc($tentang['isi']) ?></textarea>
            </div>
          </div>

          <!-- Gambar 1 -->
          <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
            <div class="col-span-3 flex items-start pt-2">
              <label for="gambar" class="font-medium text-gray-700">Gambar</label>
            </div>
            <div class="col-span-9">
              <div class="flex items-start gap-4">
                <img id="preview-gambar" 
                     src="<?= !empty($tentang['gambar']) ? base_url('uploads/'.$tentang['gambar']) : 'https://via.placeholder.com/200x150/e5e7eb/6b7280?text=No+Image' ?>" 
                     alt="Preview Gambar" 
                     class="w-48 h-36 object-cover rounded-lg border border-gray-300 flex-shrink-0">
                <div class="flex-1">
                  <input type="file" id="gambar" name="gambar" accept="image/*"
                    class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer
                           file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                           file:bg-blue-600 file:text-white file:text-sm hover:file:bg-blue-700 transition-all">
                  <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Max 2MB.</p>
                  <button type="button" id="reset-gambar"
                    class="mt-2 text-xs text-red-600 hover:text-red-800 underline <?= empty($tentang['gambar']) ? 'hidden' : '' ?>">
                    Reset ke default
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- TAB 2: SOP -->
        <div id="tab-sop" class="tab-content hidden divide-y divide-gray-100">
          
          <!-- Judul 2 -->
          <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
            <div class="col-span-3 flex items-start pt-2">
              <label for="judul2" class="font-medium text-gray-700">Judul</label>
            </div>
            <div class="col-span-9">
              <input type="text" id="judul2" name="judul2" value="<?= esc($tentang['judul2'] ?? '') ?>" placeholder="Masukkan judul kedua..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            </div>
          </div>

          <!-- Isi 2 -->
          <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
            <div class="col-span-3 flex items-start pt-2">
              <label for="isi-editor2" class="font-medium text-gray-700">Isi</label>
            </div>
            <div class="col-span-9">
              <textarea id="isi-editor2" name="isi2" rows="15"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"><?= esc($tentang['isi2'] ?? '') ?></textarea>
            </div>
          </div>

        </div>

        <!-- TAB 3: EVENT -->
        <div id="tab-event" class="tab-content hidden divide-y divide-gray-100">
          
          <!-- Judul 3 -->
          <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
            <div class="col-span-3 flex items-start pt-2">
              <label for="judul3" class="font-medium text-gray-700">Judul</label>
            </div>
            <div class="col-span-9">
              <input type="text" id="judul3" name="judul3" value="<?= esc($tentang['judul3'] ?? '') ?>" placeholder="Masukkan judul ketiga..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            </div>
          </div>

          <!-- Isi 3 -->
          <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
            <div class="col-span-3 flex items-start pt-2">
              <label for="isi-editor3" class="font-medium text-gray-700">Isi</label>
            </div>
            <div class="col-span-9">
              <textarea id="isi-editor3" name="isi3" rows="15"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none"><?= esc($tentang['isi3'] ?? '') ?></textarea>
            </div>
          </div>

          <!-- Gambar 2 -->
          <div class="grid grid-cols-12 gap-4 px-6 py-5 hover:bg-gray-50 transition-colors">
            <div class="col-span-3 flex items-start pt-2">
              <label for="gambar2" class="font-medium text-gray-700">Gambar</label>
            </div>
            <div class="col-span-9">
              <div class="flex items-start gap-4">
                <img id="preview-gambar2" 
                     src="<?= !empty($tentang['gambar2']) ? base_url('uploads/'.$tentang['gambar2']) : 'https://via.placeholder.com/200x150/e5e7eb/6b7280?text=No+Image' ?>" 
                     alt="Preview Gambar 2" 
                     class="w-48 h-36 object-cover rounded-lg border border-gray-300 flex-shrink-0">
                <div class="flex-1">
                  <input type="file" id="gambar2" name="gambar2" accept="image/*"
                    class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer
                           file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                           file:bg-blue-600 file:text-white file:text-sm hover:file:bg-blue-700 transition-all">
                  <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Max 2MB.</p>
                  <button type="button" id="reset-gambar2"
                    class="mt-2 text-xs text-red-600 hover:text-red-800 underline <?= empty($tentang['gambar2']) ? 'hidden' : '' ?>">
                    Reset ke default
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- TAB 4: HISTORY -->
        <div id="tab-history" class="tab-content hidden">
          <div class="px-6 py-8">
            <?php if (!empty($historyEvents) && count($historyEvents) > 0): ?>
              <div class="space-y-6">
                <?php foreach ($historyEvents as $event): ?>
                  <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 mb-3"><?= esc($event['judul3']) ?></h3>
                    <div class="prose max-w-none text-gray-700 text-sm mb-4">
                      <?= $event['isi3'] ?>
                    </div>
                    <?php if (!empty($event['gambar2'])): ?>
                      <img src="<?= base_url('uploads/'.$event['gambar2']) ?>" alt="Gambar Event"
                        class="w-full max-h-64 object-cover rounded-lg border border-gray-300 mb-4">
                    <?php endif; ?>
                    <p class="text-xs text-gray-500">📅 <?= esc($event['created_at'] ?? '-') ?></p>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-4 text-gray-500 italic">Belum ada history event yang tersimpan.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </form>

      <!-- Action Buttons (Hidden on History Tab) -->
      <div id="action-buttons" class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
        <button type="button" onclick="window.history.back()" 
          class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
          Batal
        </button>
        <button type="submit" form="tentangForm"
          class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all">
          Simpan Perubahan
        </button>
      </div>

    </div>

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
const actionButtons = document.getElementById('action-buttons');

tabButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    // Reset all tabs
    tabButtons.forEach(b => {
      b.classList.remove('text-blue-600', 'border-blue-600');
      b.classList.add('text-gray-500', 'border-transparent');
    });
    
    // Activate clicked tab
    btn.classList.remove('text-gray-500', 'border-transparent');
    btn.classList.add('text-blue-600', 'border-blue-600');

    // Show/hide content
    tabContents.forEach(tab => tab.classList.add('hidden'));
    document.getElementById(btn.dataset.target).classList.remove('hidden');

    // Hide action buttons on history tab
    if (btn.dataset.target === 'tab-history') {
      actionButtons.classList.add('hidden');
    } else {
      actionButtons.classList.remove('hidden');
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
    preview.src = 'https://via.placeholder.com/200x150/e5e7eb/6b7280?text=No+Image';
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
    preview2.src = 'https://via.placeholder.com/200x150/e5e7eb/6b7280?text=No+Image';
    resetBtn2.classList.add('hidden');
});
</script>

<?= $this->endSection() ?>
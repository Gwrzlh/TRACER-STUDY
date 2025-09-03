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
    <div class="flex-1 pt-5 pr-6 pb-6 pl-0 overflow-y-auto">
        <div class="w-full bg-white shadow-lg rounded-2xl p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-700">Kelola Laporan</h2>

            <!-- Notifikasi sukses -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- Notifikasi error -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

       <!-- Form -->
<form action="<?= base_url('admin/laporan/save') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
    <?= csrf_field() ?>

    <?php for ($i = 1; $i <= 7; $i++): ?>
        <div class="border rounded-xl p-5 bg-gray-50 shadow-sm">
            <input type="hidden" name="urutan[]" value="<?= $i ?>">

            <h3 class="text-lg font-semibold text-gray-600 mb-4">Laporan <?= $i ?></h3>

            <!-- Judul -->
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
            <input 
                type="text" 
                name="judul[]" 
                value="<?= $laporan[$i-1]['judul'] ?? '' ?>"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 mb-4"
            >

            <!-- Isi -->
            <label class="block text-sm font-medium text-gray-700 mb-1">Isi</label>
            <textarea 
                name="isi[]" 
                class="isi-editor w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 mb-4"
            ><?= $laporan[$i-1]['isi'] ?? '' ?></textarea>

            <!-- File PDF -->
            <label class="block text-sm font-medium text-gray-700 mb-1">File PDF</label>
            <input 
                type="file" 
                name="file_pdf[]" 
                class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 
                       file:rounded-full file:border-0 file:text-sm file:font-semibold 
                       file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 mb-2"
            >

            <?php if (!empty($laporan[$i-1]['file_pdf'])): ?>
                <p class="text-sm text-gray-600">File lama: 
                    <span class="font-medium text-blue-600"><?= $laporan[$i-1]['file_pdf'] ?></span>
                </p>
            <?php endif; ?>

            <!-- Tambahan: File Gambar -->
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
                 src="<?= !empty($laporan[$i-1]['file_gambar']) ? base_url('uploads/gambar/'.$laporan[$i-1]['file_gambar']) : '' ?>" 
                 class="mt-2 w-32 h-32 object-cover rounded-lg border <?= empty($laporan[$i-1]['file_gambar']) ? 'hidden' : '' ?>">
        </div>
    <?php endfor; ?>

    <!-- Tombol Simpan -->
    <div class="text-right">
        <button 
            type="submit" 
            class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition"
        >
            Simpan
        </button>
    </div>
</form>

<!-- Script Preview Gambar -->
<script>
    document.querySelectorAll('.preview-gambar').forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.getAttribute('data-preview');
            const previewImg = document.getElementById(previewId);

            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    previewImg.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>


    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/mulx329q2otm5e08yjpc5fw54t0uqsvqy2zd1fcj2545xggl/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: 'textarea.isi-editor',
        height: 250,
        menubar: false,
        plugins: 'lists link image table code fullscreen',
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | code fullscreen',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
    </script>
</body>
</html>

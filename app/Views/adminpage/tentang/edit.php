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
<div class="flex-1 pt-5 pr-6 pb-6 pl-0 overflow-y-auto">
    <div class="w-full bg-white shadow rounded-lg p-5 ml-0">
        <h2 class="text-2xl font-semibold mb-6">Edit Halaman Tentang</h2>

        <!-- Notifikasi sukses -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/admin/tentang/update') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= esc($tentang['id']) ?>">

            <!-- Judul -->
            <div>
                <label class="block font-medium mb-1" for="judul">Judul</label>
                <input type="text" id="judul" name="judul" value="<?= esc($tentang['judul']) ?>" required
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400">
            </div>

            <!-- Isi -->
            <div>
                <label class="block font-medium mb-1" for="isi-editor">Isi</label>
                <textarea id="isi-editor" name="isi" rows="15" required
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"><?= esc($tentang['isi']) ?></textarea>
            </div>

            <!-- Tombol -->
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TinyMCE JS -->
<script src="https://cdn.tiny.cloud/1/mulx329q2otm5e08yjpc5fw54t0uqsvqy2zd1fcj2545xggl/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#isi-editor',
    height: 500,
    menubar: false,
    plugins: 'lists link image table code fullscreen',
    toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
</script>

</body>
</html>

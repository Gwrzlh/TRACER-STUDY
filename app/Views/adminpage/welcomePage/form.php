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
    <div class="flex-1 pt-5 pr-6 pb-6 pl-0 overflow-y-auto">
        <div class="w-full bg-white shadow rounded-lg p-5 ml-0">
        
        <!-- Notifikasi -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6 shadow">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        

        <!-- Form -->
        <form action="<?= base_url('/admin/welcome-page/update') ?>" method="post" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-8 space-y-6 w-full max-w-6xl">
            <input type="hidden" name="id" value="<?= esc($welcome['id']) ?>"><!-- Judul -->
        <h2 class="text-3xl font-semibold mb-8 text-gray-800">Edit Welcome Page</h2>

            <!-- Judul 1 -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Judul 1</label>
                <input type="text" name="title_1" value="<?= esc($welcome['title_1']) ?>" required
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400">
            </div>

            <!-- Deskripsi 1 -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Deskripsi 1</label>
                <textarea name="desc_1" rows="4" required
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"><?= esc($welcome['desc_1']) ?></textarea>
            </div>

            <!-- Judul 2 -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Judul 2</label>
                <input type="text" name="title_2" value="<?= esc($welcome['title_2']) ?>" required
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400">
            </div>

            <!-- Deskripsi 2 -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Deskripsi 2</label>
                <textarea name="desc_2" rows="4" required
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"><?= esc($welcome['desc_2']) ?></textarea>
            </div>

            <!-- Gambar Saat Ini -->
            <div>
                <label class="block font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                <img src="<?= esc($welcome['image_path']) ?>" alt="Preview" class="w-48 rounded shadow mb-2">
            </div>

            <!-- Ganti Gambar -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Ganti Gambar (Opsional)</label>
                <input type="file" name="image"
                    class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <!-- Link YouTube -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Link YouTube (Embed URL)</label>
                <input type="text" name="youtube_url" value="<?= esc($welcome['youtube_url']) ?>" required
                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400">
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</body>
</html>

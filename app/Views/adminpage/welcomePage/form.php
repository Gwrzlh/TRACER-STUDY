<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Welcome Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-300 min-h-screen">

    <!-- Sidebar -->
    <?= view('layout/sidebar') ?>

    <!-- Konten -->
    <div class="flex-1 p-8 overflow-y-auto">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <h2 class="text-2xl font-bold mb-6">Edit Welcome Page</h2>

        <form action="<?= base_url('/admin/welcome-page/update') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" value="<?= esc($welcome['id']) ?>">

            <div>
                <label class="block font-medium">Judul 1</label>
                <input type="text" name="title_1" class="w-full border border-gray-300 rounded px-3 py-2" value="<?= esc($welcome['title_1']) ?>" required>
            </div>

            <div>
                <label class="block font-medium">Deskripsi 1</label>
                <textarea name="desc_1" rows="4" class="w-full border border-gray-300 rounded px-3 py-2" required><?= esc($welcome['desc_1']) ?></textarea>
            </div>

            <div>
                <label class="block font-medium">Judul 2</label>
                <input type="text" name="title_2" class="w-full border border-gray-300 rounded px-3 py-2" value="<?= esc($welcome['title_2']) ?>" required>
            </div>

            <div>
                <label class="block font-medium">Deskripsi 2</label>
                <textarea name="desc_2" rows="4" class="w-full border border-gray-300 rounded px-3 py-2" required><?= esc($welcome['desc_2']) ?></textarea>
            </div>

          

            <div>
                <label class="block font-medium mb-2">Gambar Saat Ini</label>
                <img src="<?= esc($welcome['image_path']) ?>" alt="Preview" class="w-48 rounded mb-2">
            </div>

            <div>
                <label class="block font-medium">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <div>
                <label class="block font-medium">Link YouTube (embed URL)</label>
                <input type="text" name="youtube_url" class="w-full border border-gray-300 rounded px-3 py-2" value="<?= esc($welcome['youtube_url']) ?>" required>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan Perubahan</button>
        </form>
    </div>

</body>
</html>

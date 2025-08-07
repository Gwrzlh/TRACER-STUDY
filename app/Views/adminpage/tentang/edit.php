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
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/admin/tentang/update') ?>" method="post" class="space-y-6">
                <input type="hidden" name="id" value="<?= $tentang['id'] ?>">

                <div>
                    <label class="block font-medium mb-1">Judul</label>
                    <input type="text" name="judul" value="<?= esc($tentang['judul']) ?>" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400">
                </div>

                <div>
                    <label class="block font-medium mb-1">Isi</label>
                    <textarea name="isi" rows="15" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"><?= esc($tentang['isi']) ?></textarea>
                </div>

                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

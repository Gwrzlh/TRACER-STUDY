<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Situs</title>
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
            <h2 class="text-2xl font-semibold mb-6">Pengaturan Situs</h2>

            <!-- Notifikasi sukses -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/pengaturan-situs/simpan') ?>" method="post" class="space-y-6">
                <?= csrf_field() ?>

                <!-- Pilihan Tema -->
                <div>
                    <label class="block font-medium mb-1" for="theme">Tema</label>
                    <select id="theme" name="theme" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400">
                       <option value="light" <?= ($theme == 'light') ? 'selected' : '' ?>>Terang</option>
                       <option value="dark" <?= ($theme == 'dark') ? 'selected' : '' ?>>Gelap</option>
                    </select>
                </div>

                <!-- Pilihan Bahasa -->
                <div>
                    <label class="block font-medium mb-1" for="language">Bahasa</label>
                    <select id="language" name="language" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-400">
                       <option value="id" <?= ($language == 'id') ? 'selected' : '' ?>>Indonesia</option>
                       <option value="en" <?= ($language == 'en') ? 'selected' : '' ?>>English</option>
                    </select>
                </div>

                <!-- Tombol Simpan -->
                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

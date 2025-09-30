<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Jabatan Lainnya</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/sidebar.css') ?>">

</head>

<body class="flex bg-gray-100 min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-blue-800 text-white flex flex-col">
        <div class="p-4 text-center font-bold text-lg border-b border-blue-600">
            Jabatan Lainnya
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="<?= site_url('jabatan/dashboard') ?>"
                class="block px-4 py-2 rounded hover:bg-blue-600"> Dashboard</a>

            <a href="<?= site_url('jabatan/ami-akreditasi') ?>"
                class="block px-4 py-2 rounded hover:bg-blue-600">
                Ami dan Akreditasi Kaprodi
            </a>



            <!-- Detail Ami -->
            <a href="<?= site_url('jabatan/detail-ami') ?>"
                class="block px-6 py-2 rounded hover:bg-blue-600 <?= uri_string() == 'jabatan/detail-ami' ? 'bg-blue-600 font-bold' : '' ?>">
                Detail Ami
            </a>

            <!-- Detail Akreditasi -->
            <a href="<?= site_url('jabatan/detail-akreditasi') ?>"
                class="block px-6 py-2 rounded hover:bg-blue-600 <?= uri_string() == 'jabatan/detail-akreditasi' ? 'bg-blue-600 font-bold' : '' ?>">
                Detail Akreditasi
            </a>
        </nav>
        </nav>

        <div class="p-4 border-t border-blue-600">
            <p class="mb-2">👤 <?= esc(session('username')) ?></p>
            <a href="<?= site_url('logout') ?>"
                class="block w-full text-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                Logout
            </a>
        </div>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-6">
        <?= $this->renderSection('content') ?>
    </main>

</body>

</html>
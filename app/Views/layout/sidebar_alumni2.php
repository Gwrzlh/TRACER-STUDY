<?php
$currentRoute = service('request')->uri->getPath();
$session = session();
$foto = $session->get('foto');

// Pastikan file foto ada, jika tidak pakai default
$fotoPath = FCPATH . 'uploads/foto_alumni/' . ($foto ?? '');
if ($foto && file_exists($fotoPath)) {
    $fotoUrl = base_url('uploads/foto_alumni/' . $foto);
} else {
    $fotoUrl = base_url('uploads/default.png');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Dashboard Alumni Surveyor' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-[#cfd8dc] font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 h-screen w-64 bg-white shadow-md flex flex-col justify-between">
            <div>
                <!-- Logo -->
                <div class="flex items-center gap-3 px-6 py-4 border-b">
                    <img src="<?= base_url('images/logo.png') ?>" alt="Logo POLBAN" class="w-10 h-10">
                    <span class="text-lg font-bold text-gray-700">Tracer Study</span>
                </div>

                <!-- Menu -->
                <nav class="mt-4 space-y-1">
                    <a href="<?= base_url('alumni/dashboard') ?>"
                        class="flex items-center gap-3 px-6 py-2 rounded-lg transition hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
                        <i class="fa-solid fa-house"></i><span>Dashboard</span>
                    </a>

                    <a href="javascript:void(0)" id="profileSidebarBtn"
                        class="flex items-center gap-3 px-6 py-2 rounded-lg transition hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/profil') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
                        <i class="fa-solid fa-user"></i><span>Profil</span>
                    </a>

                    <a href="<?= base_url('alumni/questionnaires') ?>"
                        class="flex items-center gap-3 px-6 py-2 rounded-lg transition hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/questionnaires') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
                        <i class="fa-solid fa-list"></i><span>Kuesioner</span>
                    </a>

                    <a href="<?= base_url('alumni/lihat_teman') ?>"
                        class="flex items-center gap-3 px-6 py-2 rounded-lg transition hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/lihat_teman') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
                        <i class="fa-solid fa-users"></i><span>Lihat Teman</span>
                    </a>
                </nav>
            </div>

            <!-- Profil + Logout -->
            <div class="px-6 py-4 border-t">
                <div class="flex items-center gap-3 mb-3 cursor-pointer hover:bg-gray-100 p-2 rounded-lg transition" id="profileSidebarBtnBottom">
                    <img id="sidebarFoto" src="<?= $fotoUrl ?>" class="w-12 h-12 rounded-full shadow-md border object-cover">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm"><?= $session->get('nama_lengkap') ?? $session->get('username') ?></p>
                        <p class="text-gray-500 text-xs"><?= $session->get('email') ?></p>
                    </div>
                </div>

                <form action="/logout" method="get">
                    <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 ml-64 p-8 overflow-auto">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Modal Foto -->
    <div id="sidebarProfileModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
        <div class="relative">
            <img id="sidebarModalFoto" src="<?= $fotoUrl ?>" class="w-80 h-80 object-cover rounded-xl shadow-xl">
            <span id="closeSidebarModal" class="absolute top-2 right-3 text-white text-2xl cursor-pointer">&times;</span>
        </div>
    </div>

    <script>
        const profileBtns = [
            document.getElementById('profileSidebarBtn'),
            document.getElementById('profileSidebarBtnBottom')
        ];
        const sidebarModal = document.getElementById('sidebarProfileModal');
        const sidebarModalFoto = document.getElementById('sidebarModalFoto');
        const closeSidebarModal = document.getElementById('closeSidebarModal');

        profileBtns.forEach(btn => {
            if (btn) btn.addEventListener('click', () => {
                sidebarModal.classList.remove('hidden');
            });
        });

        closeSidebarModal.addEventListener('click', () => {
            sidebarModal.classList.add('hidden');
        });

        sidebarModal.addEventListener('click', (e) => {
            if (e.target === sidebarModal) {
                sidebarModal.classList.add('hidden');
            }
        });

        // Update foto
        function updateSidebarFoto(newSrc) {
            document.getElementById('sidebarFoto').src = newSrc;
            sidebarModalFoto.src = newSrc;
        }
    </script>
</body>

</html>
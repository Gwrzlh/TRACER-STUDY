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
  <title><?= $title ?? 'Dashboard Alumni' ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    /* Modal animasi */
    #profileModal .modal-content {
      transform: scale(0.8);
      opacity: 0;
      transition: transform 0.3s ease, opacity 0.3s ease;
    }

    #profileModal.show .modal-content {
      transform: scale(1);
      opacity: 1;
    }
  </style>
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

          <a href="<?= base_url('alumni/profil') ?>"
            class="flex items-center gap-3 px-6 py-2 rounded-lg transition hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/profil') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
            <i class="fa-solid fa-user"></i><span>Profil</span>
          </a>

          <!-- Kuesioner -->
          <a href="<?= base_url('alumni/questioner') ?>"
            class="flex items-center gap-3 px-6 py-2 rounded-lg transition 
            hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/questioner') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
            <i class="fa-solid fa-list"></i>
            <span>Kuesioner</span>
          </a>



          <!-- Notifikasi -->
          <a href="<?= base_url('alumni/notifikasi') ?>"
            class="flex items-center gap-3 px-6 py-2 rounded-lg transition relative hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/notifikasi') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
            <i class="fa-solid fa-bell"></i><span>Notifikasi</span>
            <span id="notifCount"
              class="absolute -top-1 left-40 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 hidden">0</span>
          </a>
        </nav>
      </div>

      <!-- Profil + Logout -->
      <div class="px-6 py-4 border-t">
        <div id="profileSidebar" class="flex items-center gap-3 mb-3 cursor-pointer hover:bg-gray-100 p-2 rounded-lg transition">
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
  <div id="profileModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
    <div class="modal-content relative bg-white rounded-xl shadow-xl p-4">
      <span id="closeModal" class="absolute top-2 right-3 text-gray-700 text-xl cursor-pointer">&times;</span>
      <img id="modalFoto" src="<?= $fotoUrl ?>" class="w-80 h-80 object-cover rounded-xl">
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Update foto
    function updateSidebarFoto(url) {
      document.getElementById('sidebarFoto').src = url;
      document.getElementById('modalFoto').src = url;
    }

    // Hitung notifikasi
    function loadNotifCount() {
      $.get("<?= base_url('alumni/notifikasi/count') ?>", function(data) {
        if (data.jumlah > 0) {
          $("#notifCount").text(data.jumlah).removeClass("hidden");
        } else {
          $("#notifCount").addClass("hidden");
        }
      }, "json");
    }
    setInterval(loadNotifCount, 5000);
    loadNotifCount();

    // Modal
    const profileSidebar = document.getElementById('profileSidebar');
    const profileModal = document.getElementById('profileModal');
    const closeModal = document.getElementById('closeModal');

    profileSidebar.addEventListener('click', () => {
      profileModal.classList.remove('hidden');
      setTimeout(() => profileModal.classList.add('show'), 10);
    });

    closeModal.addEventListener('click', () => {
      profileModal.classList.remove('show');
      setTimeout(() => profileModal.classList.add('hidden'), 300);
    });

    profileModal.addEventListener('click', (e) => {
      if (e.target === profileModal) {
        profileModal.classList.remove('show');
        setTimeout(() => profileModal.classList.add('hidden'), 300);
      }
    });
  </script>
</body>

</html>
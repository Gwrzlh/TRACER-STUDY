<?php
$currentRoute = service('request')->uri->getPath();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Dashboard Alumni' ?></title>
  <link rel="stylesheet" href="<?= base_url('css/sidebar.css') ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-[#cfd8dc] font-sans">
  <div class="flex">
    <!-- Sidebar -->
    <aside class="sidebar-container fixed top-0 left-0 h-screen w-64 flex flex-col justify-between bg-white shadow-md">
      <!-- Logo -->
      <div>
        <div class="sidebar-logo flex items-center gap-3 px-6 py-4 border-b">
          <img src="/images/logo.png" alt="Logo POLBAN" class="w-10 h-10">
          <span class="text-lg font-bold text-gray-700">Tracer Study</span>
        </div>

        <!-- Menu -->
        <nav class="mt-4 space-y-1">
          <!-- Dashboard -->
          <a href="<?= base_url('alumni/dashboard') ?>"
            class="flex items-center gap-3 px-6 py-2 rounded-lg transition 
            hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
          </a>

          <!-- Profil -->
          <a href="<?= base_url('alumni/profil') ?>"
            class="flex items-center gap-3 px-6 py-2 rounded-lg transition 
            hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/profil') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
            <i class="fa-solid fa-user"></i>
            <span>Profil</span>
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
            class="flex items-center gap-3 px-6 py-2 rounded-lg transition relative
            hover:bg-gray-200 <?= str_contains($currentRoute, 'alumni/notifikasi') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
            <i class="fa-solid fa-bell"></i>
            <span>Notifikasi</span>
            <span id="notifCount"
              class="absolute -top-1 left-40 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 hidden">0</span>
          </a>
        </nav>
      </div>

      <!-- Profile + Logout -->
      <div class="px-6 py-4 border-t">
        <div class="flex items-center gap-3 mb-3">
          <?php
          $session = session();
          $foto = $session->get('foto');

          // Pastikan file benar-benar ada
          $fotoPath = FCPATH . 'uploads/foto_alumni/' . ($foto ?? '');
          if ($foto && file_exists($fotoPath)) {
            $fotoUrl = base_url('uploads/foto_alumni/' . $foto);
          } else {
            $fotoUrl = base_url('uploads/default.png');
          }
          ?>
          <img src="<?= $fotoUrl ?>" class="w-10 h-10 rounded-full border">
          <div>
            <p class="font-semibold text-gray-800 text-sm">
              <?= $session->get('nama_lengkap') ?? $session->get('username') ?>
            </p>
            <p class="text-gray-500 text-xs"><?= $session->get('email') ?></p>
          </div>
        </div>

        <form action="/logout" method="get">
          <button type="submit"
            class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
            Logout
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8 overflow-auto">
      <?= $this->renderSection('content') ?>
    </main>
  </div>

  <!-- Script AJAX Notif -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
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
  </script>
</body>

</html>
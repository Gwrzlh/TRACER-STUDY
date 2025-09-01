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
    <aside class="sidebar-container">
      <!-- Logo -->
      <div>
        <div class="sidebar-logo">
          <img src="/images/logo.png" alt="Logo POLBAN" class="logo-img" />
          Tracer Study
        </div>

        <!-- Menu -->
        <nav class="mt-4 space-y-2">
          <a href="<?= base_url('alumni/dashboard') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'alumni/dashboard') ? 'active' : '' ?>">
            <i class="fa-solid fa-house icon"></i>
            <span>Dashboard</span>
          </a>

          <a href="<?= base_url('alumni/profil') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'alumni/profil') ? 'active' : '' ?>">
            <i class="fa-solid fa-user icon"></i>
            <span>Profil</span>
          </a>

          <a href="<?= base_url('alumni/questioner') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'alumni/questioner') ? 'active' : '' ?>">
            <i class="fa-solid fa-list icon"></i>
            <span>Kuesioner</span>
          </a>

          <a href="<?= base_url('alumni/notifikasi') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'alumni/notifikasi') ? 'active' : '' ?> relative">
            <i class="fa-solid fa-bell icon"></i>
            <span>Notifikasi</span>
            <!-- Badge jumlah notif -->
            <span id="notifCount"
              class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 hidden">0</span>
          </a>
        </nav>
      </div>

      <!-- Profile + Logout -->
      <div class="mt-6 px-4 space-y-2">
        <div class="flex items-center gap-4">
          <div class="relative">
            <?php 
              $foto = session()->get('foto'); 
              $fotoUrl = $foto ? base_url('uploads/' . $foto) : base_url('uploads/default.png');
            ?>
            <img src="<?= $fotoUrl ?>" class="profile-img">
            <span class="status-indicator"></span>
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-sm">
              <?= session()->get('nama_lengkap') ?? session()->get('username') ?>
            </p>
            <p class="text-gray-500 text-xs"><?= session()->get('email') ?></p>
          </div>
        </div>

        <form action="/logout" method="get">
          <button type="submit" class="logout-btn w-full">
            Logout
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-auto">
      <?= $this->renderSection('content') ?>
    </main>
  </div>

  <!-- Script AJAX Notif -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function loadNotifCount() {
      $.get("<?= base_url('alumni/notifikasi/count') ?>", function (data) {
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

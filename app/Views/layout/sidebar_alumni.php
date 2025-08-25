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
</head>

<body class="bg-[#cfd8dc] font-sans flex">
  <!-- Sidebar (fixed) -->
  <aside class="sidebar-container fixed top-0 left-0 h-screen w-64 flex flex-col justify-between">
    <!-- Bagian Atas -->
    <div>
      <!-- Logo -->
      <div class="sidebar-logo">
        <img src="/images/logo.png" alt="Logo POLBAN" class="logo-img" />
        Tracer Study
      </div>

      <!-- Dashboard -->
      <a href="<?= base_url('alumni/dashboard') ?>"
        class="sidebar-link <?= str_contains($currentRoute, 'alumni/dashboard') ? 'active' : '' ?>">
        <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z" />
        </svg>
        <span>Dashboard</span>
      </a>

      <!-- Profil -->
      <a href="<?= base_url('alumni/profil') ?>"
        class="sidebar-link <?= str_contains($currentRoute, 'alumni/profil') ? 'active' : '' ?>">
        <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M5.121 17.804A9.969 9.969 0 0112 15c2.21 0 4.247.716 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        <span>Profil</span>
      </a>

      <!-- Kuesioner -->
      <a href="<?= base_url('alumni/questioner') ?>"
        class="sidebar-link <?= str_contains($currentRoute, 'alumni/questioner') ? 'active' : '' ?>">
        <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M9 8h6"></path>
        </svg>
        <span>Kuesioner</span>
      </a>

      <!-- 🔔 Notifikasi -->
      <a href="<?= base_url('alumni/notifikasi') ?>"
        class="sidebar-link <?= str_contains($currentRoute, 'alumni/notifikasi') ? 'active' : '' ?> relative">
        <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 17h5l-1.405-1.405C18.21 14.79 18 13.918 18 13V9c0-3.314-2.686-6-6-6S6 5.686 6 9v4c0 .918-.21 1.79-.595 2.595L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span>Notifikasi</span>
        <!-- Badge jumlah notif -->
        <span id="notifCount"
          class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 hidden">
          0
        </span>
      </a>
    </div>

    <!-- Profile + Logout -->
    <div class="px-4 space-y-2 mb-6">
      <div class="flex items-center gap-4">
        <div class="relative">
          <img src="/img/idk.jpeg" class="profile-img">
          <span class="status-indicator"></span>
        </div>
        <div>
          <p class="font-semibold text-gray-800 text-sm"><?= session()->get('username') ?></p>
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
  <main class="flex-1 ml-64 p-8 overflow-auto">
    <?= $this->renderSection('content') ?>
  </main>

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
    // cek setiap 5 detik
    setInterval(loadNotifCount, 5000);
    loadNotifCount();
  </script>
</body>
</html>
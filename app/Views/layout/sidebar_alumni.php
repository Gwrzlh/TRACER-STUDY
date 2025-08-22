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

          <!-- Dashboard (opsional) -->
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
              <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9.969 9.969 0 0112 15c2.21 0 4.247.716 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
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



          <!-- Profile + Logout -->
          <div class="mt-6 px-4 space-y-2">
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
              <button type="submit" class="logout-btn">
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
</body>

</html>
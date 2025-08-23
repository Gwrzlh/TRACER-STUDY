<?php
$currentRoute = service('request')->uri->getPath();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Dashboard' ?></title>
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

          <!-- Dashboard -->
          <a href="<?= base_url('dashboard') ?>" 
            class="sidebar-link <?= str_contains($currentRoute, 'dashboard') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z" />
            </svg>
            <span>Dashboard</span>
          </a>

          <!-- Pengguna -->
          <a href="<?= base_url('admin/pengguna') ?>" 
            class="sidebar-link <?= str_contains($currentRoute, 'admin/pengguna') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Pengguna</span>
          </a>

          <!-- Kuesioner -->
          <a href="<?= base_url('admin/questionnaire') ?>" 
            class="sidebar-link <?= str_contains($currentRoute, 'admin/questionnaire') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M9 8h6"></path>
            </svg>
            <span>Kuesioner</span>
          </a>

          <!-- Organisasi -->
          <details class="group" <?= str_contains($currentRoute, 'organisasi') ? 'open' : '' ?>>
            <summary class="sidebar-link <?= str_contains($currentRoute, 'organisasi') ? 'active' : '' ?>">
              <div class="flex items-center gap-2">
                <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
                <span>Organisasi</span>
              </div>
              <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </summary>
            <div class="ml-8 mt-1 space-y-1">
              <a href="<?= base_url('satuanorganisasi') ?>" class="submenu <?= $currentRoute == 'organisasi/struktur' ? 'text-blue-600 font-semibold' : '' ?>">
                Satuan Organisasi
              </a>
              <a href="<?= base_url('/admin/tipeorganisasi') ?>" class="submenu <?= $currentRoute == 'organisasi/prodi' ? 'text-blue-600 font-semibold' : '' ?>">
                Tipe Organisasi
              </a>
            </div>
          </details>

          <!-- Welcome Page -->
          <a href="<?= base_url('admin/welcome-page') ?>" 
            class="sidebar-link <?= str_contains($currentRoute, 'admin/welcome-page') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"></path>
            </svg>
            <span>Welcome Page</span>
          </a>

          <!-- Kontak -->
          <a href="<?= base_url('admin/kontak') ?>" 
            class="sidebar-link <?= str_contains($currentRoute, 'kontak') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h2l3 9a1 1 0 001 .6h9a1 1 0 001-.8l2.4-8H6"></path>
            </svg>
            <span>Kontak</span>
          </a>

          <!-- Tentang -->
          <a href="<?= base_url('admin/tentang/edit') ?>" 
            class="sidebar-link <?= str_contains($currentRoute, 'admin/tentang') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"></path>
            </svg>
            <span>Tentang</span>
          </a>

          <!-- Pengaturan Situs -->
          <a href="<?= base_url('pengaturan-situs') ?>" 
            class="sidebar-link <?= str_contains($currentRoute, 'pengaturan-situs') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6V4m0 16v-2m8-8h2M4 12H2m15.364 6.364l1.414 1.414M6.222 6.222L4.808 4.808m12.728 0l1.414 1.414M6.222 17.778l-1.414 1.414">
              </path>
            </svg>
            <span>Pengaturan Situs</span>
          </a>

        </nav>
      </div>

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

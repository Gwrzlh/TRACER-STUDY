<?php
$currentRoute = service('request')->uri->getPath();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Dashboard Kaprodi' ?></title>
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
          Kaprodi
        </div>

        <!-- Menu -->
        <nav class="mt-4 space-y-2">
          <!-- Dashboard -->
          <a href="<?= base_url('kaprodi/dashboard') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'dashboard') ? 'active' : '' ?>">
            <i class="fa fa-home w-5"></i>
            <span>Dashboard</span>
          </a>

           <!-- Kuesioner -->
          <a href="<?= base_url('kaprodi/questioner') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'kuesioner') ? 'active' : '' ?>">
            <i class="fa fa-file-alt w-5"></i>
            <span>Kuesioner</span>
          </a>

          <!-- Akreditasi -->
          <a href="<?= base_url('kaprodi/akreditasi') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'akreditasi') ? 'active' : '' ?>">
            <i class="fa fa-flag w-5"></i>
            <span>Akreditasi</span>
          </a>

          <!-- AMI -->
          <a href="<?= base_url('kaprodi/ami') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'ami') ? 'active' : '' ?>">
            <i class="fa fa-check-circle w-5"></i>
            <span>AMI</span>
          </a>

          <!-- Profil -->
          <a href="<?= base_url('kaprodi/profil') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'profil') ? 'active' : '' ?>">
            <i class="fa fa-user w-5"></i>
            <span>Profil</span>
          </a>
        </nav>
      </div>

      <!-- Profile + Logout -->
      <div class="mt-6 px-4 space-y-2">
        <div class="flex items-center gap-4">
          <div class="relative">
            <?php 
            $foto = session()->get('foto') ?? 'default.png';
            $fotoUrl = base_url('uploads/kaprodi/' . $foto);
            ?>
            <img src="<?= $fotoUrl ?>" class="profile-img object-cover rounded-full">
            <span class="status-indicator"></span>
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-sm"><?= session()->get('username') ?? 'kaprodi' ?></p>
            <p class="text-gray-500 text-xs"><?= session()->get('email') ?? 'kaprodi@polban.ac.id' ?></p>
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

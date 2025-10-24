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
  <?= $this->renderSection('styles') ?>
</head>

<body class="bg-[#cfd8dc] font-sans">
  <div class="flex">
    <!-- Sidebar -->
    <aside class="sidebar-container flex flex-col h-screen">
      <!-- Logo -->
      <div class="sidebar-logo shrink-0">
        <img src="/images/logo.png" alt="Logo POLBAN" class="logo-img" />
        Tracer Study
      </div>

      <!-- Menu -->
      <nav class="flex-grow overflow-y-auto mt-4 space-y-2 px-4">

        <!-- Dashboard -->
        <a href="<?= base_url('admin/dashboard') ?>"
          class="sidebar-link <?= str_contains($currentRoute, 'admin/dashboard') ? 'active' : '' ?>">
          <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z" />
          </svg>
          <span>Dashboard</span>
        </a>

        <!-- ORGANISASI -->
        <details class="group" <?= str_contains($currentRoute, 'organisasi') ? 'open' : '' ?>>
          <summary class="sidebar-link <?= str_contains($currentRoute, 'organisasi') ? 'active' : '' ?>">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-sitemap icon"></i>
              <span>Organisasi Panel</span>
            </div>
            <i class="fa-solid fa-chevron-down w-4 h-4 transition-transform duration-300 group-open:rotate-180"></i>
          </summary>
          <div class="ml-8 mt-1 space-y-1">
            <a href="<?= base_url('satuanorganisasi') ?>" class="submenu">Satuan Organisasi</a>
            <a href="<?= base_url('/admin/tipeorganisasi') ?>" class="submenu">Tipe Organisasi</a>
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
        <!-- KUESIONER -->
        <details class="group" <?= (
          str_contains($currentRoute, 'admin/pengguna') ||
          str_contains($currentRoute, 'admin/questionnaire')
        ) ? 'open' : '' ?>>
          <summary class="sidebar-link <?= (
            str_contains($currentRoute, 'admin/pengguna') ||
            str_contains($currentRoute, 'admin/questionnaire')
          ) ? 'active' : '' ?>">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-clipboard-list icon"></i>
              <span>Kuesioner Panel</span>
            </div>
            <i class="fa-solid fa-chevron-down w-4 h-4 transition-transform duration-300 group-open:rotate-180"></i>
          </summary>

          <div class="ml-8 mt-1 space-y-1">
            <a href="<?= base_url('admin/pengguna') ?>"
              class="submenu <?= str_contains($currentRoute, 'admin/pengguna') ? 'active' : '' ?>">
              Pengguna 
            </a>
            <a href="<?= base_url('admin/questionnaire') ?>"
              class="submenu <?= str_contains($currentRoute, 'admin/questionnaire') ? 'active' : '' ?>">
              Kuesioner
            </a>
          </div>
        </details>

        <!-- WELCOME PANEL -->
        <details class="group" <?= (
          str_contains($currentRoute, 'admin/laporan') ||
          str_contains($currentRoute, 'admin/welcome-page') ||
          str_contains($currentRoute, 'admin/kontak') ||
          str_contains($currentRoute, 'admin/tentang') ||
          str_contains($currentRoute, 'admin/emailtemplate') ||
          str_contains($currentRoute, 'admin/respon')
        ) ? 'open' : '' ?>>
          <summary class="sidebar-link <?= (
            str_contains($currentRoute, 'admin/laporan') ||
            str_contains($currentRoute, 'admin/welcome-page') ||
            str_contains($currentRoute, 'admin/kontak') ||
            str_contains($currentRoute, 'admin/tentang') ||
            str_contains($currentRoute, 'admin/emailtemplate') ||
            str_contains($currentRoute, 'admin/respon')
          ) ? 'active' : '' ?>">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-window-restore icon"></i>
              <span>Welcome Panel</span>
            </div>
            <i class="fa-solid fa-chevron-down w-4 h-4 transition-transform duration-300 group-open:rotate-180"></i>
          </summary>

        <!-- Log Aktivitas -->
        <a href="<?= base_url('admin/log_activities') ?>"
          class="sidebar-link <?= str_contains($currentRoute, 'admin/log_activities') && !str_contains($currentRoute, 'admin/log_activities/dashboard') ? 'active' : '' ?>">
          <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"></path>
          </svg>
          <span>Aktivitas Pengguna</span>
        </a>
          <div class="ml-8 mt-1 space-y-1">
            <a href="<?= base_url('admin/laporan') ?>" class="submenu <?= str_contains($currentRoute, 'admin/laporan') ? 'active' : '' ?>">Laporan</a>
            <a href="<?= base_url('admin/welcome-page') ?>" class="submenu <?= str_contains($currentRoute, 'admin/welcome-page') ? 'active' : '' ?>">Welcome Page</a>
            <a href="<?= base_url('admin/kontak') ?>" class="submenu <?= str_contains($currentRoute, 'admin/kontak') ? 'active' : '' ?>">Kontak</a>
            <a href="<?= base_url('admin/tentang/edit') ?>" class="submenu <?= str_contains($currentRoute, 'admin/tentang') ? 'active' : '' ?>">Tentang</a>
            <a href="<?= base_url('admin/emailtemplate') ?>" class="submenu <?= str_contains($currentRoute, 'admin/emailtemplate') ? 'active' : '' ?>">Email</a>
            <a href="<?= base_url('admin/respon') ?>" class="submenu <?= str_contains($currentRoute, 'admin/respon') ? 'active' : '' ?>">Respon</a>
          </div>
        </details>

        <!-- LAINNYA -->
        <details class="group" <?= (
          str_contains($currentRoute, 'pengaturan-situs') ||
          str_contains($currentRoute, 'admin/log_activities') ||
          str_contains($currentRoute, 'admin/profil')
        ) ? 'open' : '' ?>>
          <summary class="sidebar-link <?= (
            str_contains($currentRoute, 'pengaturan-situs') ||
            str_contains($currentRoute, 'admin/log_activities') ||
            str_contains($currentRoute, 'admin/profil')
          ) ? 'active' : '' ?>">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-ellipsis-h icon"></i>
              <span>Lainnya</span>
            </div>
            <i class="fa-solid fa-chevron-down w-4 h-4 transition-transform duration-300 group-open:rotate-180"></i>
          </summary>

          <div class="ml-8 mt-1 space-y-1">
            <a href="<?= base_url('pengaturan-situs') ?>" class="submenu <?= str_contains($currentRoute, 'pengaturan-situs') ? 'active' : '' ?>">Pengaturan Situs</a>
            <a href="<?= base_url('admin/log_activities') ?>" class="submenu <?= str_contains($currentRoute, 'admin/log_activities') ? 'active' : '' ?>">Aktivitas Pengguna</a>
            <a href="<?= base_url('admin/log_activities/dashboard') ?>" class="submenu <?= str_contains($currentRoute, 'admin/log_activities/dashboard') ? 'active' : '' ?>">Log Dashboard</a>
            <a href="<?= base_url('admin/profil') ?>" class="submenu <?= str_contains($currentRoute, 'admin/profil') ? 'active' : '' ?>">Profil</a>
          </div>
        </details>

      </nav>

      <?php
      $session = session();
      $foto = $session->get('foto');
      $fotoPath = FCPATH . 'uploads/foto_admin/' . ($foto ?? '');
      $fotoUrl = ($foto && file_exists($fotoPath))
        ? base_url('uploads/foto_admin/' . $foto)
        : base_url('uploads/default.png');
      ?>

      <!-- Profile + Logout -->
      <div class="shrink-0 bg-white pt-4 pb-2 px-4 space-y-2 border-t">
        <div class="flex items-center gap-4 cursor-pointer hover:bg-gray-100 p-2 rounded-lg transition" id="profileSidebarBtn">
          <div class="relative">
            <img id="sidebarFoto" src="<?= $fotoUrl ?>" class="w-12 h-12 rounded-full shadow-md border object-cover">
            <span class="status-indicator"></span>
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-sm"><?= $session->get('username') ?></p>
            <p class="text-gray-500 text-xs"><?= $session->get('email') ?></p>
          </div>
        </div>

        <form action="/logout" method="get">
          <button type="submit"
            style="background-color: <?= get_setting('logout_button_color', '#dc3545') ?>;
                   color: <?= get_setting('logout_button_text_color', '#ffffff') ?>;
                   padding: 10px 20px;
                   font-weight: 600;
                   border-radius: 8px;
                   width: 100%; text-align:center;"
            onmouseover="this.style.backgroundColor='<?= get_setting('logout_button_hover_color', '#a71d2a') ?>';"
            onmouseout="this.style.backgroundColor='<?= get_setting('logout_button_color', '#dc3545') ?>';">
            Logout
          </button>
        </form>
      </div>

      <!-- Modal Foto -->
      <div id="profileModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
        <div class="modal-content relative bg-white rounded-xl shadow-xl p-4">
          <span id="closeModal" class="absolute top-2 right-3 text-gray-700 text-xl cursor-pointer">&times;</span>
          <img id="modalFoto" src="<?= $fotoUrl ?>" class="w-80 h-80 object-cover rounded-xl">
        </div>
      </div>

      <script>
        const profileSidebarBtn = document.getElementById('profileSidebarBtn');
        const profileModal = document.getElementById('profileModal');
        const closeModal = document.getElementById('closeModal');

        profileSidebarBtn?.addEventListener('click', () => {
          profileModal.classList.remove('hidden');
          setTimeout(() => profileModal.classList.add('show'), 10);
        });

        closeModal?.addEventListener('click', () => {
          profileModal.classList.remove('show');
          setTimeout(() => profileModal.classList.add('hidden'), 300);
        });

        profileModal?.addEventListener('click', (e) => {
          if (e.target === profileModal) {
            profileModal.classList.remove('show');
            setTimeout(() => profileModal.classList.add('hidden'), 300);
          }
        });
      </script>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-auto">
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</body>

</html>

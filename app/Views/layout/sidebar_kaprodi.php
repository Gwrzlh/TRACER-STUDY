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
<?php
// Ambil pengaturan tombol logout Kaprodi
$logoutText        = get_setting('kaprodi_logout_button_text', 'Logout');
$logoutBgColor     = get_setting('kaprodi_logout_button_color', '#dc3545');
$logoutTextColor   = get_setting('kaprodi_logout_button_text_color', '#ffffff');
$logoutHoverColor  = get_setting('kaprodi_logout_button_hover_color', '#bb2d3b');
?>

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
          <details class="group" <?= (str_contains($currentRoute, 'questioner') || str_contains($currentRoute, 'kuesioner')) ? 'open' : '' ?>>
            <summary class="sidebar-link <?= (str_contains($currentRoute, 'questioner') || str_contains($currentRoute, 'kuesioner')) ? 'active' : '' ?>">
              <div class="flex items-center gap-2">
                <i class="fa fa-file-alt w-5"></i>
                <span>Kuesioner</span>
              </div>
              <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </summary>

            <div class="ml-8 mt-1 space-y-1">
              <a href="<?= base_url('kaprodi/questioner') ?>"
                class="submenu <?= str_contains($currentRoute, 'questioner') ? 'active' : '' ?>">
                Daftar Kuesioner
              </a>
              <a href="<?= base_url('kaprodi/kuesioner') ?>"
                class="submenu <?= str_contains($currentRoute, 'create') ? 'active' : '' ?>">
                Tambah Kuesioner
              </a>
            </div>
          </details>




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

          <!-- Data Alumni -->
          <a href="<?= base_url('kaprodi/alumni') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'alumni') ? 'active' : '' ?>">
            <i class="fa fa-users w-5"></i>
            <span>Data Alumni</span>
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
          <button type="submit"
            class="w-full font-semibold rounded-lg px-4 py-2 transition duration-300"
            style="
      background-color: <?= esc($logoutBgColor) ?>;
      color: <?= esc($logoutTextColor) ?>;
      border: none;
    "
            onmouseover="this.style.backgroundColor='<?= esc($logoutHoverColor) ?>'"
            onmouseout="this.style.backgroundColor='<?= esc($logoutBgColor) ?>'">
            <?= esc($logoutText) ?>
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
<?php
$currentRoute = service('request')->uri->getPath();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Dashboard' ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#cfd8dc] font-sans">
  <div class="flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white rounded-3xl m-4 p-4 flex flex-col justify-between shadow-xl sticky top-4 h-[calc(100vh-2rem)]">
      <!-- Logo -->
      <div>
        <div class="p-4 font-bold text-xl flex items-center gap-2 border-b border-gray-200">
          <img src="/images/logo.png" alt="Logo POLBAN" class="w-8 h-8 object-contain" />
          Tracer Study
        </div>

        <!-- Menu -->
        <nav class="mt-4 space-y-2">
          <!-- Dashboard -->
          <a href="/dashboard" class="no-underline flex items-center gap-2 px-4 py-2 rounded-lg transition
            <?= $currentRoute == 'dashboard' ? 'bg-gray-200 text-blue-600 font-semibold' : 'text-gray-700 hover:text-black hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z" />
            </svg>
            <span>Dashboard</span>
          </a>

          <!-- Pengguna -->
          <a href="/admin/pengguna" class="no-underline flex items-center gap-2 px-4 py-2 rounded-lg transition
            <?= $currentRoute == 'admin/pengguna' ? 'bg-gray-200 text-blue-600 font-semibold' : 'text-gray-700 hover:text-black hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Pengguna</span>
          </a>

          <!-- Kuesioner -->
          <a href="<?= base_url('admin/questionnaire') ?>" class="no-underline flex items-center gap-2 px-4 py-2 rounded-lg transition
            <?= $currentRoute == 'kuesioner' ? 'bg-gray-200 text-blue-600 font-semibold' : 'text-gray-700 hover:text-black hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M9 8h6"></path>
            </svg>
            <span>Kuesioner</span>
          </a>

          <!-- Organisasi (Dropdown) -->
          <details class="group" <?= str_contains($currentRoute, 'organisasi') ? 'open' : '' ?>>
            <summary class="no-underline flex items-center justify-between gap-2 px-4 py-2 rounded-lg cursor-pointer transition
              <?= str_contains($currentRoute, 'organisasi') ? 'bg-gray-200 text-blue-600 font-semibold' : 'text-gray-700 hover:text-black hover:bg-gray-100' ?>">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
                <span>Organisasi</span>
              </div>
              <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </summary>

            <div class="ml-8 mt-1 space-y-1">
              <a href="<?= base_url('satuanorganisasi') ?>" class="no-underline flex items-center gap-2 px-2 py-1 rounded hover:text-black 
                <?= $currentRoute == 'organisasi/struktur' ? 'text-blue-600 font-semibold' : 'text-gray-600' ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8m-8 6h16"></path>
                </svg>
                Satuan Organisasi
              </a>
              <a href="<?= base_url('/admin/tipeorganisasi') ?>" class="no-underline flex items-center gap-2 px-2 py-1 rounded hover:text-black 
                <?= $currentRoute == 'organisasi/prodi' ? 'text-blue-600 font-semibold' : 'text-gray-600' ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h11M9 21V3m0 0L5 7m4-4l4 4"></path>
                </svg>
                Tipe Organisasi
              </a>
            </div>
          </details>

          <!-- Welcome Page -->
          <a href="/admin/welcome-page" class="no-underline flex items-center gap-2 px-4 py-2 rounded-lg transition 
            <?= $currentRoute == 'admin/welcome-page' ? 'bg-gray-200 text-blue-600 font-semibold' : 'text-gray-700 hover:text-black hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"></path>
            </svg>
            <span>Welcome Page</span>
          </a>

          <!-- Kontak -->
          <a href="/admin/kontak" class="no-underline flex items-center gap-2 px-4 py-2 rounded-lg transition
            <?= $currentRoute == 'kontak' ? 'bg-gray-200 text-blue-600 font-semibold' : 'text-gray-700 hover:text-black hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h2l3 9a1 1 0 001 .6h9a1 1 0 001-.8l2.4-8H6"></path>
            </svg>
            <span>Kontak</span>
          </a>

          <!-- Tentang -->
          <a href="/admin/tentang/edit" class="no-underline flex items-center gap-2 px-4 py-2 rounded-lg transition
            <?= $currentRoute == 'tentang' ? 'bg-gray-200 text-blue-600 font-semibold' : 'text-gray-700 hover:text-black hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"></path>
            </svg>
            <span>Tentang</span>
          </a>

          <!-- Pengaturan Situs -->
          <a href="#" class="no-underline flex items-center gap-2 px-4 py-2 rounded-lg transition
            <?= $currentRoute == 'pengaturan' ? 'bg-gray-200 text-blue-600 font-semibold' : 'text-gray-700 hover:text-black hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6V4m0 16v-2m8-8h2M4 12H2m15.364 6.364l1.414 1.414M6.222 6.222L4.808 4.808m12.728 0l1.414 1.414M6.222 17.778l-1.414 1.414"></path>
            </svg>
            <span>Pengaturan Situs</span>
          </a>
        </nav>
      </div>

      <!-- Profile + Logout -->
      <div class="mt-6 px-4 space-y-2">
        <div class="flex items-center gap-4">
          <div class="relative">
            <img src="/img/idk.jpeg" class="w-12 h-12 rounded-full object-cover border">
            <span class="absolute bottom-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-sm"><?= session()->get('username') ?></p>
            <p class="text-gray-500 text-xs"><?= session()->get('email') ?></p>
          </div>
        </div>

        <form action="/logout" method="get">
          <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm py-2 px-4 rounded transition">
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
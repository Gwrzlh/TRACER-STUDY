<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Dashboard' ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#cfd8dc] font-sans">

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-white rounded-3xl m-4 p-4 flex flex-col justify-between shadow-xl">
    
    <!-- Bagian Atas: Logo & Menu -->
    <div>
      <!-- Logo -->
      <div class="p-4 font-bold text-xl flex items-center gap-2 border-b border-gray-200">
  <img src="/images/logo.png" alt="Logo POLBAN" class="w-8 h-8 object-contain" />
  POLBAN
</div>


      <!-- Menu -->
      <nav class="mt-4 space-y-2">
        <a href="dashboard"
           class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:text-black hover:bg-gray-100 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"></path>
          </svg>
          <span>Dashboard</span>
        </a>

        <a href="/admin/pengguna"
           class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:text-black hover:bg-gray-100 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"></path>
          </svg>
          <span>Pengguna</span>
        </a>

        <!-- Aktif -->
        <a href="#"
           class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:text-black hover:bg-gray-100 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24">     
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 17v-6h13V7H9v10zm-6-4h.01M3 13h.01M3 17h.01M3 21h.01"></path>
          </svg>
          <span>Task list</span>
        </a>

        <a href="#"
           class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:text-black hover:bg-gray-100 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 4h16v16H4z"></path>
          </svg>
          <span>Services</span>
        </a>

        <a href="#"
           class="flex items-center justify-between px-4 py-2 text-gray-700 hover:text-black hover:bg-gray-100 rounded-lg">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span>Notifications</span>
          </div>
        </a>

        <a href="#"
           class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:text-black hover:bg-gray-100 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8 10h.01M12 10h.01M16 10h.01M21 10a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span>Chat</span>
        </a>
      </nav>
    </div>

    <!-- Bagian Bawah Sidebar: Profile + Logout -->
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

  <!-- Konten utama -->
  <main class="flex-1 p-8">
    <?= $this->renderSection('content') ?>
  </main>
</div>

</body>
</html>

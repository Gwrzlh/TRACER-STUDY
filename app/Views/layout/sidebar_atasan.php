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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <?= $this->renderSection('styles') ?>
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
          <a href="<?= base_url('atasan/dashboard') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'dashboard') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z" />
            </svg>
            <span>Dashboard</span>
          </a>

          <!-- Kuesioner -->
          <a href="<?= base_url('atasan/kuesioner') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'kuesioner') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M9 8h6"></path>
            </svg>
            <span>Kuesioner</span>
          </a>
<!-- 👥 Alumni -->
 <a href="<?= base_url('atasan/alumni') ?>"
            class="sidebar-link <?= str_contains($currentRoute, 'alumni') ? 'active' : '' ?>">
            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M9 8h6"></path>
            </svg>
            <span>Alumni</span>
          </a>

<!-- 🔔 Notifikasi -->
<a href="<?= base_url('atasan/notifikasi') ?>"
  class="sidebar-link <?= str_contains($currentRoute, 'notifikasi') ? 'active' : '' ?> relative">
  <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C8.67 6.165 8 7.388 8 9v5.159c0 .538-.214 1.055-.595 1.436L6 17h5m4 0v1a3 3 0 11-6 0v-1m6 0H9" />
  </svg>
  <span>Notifikasi</span>

  <!-- 🔴 Badge Count -->
  <span id="notifBadge"
        style="display:none;"
        class="absolute top-1 right-3 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
  </span>
</a>




        </nav>
      </div>

      <?php
      $session = session();
      $foto = $session->get('foto');
      $fotoPath = FCPATH . 'uploads/foto_atasan/' . ($foto ?? '');
      $fotoUrl = ($foto && file_exists($fotoPath))
        ? base_url('uploads/foto_atasan/' . $foto)
        : base_url('uploads/default.png');
      ?>

      <!-- Profile + Logout -->
      <div class="mt-6 px-4 space-y-2">
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
    style="
      background-color: <?= get_setting('atasan_logout_button_color', '#dc3545') ?>;
      color: <?= get_setting('atasan_logout_button_text_color', '#ffffff') ?>;
      padding: 10px 20px;
      font-weight: 600;
      border-radius: 8px;
      width: 100%;
      text-align: center;
      border: none;
      transition: background-color 0.3s ease;
    "
    onmouseover="this.style.backgroundColor='<?= get_setting('atasan_logout_button_hover_color', '#a71d2a') ?>';"
    onmouseout="this.style.backgroundColor='<?= get_setting('atasan_logout_button_color', '#dc3545') ?>';">
    <?= esc(get_setting('atasan_logout_button_text', 'Logout')) ?>
  </button>
</form>


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
<script>
document.addEventListener("DOMContentLoaded", function () {
  const dropdownLinks = document.querySelectorAll(".sidebar-item.has-sub > .sidebar-toggle");

  dropdownLinks.forEach(link => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      const parent = this.closest(".sidebar-item");
      const submenu = parent.querySelector(".submenu");
      const icon = this.querySelector(".toggle-icon");

      const isOpen = submenu.style.display === "block";

      // Tutup semua dropdown lain
      document.querySelectorAll(".sidebar-item.has-sub").forEach(item => {
        item.classList.remove("open");
        const sub = item.querySelector(".submenu");
        const ic = item.querySelector(".toggle-icon");
        if (sub) sub.style.display = "none";
        if (ic) ic.style.transform = "rotate(0deg)";
      });

      // Buka/tutup dropdown ini
      submenu.style.display = isOpen ? "none" : "block";
      parent.classList.toggle("open");
      icon.style.transform = isOpen ? "rotate(0deg)" : "rotate(180deg)";
    });
  });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const badge = document.getElementById("notifBadge");

  function updateNotifCount() {
    fetch("<?= base_url('atasan/getNotifCount') ?>")
      .then(res => res.json())
      .then(data => {
        if (data.jumlah > 0) {
          badge.textContent = data.jumlah;
          badge.style.display = "inline-block";
        } else {
          badge.style.display = "none";
        }
      })
      .catch(err => console.error("Error cek notifikasi:", err));
  }

  // pertama kali dijalankan
  updateNotifCount();

  // update setiap 30 detik
  setInterval(updateNotifCount, 30000);
});
</script>



</html>

<?php
$currentRoute = service('request')->uri->getPath();
$session = session();
$role = $session->get('role') ?? 'alumni'; // alumni / surveyor
$foto = $session->get('foto');

$fotoPath = FCPATH . 'uploads/foto_alumni/' . ($foto ?? '');
$fotoUrl = ($foto && file_exists($fotoPath))
  ? base_url('uploads/foto_alumni/' . $foto)
  : base_url('uploads/default.png');
?>
<?php
$siteSettingModel = new \App\Models\SiteSettingModel();
$settings = $siteSettingModel->getSettings();
?>

<link rel="stylesheet" href="<?= base_url('css/sidebar.css') ?>">
<aside class="sidebar-container">
  <div>
    <!-- Logo -->
    <div class="sidebar-logo">
      <img src="<?= base_url('images/logo.png') ?>" alt="Logo POLBAN" class="logo-img">
      <span>Tracer Study</span>
    </div>

    <!-- Menu -->
    <nav>
      <a href="<?= base_url($role === 'surveyor' ? 'alumni/surveyor/dashboard' : 'alumni/dashboard') ?>"
         class="sidebar-link <?= str_contains($currentRoute, 'dashboard') ? 'active' : '' ?>">
        <i class="fa-solid fa-house icon"></i>
        <span>Dashboard</span>
      </a>

      <a href="<?= base_url($role === 'surveyor' ? 'alumni/surveyor/profil' : 'alumni/profil') ?>"
         class="sidebar-link <?= str_contains($currentRoute, 'profil') ? 'active' : '' ?>">
        <i class="fa-solid fa-user icon"></i>
        <span>Profil</span>
      </a>

      <a href="<?= base_url('alumni/questionnaires') ?>"
         class="sidebar-link <?= str_contains($currentRoute, 'questionnaires') ? 'active' : '' ?>">
        <i class="fa-solid fa-list icon"></i>
        <span>Kuesioner</span>
      </a>

      <?php if ($role === 'surveyor'): ?>
      <a href="<?= base_url('alumni/lihat_teman') ?>"
         class="sidebar-link <?= str_contains($currentRoute, 'lihat_teman') ? 'active' : '' ?>">
        <i class="fa-solid fa-users icon"></i>
        <span>Lihat Teman</span>
      </a>
      <?php endif; ?>

      <?php if ($role === 'alumni'): ?>
      <a href="<?= base_url('alumni/notifikasi') ?>"
         class="sidebar-link relative <?= str_contains($currentRoute, 'notifikasi') ? 'active' : '' ?>">
        <i class="fa-solid fa-bell icon"></i>
        <span>Notifikasi</span>
        <span id="notifCount"
              class="absolute -top-1 left-40 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 hidden">0</span>
      </a>
      <?php endif; ?>
    </nav>
  </div>

  <!-- Profil + Logout -->
  <div class="mt-6 border-t pt-4">
    <div id="profileSidebarBtn" class="flex items-center gap-3 mb-3 cursor-pointer hover:bg-gray-100 p-2 rounded-lg transition relative">
      <img id="sidebarFoto" src="<?= $fotoUrl ?>" class="profile-img object-cover shadow-md">
      <div>
        <p class="font-semibold text-gray-800 text-sm"><?= $session->get('nama_lengkap') ?? $session->get('username') ?></p>
        <p class="text-gray-500 text-xs"><?= $session->get('email') ?></p>
      </div>
    </div>

    <form action="/logout" method="get">
  <button type="submit" 
          class="logout-btn"
          style="background-color: <?= esc($settings['dashboard_logout_button_color'] ?? '#dc2626') ?>;
                 color: <?= esc($settings['dashboard_logout_button_text_color'] ?? '#ffffff') ?>;"
          onmouseover="this.style.backgroundColor='<?= esc($settings['dashboard_logout_button_hover_color'] ?? '#b91c1c') ?>'"
          onmouseout="this.style.backgroundColor='<?= esc($settings['dashboard_logout_button_color'] ?? '#dc2626') ?>'">
      <?= esc($settings['dashboard_logout_button_text'] ?? 'Logout') ?>
  </button>
</form>
  </div>
</aside>

<!-- Modal Foto -->
<div id="profileModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
  <div class="modal-content relative bg-white rounded-xl shadow-xl p-4">
    <span id="closeModal" class="absolute top-2 right-3 text-gray-700 text-xl cursor-pointer">&times;</span>
    <img id="modalFoto" src="<?= $fotoUrl ?>" class="w-80 h-80 object-cover rounded-xl">
  </div>
</div>

<script>
  <?php if ($role === 'alumni'): ?>

    function loadNotifCount() {
      $.get("<?= base_url('alumni/notifikasi/count') ?>", function(data) {
        if (data.jumlah > 0) {
          $("#notifCount").text(data.jumlah).removeClass("hidden");
        } else {
          $("#notifCount").addClass("hidden");
        }
      }, "json");
    }
    setInterval(loadNotifCount, 5000);
    loadNotifCount();
  <?php endif; ?>

  // Modal foto
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
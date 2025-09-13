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
<aside class="fixed top-0 left-0 h-screen w-64 bg-white shadow-md flex flex-col justify-between">
  <div>
    <div class="sidebar-logo flex items-center gap-3 px-6 py-4 border-b">
      <img src="<?= base_url('images/logo.png') ?>" alt="Logo POLBAN" class="w-10 h-10">
      <span class="text-lg font-bold text-gray-700">Tracer Study</span>
    </div>

    <nav class="mt-4 space-y-1">
      <!-- Dashboard -->
      <a href="<?= base_url($role === 'surveyor' ? 'alumni/surveyor/dashboard' : 'alumni/dashboard') ?>"
        class="flex items-center gap-3 px-6 py-2 rounded-lg transition hover:bg-gray-200 <?= str_contains($currentRoute, 'dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
        <i class="fa-solid fa-house"></i><span>Dashboard</span>
      </a>

      <!-- Profil -->
      <a href="<?= base_url($role === 'surveyor' ? 'alumni/surveyor/profil' : 'alumni/profil') ?>"
        class="flex items-center gap-3 px-6 py-2 rounded-lg transition hover:bg-gray-200 <?= str_contains($currentRoute, 'profil') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
        <i class="fa-solid fa-user"></i><span>Profil</span>
      </a>

      <!-- Kuesioner -->
      <a href="<?= base_url('alumni/questionnaires') ?>"
        class="flex items-center gap-3 px-6 py-2 rounded-lg transition hover:bg-gray-200 <?= str_contains($currentRoute, 'questionnaires') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
        <i class="fa-solid fa-list"></i><span>Kuesioner</span>
      </a>

      <?php if ($role === 'surveyor'): ?>
        <!-- Lihat Teman hanya untuk Surveyor -->
        <a href="<?= base_url('alumni/lihat_teman') ?>"
          class="flex items-center gap-3 px-6 py-2 rounded-lg transition hover:bg-gray-200 <?= str_contains($currentRoute, 'lihat_teman') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
          <i class="fa-solid fa-users"></i><span>Lihat Teman</span>
        </a>
      <?php endif; ?>

      <?php if ($role === 'alumni'): ?>
        <!-- Notifikasi hanya untuk Alumni biasa -->
        <a href="<?= base_url('alumni/notifikasi') ?>"
          class="flex items-center gap-3 px-6 py-2 rounded-lg transition relative hover:bg-gray-200 <?= str_contains($currentRoute, 'notifikasi') ? 'bg-blue-600 text-white' : 'text-gray-700' ?>">
          <i class="fa-solid fa-bell"></i><span>Notifikasi</span>
          <span id="notifCount" class="absolute -top-1 left-40 bg-red-500 text-white text-xs rounded-full px-2 py-0.5 hidden">0</span>
        </a>
      <?php endif; ?>
    </nav>
  </div>

  <!-- Profil + Logout -->
  <div class="px-6 py-4 border-t">
    <div class="flex items-center gap-3 mb-3 cursor-pointer hover:bg-gray-100 p-2 rounded-lg transition" id="profileSidebarBtn">
      <img id="sidebarFoto" src="<?= $fotoUrl ?>" class="w-12 h-12 rounded-full shadow-md border object-cover">
      <div>
        <p class="font-semibold text-gray-800 text-sm"><?= $session->get('nama_lengkap') ?? $session->get('username') ?></p>
        <p class="text-gray-500 text-xs"><?= $session->get('email') ?></p>
      </div>
    </div>

    <form action="/logout" method="get">
      <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">Logout</button>
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
    setInterval(loadNotifCount, 2000);
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
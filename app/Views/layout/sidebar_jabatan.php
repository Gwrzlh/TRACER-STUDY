<?php
$currentRoute = service('request')->uri->getPath();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Jabatan Lainnya</title>
    <link rel="stylesheet" href="<?= base_url('css/sidebar.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?= $this->renderSection('styles') ?>
</head>

<body class="bg-[#cfd8dc] font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="sidebar-container">
            <!-- Logo -->
            <div>
                <div class="sidebar-logo">
                    <img src="/images/logo.png" alt="Logo Jabatan" class="logo-img" />
                    Jabatan Lainnya
                </div>

                <!-- Menu -->
                <nav class="mt-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="<?= site_url('jabatan/dashboard') ?>"
                        class="sidebar-link <?= str_contains($currentRoute, 'dashboard') ? 'active' : '' ?>">
                        <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Control Panel -->
                    <a href="<?= site_url('jabatan/control-panel') ?>"
                        class="sidebar-link <?= str_contains($currentRoute, 'jabatan/control-panel') ? 'active' : '' ?>">
                        <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Control Panel</span>
                    </a>

                    <!-- Detail Ami -->
                    <a href="<?= site_url('jabatan/detail-ami') ?>"
                        class="sidebar-link <?= str_contains($currentRoute, 'jabatan/detail-ami') ? 'active' : '' ?>">
                        <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M9 8h6"></path>
                        </svg>
                        <span>Detail Ami</span>
                    </a>

                    <!-- Detail Akreditasi -->
                    <a href="<?= site_url('jabatan/detail-akreditasi') ?>"
                        class="sidebar-link <?= str_contains($currentRoute, 'jabatan/detail-akreditasi') ? 'active' : '' ?>">
                        <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"></path>
                        </svg>
                        <span>Detail Akreditasi</span>
                    </a>
                </nav>
            </div>

            <!-- Logout -->
            <div class="mt-6 px-4 space-y-2">
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
                        <?= esc(get_setting('logout_button_text', 'Logout')) ?>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</body>

</html>

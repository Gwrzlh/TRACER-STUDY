<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="p-6 bg-gray-50 min-h-screen">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Pengguna</h2>

    <!-- Tombol Tambah Pengguna -->
    <button onclick="window.location.href='<?= base_url('/admin/pengguna/tambahPengguna') ?>'"
        class="px-5 py-3 bg-blue-800 text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all duration-300 mb-6 shadow-lg hover:bg-blue-900 hover:shadow-xl hover:-translate-y-0.5">
        + Tambah Pengguna
    </button>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2 mb-6">
        <!-- Tombol Semua -->
        <a href="<?= base_url('/admin/pengguna') ?>"
           class="px-4 py-2 rounded-full text-sm font-medium text-decoration-none transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md <?= ($roleId == null) ? 'bg-blue-800 text-white shadow-md' : 'bg-gray-200 text-gray-600 border border-gray-300 hover:bg-blue-800 hover:text-white' ?>">
            Semua <?php if (isset($counts['all'])): ?>(<?= $counts['all'] ?>)<?php endif; ?>
        </a>

        <!-- Tombol per Role -->
        <?php foreach ($roles as $r): ?>
            <a href="<?= base_url('/admin/pengguna?role=' . $r['id']) ?>"
               class="px-4 py-2 rounded-full text-sm font-medium text-decoration-none transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md <?= ($roleId == $r['id']) ? 'bg-blue-800 text-white shadow-md' : 'bg-gray-200 text-gray-600 border border-gray-300 hover:bg-blue-800 hover:text-white' ?>">
                <?= esc($r['nama']) ?> <?php if (isset($counts[$r['id']])): ?>(<?= $counts[$r['id']] ?>)<?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Search Form -->
    <form method="get" action="<?= base_url('admin/pengguna') ?>" class="mb-6">
        <?php if ($roleId): ?>
            <input type="hidden" name="role" value="<?= esc($roleId) ?>">
        <?php endif; ?>

        <div class="flex gap-3 items-center">
            <div class="relative">
                <input type="text" 
                       name="keyword" 
                       value="<?= esc($keyword ?? '') ?>" 
                       placeholder="Cari nama pengguna..." 
                       class="w-64 px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 focus:w-80">
            </div>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-800 text-white border-none rounded-full text-sm font-medium cursor-pointer transition-all duration-200 hover:bg-blue-900 hover:shadow-md">
                Cari
            </button>
            <?php if (!empty($keyword)): ?>
                <a href="<?= base_url('admin/pengguna' . ($roleId ? '?role=' . $roleId : '')) ?>" 
                   class="px-4 py-2 bg-gray-500 text-white border-none rounded-full text-sm font-medium cursor-pointer transition-all duration-200 hover:bg-gray-600">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>

            <!-- Pagination atau Info -->
            <?php if (!empty($keyword)): ?>
                <div class="px-6 py-4 bg-gray-50 border-t">
                    <p class="text-sm text-gray-600">
                        Menampilkan <?= count($filteredData) ?> hasil untuk pencarian: "<strong><?= esc($keyword) ?></strong>"
                        <?php if ($roleId): ?>
                            dalam role: <strong><?= esc(array_column($roles, 'nama', 'id')[$roleId] ?? 'Unknown') ?></strong>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="p-12 text-center text-gray-600">
                <div class="mb-4">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pengguna ditemukan</h3>
                <p class="text-sm text-gray-500 mb-4">
                    <?php if (!empty($keyword)): ?>
                        Tidak ada pengguna yang cocok dengan pencarian "<?= esc($keyword) ?>"
                        <?php if ($roleId): ?>
                            dalam role "<?= esc(array_column($roles, 'nama', 'id')[$roleId] ?? 'Unknown') ?>"
                        <?php endif; ?>
                    <?php elseif ($roleId != null): ?>
                        <?php 
                        $selectedRole = array_filter($roles, function($role) use ($roleId) {
                            return $role['id'] == $roleId;
                        });
                        $selectedRole = array_values($selectedRole);
                        ?>
                        Belum ada pengguna dengan role "<?= esc($selectedRole[0]['nama'] ?? 'Unknown') ?>" saat ini.
                    <?php else: ?>
                        Belum ada pengguna yang terdaftar dalam sistem.
                    <?php endif; ?>
                </p>
                <?php if (!empty($keyword) || $roleId): ?>
                    <a href="<?= base_url('admin/pengguna') ?>" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                        Lihat Semua Pengguna
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('/admin/pengguna/tambahPengguna') ?>" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                        + Tambah Pengguna Pertama
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>
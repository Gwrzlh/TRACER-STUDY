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

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <?php 
        // Filter data berdasarkan role yang dipilih
        $filteredData = $account;
        if ($roleId != null) {
            $filteredData = array_filter($account, function ($acc) use ($roleId) {
                return $acc['id_role'] == $roleId;
            });
        }
        ?>

        <?php if (!empty($filteredData)): ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <!-- Header -->
                    <thead>
                        <tr class="bg-gray-50 border-b-2 border-gray-200">
                            <th class="px-6 py-4 font-semibold text-sm text-gray-600 text-left uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 font-semibold text-sm text-gray-600 text-left uppercase tracking-wider">Nama Pengguna</th>
                            <th class="px-6 py-4 font-semibold text-sm text-gray-600 text-left uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 font-semibold text-sm text-gray-600 text-left uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 font-semibold text-sm text-gray-600 text-left uppercase tracking-wider">Group</th>
                            <th class="px-6 py-4 font-semibold text-sm text-gray-600 text-center uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <!-- Body -->
                    <tbody>
                        <?php $no = 1; foreach ($filteredData as $acc): ?>
                            <tr class="border-b border-gray-200 transition-all duration-200 hover:bg-gray-50 hover:shadow-sm">
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium"><?= $no++ ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-medium"><?= esc($acc['username']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= esc($acc['email']) ?></td>
                                <td class="px-6 py-4">
                                    <?php 
                                    $isActive = (strtolower($acc['status']) == 'active' || strtolower($acc['status']) == 'aktif' || $acc['status'] == '1');
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide <?= $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $isActive ? 'AKTIF' : 'TIDAK AKTIF' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        <?= esc($acc['nama_role']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="<?= base_url('/admin/pengguna/editPengguna/'. $acc['id'] ) ?>" 
                                           class="px-3 py-1.5 bg-blue-600 text-white border-none rounded text-xs font-medium cursor-pointer text-decoration-none inline-block transition-all duration-200 hover:bg-blue-700 hover:-translate-y-0.5 hover:shadow-md">
                                            Edit
                                        </a>
                                        <form action="<?= base_url('/admin/pengguna/delete/' . $acc['id']) ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus pengguna <?= esc($acc['username']) ?>?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" 
                                                    class="px-3 py-1.5 bg-red-600 text-white border-none rounded text-xs font-medium cursor-pointer transition-all duration-200 hover:bg-red-700 hover:-translate-y-0.5 hover:shadow-md">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

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
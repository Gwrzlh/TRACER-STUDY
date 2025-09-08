<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/questioner/index.css') ?>">

<div>
   <!-- Navbar -->
   <!-- Navbar -->
<nav class="sticky top-0 bg-white navbar-shadow nav-bg border-b border-gray-100 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <!-- Nav link kiri -->
            <div class="flex items-center gap-6">
                <span class="nav-title font-semibold text-xl cursor-pointer">
                    Daftar Kuesioner
                </span>
            </div>
            
            <!-- Optional: Tambahan elemen kanan jika diperlukan -->
            <div class="flex items-center gap-4">
                <!-- Indikator status -->
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            </div>
        </div>
    </div>
</nav>

    <!-- Main Card -->
    <div class="questionnaire-card">
        <div class="card-header flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="card-header-icon"></div>
                <h3 class="card-title">Daftar Kuesioner </h3>
            </div>
            <a href="<?= base_url('admin/questionnaire/create') ?>"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                + Buat Kuesioner Baru
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="questionnaire-table w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-gray-600 text-sm uppercase">
                    <tr>
                        <th class="px-6 py-3">Judul</th>
                        <th class="px-6 py-3">Deskripsi</th>
                        <th class="px-6 py-3">Conditional</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($questionnaires as $q): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            <?= esc($q['title']) ?>
                        </td>
                        <td class="px-6 py-4"><?= esc($q['deskripsi']) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($q['conditional_logic']): ?>
                                <span class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Ya</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-200 rounded-full">Tidak</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($q['is_active']): ?>
                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-4">
                                <!-- Kelola Halaman -->
                                <a href="<?= base_url('admin/questionnaire/' . $q['id'] . '/pages') ?>" 
                                   class="text-blue-600 hover:text-blue-800" 
                                   title="Kelola Halaman">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                                <!-- Edit -->
                                <a href="<?= base_url('admin/questionnaire/' . $q['id'] . '/edit') ?>" 
                                   class="text-yellow-600 hover:text-yellow-800" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Hapus -->
                                <button class="delete-questionnaire text-red-600 hover:text-red-800" 
                                        data-id="<?= $q['id'] ?>" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const baseUrl = "<?= base_url('admin/questionnaire') ?>";

    // SweetAlert hapus
    document.querySelectorAll(".delete-questionnaire").forEach(button => {
        button.addEventListener("click", function() {
            const id = this.getAttribute("data-id");

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data questionnaire beserta halaman & pertanyaan akan terhapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `${baseUrl}/${id}/delete`;
                }
            });
        });
    });
});
</script>

<?= $this->endSection() ?>

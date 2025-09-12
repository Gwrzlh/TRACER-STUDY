<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/questioner/section/index.css') ?>">

<div>
    <!-- Navbar -->
    <nav class="sticky top-0 bg-white navbar-shadow nav-bg border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <a href="<?= base_url('admin/questionnaire') ?>"
                       class="nav-link font-semibold text-lg cursor-pointer">
                        Daftar Kuesioner
                    </a>
                    <a href="<?= base_url('admin/questionnaire/' . $questionnaire['id'] . '/pages') ?>"
                       class="nav-link font-semibold text-lg cursor-pointer">
                        <?= esc($questionnaire['title']) ?>
                    </a>
                    <span class="nav-title font-semibold text-xl cursor-pointer">
                        Data Pribadi
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Card -->
    <div class="questionnaire-card mt-4">
        <div class="card-header flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="card-header-icon"></div>
                <h3 class="card-title">Sunting Kuesioner Section</h3>
            </div>
            <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/create") ?>"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                + Tambah Section
            </a>
        </div>

        <?php if (empty($sections)): ?>
            <div class="p-8 text-center">
                <i class="fas fa-layer-group fa-3x text-gray-400 mb-3"></i>
                <h5 class="text-gray-500 font-medium">Belum ada section</h5>
                <p class="text-gray-400">Mulai dengan menambahkan section pertama untuk halaman ini.</p>
                <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/create") ?>"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    + Tambah Section Pertama
                </a>
            </div>
        <?php else: ?>
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="questionnaire-table w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-gray-600 text-sm uppercase">
                        <tr>
                            <th class="px-6 py-3">Section ID</th>
                            <th class="px-6 py-3">Section Name</th>
                            <th class="px-6 py-3">Description</th>
                            <th class="px-6 py-3">Conditional Logic</th>
                            <th class="px-6 py-3">Num of Question</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sections as $section): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full">
                                    <?= $section['id'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <?= esc($section['section_title']) ?><br>
                                <small class="text-gray-500">
                                    <i class="fas fa-eye me-1"></i>Show Title: <?= $section['show_section_title'] ? 'Yes' : 'No' ?>
                                    <i class="fas fa-align-left ms-2 me-1"></i>Show Desc: <?= $section['show_section_description'] ? 'Yes' : 'No' ?>
                                </small>
                            </td>
                            <td class="px-6 py-4 truncate max-w-xs" title="<?= esc($section['section_description']) ?>">
                                <?= esc($section['section_description']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-200 rounded-full">none</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                    <?= $section['question_count'] ?? 0 ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($section['conditional_status'] == 'Active'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-200 rounded-full">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    <!-- Move Up -->
                                    <button class="text-gray-500 hover:text-gray-700 move-up-btn" data-section-id="<?= $section['id'] ?>" title="Move Up">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <!-- Move Down -->
                                    <button class="text-gray-500 hover:text-gray-700 move-down-btn" data-section-id="<?= $section['id'] ?>" title="Move Down">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                    <!-- Manage Questions -->
                                    <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/questions") ?>"
                                       class="text-blue-600 hover:text-blue-800" title="Manage Questions">
                                        <i class="fas fa-cogs"></i>
                                    </a>
                                    <!-- Edit -->
                                    <a href="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/edit") ?>"
                                       class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Delete -->
                                    <form action="<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section['id']}/delete") ?>"
                                          method="post" onsubmit="return confirm('Yakin ingin menghapus section ini dan semua pertanyaannya?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('.move-up-btn').on('click', function() {
    const sectionId = $(this).data('section-id');
    $.post('<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>/' + sectionId + '/moveUp', {
        section_id: sectionId,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) location.reload();
        else alert('Gagal memindahkan section');
    }, 'json');
});

$('.move-down-btn').on('click', function() {
    const sectionId = $(this).data('section-id');
    $.post('<?= base_url("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections") ?>/' + sectionId + '/moveDown', {
        section_id: sectionId,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) location.reload();
        else alert('Gagal memindahkan section');
    }, 'json');
});
</script>

<?= $this->endSection() ?>

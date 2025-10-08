<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/kontak.css') ?>">

<div class="flex-1 overflow-y-auto bg-gray-50">
    <div class="max-w-7xl mx-auto px-8 py-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Kontak</h1>
        </div>

        <!-- Search Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-sm font-semibold text-gray-700">PENCARIAN KONTAK</h2>
            </div>
            <div class="p-6">
                <form id="searchForm" class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-3">
                        <label class="block font-medium text-gray-700 text-sm mb-2">Pilih Kategori</label>
                        <select name="kategori" id="kategori" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                            <option value="">-- Pilih --</option>
                            <option value="Surveyor">Surveyor</option>
                            <option value="Tim Tracer">Tim Tracer</option>
                            <option value="Wakil Direktur">Wakil Direktur</option>
                        </select>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label class="block font-medium text-gray-700 text-sm mb-2">Cari</label>
                        <input type="text" id="keyword" name="keyword" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Masukkan NIM / Nama" required>
                    </div>
                    <div class="col-span-12 md:col-span-3 flex items-end">
                        <button type="submit" class="w-full px-5 py-2.5 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-all">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Hasil Pencarian -->
        <div id="searchResult" class="mb-6" style="display:none;">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-700">HASIL PENCARIAN</h2>
                </div>
                <div class="p-6">
                    <form id="addForm" method="post" action="<?= site_url('admin/kontak/store-multiple') ?>">
                        <input type="hidden" name="kategori" id="addKategori">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr id="searchCols">
                                        <!-- Kolom akan diisi JS sesuai kategori -->
                                    </tr>
                                </thead>
                                <tbody id="resultBody"></tbody>
                            </table>
                        </div>
                        <button type="submit" class="mt-4 px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-all">
                            Tambah ke Kontak
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Contact Panel Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            
            <!-- Panel Header -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-sm font-semibold text-gray-700">DAFTAR KONTAK</h2>
            </div>

            <!-- Panel Content -->
            <div class="p-6 space-y-6">
                
                <?php
                $categories = [
                    'Surveyor' => ['data' => $surveyors, 'cols' => ['Nama', 'NIM', 'No.Telp', 'Email', 'Prodi', 'Jurusan', 'Tahun Lulus'], 'fields' => ['nama_lengkap', 'nim', 'notlp', 'email', 'nama_prodi', 'nama_jurusan', 'tahun_kelulusan']],
                    'Tim Tracer' => ['data' => $teamTracer, 'cols' => ['Nama', 'Email'], 'fields' => ['nama_lengkap', 'email']],
                    'Wakil Direktur' => ['data' => $wakilDirektur, 'cols' => ['Nama', 'Email'], 'fields' => ['nama_lengkap', 'email']],
                ];

                foreach ($categories as $title => $cat):
                ?>
                    <!-- Individual Category Card -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Category Header -->
                        <div class="px-5 py-3 bg-gray-100 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-800"><?= $title ?></h3>
                        </div>
                        
                        <!-- Category Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <?php foreach ($cat['cols'] as $col): ?>
                                            <th class="px-5 py-3 text-left font-semibold text-gray-700 text-xs uppercase tracking-wider"><?= $col ?></th>
                                        <?php endforeach; ?>
                                        <th class="px-5 py-3 text-center font-semibold text-gray-700 text-xs uppercase tracking-wider w-28">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php if (empty($cat['data'])): ?>
                                        <tr>
                                            <td colspan="<?= count($cat['cols']) + 1 ?>" class="px-5 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                    </svg>
                                                    <span class="text-sm">Belum ada data kontak</span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($cat['data'] as $row): ?>
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <?php foreach ($cat['fields'] as $field): ?>
                                                    <td class="px-5 py-3 text-gray-700"><?= $row[$field] ?? '-' ?></td>
                                                <?php endforeach; ?>
                                                <td class="px-5 py-3 text-center">
                                                    <form method="post" action="<?= site_url('admin/kontak/delete/' . $row['kontak_id']) ?>" class="deleteForm inline-block">
                                                        <button type="button" class="btnDelete px-4 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-600 hover:text-white border border-red-200 hover:border-red-600 transition-all">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script Ajax -->
<script>
$(function() {
    const colMap = {
        'Surveyor': ['Pilih', 'Nama', 'NIM', 'Email', 'No.Telp', 'Prodi', 'Jurusan', 'Tahun Lulus'],
        'Tim Tracer': ['Pilih', 'Nama', 'Email'],
        'Wakil Direktur': ['Pilih', 'Nama', 'Email', 'No.Telp']
    };

    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        let kategori = $('#kategori').val();
        let keyword = $('#keyword').val();

        $.get("<?= site_url('admin/kontak/search') ?>", {
            kategori,
            keyword
        }, function(res) {
            if (!res || res.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Ditemukan',
                    text: 'Data tidak ditemukan!',
                    confirmButtonColor: '#3b82f6'
                });
                $('#searchResult').hide();
                return;
            }

            // Set kolom tabel sesuai kategori
            let th = '';
            colMap[kategori].forEach(c => {
                th += `<th class="px-5 py-3 text-left font-semibold text-gray-700 text-xs uppercase tracking-wider">${c}</th>`;
            });
            $('#searchCols').html(th);

            // Set isi tbody
            let html = '';
            res.forEach(r => {
                html += '<tr class="border-b border-gray-100 hover:bg-gray-50">';
                html += `<td class="px-5 py-3"><input type="checkbox" name="id_account[]" value="${r.id_account}" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"></td>`;
                html += `<td class="px-5 py-3 text-gray-700">${r.nama_lengkap ?? '-'}</td>`;
                if (kategori === 'Surveyor') {
                    html += `<td class="px-5 py-3 text-gray-700">${r.nim ?? '-'}</td>`;
                    html += `<td class="px-5 py-3 text-gray-700">${r.email ?? '-'}</td>`;
                    html += `<td class="px-5 py-3 text-gray-700">${r.notlp ?? '-'}</td>`;
                    html += `<td class="px-5 py-3 text-gray-700">${r.nama_prodi ?? '-'}</td>`;
                    html += `<td class="px-5 py-3 text-gray-700">${r.nama_jurusan ?? '-'}</td>`;
                    html += `<td class="px-5 py-3 text-gray-700">${r.tahun_kelulusan ?? '-'}</td>`;
                } else if (kategori === 'Tim Tracer') {
                    html += `<td class="px-5 py-3 text-gray-700">${r.email ?? '-'}</td>`;
                } else if (kategori === 'Wakil Direktur') {
                    html += `<td class="px-5 py-3 text-gray-700">${r.email ?? '-'}</td>`;
                    html += `<td class="px-5 py-3 text-gray-700">${r.notlp ?? '-'}</td>`;
                }
                html += '</tr>';
            });
            $('#resultBody').html(html);
            $('#addKategori').val(kategori);
            $('#searchResult').show();
        }, 'json');
    });

    // Delete confirmation
    $(document).on('click', '.btnDelete', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            icon: 'warning',
            title: 'Yakin hapus?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }); 
});
</script>

<?= $this->endSection() ?>
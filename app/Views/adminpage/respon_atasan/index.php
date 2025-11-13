<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/respon/atasan.css') ?>">

<div class="page-header mb-4">
    <div class="header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
    </div>
    <h2 class="header-title">Respon Kuesioner Atasan</h2>
</div>

    <!-- Filter Form -->
    <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Filter Jabatan -->
        <div>
            <select name="jabatan" class="w-full border rounded p-2">
                <option value="">-- Semua Jabatan --</option>
                <?php foreach ($jabatanList as $j): ?>
                    <option value="<?= $j['id'] ?>" <?= ($filters['jabatan'] == $j['id']) ? 'selected' : '' ?>>
                        <?= esc($j['jabatan']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Filter Status -->
        <div>
            <select name="status" class="w-full border rounded p-2">
                <option value="">-- Semua Status --</option>
                <option value="finish" <?= ($filters['status'] == 'finish') ? 'selected' : '' ?>>Selesai</option>
                <option value="pending" <?= ($filters['status'] == 'pending') ? 'selected' : '' ?>>Belum Selesai</option>
                <option value="invalid" <?= ($filters['status'] == 'invalid') ? 'selected' : '' ?>>Tidak Valid</option>
            </select>
        </div>

        <!-- Tombol -->
        <div class="flex gap-2 items-center">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                 Terapkan
            </button>
            <a href="<?= base_url('admin/respon/atasan') ?>" 
               class="bg-gray-300 px-3 py-2 rounded hover:bg-gray-400 text-gray-800">
               Reset
            </a>
        </div>
    </form>

    <!-- Tabel Data -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left border border-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nama Atasan</th>
                    <th class="px-4 py-2 border">Jabatan</th>
                    <th class="px-4 py-2 border">Username</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Kuesioner</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Update Terakhir</th>
                    <th class="px-4 py-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($responses)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">Belum ada data respon.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($responses as $i => $res): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2"><?= $i + 1 ?></td>
                            <td class="px-4 py-2"><?= esc($res['nama_lengkap'] ?? '-') ?></td>
                            <td class="px-4 py-2"><?= esc($res['jabatan'] ?? '-') ?></td>
                            <td class="px-4 py-2"><?= esc($res['username'] ?? '-') ?></td>
                            <td class="px-4 py-2"><?= esc($res['email'] ?? '-') ?></td>
                            <td class="px-4 py-2"><?= esc($res['nama_kuesioner'] ?? '-') ?></td>
                            <td class="px-4 py-2">
                                <?php if ($res['status'] == 'finish'): ?>
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Selesai</span>
                                <?php elseif ($res['status'] == 'pending'): ?>
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Belum Selesai</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Tidak Valid</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2">
                                <?php
                                    $dt = new DateTime($res['updated_at'], new DateTimeZone('UTC'));
                                    $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                    echo $dt->format('d M Y H:i');
                                ?>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a href="<?= base_url('admin/respon/atasan/detail/' . $res['id']) ?>"
                                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs">Detail</a>
                                <button type="button" 
                                    onclick="confirmDelete('<?= base_url('admin/respon/atasan/delete/' . $res['id']) ?>')" 
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Konfirmasi hapus pakai SweetAlert2
function confirmDelete(url) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

// Notifikasi sukses jika ada flashdata
<?php if (session()->getFlashdata('success')): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '<?= session()->getFlashdata('success') ?>',
    showConfirmButton: false,
    timer: 2000
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>

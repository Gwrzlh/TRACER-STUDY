<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">📋 Respon Kuesioner Atasan</h2>

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
                🔍 Terapkan
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
                                    // Ambil updated_at dari DB (diasumsikan UTC) dan set timezone Asia/Jakarta
                                    $dt = new DateTime($res['updated_at'], new DateTimeZone('UTC'));
                                    $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                                    echo $dt->format('d M Y H:i');
                                ?>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a href="<?= base_url('admin/respon/atasan/detail/' . $res['id']) ?>"
                                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs">Detail</a>
                                <a href="<?= base_url('admin/respon/atasan/delete/' . $res['id']) ?>"
                                   onclick="return confirm('Yakin ingin menghapus data ini?')"
                                   class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

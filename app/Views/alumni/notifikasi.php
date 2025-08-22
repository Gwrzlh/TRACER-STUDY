<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">Notifikasi Pesan</h2>

    <!-- Flash message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-3 mb-4 bg-green-100 text-green-700 rounded">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="p-3 mb-4 bg-red-100 text-red-700 rounded">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($pesan)): ?>
        <p class="text-gray-600">Belum ada pesan masuk.</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($pesan as $p): ?>
                <div class="p-4 border rounded-lg flex justify-between items-center
          <?= $p['status'] === 'terkirim' ? 'bg-yellow-50' : 'bg-gray-50' ?>">

                    <!-- Pesan -->
                    <div>
                        <p class="text-gray-800 font-medium">
                            <?= esc($p['pesan']) ?>
                        </p>
                        <p class="text-sm text-gray-500">
                            Dari: Alumni Surveyor #<?= esc($p['id_pengirim']) ?> |
                            <?= date('d M Y H:i', strtotime($p['created_at'])) ?>
                        </p>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex space-x-2">
                        <?php if ($p['status'] === 'terkirim'): ?>
                            <a href="<?= base_url('alumni/notifikasi/tandai/' . $p['id_pesan']) ?>"
                                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                Tandai sudah dibaca
                            </a>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-green-200 text-green-800 text-xs rounded-full">
                                Sudah dibaca
                            </span>
                        <?php endif; ?>

                        <a href="<?= base_url('alumni/notifikasi/hapus/' . $p['id_pesan']) ?>"
                            onclick="return confirm('Yakin ingin menghapus pesan ini?')"
                            class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                            Hapus
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
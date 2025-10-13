<?= $this->extend('layout/sidebar_kaprodi') ?>
<?= $this->section('content') ?>
<link href="<?= base_url('css/kaprodi/alumni.css') ?>" rel="stylesheet">

<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-700 mb-4">
        <i class="fa fa-users mr-2 text-blue-500"></i> Daftar Alumni
    </h2>
    <a href="<?= base_url('kaprodi/alumni/export') ?>"
        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md shadow">
        <i class="fa fa-file-excel mr-1"></i> Unduh Excel
    </a>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="py-2 px-4 border">No</th>
                    <th class="py-2 px-4 border">Username</th>
                    <th class="py-2 px-4 border">Email</th>
                    <th class="py-2 px-4 border">Nama Lengkap</th>
                    <th class="py-2 px-4 border">NIM</th>
                    <th class="py-2 px-4 border">Jenis Kelamin</th>
                    <th class="py-2 px-4 border">Tahun Masuk</th>
                    <th class="py-2 px-4 border">Tahun Lulus</th>
                    <th class="py-2 px-4 border">IPK</th>
                    <th class="py-2 px-4 border">No. HP</th>
                    <th class="py-2 px-4 border">Jurusan</th>
                    <th class="py-2 px-4 border">Prodi</th>
                    <th class="py-2 px-4 border">Provinsi</th>
                    <th class="py-2 px-4 border">Kota/Kabupaten</th>
                    <th class="py-2 px-4 border">Alamat 1</th>
                    <th class="py-2 px-4 border">Alamat 2</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($alumni)): ?>
                    <?php $no = 1;
                    foreach ($alumni as $a): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border text-center"><?= $no++ ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['username'] ?? '-') ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['email'] ?? '-') ?></td>
                            <td class="py-2 px-4 border font-medium"><?= esc($a['nama_lengkap'] ?? '-') ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['nim'] ?? '-') ?></td>
                            <td class="py-2 px-4 border text-center"><?= esc($a['jenisKelamin'] ?? '-') ?></td>
                            <td class="py-2 px-4 border text-center"><?= esc($a['angkatan'] ?? '-') ?></td>
                            <td class="py-2 px-4 border text-center"><?= esc($a['tahun_kelulusan'] ?? '-') ?></td>
                            <td class="py-2 px-4 border text-center"><?= esc($a['ipk'] ?? '-') ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['notlp'] ?? '-') ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['nama_jurusan'] ?? '-') ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['nama_prodi'] ?? '-') ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['provinsi'] ?? '-') ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['kota'] ?? '-') ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['alamat'] ?? '-') ?></td>
                            <td class="py-2 px-4 border"><?= esc($a['alamat2'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="16" class="text-center py-4 text-gray-500">Belum ada data alumni.</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
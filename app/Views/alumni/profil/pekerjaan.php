<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-4xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Tentang Pekerjaan</h2>

    <form action="<?= base_url('alumni/profil/pekerjaan/save') ?>" method="post">
        <?= csrf_field() ?>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Perusahaan</label>
                <input type="text" name="perusahaan" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div>
                <label class="block font-medium">Jabatan</label>
                <input type="text" name="jabatan" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div>
                <label class="block font-medium">Tahun Masuk</label>
                <input type="number" name="tahun_masuk" class="w-full border px-3 py-2 rounded" required>
            </div>

            <!-- Status kerja -->
            <div>
                <label class="block font-medium mb-1">Status Kerja</label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status_kerja" value="masih" checked onclick="toggleTahunKeluar(false)">
                        Masih Bekerja
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status_kerja" value="hingga" onclick="toggleTahunKeluar(true)">
                        Hingga Tahun
                    </label>
                </div>
                <input type="number" name="tahun_keluar" id="tahun_keluar" class="w-full border px-3 py-2 rounded mt-2" placeholder="Tahun Keluar" disabled>
            </div>

            <div class="col-span-2">
                <label class="block font-medium">Alamat Perusahaan</label>
                <textarea name="alamat_perusahaan" class="w-full border px-3 py-2 rounded"></textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>

    </form>
    <div class="flex items-center justify-between mb-4">
        <a href="<?= base_url('alumni/profil') ?>"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            Kembali ke Profil
        </a>
    </div>

    <script>
        function toggleTahunKeluar(enable) {
            const tahunKeluarInput = document.getElementById('tahun_keluar');
            tahunKeluarInput.disabled = !enable;
            if (!enable) {
                tahunKeluarInput.value = '';
            }
        }
    </script>
    <?= $this->endSection() ?>
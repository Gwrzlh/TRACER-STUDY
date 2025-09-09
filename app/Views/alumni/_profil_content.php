<!-- Profil Alumni (View Mode) Futuristik tanpa blur -->
<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex items-center mb-6">
        <img src="<?= base_url('images/logo.png') ?>" alt="Tracer Study" class="h-10 mr-3">
        <h2 class="text-xl font-bold">Profil</h2>
    </div>

    <!-- Foto Profil -->
    <div class="flex items-center mb-6">
        <div class="relative group">
            <img id="profileFoto"
                src="<?= $alumni->foto ? base_url('uploads/foto_alumni/' . $alumni->foto) : base_url('uploads/default.png') ?>"
                alt="Foto Profil"
                class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-white cursor-pointer transition-transform duration-300 group-hover:scale-105">

            <!-- Tombol Ubah Foto Futuristik -->
            <div onclick="openChoice()"
                class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full shadow-lg cursor-pointer opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-700 hover:animate-spin-slow"
                title="Ubah Foto Profil">
                <i class="fa-solid fa-camera"></i>
            </div>
        </div>

        <div class="ml-6">
            <p class="text-lg font-semibold">Nama : <?= $alumni->nama_lengkap ?></p>
            <p class="text-gray-600">NIM : <?= $alumni->nim ?></p>
            <p class="text-gray-600">
                Program Studi : <?= isset($prodiMap[$alumni->id_prodi]) ? $prodiMap[$alumni->id_prodi] : 'Belum ada' ?>
            </p>
            <p class="text-gray-600">
                Jurusan : <?= isset($jurusanMap[$alumni->id_jurusan]) ? $jurusanMap[$alumni->id_jurusan] : 'Belum ada' ?>
            </p>
        </div>
    </div>

    <!-- Modal Success Message -->
    <div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl text-center w-80 relative">
            <button onclick="closeSuccess()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl">×</button>
            <div class="mb-4">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Success message</h3>
                <p class="text-gray-600 mt-2">Foto berhasil diperbarui!</p>
            </div>
        </div>
    </div>

    <!-- Modal Pilihan Upload/Kamera -->
    <div id="choiceModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center space-y-4 w-80">
            <h3 class="text-lg font-bold">Pilih Opsi</h3>
            <button onclick="document.getElementById('fotoInput').click()"
                class="bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700">
                Upload dari File
            </button>
            <button onclick="openCamera()"
                class="bg-green-600 text-white px-4 py-2 rounded w-full hover:bg-green-700">
                Ambil dari Kamera
            </button>
            <button onclick="closeChoice()"
                class="bg-gray-400 text-white px-4 py-2 rounded w-full hover:bg-gray-500">
                Batal
            </button>
        </div>
    </div>

    <!-- Modal Kamera -->
    <div id="cameraModal"
        class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-xl text-center w-[400px] relative">
            <h3 class="text-lg font-bold mb-4">Ambil Foto Profil</h3>

            <div class="relative mx-auto w-60 h-60 rounded-full overflow-hidden shadow-lg border-4 border-gray-200">
                <video id="video" autoplay playsinline class="absolute inset-0 w-full h-full object-cover"></video>
                <canvas id="canvas" class="hidden"></canvas>
            </div>

            <div class="mt-5 flex justify-center gap-3">
                <button onclick="takeSnapshot()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-full shadow">
                    📸 Ambil
                </button>
                <button onclick="closeCamera()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-full shadow">
                    ✖ Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Form Upload Foto -->
    <form id="uploadForm" method="post" enctype="multipart/form-data"
        action="<?= base_url('alumni/updateFoto/' . $alumni->id_account) ?>" class="hidden">
        <input type="file" name="foto" id="fotoInput" accept="image/*" onchange="previewFile(this)">
        <input type="hidden" name="foto_camera" id="fotoCamera">
    </form>

    <!-- Data Diri -->
    <h3 class="text-lg font-bold mb-3">Data Diri</h3>
    <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
        <tbody>
            <tr>
                <td class="px-4 py-2 border font-medium">NIK</td>
                <td class="px-4 py-2 border"><?= $alumni->nik ?? 'Belum ada' ?></td>
            </tr>
            <tr>
                <td class="px-4 py-2 border font-medium">NPWP</td>
                <td class="px-4 py-2 border"><?= $alumni->npwp ?? 'Belum ada' ?></td>
            </tr>
            <tr>
                <td class="px-4 py-2 border font-medium">Alamat</td>
                <td class="px-4 py-2 border"><?= $alumni->alamat ?? 'Belum ada' ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Riwayat Pekerjaan -->
    <h3 class="text-lg font-bold mt-8 mb-3">Riwayat Pekerjaan</h3>
    <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="px-4 py-2 border">Tahun Masuk</th>
                <th class="px-4 py-2 border">Tahun Selesai</th>
                <th class="px-4 py-2 border">Perusahaan</th>
                <th class="px-4 py-2 border">Alamat</th>
                <th class="px-4 py-2 border">No Telepon</th>
                <th class="px-4 py-2 border">Jabatan</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center">
                <td class="px-4 py-2 border">2020</td>
                <td class="px-4 py-2 border">2022</td>
                <td class="px-4 py-2 border">PT Sejahtera</td>
                <td class="px-4 py-2 border">Jakarta</td>
                <td class="px-4 py-2 border">021-123456</td>
                <td class="px-4 py-2 border">Staff IT</td>
            </tr>
            <tr class="text-center">
                <td class="px-4 py-2 border">2023</td>
                <td class="px-4 py-2 border">Sekarang</td>
                <td class="px-4 py-2 border">PT Teknologi Maju</td>
                <td class="px-4 py-2 border">Bandung</td>
                <td class="px-4 py-2 border">022-654321</td>
                <td class="px-4 py-2 border">Programmer</td>
            </tr>
        </tbody>
    </table>

    <!-- Tombol Aksi -->
    <div class="mt-6 flex justify-end gap-4">
        <a href="<?= base_url('alumni/profil/edit') ?>"
            class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Edit Profil
        </a>
    </div>
</div>

<style>
    #profileFoto {
        width: 128px;
        height: 128px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s;
    }

    /* Animasi tombol kamera */
    .animate-spin-slow {
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }
</style>

<script>
    let videoStream;

    function openChoice() {
        document.getElementById('choiceModal').classList.remove('hidden');
    }

    function closeChoice() {
        document.getElementById('choiceModal').classList.add('hidden');
    }

    function showSuccess() {
        document.getElementById('successModal').classList.remove('hidden');
        setTimeout(() => {
            closeSuccess();
        }, 3000);
    }

    function closeSuccess() {
        document.getElementById('successModal').classList.add('hidden');
    }

    function openCamera() {
        closeChoice();
        document.getElementById('cameraModal').classList.remove('hidden');
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => {
                videoStream = stream;
                document.getElementById('video').srcObject = stream;
            })
            .catch(err => alert("Tidak dapat mengakses kamera"));
    }

    function closeCamera() {
        document.getElementById('cameraModal').classList.add('hidden');
        if (videoStream) videoStream.getTracks().forEach(track => track.stop());
    }

    function takeSnapshot() {
        const canvas = document.getElementById('canvas');
        const video = document.getElementById('video');
        const size = 300;
        canvas.width = size;
        canvas.height = size;

        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, size, size);
        ctx.save();
        ctx.beginPath();
        ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2, true);
        ctx.closePath();
        ctx.clip();

        const scale = Math.max(size / video.videoWidth, size / video.videoHeight);
        const w = video.videoWidth * scale;
        const h = video.videoHeight * scale;
        const x = (size - w) / 2;
        const y = (size - h) / 2;
        ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight, x, y, w, h);
        ctx.restore();

        const dataUrl = canvas.toDataURL("image/png");
        document.getElementById('profileFoto').src = dataUrl;

        // 🔹 Update Sidebar Foto
        if (typeof updateSidebarFoto === 'function') updateSidebarFoto(dataUrl);

        closeCamera();
        setTimeout(() => {
            showSuccess();
        }, 500);
    }

    function previewFile(input) {
        const file = input.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profileFoto').src = e.target.result;

            // 🔹 Update Sidebar Foto
            if (typeof updateSidebarFoto === 'function') updateSidebarFoto(e.target.result);

            setTimeout(() => {
                showSuccess();
            }, 500);
        }
        reader.readAsDataURL(file);
    }
</script>
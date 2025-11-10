<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3 class="mb-4 fw-bold text-primary">👥 Tambah Alumni ke Perusahaan Anda</h3>

    <!-- 🔔 Notifikasi -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <p class="text-muted mb-3">
        Gunakan kolom pencarian atau filter di bawah untuk mencari alumni berdasarkan kriteria tertentu.
        Anda bisa mengetik sebagian nama dan hasil akan muncul otomatis.
    </p>

    <!-- 🔍 FORM FILTER -->
    <form method="get" action="<?= base_url('atasan/perusahaan/tambah-alumni') ?>" class="row g-2 align-items-end mb-4">

        <!-- 🔤 Search umum -->
        <div class="col-md-3">
            <label class="form-label fw-semibold">Kata Kunci</label>
            <input type="text" id="liveSearch" name="search" class="form-control"
                   placeholder="Cari nama, NIM, email, atau alamat..."
                   value="<?= esc($keyword ?? '') ?>" autocomplete="off">
            <ul id="suggestionBox" class="list-group position-absolute w-100 shadow-sm"
                style="z-index:1000; display:none; max-height:250px; overflow-y:auto;"></ul>
        </div>

        <!-- 🎓 Tahun Kelulusan -->
        <div class="col-md-2">
            <label class="form-label fw-semibold">Tahun lulus</label>
            <select name="tahun" class="form-select">
                <option value="">-- Semua --</option>
                <?php foreach ($tahunList as $t): ?>
                    <option value="<?= esc($t['tahun_kelulusan']) ?>" <?= ($tahun == $t['tahun_kelulusan']) ? 'selected' : '' ?>>
                        <?= esc($t['tahun_kelulusan']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- 🏫 Angkatan -->
        <div class="col-md-2">
            <label class="form-label fw-semibold">Tahun masuk</label>
            <select name="angkatan" class="form-select">
                <option value="">-- Semua --</option>
                <?php foreach ($angkatanList as $a): ?>
                    <option value="<?= esc($a['angkatan']) ?>" <?= ($angkatan == $a['angkatan']) ? 'selected' : '' ?>>
                        <?= esc($a['angkatan']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- 🚻 Jenis Kelamin -->
        <div class="col-md-2">
            <label class="form-label fw-semibold">Jenis Kelamin</label>
            <select name="jk" class="form-select">
                <option value="">-- Semua --</option>
                <option value="L" <?= ($jk == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= ($jk == 'P') ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>

        <!-- 🧑‍🎓 Jurusan -->
        <div class="col-md-3">
            <label class="form-label fw-semibold">Jurusan</label>
            <select name="jurusan" id="jurusanSelect" class="form-select">
                <option value="">-- Semua --</option>
                <?php foreach ($jurusanList as $j): ?>
                    <option value="<?= esc($j['id']) ?>" <?= ($jurusan == $j['id']) ? 'selected' : '' ?>>
                        <?= esc($j['nama_jurusan']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- 📚 Prodi -->
        <div class="col-md-3">
            <label class="form-label fw-semibold">Prodi</label>
            <select name="prodi" id="prodiSelect" class="form-select">
                <option value="">-- Semua --</option>
                <?php foreach ($prodiList as $p): ?>
                    <option value="<?= esc($p['id']) ?>" <?= ($prodi == $p['id']) ? 'selected' : '' ?>>
                        <?= esc($p['nama_prodi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- 🔘 Tombol -->
        <div class="col-md-12 d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-filter me-1"></i> Cari
            </button>
            <a href="<?= base_url('atasan/perusahaan/tambah-alumni') ?>" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset
            </a>
        </div>
    </form>

    <!-- ⏳ Loading Indicator -->
    <div id="loadingIndicator" class="text-center my-3" style="display:none;">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2 text-muted">Mencari data...</p>
    </div>

    <!-- 📋 Tabel Alumni -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="tableAlumni">
                    <thead class="table-primary text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Lengkap</th>
                            <th>NIM</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th>Jurusan</th>
                            <th>Prodi</th>
                            <th>Angkatan</th>
                            <th>Tahun Lulus</th>
                            <th>IPK</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($alumni)): ?>
                            <?php $no = 1; foreach ($alumni as $a): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= esc($a['nama_lengkap']) ?></td>
                                    <td><?= esc($a['nim'] ?? '-') ?></td>
                                    <td><?= esc($a['email'] ?? '-') ?></td>
                            <td class="text-center">
<?php 
    $jkText = '-';
    if (stripos($a['jenisKelamin'], 'laki') !== false) {
        $jkText = 'Laki-laki';
    } elseif (stripos($a['jenisKelamin'], 'p') !== false) {
        $jkText = 'Perempuan';
    }
    echo esc($jkText);
?>
</td>



                                    <td><?= esc($a['nama_jurusan'] ?? '-') ?></td>
                                    <td><?= esc($a['nama_prodi'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($a['angkatan'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($a['tahun_kelulusan'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($a['ipk'] ?? '-') ?></td>
                                    <td><?= esc($a['alamat'] ?? '-') ?></td>
                                    <td><?= esc($a['notlp'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php if (in_array($a['id'], $alumniSudah)): ?>
                                            <span class="badge bg-success">✔️ Sudah</span>
                                        <?php else: ?>
                                            <form action="<?= base_url('atasan/perusahaan/simpan-alumni/' . $a['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-primary shadow-sm"
                                                    onclick="return confirm('Tambahkan alumni ini ke perusahaan Anda?')">
                                                    ➕ Tambah
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="13" class="text-center text-muted">Tidak ada data alumni ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 💡 SCRIPT: Autocomplete & Dynamic Prodi -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("liveSearch");
    const suggestionBox = document.getElementById("suggestionBox");
    const tbody = document.querySelector("#tableAlumni tbody");
    const loading = document.getElementById("loadingIndicator");
    let delayTimer;

    // 🔍 Autocomplete
    input.addEventListener("keyup", function () {
        clearTimeout(delayTimer);
        const keyword = this.value.trim();
        if (keyword.length < 2) {
            suggestionBox.style.display = "none";
            return;
        }

        delayTimer = setTimeout(() => {
            $.ajax({
                url: "<?= base_url('atasan/perusahaan/suggestion-alumni') ?>",
                method: "GET",
                data: { q: keyword },
                dataType: "json",
                success: function (res) {
                    suggestionBox.innerHTML = "";
                    if (res.length > 0) {
                        res.forEach(item => {
                            suggestionBox.innerHTML += `
                                <li class="list-group-item list-group-item-action suggestion-item"
                                    data-nama="${item.nama_lengkap}">
                                    <i class="fa-solid fa-user text-primary me-2"></i> ${item.nama_lengkap}
                                    <small class="text-muted">(${item.nim ?? '-'})</small>
                                </li>`;
                        });
                        suggestionBox.style.display = "block";
                    } else {
                        suggestionBox.style.display = "none";
                    }
                }
            });
        }, 250);
    });

    // Klik suggestion → tampilkan hasil langsung
    $(document).on("click", ".suggestion-item", function () {
        const nama = $(this).data("nama");
        input.value = nama;
        suggestionBox.style.display = "none";

        loading.style.display = "block";
        $.ajax({
            url: "<?= base_url('atasan/perusahaan/search-alumni') ?>",
            method: "GET",
            data: { q: nama },
            dataType: "json",
            success: function (res) {
                tbody.innerHTML = res.html;
            },
            complete: function () {
                loading.style.display = "none";
            }
        });
    });

    // Klik di luar -> tutup suggestion
    document.addEventListener("click", function (e) {
        if (!input.contains(e.target) && !suggestionBox.contains(e.target)) {
            suggestionBox.style.display = "none";
        }
    });
});
</script>

<?= $this->endSection() ?>

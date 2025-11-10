<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<div class="container mt-4">

    <!-- 🏢 Detail Perusahaan -->
    <?php if (!empty($perusahaan)): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h4 class="fw-bold text-primary mb-2">
                    🏢 <?= esc($perusahaan['nama_perusahaan']) ?>
                </h4>
                <div class="text-secondary small">
                    <p class="mb-1"><strong>Alamat 1:</strong> <?= esc($perusahaan['alamat1'] ?? '-') ?></p>
                    <?php if (!empty($perusahaan['alamat2'])): ?>
                        <p class="mb-1"><strong>Alamat 2:</strong> <?= esc($perusahaan['alamat2']) ?></p>
                    <?php endif; ?>
                    <p class="mb-1"><strong>Kota:</strong> <?= esc($perusahaan['kota'] ?? '-') ?></p>
                    <p class="mb-1"><strong>Provinsi:</strong> <?= esc($perusahaan['provinsi'] ?? '-') ?></p>
                    <p class="mb-1"><strong>Kode Pos:</strong> <?= esc($perusahaan['kodepos'] ?? '-') ?></p>
                    <p class="mb-0"><strong>Telepon:</strong> <?= esc($perusahaan['noTlp'] ?? '-') ?></p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning shadow-sm">
            ⚠️ <strong>Perhatian!</strong> Data perusahaan belum ditemukan.
        </div>
    <?php endif; ?>

    <!-- 📊 Response Alumni -->
    <h3 class="fw-bold text-primary mb-4">📊 Response Alumni di Perusahaan Anda</h3>

    <!-- 🔔 Notifikasi -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <?php if (!empty($responses)): ?>
                <table class="table table-bordered table-hover align-middle" id="tableResponse">
                    <thead class="table-primary text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Alumni</th>
                            <th>ID Kuesioner</th>
                            <th>Status</th>
                            <th>Tanggal Dikirim</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($responses as $r): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= esc($r['nama_lengkap'] ?? '-') ?></td>
                                <td class="text-center"><?= esc($r['questionnaire_id'] ?? '-') ?></td>
                                <td class="text-center">
                                    <?php if ($r['status'] === 'completed'): ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php elseif ($r['status'] === 'draft'): ?>
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum Ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?= !empty($r['submitted_at']) 
                                        ? date('d M Y, H:i', strtotime($r['submitted_at'])) 
                                        : '-' ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($r['id_response'])): ?>
                                        <a href="<?= base_url('atasan/perusahaan/response-alumni/lihat/' . $r['id_response']) ?>" 
                                           class="btn btn-sm btn-info shadow-sm">
                                            👁️ Lihat
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled>-</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center p-4 rounded-4">
                    <h5 class="fw-semibold mb-2">Belum Ada Response</h5>
                    <p class="text-muted mb-0">Belum ada alumni yang mengisi kuesioner di perusahaan Anda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- 📈 DataTables -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("tableResponse");
    if (window.jQuery && $.fn.DataTable) {
        $(table).DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 20, 50],
            order: [[4, "desc"]],
            language: {
                search: "Cari Alumni:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                zeroRecords: "Tidak ada data ditemukan",
                paginate: {
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    }
});
</script>

<?= $this->endSection() ?>

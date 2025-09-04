<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/kuesioner/index.css') ?>">
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Daftar Kuesioner Alumni</h3>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 8%;">NO</th>
                                    <th style="width: 50%;">KUESIONER</th>
                                    <th style="width: 20%;">STATUS</th>
                                    <th style="width: 22%;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($data as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($row['judul']) ?></td>
                                        <td class="text-center">
                                            <?php if ($row['status'] == 'Belum Mengisi'): ?>
                                                <span class="badge bg-secondary">Belum Mengisi</span>
                                            <?php elseif ($row['status'] == 'On Going'): ?>
                                                <span class="badge bg-warning">On Going</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Finish</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['status'] == 'Belum Mengisi'): ?>
                                                <a href="<?= base_url('alumni/questionnaire/mulai/' . $row['id']) ?>" class="btn btn-primary btn-sm">Isi</a>
                                            <?php elseif ($row['status'] == 'On Going'): ?>
                                                <a href="<?= base_url('alumni/questionnaire/lanjutkan/' . $row['id']) ?>" class="btn btn-warning btn-sm">Lanjutkan</a>
                                            <?php else: ?>
                                                <a href="<?= base_url('alumni/questionnaire/lihat/' . $row['id']) ?>" class="btn btn-success btn-sm">Lihat Jawaban</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted">
                    <small>Menampilkan <?= count($data) ?> kuesioner</small>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const baseUrl = "<?= base_url('admin/questionnaire') ?>";

    // SweetAlert hapus
    document.querySelectorAll(".delete-questionnaire").forEach(button => {
        button.addEventListener("click", function() {
            const id = this.getAttribute("data-id");

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data questionnaire beserta halaman & pertanyaan akan terhapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `${baseUrl}/${id}/delete`;
                }
            });
        });
    });
});
</script>
<?= $this->endSection() ?>

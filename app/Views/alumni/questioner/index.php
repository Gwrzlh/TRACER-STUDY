<?= $this->extend('layout/sidebar_alumni') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/kuesioner/index.css') ?>">
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Daftar Questioner Alumni</h3>
            
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
                                <?php 
                                // Data dummy dengan status yang sesuai foto
                                $data = [
                                    [
                                        "id" => 1,
                                        "judul" => "Tracer Study Tahun 2025", 
                                        "status" => "Belum Mengisi"
                                    ],
                                    [
                                        "id" => 2,
                                        "judul" => "Survey Kepuasan Alumni 2024", 
                                        "status" => "On Going"
                                    ],
                                    [
                                        "id" => 3,
                                        "judul" => "Tracer Study Tahun 2024", 
                                        "status" => "Finish"
                                    ],
                                    [
                                        "id" => 4,
                                        "judul" => "Survey Alumni Prodi Teknik", 
                                        "status" => "Belum Mengisi"
                                    ],
                                    [
                                        "id" => 5,
                                        "judul" => "Evaluasi Kurikulum Alumni", 
                                        "status" => "Finish"
                                    ]
                                ];

                                $no = 1;
                                foreach ($data as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row["judul"] ?></td>
                                        <td class="text-center">
                                            <?php if ($row["status"] == "Belum Mengisi"): ?>
                                                <span class="badge bg-secondary">Belum Mengisi</span>
                                            <?php elseif ($row["status"] == "On Going"): ?>
                                                <span class="badge bg-warning">On Going</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Finish</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row["status"] == "Belum Mengisi"): ?>
                                                <a href="<?= base_url('alumni/questioner/mulai/' . $row['id']) ?>" class="btn btn-primary btn-sm">
                                                    Isi
                                                </a>
                                            <?php elseif ($row["status"] == "On Going"): ?>
                                                <a href="<?= base_url('alumni/questioner/lanjutkan/' . $row['id']) ?>" class="btn btn-warning btn-sm">
                                                    Lanjutkan
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('alumni/questioner/lihat/' . $row['id']) ?>" class="btn btn-success btn-sm">
                                                    Lihat Jawaban
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted">
                    <small>Menampilkan <?= count($data) ?> questioner</small>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>
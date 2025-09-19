<?php $layout = 'layout/layout_alumni'; ?>
<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/alumni/kuesioner/index.css') ?>">
<div class="questionnaire-container">
    <div class="page-wrapper">
        <div class="page-container">
            <div class="top-controls">
                <h3 class="page-title">Daftar Kuesioner Alumni</h3>
                <!-- Tombol ini bisa dihapus atau disesuaikan jika tidak diperlukan untuk alumni -->
                <!-- <a href="#" class="btn-add">Buat Kuesioner Baru</a> -->
            </div>
            <div class="table-container">
                <div class="table-wrapper">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KUESIONER</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            if (empty($data)): ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <div class="empty-state-title">Tidak ada kuesioner yang tersedia untuk Anda saat ini.</div>
                                        <div class="empty-state-description">Menampilkan <?= count($data ?? []) ?> kuesioner yang sesuai dengan profil Anda.</div>
                                    </td>
                                </tr>
                            <?php else: foreach ($data as $row): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= esc($row['judul']) ?></td>
                                    <td class="text-center">
                                        <?php if ($row['statusIsi'] == 'Belum Mengisi'): ?>
                                            <span class="status-badge belum-mengisi">Belum Mengisi</span>
                                        <?php elseif ($row['statusIsi'] == 'On Going'): ?>
                                            <span class="status-badge on-going">On Going
                                                <?php if (isset($row['progress']) && $row['progress'] > 0): ?>
                                                    (<?= round($row['progress'], 1) ?>%)
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge finish">Finish</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <?php if ($row['statusIsi'] == 'Belum Mengisi'): ?>
                                                <a href="<?= base_url('alumni/questionnaires/mulai/' . $row['id']) ?>" class="btn-action btn-mulai">▶</a>
                                            <?php elseif ($row['statusIsi'] == 'On Going'): ?>
                                                <a href="<?= base_url('alumni/questionnaires/lanjutkan/' . $row['id']) ?>" class="btn-action btn-lanjutkan">⏵</a>
                                            <?php else: ?>
                                                <a href="<?= base_url('alumni/questionnaires/lihat/' . $row['id']) ?>" class="btn-action btn-lihat">👁️</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
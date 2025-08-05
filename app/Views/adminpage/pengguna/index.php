<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div style="padding: 20px;">
    <h2 style="margin-bottom: 16px; font-weight: 600; font-size: 22px;">Daftar Pengguna</h2>

    <!-- Tombol Tambah Pengguna -->
    <button onclick="window.location.href='<?= base_url('/admin/pengguna/tambahPengguna') ?>'"
        style="padding: 10px 16px;
               background-color: #001BB7;
               color: white;
               border: none;
               border-radius: 5px;
               font-size: 14px;
               font-weight: 600;
               cursor: pointer;
               transition: background-color 0.3s ease;">
        Tambah Pengguna
    </button>

    <div style="margin-top: 30px;">
        <ul class="nav nav-tabs" id="userTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="semua-tab" data-bs-toggle="tab" data-bs-target="#semua" type="button" role="tab">Semua Group</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab">Site Admin</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="alumni-tab" data-bs-toggle="tab" data-bs-target="#alumni" type="button" role="tab">Alumni</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="jurusan-tab" data-bs-toggle="tab" data-bs-target="#jurusan" type="button" role="tab">Admin Jurusan</button>
            </li>
        </ul>
    </div>

    <div class="tab-content mt-4" id="userTabContent">
        <?php
        // Daftar tab dan data dummy
        $tabs = [
            'semua' => [
                ['no' => 1, 'nama' => 'Budi Santoso', 'email' => 'budi@example.com', 'status' => 'Aktif', 'group' => 'Alumni'],
                ['no' => 2, 'nama' => 'Ani Rahmawati', 'email' => 'ani@example.com', 'status' => 'Aktif', 'group' => 'Site Admin'],
            ],
            'admin' => [
                ['no' => 1, 'nama' => 'Ani Rahmawati', 'email' => 'ani@example.com', 'status' => 'Aktif', 'group' => 'Site Admin'],
            ],
            'alumni' => [
                ['no' => 1, 'nama' => 'Budi Santoso', 'email' => 'budi@example.com', 'status' => 'Aktif', 'group' => 'Alumni'],
            ],
            'jurusan' => [
                ['no' => 1, 'nama' => 'Sari Utami', 'email' => 'sari@example.com', 'status' => 'Aktif', 'group' => 'Admin Jurusan'],
            ]
        ];
        ?>

        <?php foreach ($tabs as $id => $rows): ?>
        <div class="tab-pane fade <?= $id === 'semua' ? 'show active' : '' ?>" id="<?= $id ?>" role="tabpanel">
            <div style="overflow-x:auto;">
                <table style="
                    width: 100%;
                    border-collapse: collapse;
                    background-color: rgba(255, 255, 255, 0.25);
                    backdrop-filter: blur(6px);
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.06);">
                    <thead style="background-color: rgba(243, 244, 246, 0.47); color: #111;">
                        <tr>
                            <th style="padding: 14px; text-align: left;">No</th>
                            <th style="padding: 14px; text-align: left;">Nama Pengguna</th>
                            <th style="padding: 14px; text-align: left;">Email</th>
                            <th style="padding: 14px; text-align: left;">Status</th>
                            <th style="padding: 14px; text-align: left;">Group</th>
                            <th style="padding: 14px; text-align: left;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $user): ?>
                        <tr>
                            <td style="padding: 12px;"><?= $user['no'] ?></td>
                            <td style="padding: 12px;"><?= $user['nama'] ?></td>
                            <td style="padding: 12px;"><?= $user['email'] ?></td>
                            <td style="padding: 12px;"><?= $user['status'] ?></td>
                            <td style="padding: 12px;"><?= $user['group'] ?></td>
                            <td style="padding: 12px;">
                                <a href="#" style="background-color: #001BB7; padding: 6px 10px; color: white; border-radius: 4px; text-decoration: none; font-size: 13px;">Edit</a>
                                <a href="#" style="background-color: #dc3545; padding: 6px 10px; color: white; border-radius: 4px; text-decoration: none; font-size: 13px;">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<?= $this->endSection() ?>

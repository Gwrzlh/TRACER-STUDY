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

   <div class="d-flex flex-wrap gap-2 mb-4">
    <!-- Tombol Semua -->
     <a class="nav-link <?= ($roleId == null) ? 'active' : '' ?>" href="<?= base_url('admin/pengguna') ?>">Semua</a>
  

    <!-- Tombol per Role -->
    <?php foreach ($roles as $r): ?>
        <a href="<?= base_url('/admin/pengguna?role=' . $r['id']) ?>"
           class="btn btn-outline-primary <?= ($roleId == $r['id']) ? 'active' : '' ?>">
            <?= esc($r['nama']) ?>
        </a>
    <?php endforeach;?>
</div>


 <div class="tab-content mt-4" id="userTabContent">

    <!-- Tab Semua -->
    <div class="tab-pane fade <?= ($roleId === null) ? 'show active' : '' ?>" id="role_all" role="tabpanel">
        <?php if (!empty($account)): ?>
            <div style="overflow-x:auto;">
                <table class="table">
                    <!-- Header -->
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Group</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <!-- Body -->
                    <tbody>
                        <?php $no = 1; foreach ($account as $acc): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $acc['username'] ?></td>
                                <td><?= $acc['email'] ?></td>
                                <td><?= $acc['status'] ?></td>
                                <td><?= $acc['nama_role'] ?></td>
                                <td>                                        
                                    <a href="<?= base_url('/admin/pengguna/editPengguna/'. $acc['id'] ) ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <<form action="<?= base_url('/admin/pengguna/delete/' . $acc['id']) ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="text-red-600 hover:underline">delete</button>
                                         </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Tidak ada data pengguna.</p>
        <?php endif; ?>
    </div>

    <!-- Tab untuk tiap Role -->
    <?php foreach ($roles as $role): ?>
        <div class="tab-pane fade <?= ($roleId == $role['id']) ? 'show active' : '' ?>" id="role_<?= $role['id'] ?>" role="tabpanel">
            <?php
            // Filter data berdasarkan role yang sedang ditampilkan
            $filtered = array_filter($account, function ($acc) use ($role) {
                return $acc['id_role'] == $role['id'];
            });
            ?>
            <?php if (!empty($filtered)): ?>
                <div style="overflow-x:auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pengguna</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Group</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($filtered as $acc): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $acc['username'] ?></td>
                                    <td><?= $acc['email'] ?></td>
                                    <td><?= $acc['status'] ?></td>
                                    <td><?= $acc['nama_role'] ?></td>
                                    <td>
                                        <a href="<?= base_url('/admin/pengguna/editPengguna/'. $acc['id'] ) ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="<?= base_url('/admin/pengguna/delete/' . $acc['id']) ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="text-red-600 hover:underline">delete</button>
                                         </form>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>Tidak ada data pengguna untuk role <?= esc($role['nama']) ?>.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->endSection(); ?>
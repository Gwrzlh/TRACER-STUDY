<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div style="padding: 20px; background-color: #f8f9fa;">
    <h2 style="margin-bottom: 24px; font-weight: 600; font-size: 24px; color: #333;">Daftar Pengguna</h2>

    <!-- Tombol Tambah Pengguna -->
    <button onclick="window.location.href='<?= base_url('/admin/pengguna/tambahPengguna') ?>'"
        style="padding: 12px 20px;
               background-color: #001BB7;
               color: white;
               border: none;
               border-radius: 8px;
               font-size: 14px;
               font-weight: 600;
               cursor: pointer;
               transition: all 0.3s ease;
               margin-bottom: 24px;
               box-shadow: 0 2px 4px rgba(0,27,183,0.2);">
        + Tambah Pengguna
    </button>

   <!-- Filter Buttons -->
<div class="role-buttons" style="margin-bottom: 20px; display: flex; gap: 10px;">
    <!-- Tombol Semua -->
    <a href="<?= base_url('/admin/pengguna') ?>"
       class="filter-btn <?= ($roleId == null) ? 'active' : '' ?>"
       style="padding: 8px 16px;
              border-radius: 20px;
              font-size: 13px;
              font-weight: 500;
              text-decoration: none;
              transition: all 0.3s ease;
              <?= ($roleId == null) 
                    ? 'background-color: #001BB7; color: white;' 
                    : 'background-color: #e9ecef; color: #6c757d; border: 1px solid #dee2e6;' ?>">
        Semua (<?= $counts['all'] ?? 0 ?>)
    </a>

    <!-- Tombol per Role -->
    <?php foreach ($roles as $r): ?>
        <a href="<?= base_url('/admin/pengguna?role=' . $r['id']) ?>"
           class="filter-btn <?= ($roleId == $r['id']) ? 'active' : '' ?>"
           style="padding: 8px 16px;
                  border-radius: 20px;
                  font-size: 13px;
                  font-weight: 500;
                  text-decoration: none;
                  transition: all 0.3s ease;
                  <?= ($roleId == $r['id']) 
                        ? 'background-color: #001BB7; color: white;' 
                        : 'background-color: #e9ecef; color: #6c757d; border: 1px solid #dee2e6;' ?>">
            <?= esc($r['nama']) ?> (<?= $counts[$r['id']] ?? 0 ?>)
        </a>
    <?php endforeach; ?>
</div>


    <!-- Table Container -->
    <div class="table-container" style="background-color: white; 
                                      border-radius: 12px; 
                                      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                                      overflow: hidden;">

        <div class="tab-content" id="userTabContent">
           <!-- from sreach -->
    <form method="get" action="<?= base_url('admin/pengguna') ?>" style="margin-bottom:15px;">
    <?php if ($roleId): ?>
        <input type="hidden" name="role" value="<?= esc($roleId) ?>">
    <?php endif; ?>

    <div style="display:flex; gap:10px;">
        <input type="text" 
               name="keyword" 
               value="<?= esc($keyword ?? '') ?>" 
               placeholder="Cari nama..." 
               class="search-input" 
               style="padding:8px 12px; border:1px solid #ccc; border-radius:20px; transition: width 0.3s ease;">
        
        <button type="submit" style="padding:8px 16px; background: #001BB7; color:white; border:none; border-radius:20px; cursor:pointer;">
            Search
        </button>
    </div>
   
</form>

<style>
    .search-input {
        width: 150px; /* Lebar awal */
    }
    .search-input:focus {
        width: 300px; /* Lebar saat fokus */
        outline: none;
    }
</style>  
<form method="get" action="<?= base_url('admin/pengguna') ?>" class="mb-3">
    <!-- filter role dan keyword tetap sama -->
    
    <label for="perpage">Tampilkan per halaman:</label>
    <input 
        type="number" 
        name="perpage" 
        id="perpage" 
        min="1" 
        value="<?= esc($perPage) ?>" 
        style="width: 80px;"
        onchange="this.form.submit()"
    >
    
    
    
    <!-- kalau mau tombol submit manual bisa dihilangkan karena onchange sudah submit otomatis -->
</form>

<!-- Tab Semua -->
            <div class="tab-pane fade <?= ($roleId === null) ? 'show active' : '' ?>" id="role_all" role="tabpanel">
                <?php if (!empty($account)): ?>
                    
                    <div style="overflow-x:auto;">
                        <table class="modern-table" style="width: 100%; border-collapse: collapse;">
                            <!-- Header -->
                            <thead>
                                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                                    <th style="padding: 16px 20px; 
                                              font-weight: 600; 
                                              font-size: 13px; 
                                              color: #495057; 
                                              text-align: left;
                                              text-transform: uppercase;
                                              letter-spacing: 0.5px;">No</th>
                                    <th style="padding: 16px 20px; 
                                              font-weight: 600; 
                                              font-size: 13px; 
                                              color: #495057; 
                                              text-align: left;
                                              text-transform: uppercase;
                                              letter-spacing: 0.5px;">Nama Pengguna</th>
                                    <th style="padding: 16px 20px; 
                                              font-weight: 600; 
                                              font-size: 13px; 
                                              color: #495057; 
                                              text-align: left;
                                              text-transform: uppercase;
                                              letter-spacing: 0.5px;">Email</th>
                                    <th style="padding: 16px 20px; 
                                              font-weight: 600; 
                                              font-size: 13px; 
                                              color: #495057; 
                                              text-align: left;
                                              text-transform: uppercase;
                                              letter-spacing: 0.5px;">Status</th>
                                    <th style="padding: 16px 20px; 
                                              font-weight: 600; 
                                              font-size: 13px; 
                                              color: #495057; 
                                              text-align: left;
                                              text-transform: uppercase;
                                              letter-spacing: 0.5px;">Group</th>
                                    <th style="padding: 16px 20px; 
                                              font-weight: 600; 
                                              font-size: 13px; 
                                              color: #495057; 
                                              text-align: center;
                                              text-transform: uppercase;
                                              letter-spacing: 0.5px;">Aksi</th>
                                </tr>
                            </thead>
                            <!-- Body -->
                            <tbody>
                              <?php $no = 1 + ($perPage * ($currentPage - 1)); foreach ($account as $acc): ?>
                                    <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                                        onmouseover="this.style.backgroundColor='#f8f9fa'" 
                                        onmouseout="this.style.backgroundColor='white'">
                                        <td style="padding: 16px 20px; font-size: 14px; color: #6c757d; font-weight: 500;"><?= $no++ ?></td>
                                        <td style="padding: 16px 20px; font-size: 14px; color: #333; font-weight: 500;"><?= $acc['username'] ?></td>
                                        <td style="padding: 16px 20px; font-size: 14px; color: #6c757d;"><?= $acc['email'] ?></td>
                                        <td style="padding: 16px 20px;">
                                            <?php 
                                            $isActive = (strtolower($acc['status']) == 'active' || strtolower($acc['status']) == 'aktif' || $acc['status'] == '1');
                                            ?>
                                            <span style="padding: 4px 12px;
                                                        border-radius: 20px;
                                                        font-size: 12px;
                                                        font-weight: 600;
                                                        text-transform: uppercase;
                                                        letter-spacing: 0.5px;
                                                        <?= $isActive ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;' ?>">
                                                <?= $isActive ? 'AKTIF' : 'TIDAK AKTIF' ?>
                                            </span>
                                        </td>
                                        <td style="padding: 16px 20px;">
                                            <span style="padding: 4px 12px;
                                                        background-color: #e3f2fd;
                                                        color: #1565c0;
                                                        border-radius: 20px;
                                                        font-size: 12px;
                                                        font-weight: 500;">
                                                <?= $acc['nama_role'] ?>
                                            </span>
                                        </td>
                                        <td style="padding: 16px 20px; text-align: center;">
                                            <a href="<?= base_url('/admin/pengguna/editPengguna/'. $acc['id'] ) ?>" 
                                               style="padding: 6px 12px;
                                                      background-color: #007bff;
                                                      color: white;
                                                      border: none;
                                                      border-radius: 4px;
                                                      font-size: 12px;
                                                      font-weight: 500;
                                                      cursor: pointer;
                                                      margin-right: 6px;
                                                      text-decoration: none;
                                                      display: inline-block;
                                                      transition: all 0.2s ease;">
                                                Edit
                                            </a>
                                            <form action="<?= base_url('/admin/pengguna/delete/' . $acc['id']) ?>" method="post" style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" 
                                                        style="padding: 6px 12px;
                                                               background-color: #dc3545;
                                                               color: white;
                                                               border: none;
                                                               border-radius: 4px;
                                                               font-size: 12px;
                                                               font-weight: 500;
                                                               cursor: pointer;
                                                               transition: all 0.2s ease;">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                       <div style="margin-top: 20px;">
                         <?= $pager->links('accounts', 'paginations') ?>
                        </div>
                <?php else: ?>
                    <div style="padding: 40px; text-align: center; color: #6c757d;">
                        <p style="font-size: 16px; margin: 0;">Tidak ada data pengguna.</p>
                    </div>
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
                        <div style="overflow-x:auto;">
                            <table class="modern-table" style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                                        <th style="padding: 16px 20px; 
                                                  font-weight: 600; 
                                                  font-size: 13px; 
                                                  color: #495057; 
                                                  text-align: left;
                                                  text-transform: uppercase;
                                                  letter-spacing: 0.5px;">No</th>
                                        <th style="padding: 16px 20px; 
                                                  font-weight: 600; 
                                                  font-size: 13px; 
                                                  color: #495057; 
                                                  text-align: left;
                                                  text-transform: uppercase;
                                                  letter-spacing: 0.5px;">Nama Pengguna</th>
                                        <th style="padding: 16px 20px; 
                                                  font-weight: 600; 
                                                  font-size: 13px; 
                                                  color: #495057; 
                                                  text-align: left;
                                                  text-transform: uppercase;
                                                  letter-spacing: 0.5px;">Email</th>
                                        <th style="padding: 16px 20px; 
                                                  font-weight: 600; 
                                                  font-size: 13px; 
                                                  color: #495057; 
                                                  text-align: left;
                                                  text-transform: uppercase;
                                                  letter-spacing: 0.5px;">Status</th>
                                        <th style="padding: 16px 20px; 
                                                  font-weight: 600; 
                                                  font-size: 13px; 
                                                  color: #495057; 
                                                  text-align: left;
                                                  text-transform: uppercase;
                                                  letter-spacing: 0.5px;">Group</th>
                                        <th style="padding: 16px 20px; 
                                                  font-weight: 600; 
                                                  font-size: 13px; 
                                                  color: #495057; 
                                                  text-align: center;
                                                  text-transform: uppercase;
                                                  letter-spacing: 0.5px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($filtered as $acc): ?>
                                        <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                                            onmouseover="this.style.backgroundColor='#f8f9fa'" 
                                            onmouseout="this.style.backgroundColor='white'">
                                            <td style="padding: 16px 20px; font-size: 14px; color: #6c757d; font-weight: 500;"><?= $no++ ?></td>
                                            <td style="padding: 16px 20px; font-size: 14px; color: #333; font-weight: 500;"><?= $acc['username'] ?></td>
                                            <td style="padding: 16px 20px; font-size: 14px; color: #6c757d;"><?= $acc['email'] ?></td>
                                            <td style="padding: 16px 20px;">
                                                <?php 
                                                $isActive = (strtolower($acc['status']) == 'active' || strtolower($acc['status']) == 'aktif' || $acc['status'] == '1');
                                                ?>
                                                <span style="padding: 4px 12px;
                                                            border-radius: 20px;
                                                            font-size: 12px;
                                                            font-weight: 600;
                                                            text-transform: uppercase;
                                                            letter-spacing: 0.5px;
                                                            <?= $isActive ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;' ?>">
                                                    <?= $isActive ? 'AKTIF' : 'TIDAK AKTIF' ?>
                                                </span>
                                            </td>
                                            <td style="padding: 16px 20px;">
                                                <span style="padding: 4px 12px;
                                                            background-color: #e3f2fd;
                                                            color: #1565c0;
                                                            border-radius: 20px;
                                                            font-size: 12px;
                                                            font-weight: 500;">
                                                    <?= $acc['nama_role'] ?>
                                                </span>
                                            </td>
                                            <td style="padding: 16px 20px; text-align: center;">
                                                <a href="<?= base_url('/admin/pengguna/editPengguna/'. $acc['id'] ) ?>" 
                                                   style="padding: 6px 12px;
                                                          background-color: #007bff;
                                                          color: white;
                                                          border: none;
                                                          border-radius: 4px;
                                                          font-size: 12px;
                                                          font-weight: 500;
                                                          cursor: pointer;
                                                          margin-right: 6px;
                                                          text-decoration: none;
                                                          display: inline-block;
                                                          transition: all 0.2s ease;">
                                                    Edit
                                                </a>
                                                <form action="<?= base_url('/admin/pengguna/delete/' . $acc['id']) ?>" method="post" style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" 
                                                            style="padding: 6px 12px;
                                                                   background-color: #dc3545;
                                                                   color: white;
                                                                   border: none;
                                                                   border-radius: 4px;
                                                                   font-size: 12px;
                                                                   font-weight: 500;
                                                                   cursor: pointer;
                                                                   transition: all 0.2s ease;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="padding: 40px; text-align: center; color: #6c757d;">
                            <p style="font-size: 16px; margin: 0;">Tidak ada data pengguna untuk role <?= esc($role['nama']) ?>.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    .filter-btn:hover {
        background-color: #001BB7 !important;
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,27,183,0.2);
    }
    
    .modern-table tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .modern-table td a:hover {
        background-color: #0056b3 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,123,255,0.3);
    }
    
    .modern-table td button:hover {
        background-color: #c82333 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(220,53,69,0.3);
    }
    
    @media (max-width: 768px) {
        .table-container {
            margin: 0 -10px;
            border-radius: 8px !important;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 12px 16px !important;
            font-size: 13px !important;
        }
        
        .modern-table td button {
            padding: 6px 12px !important;
            font-size: 11px !important;
        }
    }
</style>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<?= $this->endSection(); ?>
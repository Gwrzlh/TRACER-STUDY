<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
    /* PENTING: Reset dan isolasi styling untuk halaman pengguna */
    .pengguna-page {
        /* Pastikan tidak ada konflik dengan sidebar */
        position: static !important;
        z-index: 1 !important;
        width: 100% !important;
        height: auto !important;
        transform: none !important;
        margin: 0 !important;
        padding: 0 !important;
        display: block !important;
        overflow: visible !important;
    }

    /* Enhanced Page Wrapper - Konsisten dengan Dashboard */
    .pengguna-page .page-wrapper {
        padding: 30px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: calc(100vh - 50px);
        position: relative;
        z-index: 1;
        margin: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .pengguna-page .page-container {
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
    }

    /* Enhanced Page Title - Konsisten dengan Dashboard */
    .pengguna-page .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(30, 64, 175, 0.1);
        position: relative;
        padding-bottom: 15px;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .pengguna-page .page-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        border-radius: 2px;
    }

    /* Enhanced Button Styles - Konsisten dengan Dashboard */
    .pengguna-page .button-container {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
        align-items: center;
    }

    .pengguna-page .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        white-space: nowrap;
    }

    .pengguna-page .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .pengguna-page .btn:hover::before {
        left: 100%;
    }

    .pengguna-page .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
    }

    .pengguna-page .btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }

    .pengguna-page .btn-success {
        background: linear-gradient(135deg, #10b981, #16a34a);
        color: white;
    }

    .pengguna-page .btn-success:hover {
        background: linear-gradient(135deg, #059669, #15803d);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        color: white;
        text-decoration: none;
    }

    /* Enhanced Alert Styles - Konsisten dengan Dashboard */
    .pengguna-page .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 500;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        animation: slideInDown 0.5s ease;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .pengguna-page .alert-success {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border-left: 4px solid #22c55e;
    }

    .pengguna-page .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    /* Enhanced Search and Filter Container - Konsisten dengan Dashboard */
    .pengguna-page .controls-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .pengguna-page .controls-container:hover {
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .pengguna-page .search-wrapper {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        align-items: center;
        flex-wrap: wrap;
    }

    .pengguna-page .search-input {
        flex: 1;
        min-width: 250px;
        padding: 14px 18px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #ffffff;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .pengguna-page .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: #fefefe;
    }

    .pengguna-page .search-btn {
        padding: 14px 28px;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        white-space: nowrap;
    }

    .pengguna-page .search-btn:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }

    .pengguna-page .perpage-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        color: #64748b;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        flex-wrap: wrap;
    }

    .pengguna-page .perpage-wrapper input {
        padding: 8px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        text-align: center;
        transition: all 0.3s ease;
        min-width: 60px;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .pengguna-page .perpage-wrapper input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Enhanced Table Container - Konsisten dengan Dashboard */
    .pengguna-page .table-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .pengguna-page .table-container:hover {
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    }

    /* Enhanced Modern Table - Konsisten dengan Dashboard */
    .pengguna-page .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        background: transparent;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .pengguna-page .modern-table thead {
        background: linear-gradient(135deg, #1e40af, #3b82f6);
    }

    .pengguna-page .modern-table thead th {
        padding: 20px 16px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        position: relative;
        white-space: nowrap;
    }

    .pengguna-page .modern-table thead th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));
    }

    .pengguna-page .modern-table tbody tr {
        background: #ffffff;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    }

    .pengguna-page .modern-table tbody tr:hover {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .pengguna-page .modern-table tbody td {
        padding: 18px 16px;
        color: #374151;
        font-weight: 500;
        vertical-align: middle;
        border: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    /* Enhanced Badge Styles - Konsisten dengan Dashboard */
    .pengguna-page .badge-status, 
    .pengguna-page .badge-role {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        white-space: nowrap;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .pengguna-page .badge-status:hover, 
    .pengguna-page .badge-role:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .pengguna-page .badge-active {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .pengguna-page .badge-inactive {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .pengguna-page .badge-role {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    /* Enhanced Action Buttons - Konsisten dengan Dashboard */
    .pengguna-page .btn-edit, 
    .pengguna-page .btn-delete {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 0 3px;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        white-space: nowrap;
    }

    .pengguna-page .btn-edit {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    .pengguna-page .btn-edit:hover {
        background: linear-gradient(135deg, #0891b2, #0e7490);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
        color: white;
        text-decoration: none;
    }

    .pengguna-page .btn-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .pengguna-page .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        color: white;
        text-decoration: none;
    }

    /* Enhanced Modal Styles - Konsisten dengan Dashboard */
    .pengguna-page .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .pengguna-page .modal-header {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
        padding: 20px 25px;
        border: none;
    }

    .pengguna-page .modal-title {
        font-weight: 700;
        font-size: 18px;
    }

    .pengguna-page .btn-close {
        background: none;
        border: none;
        font-size: 24px;
        color: white;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .pengguna-page .btn-close:hover {
        opacity: 1;
    }

    .pengguna-page .modal-body {
        padding: 25px;
        background: #ffffff;
    }

    .pengguna-page .modal-footer {
        background: #f8fafc;
        padding: 20px 25px;
        border: none;
    }

    .pengguna-page .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .pengguna-page .form-control, 
    .pengguna-page .form-select {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #ffffff;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        width: 100%;
        box-sizing: border-box;
    }

    .pengguna-page .form-control:focus, 
    .pengguna-page .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Enhanced Empty State - Konsisten dengan Dashboard */
    .pengguna-page .empty-state {
        padding: 60px 40px;
        text-align: center;
        color: #64748b;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 16px;
        margin: 20px 0;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .pengguna-page .empty-state p {
        font-size: 16px;
        margin: 10px 0;
        font-weight: 500;
    }

    .pengguna-page .empty-state .debug-info {
        font-size: 14px;
        color: #94a3b8;
        font-style: italic;
    }

    /* Enhanced Pagination Styles - Konsisten dengan Dashboard */
    .pengguna-page .pagination-container {
        margin-top: 30px;
        padding: 25px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    /* Enhanced Responsive Design - Konsisten dengan Dashboard */
    @media (max-width: 1024px) {
        .pengguna-page .page-wrapper {
            padding: 25px 20px;
        }
        
        .pengguna-page .search-wrapper {
            flex-direction: column;
            gap: 15px;
        }
        
        .pengguna-page .search-input {
            min-width: 100%;
        }
        
        .pengguna-page .modern-table thead th,
        .pengguna-page .modern-table tbody td {
            padding: 15px 12px;
            font-size: 13px;
        }
    }

    @media (max-width: 768px) {
        .pengguna-page .page-wrapper {
            padding: 20px 15px;
        }

        .pengguna-page .page-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .pengguna-page .button-container {
            flex-direction: column;
            gap: 12px;
        }

        .pengguna-page .btn {
            justify-content: center;
            width: 100%;
        }

        .pengguna-page .controls-container {
            padding: 20px;
        }

        .pengguna-page .perpage-wrapper {
            justify-content: center;
            text-align: center;
        }

        .pengguna-page .modern-table {
            font-size: 12px;
        }

        .pengguna-page .modern-table thead th,
        .pengguna-page .modern-table tbody td {
            padding: 12px 8px;
        }

        .pengguna-page .badge-status, 
        .pengguna-page .badge-role {
            font-size: 10px;
            padding: 4px 10px;
        }

        .pengguna-page .btn-edit, 
        .pengguna-page .btn-delete {
            font-size: 11px;
            padding: 6px 12px;
            margin: 2px;
            display: block;
            margin-bottom: 5px;
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .pengguna-page .modern-table thead {
            display: none;
        }

        .pengguna-page .modern-table tbody tr {
            display: block;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 15px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .pengguna-page .modern-table tbody td {
            display: block;
            padding: 8px 0;
            border: none;
            position: relative;
            padding-left: 40%;
            white-space: normal;
            max-width: none;
            text-overflow: initial;
            overflow: visible;
        }

        .pengguna-page .modern-table tbody td::before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 35%;
            font-weight: 600;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            line-height: 1.4;
        }
    }

    /* PENTING: Isolasi CSS - Pastikan tidak mengganggu sidebar */
    .pengguna-page * {
        box-sizing: border-box;
    }

    /* Fix untuk memastikan sidebar logo tidak terganggu */
    .sidebar .logo,
    .sidebar .logo img,
    nav .logo,
    nav .logo img,
    .navbar-brand,
    .navbar-brand img {
        /* Jangan override styling logo sidebar */
        transform: none !important;
        width: auto !important;
        height: auto !important;
        aspect-ratio: auto !important;
    }

    /* Ensure proper font loading */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
</style>

<!-- Wrap semua konten dalam container khusus untuk menghindari CSS conflict -->
<div class="pengguna-page">
    <div class="page-wrapper">
        <div class="page-container">
            <h2 class="page-title">Daftar Pengguna</h2>

            <!-- Enhanced Button Container -->
            <div class="button-container">
                <a href="<?= base_url('/admin/pengguna/tambahPengguna') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Pengguna
                </a>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import"></i> Import Akun
                </button>
            </div>

            <!-- Enhanced Modal Import -->
            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Import Akun dari Excel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('admin/pengguna/import') ?>" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Pilih File (xls, xlsx, csv)</label>
                                    <input type="file" name="file" id="file" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="id_role" class="form-label">Pilih Role</label>
                                    <select name="id_role" id="id_role" class="form-select" required>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="1">Alumni</option>
                                        <option value="2">Admin</option>
                                        <option value="6">Kaprodi</option>
                                        <option value="7">Perusahaan</option>
                                        <option value="8">Atasan</option>
                                        <option value="9">Jabatan lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Enhanced Flashdata -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Enhanced Controls Container -->
            <div class="controls-container">
                <!-- Enhanced Search Form -->
                <form method="get" action="<?= base_url('admin/pengguna') ?>">
                    <?php if (isset($roleId) && $roleId): ?>
                        <input type="hidden" name="role" value="<?= esc($roleId) ?>">
                    <?php endif; ?>
                    
                    <div class="search-wrapper">
                        <input type="text" 
                               name="keyword" 
                               value="<?= esc($keyword ?? '') ?>" 
                               placeholder="Cari nama pengguna..." 
                               class="search-input">
                        
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>

                <!-- Enhanced Per Page Form -->
                <form method="get" action="<?= base_url('admin/pengguna') ?>">
                    <?php if (isset($roleId) && $roleId): ?>
                        <input type="hidden" name="role" value="<?= esc($roleId) ?>">
                    <?php endif; ?>
                    <?php if (isset($keyword) && $keyword): ?>
                        <input type="hidden" name="keyword" value="<?= esc($keyword) ?>">
                    <?php endif; ?>
                    
                    <div class="perpage-wrapper">
                        <label for="perpage">Tampilkan per halaman:</label>
                        <input type="number" 
                               name="perpage" 
                               id="perpage" 
                               min="1" 
                               value="<?= esc($perPage ?? 10) ?>" 
                               onchange="this.form.submit()">
                    </div>
                </form>
            </div>

            <!-- Enhanced Main Table -->
            <?php if (isset($accounts) && !empty($accounts)): ?>
                <div class="table-container">
                    <div style="overflow-x:auto;">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengguna</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Group</th>
                                    <th style="text-align:center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1 + (($perPage ?? 10) * (($currentPage ?? 1) - 1)); 
                                foreach ($accounts as $acc): 
                                ?>
                                    <tr>
                                        <td data-label="No"><?= $no++ ?></td>
                                        <td data-label="Nama Pengguna" title="<?= esc($acc['username']) ?>"><?= esc($acc['username']) ?></td>
                                        <td data-label="Email" title="<?= esc($acc['email']) ?>"><?= esc($acc['email']) ?></td>
                                        <td data-label="Status">
                                            <?php 
                                            $isActive = (strtolower($acc['status']) == 'active' || strtolower($acc['status']) == 'aktif' || $acc['status'] == '1');
                                            ?>
                                            <span class="badge-status <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
                                                <?= $isActive ? 'AKTIF' : 'TIDAK AKTIF' ?>
                                            </span>
                                        </td>
                                        <td data-label="Group">
                                            <span class="badge-role">
                                                <?= esc($acc['nama_role'] ?? 'Tidak ada role') ?>
                                            </span>
                                        </td>
                                        <td data-label="Aksi" style="text-align:center;">
                                            <a href="<?= base_url('/admin/pengguna/editPengguna/'. $acc['id'] ) ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="<?= base_url('/admin/pengguna/delete/' . $acc['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Enhanced Pagination -->
                    <div class="pagination-container">
                        <?php if (isset($pager)): ?>
                            <?= $pager->links('accounts', 'paginations') ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users" style="font-size: 48px; color: #cbd5e1; margin-bottom: 20px;"></i>
                    <p>Tidak ada data pengguna ditemukan.</p>
                    <?php if (isset($accounts)): ?>
                        <p class="debug-info">Variable accounts ditemukan tapi kosong.</p>
                    <?php else: ?>
                        <p class="debug-info">Variable accounts tidak ditemukan.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Enhanced Scripts - Load CDN terlebih dahulu -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scoped JavaScript untuk halaman pengguna - Konsisten dengan Dashboard
    const penggunaPage = document.querySelector('.pengguna-page');
    if (!penggunaPage) return;

    console.log('Enhanced Pengguna Index page initializing...');

    // Enhanced table row animations - staggered untuk performa lebih baik
    function animateTableRows() {
        const tableRows = penggunaPage.querySelectorAll('.modern-table tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            setTimeout(() => {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }

    // Enhanced search input focus effect
    function enhanceSearchInput() {
        const searchInput = penggunaPage.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('focus', function() {
                this.closest('.search-wrapper').style.transform = 'scale(1.02)';
                this.closest('.search-wrapper').style.transition = 'all 0.3s ease';
            });
            
            searchInput.addEventListener('blur', function() {
                this.closest('.search-wrapper').style.transform = 'scale(1)';
            });

            // Real-time search hint
            searchInput.addEventListener('input', function() {
                const searchBtn = penggunaPage.querySelector('.search-btn');
                if (this.value.length >= 3) {
                    searchBtn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
                    searchBtn.innerHTML = '<i class="fas fa-search"></i> Search Ready';
                } else if (this.value.length === 0) {
                    searchBtn.style.background = 'linear-gradient(135deg, #3b82f6, #1e40af)';
                    searchBtn.innerHTML = '<i class="fas fa-search"></i> Search';
                } else {
                    searchBtn.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
                    searchBtn.innerHTML = '<i class="fas fa-search"></i> Type more...';
                }
            });
        }
    }

    // Enhanced button hover effects
    function enhanceButtons() {
        const buttons = penggunaPage.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                if (!this.disabled) {
                    this.style.transform = 'translateY(-2px) scale(1.05)';
                }
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });

            // Enhanced click animation
            button.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'translateY(0) scale(1)';
                }, 150);
            });
        });
    }

    // Enhanced form validation dengan visual feedback
    function enhanceFormValidation() {
        const forms = penggunaPage.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredInputs = this.querySelectorAll('[required]');
                let isValid = true;
                let firstInvalidInput = null;
                
                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        if (!firstInvalidInput) firstInvalidInput = input;
                        
                        input.style.borderColor = '#ef4444';
                        input.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
                        input.style.animation = 'shake 0.5s ease-in-out';
                        
                        // Remove error styling after 3 seconds
                        setTimeout(() => {
                            input.style.borderColor = '#e2e8f0';
                            input.style.boxShadow = 'none';
                            input.style.animation = 'none';
                        }, 3000);
                    } else {
                        input.style.borderColor = '#22c55e';
                        input.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.1)';
                        
                        setTimeout(() => {
                            input.style.borderColor = '#e2e8f0';
                            input.style.boxShadow = 'none';
                        }, 1000);
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    if (firstInvalidInput) {
                        firstInvalidInput.focus();
                        firstInvalidInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    
                    showToast('Mohon lengkapi semua field yang diperlukan.', 'error');
                }
            });
        });
    }

    // Enhanced alert auto-dismiss dengan progress bar
    function enhanceAlerts() {
        const alerts = penggunaPage.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Add progress bar
            const progressBar = document.createElement('div');
            progressBar.style.cssText = `
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background: rgba(255, 255, 255, 0.3);
                width: 100%;
                transform-origin: left;
                animation: progressBar 5s linear forwards;
            `;
            alert.style.position = 'relative';
            alert.appendChild(progressBar);
            
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }, 5000);
        });
    }

    // Enhanced responsive table behavior
    function handleResponsiveTable() {
        const table = penggunaPage.querySelector('.modern-table');
        if (!table) return;
        
        const isMobile = window.innerWidth <= 480;
        
        if (isMobile) {
            const cells = table.querySelectorAll('tbody td');
            const headers = ['No', 'Nama Pengguna', 'Email', 'Status', 'Group', 'Aksi'];
            
            cells.forEach((cell, index) => {
                const headerIndex = index % headers.length;
                cell.setAttribute('data-label', headers[headerIndex]);
            });
        }
    }

    // Enhanced modal animations
    function enhanceModals() {
        const modals = penggunaPage.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                const modalContent = this.querySelector('.modal-content');
                modalContent.style.transform = 'scale(0.7) translateY(-50px)';
                modalContent.style.opacity = '0';
                
                setTimeout(() => {
                    modalContent.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    modalContent.style.transform = 'scale(1) translateY(0)';
                    modalContent.style.opacity = '1';
                }, 100);
            });

            modal.addEventListener('hide.bs.modal', function() {
                const modalContent = this.querySelector('.modal-content');
                modalContent.style.transition = 'all 0.2s ease';
                modalContent.style.transform = 'scale(0.9) translateY(-20px)';
                modalContent.style.opacity = '0';
            });
        });
    }

    // Enhanced delete confirmation dengan better UX
    function enhanceDeleteButtons() {
        const deleteButtons = penggunaPage.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const row = this.closest('tr');
                const username = row.querySelector('td:nth-child(2)').textContent.trim();
                
                // Create custom modal-style confirmation
                showConfirmModal(
                    'Konfirmasi Hapus Pengguna',
                    `Apakah Anda yakin ingin menghapus pengguna "${username}"?`,
                    'Tindakan ini tidak dapat dibatalkan.',
                    () => {
                        // Show loading state
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                        this.disabled = true;
                        this.style.opacity = '0.6';
                        
                        // Submit the form after delay for better UX
                        setTimeout(() => {
                            this.closest('form').submit();
                        }, 500);
                    }
                );
            });
        });
    }

    // Enhanced pagination smooth scroll
    function enhancePagination() {
        const paginationLinks = penggunaPage.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Add loading state to table
                const tableContainer = penggunaPage.querySelector('.table-container');
                if (tableContainer) {
                    tableContainer.style.opacity = '0.7';
                    tableContainer.style.pointerEvents = 'none';
                    
                    // Smooth scroll to top of table
                    tableContainer.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // Enhanced accessibility improvements
    function enhanceAccessibility() {
        // Add aria-labels to buttons
        const editButtons = penggunaPage.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            const row = button.closest('tr');
            const username = row?.querySelector('td:nth-child(2)')?.textContent?.trim();
            if (username) {
                button.setAttribute('aria-label', `Edit pengguna ${username}`);
                button.setAttribute('title', `Edit pengguna ${username}`);
            }
        });

        const deleteButtons = penggunaPage.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            const row = button.closest('tr');
            const username = row?.querySelector('td:nth-child(2)')?.textContent?.trim();
            if (username) {
                button.setAttribute('aria-label', `Hapus pengguna ${username}`);
                button.setAttribute('title', `Hapus pengguna ${username}`);
            }
        });

        // Add role descriptions for screen readers
        const badges = penggunaPage.querySelectorAll('.badge-role, .badge-status');
        badges.forEach(badge => {
            badge.setAttribute('role', 'status');
            badge.setAttribute('aria-live', 'polite');
        });

        // Keyboard navigation for table
        const tableRows = penggunaPage.querySelectorAll('.modern-table tbody tr');
        tableRows.forEach((row, index) => {
            row.setAttribute('tabindex', '0');
            row.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    const editButton = this.querySelector('.btn-edit');
                    if (editButton) editButton.click();
                }
                if (e.key === 'ArrowDown' && tableRows[index + 1]) {
                    tableRows[index + 1].focus();
                }
                if (e.key === 'ArrowUp' && tableRows[index - 1]) {
                    tableRows[index - 1].focus();
                }
            });
        });
    }

    // Utility functions
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        `;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
        `;
        
        document.body.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    function showConfirmModal(title, message, subtitle, onConfirm) {
        const modalHtml = `
            <div class="modal fade" id="confirmModal" tabindex="-1" style="z-index: 10000;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                        <div class="modal-header" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border: none;">
                            <h5 class="modal-title">${title}</h5>
                        </div>
                        <div class="modal-body" style="padding: 30px; text-align: center;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f59e0b; margin-bottom: 20px;"></i>
                            <p style="font-size: 16px; margin-bottom: 10px; font-weight: 600;">${message}</p>
                            <p style="font-size: 14px; color: #64748b;">${subtitle}</p>
                        </div>
                        <div class="modal-footer" style="border: none; padding: 20px 30px;">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        
        document.getElementById('confirmDelete').onclick = () => {
            modal.hide();
            onConfirm();
        };
        
        document.getElementById('confirmModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
        
        modal.show();
    }

    // Initialize all enhancements
    setTimeout(() => {
        animateTableRows();
        enhanceSearchInput();
        enhanceButtons();
        enhanceFormValidation();
        enhanceAlerts();
        handleResponsiveTable();
        enhanceModals();
        enhanceDeleteButtons();
        enhancePagination();
        enhanceAccessibility();
        
        showToast('Halaman berhasil dimuat!', 'success');
    }, 300);

    // Event listeners for responsive design
    window.addEventListener('resize', handleResponsiveTable);

    console.log('Enhanced Pengguna Index page loaded successfully!');
});

// Additional CSS animations
const additionalCSS = `
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@keyframes progressBar {
    from { transform: scaleX(1); }
    to { transform: scaleX(0); }
}

.pengguna-page .loading {
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Enhanced button states */
.pengguna-page .btn:active {
    transform: scale(0.95) !important;
}

.pengguna-page .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

/* Enhanced table hover states */
.pengguna-page .modern-table tbody tr:hover {
    cursor: pointer;
}

/* Enhanced focus states for accessibility */
.pengguna-page *:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

.pengguna-page .btn:focus {
    outline: 3px solid rgba(59, 130, 246, 0.3);
    outline-offset: 2px;
}
`;

// Inject additional CSS
if (!document.querySelector('#pengguna-additional-css')) {
    const style = document.createElement('style');
    style.id = 'pengguna-additional-css';
    style.textContent = additionalCSS;
    document.head.appendChild(style);
}

// Enhanced utility functions untuk global use
window.penggunaPageUtils = {
    showToast: function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        `;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    },

    refreshTable: function() {
        const penggunaPage = document.querySelector('.pengguna-page');
        const tableContainer = penggunaPage?.querySelector('.table-container');
        if (tableContainer) {
            tableContainer.classList.add('loading');
            
            setTimeout(() => {
                tableContainer.classList.remove('loading');
                this.showToast('Data berhasil dimuat ulang!', 'success');
                location.reload(); // Refresh the page for real data update
            }, 1000);
        }
    },

    handleResponsiveTable: function() {
        const penggunaPage = document.querySelector('.pengguna-page');
        const table = penggunaPage?.querySelector('.modern-table');
        const isMobile = window.innerWidth <= 480;
        
        if (table && isMobile) {
            const cells = table.querySelectorAll('tbody td');
            const headers = ['No', 'Nama Pengguna', 'Email', 'Status', 'Group', 'Aksi'];
            
            cells.forEach((cell, index) => {
                const headerIndex = index % headers.length;
                cell.setAttribute('data-label', headers[headerIndex]);
            });
        }
    }
};
</script>

<?= $this->endSection() ?>
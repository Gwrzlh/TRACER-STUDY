<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div style="padding: 20px; background-color: #f8f9fa;">
    <!-- Tab Buttons -->
    <div class="flex space-x-4 border-b border-gray-300 mb-6" style="margin-bottom: 24px;">
        <a href="<?= base_url('satuanorganisasi') ?>" 
           style="padding: 8px 16px;
                  border-radius: 20px;
                  font-size: 13px;
                  font-weight: 500;
                  text-decoration: none;
                  transition: all 0.3s ease;
                  background-color: #e9ecef; 
                  color: #6c757d; 
                  border: 1px solid #dee2e6;">
            Satuan Organisasi
        </a>
        <a href="<?= base_url('satuanorganisasi/jurusan') ?>" 
           style="padding: 8px 16px;
                  border-radius: 20px;
                  font-size: 13px;
                  font-weight: 500;
                  text-decoration: none;
                  transition: all 0.3s ease;
                  background-color: #001BB7; 
                  color: white;">
            Jurusan
        </a>
        <a href="<?= base_url('satuanorganisasi/prodi') ?>" 
           style="padding: 8px 16px;
                  border-radius: 20px;
                  font-size: 13px;
                  font-weight: 500;
                  text-decoration: none;
                  transition: all 0.3s ease;
                  background-color: #e9ecef; 
                  color: #6c757d; 
                  border: 1px solid #dee2e6;">
            Prodi
        </a>
    </div>

    <!-- Konten Jurusan -->
    <div class="max-w-6xl mx-auto">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="margin: 0; font-weight: 600; font-size: 24px; color: #333;">Daftar Jurusan</h2>
            <a href="<?= base_url('satuanorganisasi/jurusan/create') ?>" 
               style="padding: 12px 20px;
                      background-color: #001BB7;
                      color: white;
                      border: none;
                      border-radius: 8px;
                      font-size: 14px;
                      font-weight: 600;
                      cursor: pointer;
                      transition: all 0.3s ease;
                      text-decoration: none;
                      display: inline-block;
                      box-shadow: 0 2px 4px rgba(0,27,183,0.2);">
                + Tambah
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div style="background-color: #d4edda; 
                        color: #155724; 
                        padding: 12px 16px; 
                        margin-bottom: 16px; 
                        border-radius: 8px; 
                        border: 1px solid #c3e6cb;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif ?>

        <!-- Table Container -->
        <div class="table-container" style="background-color: white; 
                                          border-radius: 12px; 
                                          box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                                          overflow: hidden;">
            <div style="overflow-x:auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                            <th style="padding: 16px 20px; 
                                      font-weight: 600; 
                                      font-size: 13px; 
                                      color: #495057; 
                                      text-align: left;
                                      text-transform: uppercase;
                                      letter-spacing: 0.5px;">ID</th>
                            <th style="padding: 16px 20px; 
                                      font-weight: 600; 
                                      font-size: 13px; 
                                      color: #495057; 
                                      text-align: left;
                                      text-transform: uppercase;
                                      letter-spacing: 0.5px;">Nama Jurusan</th>
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
                        <?php foreach ($jurusan as $row): ?>
                            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s ease;"
                                onmouseover="this.style.backgroundColor='#f8f9fa'" 
                                onmouseout="this.style.backgroundColor='white'">
                                <td style="padding: 16px 20px; font-size: 14px; color: #333; font-weight: 500;"><?= esc($row['id']) ?></td>
                                <td style="padding: 16px 20px; font-size: 14px; color: #333; font-weight: 500;"><?= esc($row['nama_jurusan']) ?></td>
                                <td style="padding: 16px 20px; text-align: center;">
                                    <a href="<?= base_url('satuanorganisasi/jurusan/edit/' . $row['id']) ?>" 
                                       style="padding: 6px 12px;
                                              background-color: #ffc107;
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
                                    <form action="<?= base_url('satuanorganisasi/jurusan/delete/' . $row['id']) ?>" method="post" style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
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
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .tab-btn:hover {
        background-color: #001BB7 !important;
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,27,183,0.2);
    }
    
    .table-container table tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .table-container table td a:hover {
        background-color: #e0a800 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(255,193,7,0.3);
    }
    
    .table-container table td button:hover {
        background-color: #c82333 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(220,53,69,0.3);
    }
    
    a[href*="create"]:hover {
        background-color: #0056b3 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,27,183,0.3);
    }
    
    @media (max-width: 768px) {
        .table-container {
            margin: 0 -10px;
            border-radius: 8px !important;
        }
        
        .table-container table th,
        .table-container table td {
            padding: 12px 16px !important;
            font-size: 13px !important;
        }
        
        .table-container table td a,
        .table-container table td button {
            padding: 6px 12px !important;
            font-size: 11px !important;
        }
        
        .flex {
            flex-wrap: wrap;
            gap: 8px;
        }
    }
</style>

<?= $this->endSection() ?>
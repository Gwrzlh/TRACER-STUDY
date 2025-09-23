<?php

namespace App\Models\LandingPage;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table            = 'laporan';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'urutan',
        'judul',
        'isi',
        'tahun',
        'file_pdf',
        'file_gambar'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class TentangModel extends Model
{
    protected $table = 'tentang';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul', 'isi',
        'judul2', 'isi2',
        'judul3', 'isi3',
        'gambar', 'gambar2'
    ];
    protected $useTimestamps = true;
}

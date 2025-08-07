<?php

namespace App\Models;

use CodeIgniter\Model;

class TentangModel extends Model
{
    protected $table = 'tentang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul', 'isi'];
    protected $useTimestamps = true;
}

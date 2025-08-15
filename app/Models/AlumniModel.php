<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumniModel extends Model
{
    protected $table = 'alumni';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'prodi', 'tahun_lulus', 'status_pekerjaan'];
}

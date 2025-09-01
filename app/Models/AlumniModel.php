<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumniModel extends Model
{
    protected $table = 'detailaccount_alumni';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_account',
        'nama_lengkap',
        'nim',
        'nik',
        'npwp',
        'alamat',
        'foto'
    ];
}

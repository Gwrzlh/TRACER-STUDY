<?php

namespace App\Models;

use CodeIgniter\Model;

class SatuanOrganisasiModel extends Model
{
    protected $table = 'satuan_organisasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_satuan',
        'nama_singkatan',
        'nama_slug',
        'deskripsi',
        'id_tipe',
        'urutan',
        'satuan_induk',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
}

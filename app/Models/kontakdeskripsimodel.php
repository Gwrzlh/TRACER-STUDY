<?php

namespace App\Models;

use CodeIgniter\Model;

class KontakDeskripsiModel extends Model
{
    protected $table = 'kontak_deskripsi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['isi'];
    protected $returnType = 'array';
}

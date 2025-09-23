<?php

namespace App\Models\LandingPage;
use CodeIgniter\Model;

class KontakModel extends Model
{
    protected $table = 'kontak';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kategori', 'id_account'];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class Tipeorganisasi extends Model
{
    protected $table            = 'tipe_organisasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_tipe','level','deskripsi','id_group'];

    protected bool $allowEmptyInserts = false;

    public function getgroupid(){
        return $this->select('tipe_organisasi.*, role.nama as nama_role')
                    ->join('role', 'role.id = tipe_organisasi.id_group');
        // Tidak pakai findAll() supaya bisa dipanggil paginate()
    }

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';                                        
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

}

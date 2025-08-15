<?php

namespace App\Models;

use CodeIgniter\Model;

class Accounts extends Model
{
    protected $table            = 'account';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','username','email','password','status','id_role','id_surveyor'];

    protected bool $allowEmptyInserts = false;
    public function getroleid(){
        return $this->select('account.*, role.nama as nama_role')->join('role', 'role.id = account.id_role')->findAll();
    }
    public function getjabatanid(){
        return $this->select('account.*, jabatan.jabatan as nama_jabatan')->join('jabatan', 'jabatan.id = account.jabatan_id')->findAll();
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
     public function insertMultiple(array $data)
    {
        return $this->insertBatch($data);
    }
}

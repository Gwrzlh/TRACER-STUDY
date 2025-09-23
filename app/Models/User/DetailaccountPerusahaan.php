<?php

namespace App\Models\User;

use CodeIgniter\Model;

class DetailaccountPerusahaan extends Model
{
    protected $table = 'detailaccount_perusahaan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','nama_perusahaan','alamat1','alamat2','id_provinsi','id_kota','noTlp','id_account','kodepos'];

    protected bool $allowEmptyInserts = false;

    public function getaccountidPerusahaan(){
        return $this->select('detailaccount_perusahaan.*, account.*')
                    ->join('account', 'account.id = detailaccount_perusahaan.id_account')
                    ->findAll();
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

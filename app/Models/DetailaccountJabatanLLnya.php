<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailaccountJabatanLLnya extends Model
{
    protected $table            = 'detailaccount_jabatan_lainnya';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_lengkap','id_prodi','id_jurusan','notlp','id_account','id_jabatan'];

    protected bool $allowEmptyInserts = false;

    public function getrelationjabatanll(){
        $builder = $this->db->table($this->table);
        $builder->select('detailaccount_jabatan_lainnya.*, account.*, prodi.nama_prodi as prodi, jurusan.nama_jurusan as jurusan, jabatan.jabatan as nama_jabatan');
        $builder->join('account', 'account.id = detailaccount_jabatan_lainnya.id_account');
        $builder->join('prodi', 'prodi.id = detailaccount_jabatan_lainnya.id_prodi');
        $builder->join('jurusan', 'jurusan.id = detailaccount_jabatan_lainnya.id_jurusan');
        $builder->join('jabatan', 'jabatan.id = detailaccount_jabatan_lainnya.id_jabatan');
        return $builder->get()->getResult();
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

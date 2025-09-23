<?php
namespace App\Models\User;

use CodeIgniter\Model;

class DetailaccountAtasan extends Model
{
    protected $table            = 'detailaccount_atasan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_lengkap','id_jabatan','notlp','id_account'];

    protected bool $allowEmptyInserts = false;

    public function getrelationAtasan(){
        $builder = $this->db->table($this->table);
        $builder->select('detailaccount_atasan.*, account.*, jabatan.jabatan as nama_jabatan');
        $builder->join('account', 'account.id = detailaccount_atasan.id_account');
        $builder->join('jabatan', 'jabatan.id = detailaccount_atasan.id_jabatan');
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

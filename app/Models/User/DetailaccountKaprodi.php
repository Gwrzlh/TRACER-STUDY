<?php
namespace App\Models\User;

use CodeIgniter\Model;

class DetailaccountKaprodi extends Model
{
    protected $table            = 'detailaccount_kaprodi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_lengkap','id_prodi','id_jurusan','notlp','id_account'];

    protected bool $allowEmptyInserts = false;

     public function getrelationKaprodi(){
        $builder = $this->db->table($this->table);
        $builder->select('detailaccount_kaprodi.*, account.*, prodi.nama_prodi as prodi, jurusan.nama_jurusan as jurusan');
        $builder->join('account', 'account.id = detailaccount_kaprodi.id_account');
        $builder->join('prodi', 'prodi.id = detailaccount_kaprodi.id_prodi');
        $builder->join('jurusan', 'jurusan.id = detailaccount_kaprodi.id_jurusan');
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

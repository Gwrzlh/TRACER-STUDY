<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailaccountAtasan extends Model
{
    protected $table            = 'detailaccount_atasan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_lengkap','id_jabatan','notlp','id_account','id_perusahaan'];

    protected bool $allowEmptyInserts = false;

    public function getrelationAtasan(){
        $builder = $this->db->table($this->table);
        $builder->select('detailaccount_atasan.*, account.*, jabatan.jabatan as nama_jabatan');
        $builder->join('account', 'account.id = detailaccount_atasan.id_account');
        $builder->join('jabatan', 'jabatan.id = detailaccount_atasan.id_jabatan');
        return $builder->get()->getResult();
    }
     public function getAlumniBinaan($idDetailAtasan)
    {
        $sql = "SELECT 
                    da.id,
                    da.nama_lengkap, 
                    da.id_account,
                    da.nim, 
                    COALESCE(j.nama_jurusan, '-') as nama_jurusan,
                    COALESCE(p.nama_prodi, '-') as nama_prodi
                FROM atasan_alumni aa
                JOIN detailaccount_alumni da ON da.id = aa.id_alumni
                LEFT JOIN jurusan j ON j.id = da.id_jurusan
                LEFT JOIN prodi p ON p.id = da.id_prodi
                WHERE aa.id_atasan = ?
                ORDER BY da.nama_lengkap ASC";

        return $this->db->query($sql, [$idDetailAtasan])->getResultArray();
    }

    public function getDetailAtasanId($id_account)
    {
        return $this->db->table('detailaccount_atasan')
            ->select('id')
            ->where('id_account', $id_account)
            ->get()
            ->getRowArray();
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

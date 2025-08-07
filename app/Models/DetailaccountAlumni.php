<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailaccountAlumni extends Model
{
    protected $table            = 'detailaccount_alumni';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_lengkap',
                                    'nim',
                                    'id_jurusan',
                                    'id_prodi',
                                    'angkatan',
                                    'tahun_kelulusan',
                                    'ipk',
                                    'alamat',
                                    'alamat2',
                                    'id_cities',
                                    'id_provinsi',
                                    'kodepos',
                                    'jenisKelamin',
                                'notlp',
                                'id_account'];


    public function getDetailWithRelations($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            detailaccount_alumni.*,
            account.*,
            jurusan.nama_jurusan,
            prodi.nama_prodi
        ');
        $builder->join('jurusan', 'jurusan.id = detailaccount_alumni.id_jurusan', 'left');
        $builder->join('prodi', 'prodi.id = detailaccount_alumni.id_prodi', 'left');
        $builder->join('account','account.id = detailaccount_alumni.id_account');

        if ($id !== null) {
            $builder->where('detailaccount_alumni.id', $id);
            return $builder->get()->getRow();
        }

        return $builder->get()->getResult();
    }

    protected bool $allowEmptyInserts = false;

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

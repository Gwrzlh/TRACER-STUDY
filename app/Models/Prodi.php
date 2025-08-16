<?php

namespace App\Models;

use CodeIgniter\Model;

class Prodi extends Model
{
    protected $table            = 'prodi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    

    protected $allowedFields    = ['nama_prodi', 'id_jurusan'];

    protected $useTimestamps    = false;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks (kosong = tidak masalah)
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Ambil data prodi + nama jurusan terkait (join)
     */
    public function getWithJurusan()
    {
        return $this->select('prodi.*, jurusan.nama_jurusan')
            ->join('jurusan', 'jurusan.id = prodi.id_jurusan')
            ->findAll();
    }
}

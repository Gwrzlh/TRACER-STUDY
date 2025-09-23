<?php

namespace App\Models\Support;

use CodeIgniter\Model;

class Cities extends Model
{
    protected $table            = 'cities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','province_id','name'];

    
   public function getCitiesWithProvince()
    {
        return $this->select('cities.*, provinces.name as province_name')
                    ->join('provinces', 'provinces.id = cities.province_id')
                    ->orderBy('cities.name', 'ASC')
                    ->findAll();
    }

    public function getCitiesByProvince($province_id)
    {
        return $this->where('province_id', $province_id)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function getUniqueProvinces() 
    {
        return $this->select('cities.province_id, provinces.name')
                    ->join('provinces', 'provinces.id = cities.province_id')
                    ->groupBy('cities.province_id')
                    ->orderBy('provinces.name', 'ASC')
                    ->findAll();
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

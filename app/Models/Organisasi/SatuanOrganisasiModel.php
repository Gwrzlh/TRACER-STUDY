<?php

namespace App\Models\Organisasi;

use CodeIgniter\Model;

class SatuanOrganisasiModel extends Model
{
    protected $table = 'satuan_organisasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
    'nama_satuan',
    'nama_singkatan',
    'nama_slug',
    'deskripsi',
    'id_tipe',
    'id_jurusan',   
    'id_prodi',     
    'urutan',
    'satuan_induk'
];

protected $useTimestamps = true;
protected $createdField  = 'created_at';
protected $updatedField  = 'updated_at';


    public function getWithTipe()
    {
        return $this->select('satuan_organisasi.*, tipe_organisasi.nama_tipe')
                    ->join('tipe_organisasi', 'tipe_organisasi.id = satuan_organisasi.id_tipe')
                    ->findAll();
    }
}

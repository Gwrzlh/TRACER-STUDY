<?php

namespace App\Models;

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
        'id_prodi',
        'id_jurusan',
        'urutan',
        'satuan_induk',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;

    // ✅ Tambahkan method untuk ambil data lengkap (JOIN)
    public function getWithTipe()
    {
        return $this->select('satuan_organisasi.*, tipe_organisasi.nama_tipe')
            ->join('tipe_organisasi', 'tipe_organisasi.id = satuan_organisasi.id_tipe')
            ->findAll();
    }
}

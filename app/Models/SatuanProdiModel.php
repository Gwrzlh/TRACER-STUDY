<?php

namespace App\Models;

use CodeIgniter\Model;

class SatuanProdiModel extends Model
{
    protected $table            = 'satuan_prodi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    // kolom yang bisa diisi
    protected $allowedFields    = ['satuan_id', 'prodi_id'];

    // timestamps (opsional, aktifkan kalau ada kolom created_at/updated_at di tabel)
    protected $useTimestamps = false;

    /**
     * Ambil semua prodi berdasarkan satuan organisasi
     */
    public function getProdiBySatuan($satuanId)
    {
        return $this->select('prodi.id, prodi.nama_prodi')
            ->join('prodi', 'prodi.id = satuan_prodi.prodi_id')
            ->where('satuan_prodi.satuan_id', $satuanId)
            ->findAll();
    }

    /**
     * Ambil semua satuan berdasarkan prodi
     */
    public function getSatuanByProdi($prodiId)
    {
        return $this->select('satuan_organisasi.id, satuan_organisasi.nama_satuan')
            ->join('satuan_organisasi', 'satuan_organisasi.id = satuan_prodi.satuan_id')
            ->where('satuan_prodi.prodi_id', $prodiId)
            ->findAll();
    }
}

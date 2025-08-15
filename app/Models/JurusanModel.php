<?php

namespace App\Models;

use CodeIgniter\Model;

class JurusanModel extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_jurusan'];

      // ✅ Tambahkan method untuk ambil data lengkap (JOIN)
   public function getWithTipe()
{
    return $this->select('satuan_organisasi.*, tipe_organisasi.nama_tipe')
                ->join('tipe_organisasi', 'tipe_organisasi.id = satuan_organisasi.id_tipe')
                ->findAll();
}
}

<?php


namespace App\Models\Organisasi;

use CodeIgniter\Model;

class JurusanModel extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_jurusan'];

      // ✅ Tambahkan method untuk ambil data lengkap (JOIN)
   public function getWithRole()
{
    return $this->select('tipe_organisasi.*, role.nama as role_nama')
                ->join('role', 'role.id = tipe_organisasi.id_group')
                ->findAll();
}

}

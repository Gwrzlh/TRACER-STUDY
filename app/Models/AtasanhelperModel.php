<?php
namespace App\Models;

use CodeIgniter\Model;

class AtasanHelperModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Cek relasi atasan-alumni
    public function cekRelasi($atasanAccountId, $alumniDetailId)
    {
        return $this->db->table('detailaccount_atasan da')
            ->join('atasan_alumni aa', 'aa.id_atasan = da.id')
            ->where('da.id_account', $atasanAccountId)
            ->where('aa.id_alumni', $alumniDetailId)
            ->get()
            ->getRowArray();
    }

    // Ambil detail atasan (id dari detailaccount_atasan)
    public function getDetailAtasanId($atasanAccountId)
    {
        return $this->db->table('detailaccount_atasan')
            ->select('id')
            ->where('id_account', $atasanAccountId)
            ->get()
            ->getRowArray();
    }

    // Ambil detail alumni lengkap
    public function getAlumniDetail($alumniDetailId)
    {
        return $this->db->table('detailaccount_alumni')
            ->where('id', $alumniDetailId)
            ->get()
            ->getRowArray();
    }
}
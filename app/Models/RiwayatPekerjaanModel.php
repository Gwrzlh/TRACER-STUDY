<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatPekerjaanModel extends Model
{
    protected $table      = 'riwayat_pekerjaan';  // Nama tabel
    protected $primaryKey = 'id';                 // Primary key
    protected $allowedFields = [
        'id_alumni',           // foreign key ke detailaccount_alumni
        'perusahaan',
        'jabatan',
        'tahun_masuk',
        'tahun_keluar',
        'masih',               // 1 = masih bekerja, 0 = selesai
        'alamat_perusahaan',
        'is_current'           // 1 = pekerjaan sekarang, 0 = riwayat
    ];

    protected $useTimestamps = true;             // auto created_at & updated_at
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

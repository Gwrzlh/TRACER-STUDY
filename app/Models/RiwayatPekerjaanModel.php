<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatPekerjaanModel extends Model
{
    protected $table      = 'riwayat_pekerjaan';
    protected $primaryKey = 'id';

    // Tambahkan kolom id_perusahaan supaya bisa relasi
    protected $allowedFields = [
        'id_alumni',
        'id_perusahaan',       // 🔥 relasi ke detailaccount_perusahaan
        'perusahaan',          // redundan nama perusahaan (biar tampil cepat)
        'jabatan',
        'tahun_masuk',
        'tahun_keluar',
        'masih',
        'alamat_perusahaan',
        'is_current'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ============================
    // 🔍 CUSTOM FUNCTION TAMBAHAN
    // ============================

    /**
     * Ambil semua riwayat kerja alumni beserta nama perusahaan
     * @return array
     */
    public function getRiwayatLengkap()
    {
        return $this->select('riwayat_pekerjaan.*, detailaccount_perusahaan.nama_perusahaan')
            ->join('detailaccount_perusahaan', 'detailaccount_perusahaan.id = riwayat_pekerjaan.id_perusahaan', 'left')
            ->orderBy('riwayat_pekerjaan.tahun_masuk', 'DESC')
            ->findAll();
    }

    /**
     * Ambil semua alumni yang bekerja di perusahaan tertentu
     * (digunakan di halaman Atasan -> Detail Perusahaan)
     * @param int $idPerusahaan
     * @return array
     */
    public function getAlumniByPerusahaan($idPerusahaan)
    {
        return $this->select('riwayat_pekerjaan.*, detailaccount_alumni.nama_lengkap, detailaccount_alumni.nim, detailaccount_alumni.id_prodi, detailaccount_alumni.id_jurusan')
            ->join('detailaccount_alumni', 'detailaccount_alumni.id_account = riwayat_pekerjaan.id_alumni', 'left')
            ->where('riwayat_pekerjaan.id_perusahaan', $idPerusahaan)
            ->orderBy('riwayat_pekerjaan.tahun_masuk', 'DESC')
            ->findAll();
    }

    /**
     * Ambil pekerjaan aktif alumni (yang is_current = 1)
     * @param int $idAlumni
     * @return array|null
     */
    public function getCurrentJob($idAlumni)
    {
        return $this->where('id_alumni', $idAlumni)
            ->where('is_current', 1)
            ->first();
    }
}

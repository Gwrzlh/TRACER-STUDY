<?php

namespace App\Models;

use CodeIgniter\Model;

class KontakModel extends Model
{
    protected $table            = 'kontak';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['kategori', 'id_account', 'alamat'];

    // Kalau mau pakai otomatis created_at / updated_at
    protected $useTimestamps    = false;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Ambil kontak beserta data account terkait.
     */
    public function getKontakWithAccount()
    {
        return $this->select('kontak.*, account.nama_lengkap, account.email, account.telepon')
            ->join('account', 'account.id = kontak.id_account')
            ->findAll();
    }

    /**
     * Ambil data account berdasarkan kategori, 
     * kecuali yang sudah ada di kontak (anti duplikat).
     */
    public function getAvailableAccountsByKategori($kategori)
    {
        return $this->db->table('account')
            ->select('account.*')
            ->where('account.kategori', $kategori)
            ->whereNotIn('account.id', function ($builder) use ($kategori) {
                return $builder->select('id_account')
                    ->from('kontak')
                    ->where('kategori', $kategori);
            })
            ->get()
            ->getResultArray();
    }
}

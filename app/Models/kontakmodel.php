<?php

namespace App\Models;

use CodeIgniter\Model;

class KontakModel extends Model
{
    protected $table = 'kontak';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama',
        'posisi',
        'kualifikasi',
        'tipe_kontak',
        'urutan',
        'aktif',
        'kontak',
        'id_prodi',
        'id_jurusan'
    ];

    protected $returnType = 'array';

    /**
     * Ambil semua kontak aktif dengan relasi prodi dan jurusan
     */
    public function getGroupedContacts()
    {
        return $this->select('kontak.*, prodi.nama_prodi, jurusan.nama_jurusan')
            ->join('prodi', 'prodi.id = kontak.id_prodi', 'left')
            ->join('jurusan', 'jurusan.id = kontak.id_jurusan', 'left')
            ->where('kontak.aktif', 1)
            ->orderBy('tipe_kontak', 'asc')
            ->orderBy('urutan', 'asc')
            ->findAll();
    }
}

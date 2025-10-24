<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanDashboardModel extends Model
{
    protected $table = 'dashboard_alumni'; // Pastikan tabel ini juga menyimpan data dashboard kaprodi
    protected $primaryKey = 'id';
    protected $useTimestamps = true;

    // Kolom yang dapat disimpan/diupdate
    protected $allowedFields = [
        'tipe',              // tipe = 'kaprodi' atau 'alumni'
        'judul',             // Judul utama dashboard
        'deskripsi',         // Teks sapaan / subjudul
        'judul_kuesioner',   // Card 1
        'judul_data_alumni', // Card 2
        'judul_profil',      // Card 3 (Akreditasi)
        'judul_ami',         // Card 4 (AMI)
    ];
}

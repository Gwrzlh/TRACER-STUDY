<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanModel extends Model
{
    protected $table            = 'pesan';
    protected $primaryKey       = 'id_pesan';
    protected $returnType       = 'array'; // biar konsisten return array
    protected $allowedFields    = ['id_pengirim', 'id_penerima', 'subject', 'pesan', 'status'];

    // Aktifkan timestamps
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Hook sebelum insert
    protected $beforeInsert     = ['setDefaultStatus'];

    /**
     * Set default status pesan = 'terkirim'
     */
    protected function setDefaultStatus(array $data)
    {
        if (! isset($data['data']['status']) || empty($data['data']['status'])) {
            $data['data']['status'] = 'terkirim';
        }
        return $data;
    }

    // ==============================
    // Tambahan Helper Method
    // ==============================

    /**
     * Ambil semua pesan masuk untuk user tertentu
     */
    public function getPesanMasuk($idPenerima)
    {
        return $this->where('id_penerima', $idPenerima)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Ambil detail pesan by id
     */
    public function getPesanById($idPesan)
    {
        return $this->find($idPesan);
    }

    /**
     * Tandai pesan sebagai dibaca
     */
    public function markAsRead($idPesan)
    {
        return $this->update($idPesan, ['status' => 'dibaca']);
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanModel extends Model
{
    protected $table            = 'pesan';
    protected $primaryKey       = 'id_pesan';
    protected $allowedFields    = ['id_pengirim', 'id_penerima', 'pesan', 'status'];

    // Aktifkan timestamps
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Optional: biar default status selalu "terkirim" kalau tidak dikasih
    protected $beforeInsert = ['setDefaultStatus'];

    protected function setDefaultStatus(array $data)
    {
        if (! isset($data['data']['status'])) {
            $data['data']['status'] = 'terkirim';
        }
        return $data;
    }
}

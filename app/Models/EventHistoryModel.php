<?php

namespace App\Models;

use CodeIgniter\Model;

class EventHistoryModel extends Model
{
    protected $table = 'event_history';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul3', 'isi3', 'gambar2'];
    protected $useTimestamps = true;
}

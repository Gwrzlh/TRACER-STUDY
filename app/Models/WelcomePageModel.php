<?php

namespace App\Models;
use CodeIgniter\Model;

class WelcomePageModel extends Model
{
    protected $table = 'welcome_page';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title_1', 'desc_1', 'title_2', 'desc_2',
        'title_3', 'desc_3',
        'image_path', 'youtube_url', 'updated_at', 'image_path_2'
    ];
    protected $useTimestamps = false;
}

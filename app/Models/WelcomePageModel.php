<?php

namespace App\Models;

use CodeIgniter\Model;

class WelcomePageModel extends Model
{
    protected $table = 'welcome_page';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title_1',
        'desc_1',
        'title_2',
        'desc_2',
        'title_3',
        'desc_3',
        'image_path',
        'image_path_2',
        'youtube_url',
        'video_path', 
        'updated_at'
    ];
}

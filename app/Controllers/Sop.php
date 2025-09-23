<?php
namespace App\Controllers\LandingPage;

use App\Controllers\BaseController;
use App\Models\LandingPage\TentangModel;

class Sop extends BaseController
{
    protected $tentangModel;

    public function __construct()
    {
        $this->tentangModel = new TentangModel();
    }

    public function index()
    {
$tentang = [
        'judul'  => 'SOP Tracer Study',
        'isi'    => 'Isi dari SOP ...',
        'gambar' => 'sop.jpg',
        'judul2' => 'Tahap Kedua SOP',
        'isi2'   => 'Penjelasan tahap kedua SOP ...',
    ];

    return view('LandingPage/sop', compact('tentang'));
    }
   
}

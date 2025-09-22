<?php
namespace App\Controllers\LandingPage;

use App\Controllers\BaseController;
use App\Models\LandingPage\TentangModel;

class Event extends BaseController
{
    protected $tentangModel;

    public function __construct()
    {
        $this->tentangModel = new TentangModel();
    }

   public function index()
{
    $tentang = [
        'judul3' => 'Event Tracer Study',
        'isi3'   => 'Daftar event terbaru ...',
    ];

    return view('LandingPage/event', compact('tentang'));
}

}

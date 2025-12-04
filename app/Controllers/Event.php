<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TentangModel;

class Event extends BaseController
{
    protected $tentangModel;

    public function __construct()
    {
        $this->tentangModel = new TentangModel();
    }

    public function index()
    {
        // Data event (bisa nanti diganti ambil dari DB)
        $tentang = [
            'judul3'  => 'Event Tracer Study',
            'isi3'    => 'Daftar event terbaru ...',
            'gambar2' => '' // pastikan selalu ada untuk menghindari error
        ];

        return view('LandingPage/event', compact('tentang'));
    }
}
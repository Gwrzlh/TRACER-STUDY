<?php

namespace App\Controllers\User;

use CodeIgniter\Controller;

class AtasanKuesionerController extends Controller
{
    public function index()
    {
        // Dummy data sementara
        $data['kuesioner'] = [
            [
                'id' => 1,
                'judul' => 'questionnaire buat demo',
                'status' => 'Belum Mengisi',
            ],
            [
                'id' => 2,
                'judul' => 'testing222',
                'status' => 'Belum Mengisi',
            ],
        ];

        // arahkan ke app/Views/kuesioner/index.php
      return view('atasan/kuesioner/index', $data);

    }
}

<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AtasanFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user login dan role sesuai
        if (session()->get('role_id') != 8) {
            return redirect()
                ->to('/login')
                ->with('error', 'Akses ditolak. Anda bukan Atasan.');
        }

        return null; // lanjut ke controller
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi tambahan setelah
    }
}

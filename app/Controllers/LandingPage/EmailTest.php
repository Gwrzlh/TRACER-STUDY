<?php

namespace App\Controllers\LandingPage;

use CodeIgniter\Controller;
use Config\Services;

class EmailTest extends Controller
{
    public function index()
    {
        $email = Services::email();

        // alamat tujuan (ganti sesuai kebutuhan)
        $to = 'reyhanvkp01@gmail.com';

        $email->setTo($to);
        $email->setFrom('tspolban@gmail.com', 'Tracer Study Polban');
        $email->setSubject('Testing Email dari CodeIgniter 4 via Brevo SMTP');
        $email->setMessage('<h3>Halo!</h3><p>Email ini dikirim dari <b>CodeIgniter 4</b> menggunakan Brevo SMTP.</p>');

        if ($email->send()) {
            echo "✅ Email berhasil dikirim ke: " . $to;
        } else {
            // debug detail jika gagal
            echo "❌ Gagal kirim email.<br>";
            echo $email->printDebugger(['headers', 'subject', 'body']);
        }
    }
}

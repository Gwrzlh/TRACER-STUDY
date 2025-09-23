<?php

namespace App\Controllers\LandingPage;
use App\Controllers\BaseController;
use App\Models\LandingPage\LaporanModel;

class Laporan extends BaseController
{
    protected $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
    }

    // parameter opsional $tahun
    public function index($tahun = null)
    {
        // Ambil tahun maksimum dari tabel laporan
        $rowMax = $this->laporanModel->selectMax('tahun')->first();
        $maxYear = $rowMax ? (int) $rowMax['tahun'] : date('Y');

        // Kalau user tidak pilih tahun → default tahun terbaru
        if ($tahun === null) {
            $tahun = $maxYear;
        }

        // Ambil semua laporan sesuai tahun
        $laporan = $this->laporanModel
            ->where('tahun', $tahun)
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('landingpage/laporan', [
            'laporan' => $laporan,
            'tahun'   => (int) $tahun,
            'maxYear' => $maxYear
        ]);
    }
}

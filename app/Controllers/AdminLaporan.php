<?php

namespace App\Controllers;

use App\Models\LaporanModel;

class AdminLaporan extends BaseController
{
    protected $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
    }

    /**
     * Halaman Admin: Kelola laporan (max 7 data)
     */
    public function index()
    {
        $laporan = $this->laporanModel
            ->orderBy('urutan', 'ASC')
            ->findAll(7);

        $data['laporan'] = $laporan;

        return view('adminpage/laporan/index', $data);
    }

    /**
     * Simpan atau update banyak laporan sekaligus
     */
    public function save()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->back()->with('error', 'Request tidak valid.');
        }

        $judul   = (array) $this->request->getPost('judul');
        $isi     = (array) $this->request->getPost('isi');
        $urutan  = (array) $this->request->getPost('urutan');
        $tahun   = (array) $this->request->getPost('tahun'); // ambil tahun dari input
        $filePDF = $this->request->getFiles();

        foreach ($urutan as $i => $urut) {
            $jdl        = trim($judul[$i] ?? '');
            $isiLaporan = trim($isi[$i] ?? '');
            $thn        = trim($tahun[$i] ?? '');
            $pdfFile    = $filePDF['file_pdf'][$i] ?? null;

            // Skip jika kosong semua
            if (empty($jdl) && empty($isiLaporan) && empty($thn) && (empty($pdfFile) || !$pdfFile->isValid())) {
                continue;
            }

            // Cek apakah urutan sudah ada di DB
            $laporanDb = $this->laporanModel->where('urutan', $urut)->first();

            // Data default
            $data = [
                'urutan' => $urut,
                'judul'  => $jdl,
                'isi'    => $isiLaporan,
                'tahun'  => $thn,
            ];

            // Upload PDF jika ada
            if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
                $namaPDF = $pdfFile->getRandomName();
                $pdfFile->move(ROOTPATH . 'public/uploads/pdf', $namaPDF);
                $data['file_pdf'] = $namaPDF;

                // Hapus file lama jika update
                if ($laporanDb && !empty($laporanDb['file_pdf'])) {
                    $oldPath = ROOTPATH . 'public/uploads/pdf/' . $laporanDb['file_pdf'];
                    if (is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }
            } else {
                // Jika edit & tidak upload baru → pertahankan file lama
                if ($laporanDb) {
                    $data['file_pdf'] = $laporanDb['file_pdf'];
                }
            }

            // Update atau Insert
            if ($laporanDb) {
                $this->laporanModel->update($laporanDb['id'], $data);
            } else {
                $this->laporanModel->insert($data);
            }
        }

        return redirect()->to(base_url('admin/laporan'))->with('success', 'Semua laporan berhasil disimpan.');
    }

    /**
     * Landing page (public): tampilkan laporan per tahun (jika ada filter)
     */
public function showAll($tahun = null)
{
    if ($tahun === null) {
        $tahun = 2024; // default tahun terbaru
    }

    $laporan = $this->laporanModel
        ->like('judul', $tahun) // filter berdasarkan tahun di judul
        ->orderBy('urutan', 'ASC')
        ->findAll();

    $data = [
        'laporan' => $laporan,
        'tahun'   => $tahun
    ];

    return view('landingpage/laporan', $data);
}


}

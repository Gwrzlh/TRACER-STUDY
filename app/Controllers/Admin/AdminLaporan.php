<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LandingPage\LaporanModel;

class AdminLaporan extends BaseController
{
    protected $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
    }

    /**
     * Halaman Admin: Kelola laporan dengan pagination (5 data per halaman)
     */
    public function index()
    {
        // Ambil laporan dengan pagination (5 per halaman)
        $laporan = $this->laporanModel
            ->orderBy('urutan', 'ASC')
            ->paginate(5, 'laporan'); // <-- paginate(5)

        // Data untuk view
        $data['laporan'] = $laporan;
        $data['pager']   = $this->laporanModel->pager;

        return view('adminpage/laporan/index', $data);
    }

    /**
     * Simpan atau update laporan
     */
    public function save()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->back()->with('error', 'Request tidak valid.');
        }

        $ids        = (array) $this->request->getPost('id');      // ✅ hidden input id[]
        $judul      = (array) $this->request->getPost('judul');
        $isi        = (array) $this->request->getPost('isi');
        $urutan     = (array) $this->request->getPost('urutan');
        $tahun      = (array) $this->request->getPost('tahun'); 
        $filePDF    = $this->request->getFiles();
        $fileGambar = $this->request->getFiles();

        foreach ($judul as $i => $jdl) {
            $id         = $ids[$i] ?? null;
            $isiLaporan = trim($isi[$i] ?? '');
            $urut       = trim($urutan[$i] ?? '');
            $thn        = trim($tahun[$i] ?? '');
            $pdfFile    = $filePDF['file_pdf'][$i] ?? null;
            $imgFile    = $fileGambar['file_gambar'][$i] ?? null;

            // Skip jika kosong semua
            if (
                empty($jdl) &&
                empty($isiLaporan) &&
                empty($thn) &&
                (empty($pdfFile) || !$pdfFile->isValid()) &&
                (empty($imgFile) || !$imgFile->isValid())
            ) {
                continue;
            }

            // Cek apakah data sudah ada di DB (berdasarkan id)
            $laporanDb = $id ? $this->laporanModel->find($id) : null;

            // Data default
            $data = [
                'urutan' => $urut,
                'judul'  => $jdl,
                'isi'    => $isiLaporan,
                'tahun'  => $thn,
            ];

            // ✅ Upload PDF jika ada
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
                if ($laporanDb) {
                    $data['file_pdf'] = $laporanDb['file_pdf'];
                }
            }

            // ✅ Upload Gambar jika ada
            if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
                $namaImg = $imgFile->getRandomName();
                $imgFile->move(ROOTPATH . 'public/uploads/gambar', $namaImg);
                $data['file_gambar'] = $namaImg;

                if ($laporanDb && !empty($laporanDb['file_gambar'])) {
                    $oldPath = ROOTPATH . 'public/uploads/gambar/' . $laporanDb['file_gambar'];
                    if (is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }
            } else {
                if ($laporanDb) {
                    $data['file_gambar'] = $laporanDb['file_gambar'];
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
        // Ambil tahun terbaru dari judul (paling besar yang mengandung angka tahun)
        $latest = $this->laporanModel
            ->select('judul')
            ->orderBy('id', 'DESC')
            ->first();

        // fallback ke tahun sekarang kalau ga ada
        preg_match('/\d{4}/', $latest['judul'] ?? date('Y'), $match);
        $tahun = $match[0] ?? date('Y');
    }

    // Cari laporan yang judulnya mengandung tahun
    $laporan = $this->laporanModel
        ->like('judul', $tahun)
        ->orderBy('urutan', 'ASC')
        ->findAll();

    // Cari tahun terbesar dari judul
    $allJudul = $this->laporanModel->select('judul')->findAll();
    $allYears = [];
    foreach ($allJudul as $row) {
        if (preg_match('/\d{4}/', $row['judul'], $match)) {
            $allYears[] = (int) $match[0];
        }
    }
    $maxYear = !empty($allYears) ? max($allYears) : date('Y');

    $data = [
        'laporan' => $laporan,
        'tahun'   => $tahun,
        'maxYear' => $maxYear
    ];

    return view('landingpage/laporan', $data);
}


    /**
     * Hapus laporan
     */
    public function delete($id)
    {
        $laporan = $this->laporanModel->find($id);

        if (!$laporan) {
            // Kalau AJAX
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan.'
                ]);
            }
            // Kalau bukan AJAX
            return redirect()->to(base_url('admin/laporan'))->with('error', 'Laporan tidak ditemukan.');
        }

        // Hapus file PDF & gambar kalau ada
        if (!empty($laporan['file_pdf']) && file_exists(ROOTPATH . 'public/uploads/pdf/' . $laporan['file_pdf'])) {
            unlink(ROOTPATH . 'public/uploads/pdf/' . $laporan['file_pdf']);
        }
        if (!empty($laporan['file_gambar']) && file_exists(ROOTPATH . 'public/uploads/gambar/' . $laporan['file_gambar'])) {
            unlink(ROOTPATH . 'public/uploads/gambar/' . $laporan['file_gambar']);
        }

        // Hapus data di database
        $this->laporanModel->delete($id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Laporan berhasil dihapus.'
            ]);
        }

        return redirect()->to(base_url('admin/laporan'))->with('success', 'Laporan berhasil dihapus.');
    }
}

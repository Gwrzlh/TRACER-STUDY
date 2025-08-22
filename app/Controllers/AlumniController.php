<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailaccountAlumni;
use App\Models\PesanModel; // <- tambahin model pesan

class AlumniController extends BaseController
{
    protected $pesanModel;

    public function __construct()
    {
        $this->pesanModel = new PesanModel();
    }

    public function dashboard()
    {
        return view('alumni/dashboard');
    }

    public function questioner()
    {
        return view('alumni/questioner/index');
    }

    public function questionersurveyor()
    {
        return view('alumni/alumnisurveyor/questioner/index');
    }

    public function supervisi()
    {
        return view('alumni/alumnisurveyor/supervisi');
    }

    public function lihatTeman()
    {
        $alumniModel  = new \App\Models\DetailaccountAlumni();
        $jurusanModel = new \App\Models\JurusanModel();
        $prodiModel   = new \App\Models\Prodi();

        $currentAlumni = $alumniModel
            ->where('id_account', session('id'))
            ->first();

        if (!$currentAlumni) {
            return redirect()->back()->with('error', 'Data alumni tidak ditemukan.');
        }

        $jurusanNama = $jurusanModel->find($currentAlumni['id_jurusan'])['nama_jurusan'] ?? '-';
        $prodiNama   = $prodiModel->find($currentAlumni['id_prodi'])['nama_prodi'] ?? '-';

        $teman = $alumniModel
            ->where('id_jurusan', $currentAlumni['id_jurusan'])
            ->where('id_prodi', $currentAlumni['id_prodi'])
            ->where('id_account !=', session('id'))
            ->findAll();

        foreach ($teman as &$t) {
            $statuses = ['Finish', 'Ongoing', 'Belum Mengisi'];
            $t['status'] = $statuses[array_rand($statuses)];
        }
        unset($t);

        $data = [
            'teman'   => $teman,
            'jurusan' => $jurusanNama,
            'prodi'   => $prodiNama,
        ];

        return view('alumni/alumnisurveyor/lihat_teman', $data);
    }

    // =============================
    // 🔔 FITUR NOTIFIKASI PESAN
    // =============================

    // 1. Kirim pesan otomatis (dipakai alumni surveyor)
    public function kirimPesan($idPenerima)
    {
        $idPengirim = session()->get('id'); // alumni surveyor yg login

        $pesanOtomatis = "Halo, ada pesan baru dari surveyor terkait tracer study.";

        $insert = $this->pesanModel->insert([
            'id_pengirim' => $idPengirim,
            'id_penerima' => $idPenerima,
            'pesan'       => $pesanOtomatis,
            'status'      => 'terkirim'
        ]);

        if ($insert) {
            return redirect()->back()->with('success', '✅ Pesan berhasil dikirim!');
        } else {
            return redirect()->back()->with('error', '❌ Pesan gagal dikirim.');
        }
    }

    // 2. Ambil notifikasi (buat icon lonceng)
    // Tampilkan halaman notifikasi
    public function notifikasi()
    {
        $idAlumni = session()->get('id');
        $pesan = $this->pesanModel
            ->where('id_penerima', $idAlumni)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('alumni/notifikasi', ['pesan' => $pesan]);
    }

    // Ambil data jumlah notif via AJAX
    public function getNotifCount()
    {
        $idAlumni = session()->get('id');
        $pesan = $this->pesanModel
            ->where('id_penerima', $idAlumni)
            ->where('status', 'terkirim')
            ->findAll();

        return $this->response->setJSON(['jumlah' => count($pesan)]);
    }


    // 3. Tandai sudah dibaca
    public function tandaiDibaca($id_pesan)
    {
        $this->pesanModel->update($id_pesan, ['status' => 'dibaca']);
        return redirect()->back()->with('success', 'Pesan ditandai sudah dibaca.');
    }
    public function hapusNotifikasi($id)
    {
        $pesanModel = new \App\Models\PesanModel();
        $pesan = $pesanModel->find($id);

        if ($pesan && $pesan['id_penerima'] == session()->get('id')) {
            $pesanModel->delete($id);
            return redirect()->to('/alumni/notifikasi')->with('success', 'Pesan berhasil dihapus.');
        }

        return redirect()->to('/alumni/notifikasi')->with('error', 'Pesan tidak ditemukan atau bukan milik Anda.');
    }
    public function pesan($idPenerima)
    {
        // ambil data penerima
        $db = db_connect();
        $penerima = $db->table('account')->where('id', $idPenerima)->get()->getRowArray();

        if (!$penerima) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("User tidak ditemukan");
        }

        return view('alumni/pesan_form', [
            'penerima' => $penerima
        ]);
    }
}

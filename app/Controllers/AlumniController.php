<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailaccountAlumni;
use App\Models\PesanModel;
use App\Models\JurusanModel;
use App\Models\Prodi;

class AlumniController extends BaseController
{
    protected $pesanModel;

    public function __construct()
    {
        $this->pesanModel = new PesanModel();
    }

    // =============================
    // 📊 DASHBOARD & PROFIL
    // =============================
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

    public function profil()
    {
        return view('alumni/profil/index');
    }

    public function editProfil()
    {
        return view('alumni/profil/edit');
    }

    public function supervisi()
    {
        return view('alumni/alumnisurveyor/supervisi');
    }

    // =============================
    // 👥 LIHAT TEMAN
    // =============================
    public function lihatTeman()
    {
        $alumniModel  = new DetailaccountAlumni();
        $jurusanModel = new JurusanModel();
        $prodiModel   = new Prodi();

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

        // contoh dummy status
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
    // 🔔 FITUR PESAN & NOTIFIKASI
    // =============================

    // Form manual kirim pesan
    public function pesan($idPenerima)
    {
        $db = db_connect();
        $penerima = $db->table('account')->where('id', $idPenerima)->get()->getRowArray();

        if (!$penerima) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("User tidak ditemukan");
        }

        return view('alumni/pesanform', [
            'penerima' => $penerima
        ]);
    }

    public function kirimPesanManual()
    {
        $idPengirim = session()->get('id');
        $idPenerima = $this->request->getPost('id_penerima');
        $subject    = $this->request->getPost('subject');
        $message    = $this->request->getPost('message'); // ini untuk notif web

        $db = db_connect();

        // Ambil data pengirim
        $pengirim = $db->table('detailaccount_alumni')
            ->where('id_account', $idPengirim)
            ->get()
            ->getRowArray();
        $namaPengirim = $pengirim['nama_lengkap'] ?? 'Alumni #' . $idPengirim;

        // Simpan notif ke web
        $this->pesanModel->insert([
            'id_pengirim' => $idPengirim,
            'id_penerima' => $idPenerima,
            'subject'     => $subject ?: 'Pesan dari ' . $namaPengirim,
            'pesan'       => $message,
            'status'      => 'terkirim'
        ]);

        // =========================
        // Kirim email otomatis pakai template
        // =========================
        $alumniModel    = new \App\Models\DetailaccountAlumni();
        $templateModel  = new \App\Models\EmailTemplateModel();

        // Data penerima
        $alumni = $alumniModel->where('id_account', $idPenerima)->first();
        $penerima = $db->table('account')->where('id', $idPenerima)->get()->getRowArray();

        if ($alumni && $penerima && !empty($penerima['email'])) {
            $template = $templateModel->where('status', $alumni['status'] ?? 'Belum Mengisi')->first();

            if ($template) {
                // Replace placeholder
                $subjectTpl = $this->replaceTemplate($template['subject'], $alumni);
                $messageTpl = $this->replaceTemplate($template['message'], $alumni);

                $email = \Config\Services::email();
                $email->setFrom('reyhanvkp01@gmail.com', 'Tracer Study');
                $email->setTo($penerima['email']);
                $email->setSubject($subjectTpl);
                $email->setMessage($messageTpl);
                $email->send();
            }
        }

        return redirect()->to('/alumni/lihat_teman')->with('success', 'Pesan berhasil dikirim & email otomatis terkirim.');
    }

    /**
     * Helper untuk replace template dengan data alumni
     */
    private function replaceTemplate(string $text, array $alumni): string
    {
        $jurusanModel = new \App\Models\JurusanModel();
        $prodiModel   = new \App\Models\Prodi();

        $placeholders = [
            '{{nama}}'    => $alumni['nama_lengkap'] ?? '',
            '{{prodi}}'   => $prodiModel->find($alumni['id_prodi'])['nama_prodi'] ?? '',
            '{{jurusan}}' => $jurusanModel->find($alumni['id_jurusan'])['nama_jurusan'] ?? '',
        ];

        return strtr($text, $placeholders);
    }




    // Halaman notifikasi
    public function notifikasi()
    {
        $idAlumni = session()->get('id');
        $pesan = $this->pesanModel
            ->select('pesan.*, detailaccount_alumni.nama_lengkap as nama_pengirim')
            ->join('account', 'account.id = pesan.id_pengirim', 'left')
            ->join('detailaccount_alumni', 'detailaccount_alumni.id_account = account.id', 'left')
            ->where('id_penerima', $idAlumni)
            ->orderBy('pesan.created_at', 'DESC')
            ->findAll();

        return view('alumni/notifikasi', ['pesan' => $pesan]);
    }

    // View detail pesan
    public function viewPesan($idPesan)
    {
        $pesan = $this->pesanModel
            ->select('pesan.*, detailaccount_alumni.nama_lengkap as nama_pengirim')
            ->join('account', 'account.id = pesan.id_pengirim', 'left')
            ->join('detailaccount_alumni', 'detailaccount_alumni.id_account = account.id', 'left')
            ->where('pesan.id_pesan', $idPesan)
            ->first();

        if (!$pesan) {
            return redirect()->to('/alumni/notifikasi')->with('error', 'Pesan tidak ditemukan.');
        }

        // tandai dibaca
        $this->pesanModel->update($idPesan, ['status' => 'dibaca']);

        return view('alumni/viewpesan', ['pesan' => $pesan]);
    }

    // Jumlah notif (AJAX)
    public function getNotifCount()
    {
        $idAlumni = session()->get('id');
        $pesan = $this->pesanModel
            ->where('id_penerima', $idAlumni)
            ->where('status', 'terkirim')
            ->findAll();

        return $this->response->setJSON(['jumlah' => count($pesan)]);
    }

    // Tandai sudah dibaca
    public function tandaiDibaca($id_pesan)
    {
        $this->pesanModel->update($id_pesan, ['status' => 'dibaca']);
        return redirect()->back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    // Hapus pesan
    public function hapusNotifikasi($id)
    {
        $pesan = $this->pesanModel->find($id);

        if ($pesan && $pesan['id_penerima'] == session()->get('id')) {
            $this->pesanModel->delete($id);
            return redirect()->to('/alumni/notifikasi')->with('success', 'Pesan berhasil dihapus.');
        }

        return redirect()->to('/alumni/notifikasi')->with('error', 'Pesan tidak ditemukan atau bukan milik Anda.');
    }
}

<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\DetailaccountAlumni;
use App\Models\LandingPage\PesanModel;
use App\Models\User\AlumniModel;
use App\Models\Organisasi\JurusanModel;
use App\Models\Organisasi\Prodi;

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
        $session = session();
        $alumniModel = new AlumniModel();

        $idAccount = $session->get('id_account');

        // Ambil data alumni dari database
        $alumni = $alumniModel->where('id_account', $idAccount)->first();

        // Kalau tidak ada di DB, fallback dari session biar tidak error
        if (!$alumni) {
            $alumni = [
                'nama_lengkap' => $session->get('nama_lengkap'),
                'nim'          => '-',
                'nama_prodi'   => '-',
                'foto'         => null
            ];
        }

        return view('alumni/profil/index', [
            'alumni' => (object) $alumni
        ]);
    }

    public function editProfil()
    {
        $id = session()->get('id_account');
        $alumniModel = new AlumniModel();
        $alumni = $alumniModel->where('id_account', $id)->first();

        return view('alumni/profil/edit', [
            'alumni' => (object) $alumni
        ]);
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

        return view('alumni/pesan_form', [
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
        $alumniModel    = new \App\Models\User\DetailaccountAlumni();
        $templateModel  = new \App\Models\LandingPage\EmailTemplateModel();

        // Data penerima
        $alumni   = $alumniModel->where('id_account', $idPenerima)->first();
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
        $jurusanModel = new \App\Models\Organisasi\JurusanModel();
        $prodiModel   = new \App\Models\Organisasi\Prodi();

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

    // =============================
    // ✏️ UPDATE PROFIL
    // =============================
    public function update()
    {
        $id_account = session()->get('id_account');
        $alumniModel = new AlumniModel();

        $alumni = $alumniModel->where('id_account', $id_account)->first();
        if (!$alumni) {
            return redirect()->to(base_url('alumni/profil'))
                ->with('error', 'Data alumni tidak ditemukan');
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'alamat'       => $this->request->getPost('alamat'),
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move('uploads', $newName);
            $data['foto'] = $newName;
        }

        $alumniModel->where('id_account', $id_account)->set($data)->update();

        return redirect()->to(base_url('alumni/profil'))->with('success', 'Profil berhasil diupdate');
    }

    public function updateProfil()
    {
        $session = session();
        $idAccount = $session->get('id_account');

        if (!$idAccount) {
            return redirect()->to('/login')->with('error', 'Silakan login kembali.');
        }

        $alumniModel = new \App\Models\User\AlumniModel();

        // Ambil data dari form
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'alamat'       => $this->request->getPost('alamat'),
        ];

        // Upload foto jika ada
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads', $newName);
            $data['foto'] = $newName;
        }

        // Update data alumni
        $alumniModel->where('id_account', $idAccount)->set($data)->update();

        // ✅ update session supaya sidebar ikut berubah langsung
        if (isset($data['nama_lengkap']) && !empty($data['nama_lengkap'])) {
            $session->set('nama_lengkap', $data['nama_lengkap']);
        }
        if (isset($data['foto'])) {
            $session->set('foto', $data['foto']);
        }

        return redirect()->to(base_url('alumni/profil'))->with('success', 'Profil berhasil diperbarui.');
    }
    public function updateFoto($idAccount)
    {
        $alumniModel = new AlumniModel();
        $uploadPath = FCPATH . 'uploads/foto_alumni/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $alumni = $alumniModel->where('id_account', $idAccount)->first();
        if (!$alumni) {
            return redirect()->back()->with('error', 'Data alumni tidak ditemukan.');
        }

        $newName = null;

        // Upload file dari komputer
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
        }
        // Upload dari kamera
        elseif ($this->request->getPost('foto_camera')) {
            $dataUrl = $this->request->getPost('foto_camera');
            $dataParts = explode(',', $dataUrl);
            if (isset($dataParts[1])) {
                $decoded = base64_decode($dataParts[1]);
                $newName = uniqid('foto_') . '.png';
                file_put_contents($uploadPath . $newName, $decoded);
            } else {
                return redirect()->back()->with('error', 'Format foto dari kamera salah');
            }
        } else {
            return redirect()->back()->with('error', 'Tidak ada file untuk diupload');
        }

        // Update database
        if ($newName && file_exists($uploadPath . $newName)) {
            $alumniModel->where('id_account', $idAccount)
                ->set(['foto' => $newName])
                ->update();

            // ✅ Update session supaya sidebar ikut berubah otomatis
            $session = session();
            $session->set('foto', $newName);

            return redirect()->back()->with('success', 'Foto profil berhasil diubah');
        }

        return redirect()->back()->with('error', 'Gagal update database');
    }
}

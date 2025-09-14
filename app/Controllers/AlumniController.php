<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailaccountAlumni;
use App\Models\PesanModel;
use App\Models\AlumniModel;
use App\Models\JurusanModel;
use App\Models\Prodi;
use App\Models\RiwayatPekerjaanModel;

// Tambahan untuk kuesioner
use App\Models\QuestionnairModel;
use App\Models\ResponseModel;
use App\Models\AnswerModel;
use App\Models\QuestionModel;

class AlumniController extends BaseController
{
    protected $pesanModel;
    protected $alumniModel;
    protected $riwayatModel;

    public function __construct()
    {
        $this->pesanModel = new PesanModel();
        $this->alumniModel = new AlumniModel();
        $this->riwayatModel = new RiwayatPekerjaanModel();
    }

    // =============================
    // 📊 DASHBOARD & PROFIL
    // =============================
    public function dashboard($role = 'alumni')
    {
        $session = session();

        // Pastikan session role tersimpan
        $role = ($role === 'surveyor') ? 'surveyor' : 'alumni';
        $session->set('role', $role);

        $alumniId = $session->get('id');

        $questionnaireModel = new \App\Models\QuestionnairModel();
        $responseModel      = new \App\Models\ResponseModel();

        $totalKuesioner = $questionnaireModel
            ->where('is_active', 'active')
            ->countAllResults();

        $selesai = $responseModel
            ->where('account_id', $alumniId)
            ->where('status', 'completed')
            ->countAllResults();

        $sedangBerjalan = $responseModel
            ->where('account_id', $alumniId)
            ->where('status', 'draft')
            ->countAllResults();

        $showNotifikasi = $role === 'alumni';
        $showLihatTeman = $role === 'surveyor';

        return view('alumni/dashboard', [
            'totalKuesioner' => $totalKuesioner,
            'selesai' => $selesai,
            'sedangBerjalan' => $sedangBerjalan,
            'showNotifikasi' => $showNotifikasi,
            'showLihatTeman' => $showLihatTeman,
            'role' => $role
        ]);
    }



    // public function questioner()
    // {
    // langsung pakai method baru
    //     return $this->questionnairesForAlumni();
    // }


    // public function questionersurveyor()
    // {
    //     return view('alumni/alumnisurveyor/questioner/index');
    // }

    // =============================
    // 📊 PROFIL ALUMNI (BISA UNTUK BIASA & SURVEYOR)
    // =============================
    public function profil($role = 'alumni')
    {
        $session     = session();
        $alumniModel = new \App\Models\DetailaccountAlumni();
        $idAccount   = $session->get('id_account');

        // ambil data alumni
        $alumni = $alumniModel
            ->select('detailaccount_alumni.*, prodi.nama_prodi, jurusan.nama_jurusan')
            ->join('prodi', 'prodi.id = detailaccount_alumni.id_prodi', 'left')
            ->join('jurusan', 'jurusan.id = detailaccount_alumni.id_jurusan', 'left')
            ->where('detailaccount_alumni.id_account', $idAccount)
            ->first();

        // ambil pekerjaan saat ini
        $currentJob = $this->riwayatModel
            ->where('id_alumni', $idAccount)
            ->where('is_current', 1)
            ->first();

        // ambil riwayat pekerjaan
        $riwayat = $this->riwayatModel
            ->where('id_alumni', $idAccount)
            ->where('is_current', 0)
            ->orderBy('tahun_masuk', 'DESC')
            ->findAll();

        // tentukan layout
        $layout = ($role === 'surveyor')
            ? 'layout/sidebar_alumni2'
            : 'layout/sidebar_alumni';

        return view('alumni/profil/index', [
            'alumni'     => $alumni ? (object) $alumni : null,
            'layout'     => $layout,
            'role'       => $role,
            'currentJob' => $currentJob ? (object) $currentJob : null,   // 🔑 convert ke object
            'riwayat'    => array_map(fn($r) => (object) $r, $riwayat)   // 🔑 convert semua ke object
        ]);
    }



    // public function editProfil($role = 'alumni')
    // {
    //     $session     = session();
    //     $alumniModel = new \App\Models\DetailaccountAlumni();
    //     $idAccount   = $session->get('id_account');

    //     $alumni = $alumniModel->where('id_account', $idAccount)->first();

    //     $layout = ($role === 'surveyor')
    //         ? 'layout/sidebar_alumni2'
    //         : 'layout/sidebar_alumni';

    //     return view('alumni/profil/edit', [
    //         'alumni' => (object) $alumni,
    //         'layout' => $layout
    //     ]);
    // }

    // =============================
    // 👔 PEKERJAAN
    // =============================
    public function pekerjaan($role = 'alumni')
    {
        $session = session();
        $idAccount = $session->get('id_account');

        // Ambil pekerjaan saat ini
        $currentJob = $this->riwayatModel
            ->where('id_alumni', $idAccount)
            ->where('is_current', 1)
            ->first();

        $layout = ($role === 'surveyor') ? 'layout/sidebar_alumni2' : 'layout/sidebar_alumni';

        return view('alumni/profil/pekerjaan', [
            'currentJob' => $currentJob,
            'layout'     => $layout,
        ]);
    }

    public function savePekerjaan()
    {
        $session   = session();
        $idAccount = $session->get('id_account');

        $perusahaan        = $this->request->getPost('perusahaan');
        $jabatan           = $this->request->getPost('jabatan');
        $tahun_masuk       = $this->request->getPost('tahun_masuk');
        $tahun_keluar      = $this->request->getPost('tahun_keluar');
        $alamat_perusahaan = $this->request->getPost('alamat_perusahaan');
        $status_kerja      = $this->request->getPost('status_kerja'); // "masih" / "hingga"

        // 🔹 Pindahkan pekerjaan lama ke riwayat
        $oldJob = $this->riwayatModel
            ->where('id_alumni', $idAccount)
            ->where('is_current', 1)
            ->first();

        if ($oldJob) {
            $this->riwayatModel->update($oldJob['id'], [
                'is_current'   => 0,
                'masih'        => 0, // otomatis jadi riwayat
                'tahun_keluar' => ($oldJob['masih'] == 1 || $oldJob['tahun_keluar'] === '0000')
                    ? date('Y') // kalau masih "0000" isi tahun sekarang
                    : $oldJob['tahun_keluar']
            ]);
        }

        // 🔹 Tentukan data baru
        $dataBaru = [
            'id_alumni'         => $idAccount,
            'perusahaan'        => $perusahaan,
            'jabatan'           => $jabatan,
            'tahun_masuk'       => $tahun_masuk,
            'alamat_perusahaan' => $alamat_perusahaan,
            'is_current'        => 1 // pekerjaan baru selalu jadi current
        ];

        if ($status_kerja === 'masih') {
            // Jika masih bekerja
            $dataBaru['masih']        = 1;
            $dataBaru['tahun_keluar'] = '0000';
        } else {
            // Jika hingga tahun tertentu
            $dataBaru['masih']        = 0;
            $dataBaru['tahun_keluar'] = $tahun_keluar;
        }

        // 🔹 Simpan pekerjaan baru
        $this->riwayatModel->insert($dataBaru);

        return redirect()->to('/alumni/profil')->with('success', 'Pekerjaan berhasil disimpan.');
    }



    // =============================
    // 📜 RIWAYAT PEKERJAAN
    // =============================
    public function riwayatPekerjaan($role = 'alumni')
    {
        $session   = session();
        $idAccount = $session->get('id_account');

        $riwayat = $this->riwayatModel
            ->where('id_alumni', $idAccount)
            ->where('is_current', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // 🔹 Ubah semua array hasil query jadi object
        $riwayat = array_map(fn($r) => (object) $r, $riwayat);

        $layout = ($role === 'surveyor') ? 'layout/sidebar_alumni2' : 'layout/sidebar_alumni';

        return view('alumni/profil/riwayat_pekerjaan', [
            'riwayat' => $riwayat,
            'layout'  => $layout
        ]);
    }


    public function deleteRiwayat($id)
    {
        $this->riwayatModel->delete($id);
        return redirect()->back()->with('success', 'Riwayat pekerjaan berhasil dihapus.');
    }


    public function profilSurveyor()
    {
        return $this->profil('surveyor');
    }

    // public function editProfilSurveyor()
    // {
    //     return $this->editProfil('surveyor');
    // }


    public function supervisi()
    {
        // Set role surveyor di session supaya sidebar menampilkan Lihat Teman
        session()->set('role', 'surveyor');

        // Panggil dashboard dengan role surveyor
        return $this->dashboard('surveyor');
    }

    // =============================
    // 👥 LIHAT TEMAN
    // =============================
    public function lihatTeman()
    {
        $alumniModel  = new DetailaccountAlumni();
        $jurusanModel = new JurusanModel();
        $prodiModel   = new Prodi();
        $db           = \Config\Database::connect();

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

        // cek status kuesioner multi
        foreach ($teman as &$t) {
            $responses = $db->table('responses')
                ->where('account_id', $t['id_account'])
                ->get()
                ->getResult();

            if (empty($responses)) {
                $t['status'] = 'Belum Mengisi';
            } else {
                $statuses = array_column($responses, 'status');

                if (count(array_unique($statuses)) === 1 && $statuses[0] === 'completed') {
                    // semua completed
                    $t['status'] = 'Finish';
                } else {
                    // ada draft atau campuran
                    $t['status'] = 'Ongoing';
                }
            }
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
        $message    = $this->request->getPost('message');

        $db = db_connect();

        // ✅ 1. Simpan pesan ke dashboard (notif internal)
        $this->pesanModel->insert([
            'id_pengirim' => $idPengirim,
            'id_penerima' => $idPenerima,
            'pesan'       => $message,
            'status'      => 'terkirim'
        ]);

        // ✅ 2. Ambil data penerima
        $alumniModel   = new \App\Models\DetailaccountAlumni();
        $templateModel = new \App\Models\EmailTemplateModel();

        $alumni   = $alumniModel->where('id_account', $idPenerima)->first();
        $penerima = $db->table('account')->where('id', $idPenerima)->get()->getRowArray();

        // ✅ 3. Cari template email sesuai status
        if ($alumni && $penerima && !empty($penerima['email'])) {
            $status   = $alumni['status'] ?? 'Belum Mengisi';
            $template = $templateModel->where('status', $status)->first();

            if ($template) {
                // Replace placeholder {nama_lengkap} dll
                $subjectTpl = $this->replaceTemplate($template['subject'], $alumni);
                $messageTpl = $this->replaceTemplate($template['message'], $alumni);

                // ✅ 4. Kirim email via Brevo API
                $this->sendEmailBrevo($penerima['email'], $subjectTpl, $messageTpl);
            }
        }

        return redirect()->to('/alumni/lihat_teman')
            ->with('success', 'Pesan berhasil dikirim');
    }


    private function replaceTemplate(string $text, array $alumni): string
    {
        $jurusanModel = new \App\Models\JurusanModel();
        $prodiModel   = new \App\Models\Prodi();

        $prodi   = $alumni['id_prodi'] ? $prodiModel->find($alumni['id_prodi']) : null;
        $jurusan = $alumni['id_jurusan'] ? $jurusanModel->find($alumni['id_jurusan']) : null;

        $placeholders = [
            '[NAMA]'    => $alumni['nama_lengkap'] ?? '',
            '[PRODI]'   => $prodi['nama_prodi'] ?? '',
            '[JURUSAN]' => $jurusan['nama_jurusan'] ?? '',
        ];

        return strtr($text, $placeholders);
    }

    private function sendEmailBrevo(string $toEmail, string $subject, string $htmlContent): void
    {
        $apiKey = getenv('BREVO_API_KEY');

        $data = [
            "sender" => [
                "email" => "tspolban@gmail.com", // pastikan sudah diverifikasi di Brevo
                "name"  => "Tracer Study Polban"
            ],
            "to" => [["email" => $toEmail]],
            "subject"     => $subject,
            "htmlContent" => $htmlContent
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/smtp/email");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "accept: application/json",
            "api-key: {$apiKey}",
            "content-type: application/json",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            log_message('error', 'Brevo API Error: ' . curl_error($ch));
        } else {
            log_message('info', "Brevo API Response ({$httpCode}): " . $response);
        }

        curl_close($ch);
    }


    public function notifikasi()
    {
        $idAlumni = session()->get('id');
        $pesan = $this->pesanModel
            ->select('pesan.*, COALESCE(detailaccount_alumni.nama_lengkap, account.username, "Pengguna") as nama_pengirim')
            ->join('account', 'account.id = pesan.id_pengirim', 'left')
            ->join('detailaccount_alumni', 'detailaccount_alumni.id_account = account.id', 'left')
            ->where('id_penerima', $idAlumni)
            ->orderBy('pesan.created_at', 'DESC')
            ->findAll();

        return view('alumni/notifikasi', ['pesan' => $pesan]);
    }

    public function viewPesan($idPesan)
    {
        $pesan = $this->pesanModel
            ->select('pesan.*, COALESCE(detailaccount_alumni.nama_lengkap, account.username, "Pengguna") as nama_pengirim')
            ->join('account', 'account.id = pesan.id_pengirim', 'left')
            ->join('detailaccount_alumni', 'detailaccount_alumni.id_account = account.id', 'left')
            ->where('pesan.id_pesan', $idPesan)
            ->first();

        if (!$pesan) {
            return redirect()->to('/alumni/notifikasi')->with('error', 'Pesan tidak ditemukan.');
        }

        // update status
        $this->pesanModel->update($idPesan, ['status' => 'dibaca']);

        return view('alumni/viewpesan', ['pesan' => $pesan]);
    }


    public function getNotifCount()
    {
        $idAlumni = session()->get('id');
        $pesan = $this->pesanModel
            ->where('id_penerima', $idAlumni)
            ->where('status', 'terkirim')
            ->findAll();

        return $this->response->setJSON(['jumlah' => count($pesan)]);
    }

    public function tandaiDibaca($id_pesan)
    {
        $this->pesanModel->update($id_pesan, ['status' => 'dibaca']);
        return redirect()->back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

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
    // ✏️ UPDATE PROFIL (Data + Foto)
    // =============================
    public function updateProfil($role = 'alumni')
    {
        $session   = session();
        $idAccount = $session->get('id_account');

        if (!$idAccount) {
            return redirect()->to('/login')->with('error', 'Silakan login kembali.');
        }

        $alumniModel = new \App\Models\AlumniModel();
        $riwayatModel = new \App\Models\RiwayatPekerjaanModel();

        // =====================
        // Update data profil
        // =====================
        $profilData = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'alamat'       => $this->request->getPost('alamat'),
        ];

        // Upload foto jika ada
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/foto_alumni/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

            $newName = $foto->getRandomName();
            $foto->move($uploadPath, $newName);
            $profilData['foto'] = $newName;

            // Update session foto
            $session->set('foto', $newName);
        }

        // Update tabel alumni
        $alumniModel->where('id_account', $idAccount)->set($profilData)->update();

        // Update session nama_lengkap
        if (!empty($profilData['nama_lengkap'])) {
            $session->set('nama_lengkap', $profilData['nama_lengkap']);
        }

        // =====================
        // Tambah riwayat pekerjaan (jika ada)
        // =====================
        if ($this->request->getPost('perusahaan')) {
            $riwayatData = [
                'id_alumni'         => $idAccount,
                'perusahaan'        => $this->request->getPost('perusahaan'),
                'jabatan'           => $this->request->getPost('jabatan'),
                'tahun_masuk'       => $this->request->getPost('tahun_masuk'),
                'tahun_keluar'      => $this->request->getPost('tahun_keluar'),
                'alamat_perusahaan' => $this->request->getPost('alamat_perusahaan'),
            ];

            $riwayatModel->insert($riwayatData);
        }

        // =====================
        // Redirect sesuai role
        // =====================
        $redirectUrl = $role === 'surveyor'
            ? base_url('alumni/surveyor/profil')
            : base_url('alumni/profil');

        return redirect()->to($redirectUrl)->with('success', 'Profil & riwayat pekerjaan berhasil diperbarui.');
    }


    // public function editProfil($role = 'alumni')
    // {
    //     $session = session();
    //     $idAccount = $session->get('id_account');

    //     // Ambil data alumni
    //     $alumniModel = new \App\Models\DetailaccountAlumni();
    //     $alumni = $alumniModel->where('id_account', $idAccount)->first();

    //     // Ambil riwayat pekerjaan alumni
    //     $riwayatModel = new RiwayatPekerjaanModel();
    //     $riwayat = $riwayatModel->where('id_alumni', $idAccount)->orderBy('tahun_masuk', 'DESC')->findAll();

    //     // Tentukan layout
    //     $layout = ($role === 'surveyor') ? 'layout/sidebar_alumni2' : 'layout/sidebar_alumni';

    //     return view('alumni/profil/edit', [
    //         'alumni'  => (object) $alumni,
    //         'riwayat' => $riwayat,
    //         'layout'  => $layout
    //     ]);
    // }




    // =============================
    // 📸 UPDATE FOTO SAJA (Upload / Kamera)
    // =============================
    public function updateFoto($idAccount, $role = 'alumni')
    {
        // Cari data alumni berdasarkan id_account
        $alumni = $this->alumniModel->where('id_account', $idAccount)->first();
        if (!$alumni) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data alumni tidak ditemukan'
            ]);
        }

        $uploadPath = FCPATH . 'uploads/foto_alumni/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = null;

        // 🔹 Upload dari file (manual pilih file)
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Format file tidak didukung (hanya jpg, jpeg, png)'
                ]);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
        }
        // 🔹 Upload dari kamera (base64)
        elseif ($this->request->getPost('foto_camera')) {
            $dataUrl = $this->request->getPost('foto_camera');
            $dataParts = explode(',', $dataUrl);
            if (!isset($dataParts[1])) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Format foto dari kamera salah'
                ]);
            }
            $decoded = base64_decode($dataParts[1]);
            $newName = uniqid('foto_') . '.png';
            file_put_contents($uploadPath . $newName, $decoded);
        } else {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tidak ada file untuk diupload'
            ]);
        }

        // 🔹 Update database dan session
        if ($newName && file_exists($uploadPath . $newName)) {
            // Update data alumni (pastikan pakai id_account sebagai filter)
            $this->alumniModel->where('id_account', $idAccount)
                ->set(['foto' => $newName])
                ->update();

            // Update session biar sidebar/profil ikut ganti
            session()->set('foto', $newName);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Foto profil berhasil diubah',
                'fotoUrl' => base_url('uploads/foto_alumni/' . $newName) . '?t=' . time()
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal update database'
        ]);
    }
}

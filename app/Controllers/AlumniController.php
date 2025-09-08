<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailaccountAlumni;
use App\Models\PesanModel;
use App\Models\AlumniModel;
use App\Models\JurusanModel;
use App\Models\Prodi;

// Tambahan untuk kuesioner
use App\Models\QuestionnairModel;
use App\Models\ResponseModel;
use App\Models\AnswerModel;
use App\Models\QuestionModel;

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
        $session = session();
        $alumniId = $session->get('id'); // pastikan sesuai dengan kolom id user di tabel responses

        $questionnaireModel = new \App\Models\QuestionnairModel();
        $responseModel      = new \App\Models\ResponseModel();

        // Hitung total kuesioner yang aktif
        $totalKuesioner = $questionnaireModel
            ->where('is_active', 'active')
            ->countAllResults();

        // Hitung kuesioner yang sudah diselesaikan oleh alumni ini
        $selesai = $responseModel
            ->where('account_id', $alumniId)
            ->where('status', 'completed')
            ->countAllResults();

        // Hitung kuesioner yang sedang berjalan (draft / belum complete)
        $sedangBerjalan = $responseModel
            ->where('account_id', $alumniId)
            ->where('status', 'draft')
            ->countAllResults();

        return view('alumni/dashboard', [
            'title'          => 'Dashboard Alumni',
            'totalKuesioner' => $totalKuesioner,
            'selesai'        => $selesai,
            'sedangBerjalan' => $sedangBerjalan,
        ]);
    }

    // public function questioner()
    // {
    // langsung pakai method baru
    //     return $this->questionnairesForAlumni();
    // }


    public function questionersurveyor()
    {
        return view('alumni/alumnisurveyor/questioner/index');
    }

    // =============================
    // 📊 PROFIL ALUMNI (BISA UNTUK BIASA & SURVEYOR)
    // =============================
    public function profil($role = 'alumni')
    {
        $session     = session();
        $alumniModel = new AlumniModel();
        $idAccount   = $session->get('id_account');

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

        // Tentukan layout sesuai role
        $viewFile = $role === 'surveyor'
            ? 'alumni/alumnisurveyor/profil/index'
            : 'alumni/profil/index';

        return view($viewFile, [
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
        $message    = $this->request->getPost('message');

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

        // Kirim email otomatis pakai template
        $alumniModel    = new \App\Models\DetailaccountAlumni();
        $templateModel  = new \App\Models\EmailTemplateModel();

        $alumni   = $alumniModel->where('id_account', $idPenerima)->first();
        $penerima = $db->table('account')->where('id', $idPenerima)->get()->getRowArray();

        if ($alumni && $penerima && !empty($penerima['email'])) {
            $template = $templateModel->where('status', $alumni['status'] ?? 'Belum Mengisi')->first();

            if ($template) {
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

        $alumniModel = new \App\Models\AlumniModel();

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'alamat'       => $this->request->getPost('alamat'),
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads', $newName);
            $data['foto'] = $newName;
        }

        $alumniModel->where('id_account', $idAccount)->set($data)->update();

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
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
        } elseif ($this->request->getPost('foto_camera')) {
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

        if ($newName && file_exists($uploadPath . $newName)) {
            $alumniModel->where('id_account', $idAccount)
                ->set(['foto' => $newName])
                ->update();

            $session = session();
            $session->set('foto', $newName);

            return redirect()->back()->with('success', 'Foto profil berhasil diubah');
        }

        return redirect()->back()->with('error', 'Gagal update database');
    }

    // =============================
    // 📋 KUESIONER ALUMNI (BARU)
    // =============================
    public function questionnairesForAlumni()
    {
        $alumniModel = new DetailaccountAlumni();
        $alumni = $alumniModel->where('id_account', session()->get('id_account'))->first();

        if (!$alumni) {
            return redirect()->back()->with('error', 'Data alumni tidak ditemukan.');
        }

        $questionnaireModel = new QuestionnairModel();
        $responseModel      = new ResponseModel();

        $allQuestionnaires = $questionnaireModel->where('is_active', 'active')->findAll();
        $list = [];

        foreach ($allQuestionnaires as $q) {
            $show = true;

            if (!empty($q['conditional_logic'])) {
                $conds = json_decode($q['conditional_logic'], true);
                foreach ($conds as $c) {
                    if ($c['field'] === 'id_jurusan' && $alumni['id_jurusan'] != $c['value']) $show = false;
                    if ($c['field'] === 'id_prodi' && $alumni['id_prodi'] != $c['value']) $show = false;
                }
            }

            if (!$show) continue;

            $response = $responseModel->where('questionnaire_id', $q['id'])
                ->where('account_id', $alumni['id_account'])
                ->first();

            if (!$response) {
                $status = 'Belum Mengisi';
            } elseif ($response['status'] === 'draft') {
                $status = 'On Going';
            } else {
                $status = 'Finish';
            }

            $q['alumni_status'] = $status;
            $list[] = $q;
        }

        return view('alumni/questionnaires', ['questionnaires' => $list]);
    }
    public function fillQuestionnaire($questionnaireId)
    {
        $questionModel = new \App\Models\QuestionModel();
        $optionModel   = new \App\Models\QuestionOptionModel();

        // Ambil semua pertanyaan
        $questions = $questionModel
            ->where('questionnaires_id', $questionnaireId)
            ->orderBy('order_no', 'ASC')
            ->findAll();

        // Tambahkan opsi untuk pertanyaan yang punya pilihan
        foreach ($questions as &$q) {
            $q['options'] = $optionModel->getQuestionOptions($q['id']);
        }

        return view('alumni/fill_questionnaire', [
            'questions' => $questions,
            'questionnaire_id' => $questionnaireId
        ]);
    }


    public function submitAnswers()
    {
        $responseModel = new ResponseModel();
        $answerModel   = new AnswerModel();

        $accountId     = session()->get('id_account');
        $qid           = $this->request->getPost('questionnaire_id');
        $answers       = $this->request->getPost('answers');

        $response = $responseModel->where('questionnaire_id', $qid)
            ->where('account_id', $accountId)
            ->first();

        if (!$response) {
            $responseId = $responseModel->insert([
                'questionnaire_id' => $qid,
                'account_id'       => $accountId,
                'status'           => 'draft',
                'ip_address'       => $this->request->getIPAddress(),
                'submitted_at'     => date('Y-m-d H:i:s')
            ]);
        } else {
            $responseId = $response['id'];
        }

        $answerModel->where('response_id', $responseId)->delete();
        $answerModel->saveAnswers($responseId, $answers);

        $status = $this->request->getPost('submit_final') ? 'completed' : 'draft';
        $responseModel->update($responseId, [
            'status' => $status,
            'submitted_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('questionnaires')
            ->with('success', 'Jawaban berhasil disimpan.');
    }
    public function questionnairesForSurveyor()
    {
        $alumniModel = new DetailaccountAlumni();
        $alumni = $alumniModel->where('id_account', session()->get('id_account'))->first();

        if (!$alumni) {
            return redirect()->back()->with('error', 'Data alumni tidak ditemukan.');
        }

        $questionnaireModel = new QuestionnairModel();
        $responseModel      = new ResponseModel();

        $allQuestionnaires = $questionnaireModel->where('is_active', 'active')->findAll();
        $list = [];

        foreach ($allQuestionnaires as $q) {
            $show = true;

            // cek conditional logic sama seperti alumni biasa
            if (!empty($q['conditional_logic'])) {
                $conds = json_decode($q['conditional_logic'], true);
                foreach ($conds as $c) {
                    if ($c['field'] === 'id_jurusan' && $alumni['id_jurusan'] != $c['value']) $show = false;
                    if ($c['field'] === 'id_prodi' && $alumni['id_prodi'] != $c['value']) $show = false;
                }
            }

            if (!$show) continue;

            $response = $responseModel->where('questionnaire_id', $q['id'])
                ->where('account_id', $alumni['id_account'])
                ->first();

            if (!$response) {
                $status = 'Belum Mengisi';
            } elseif ($response['status'] === 'draft') {
                $status = 'On Going';
            } else {
                $status = 'Finish';
            }

            $q['alumni_status'] = $status;
            $list[] = $q;
        }

        return view('alumni/alumnisurveyor/questioner/questionnaire', ['questionnaires' => $list]);
    }
}

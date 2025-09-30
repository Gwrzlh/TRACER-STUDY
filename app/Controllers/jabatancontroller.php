<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QuestionModel;
use App\Models\AnswerModel;
use App\Models\Prodi;

class JabatanController extends Controller
{
    public function dashboard()
    {
        // Cek role hanya untuk Jabatan Lainnya
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $questionModel = new QuestionModel();
        $answerModel   = new AnswerModel();

        // Hitung jumlah pertanyaan AMI & Akreditasi
        $totalPertanyaanAmi = $questionModel->where('is_for_ami', 1)->countAllResults();
        $totalPertanyaanAkreditasi = $questionModel->where('is_for_accreditation', 1)->countAllResults();

        // Hitung jumlah jawaban AMI (completed)
        $db = \Config\Database::connect();

        $totalJawabanAmi = $db->table('answers a')
            ->join('questions q', 'q.id = a.question_id')
            ->where('q.is_for_ami', 1)
            ->where('a.status', 'completed')
            ->countAllResults();

        // Hitung jumlah jawaban Akreditasi (completed)
        $totalJawabanAkreditasi = $db->table('answers a')
            ->join('questions q', 'q.id = a.question_id')
            ->where('q.is_for_accreditation', 1)
            ->where('a.status', 'completed')
            ->countAllResults();

        // Data untuk grafik default (AMI)
        $grafikAmi = $db->table('answers a')
            ->select('q.question_text, COUNT(a.id) as total')
            ->join('questions q', 'q.id = a.question_id')
            ->where('q.is_for_ami', 1)
            ->where('a.status', 'completed')
            ->groupBy('q.id')
            ->get()->getResultArray();

        // Data untuk grafik default (Akreditasi)
        $grafikAkreditasi = $db->table('answers a')
            ->select('q.question_text, COUNT(a.id) as total')
            ->join('questions q', 'q.id = a.question_id')
            ->where('q.is_for_accreditation', 1)
            ->where('a.status', 'completed')
            ->groupBy('q.id')
            ->get()->getResultArray();

        $data = [
            'totalPertanyaanAmi'        => $totalPertanyaanAmi,
            'totalPertanyaanAkreditasi' => $totalPertanyaanAkreditasi,
            'totalJawabanAmi'           => $totalJawabanAmi,
            'totalJawabanAkreditasi'    => $totalJawabanAkreditasi,
            'grafikAmi'                 => $grafikAmi,
            'grafikAkreditasi'          => $grafikAkreditasi,
        ];

        return view('jabatan/dashboard', $data);
    }

    /**
     * Halaman filter AMI / Akreditasi per prodi
     */
    public function amiAkreditasi()
    {
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $prodiModel = new Prodi();
        $prodiList  = $prodiModel->getWithJurusan();

        return view('jabatan/ami_akreditasi', [
            'prodiList'     => $prodiList,
            'answers'       => [],
            'selectedProdi' => null,
            'selectedJenis' => null,
        ]);
    }



    /**
     * Proses filter dropdown (Prodi + Jenis)
     */
    public function filterAmiAkreditasi()
    {
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $prodiId = $this->request->getPost('prodi_id');
        $jenis   = $this->request->getPost('jenis'); // ami / akreditasi

        $answerModel = new AnswerModel();
        $answers = $answerModel->getAnswersRaw($prodiId);


        $prodiModel = new Prodi();
        $prodiList  = $prodiModel->getWithJurusan();

        return view('jabatan/ami_akreditasi', [
            'prodiList'     => $prodiList,
            'answers'       => $answers,
            'selectedProdi' => $prodiId,
            'selectedJenis' => $jenis,
        ]);
    }

    // ================== Detail AMI / Akreditasi ==================
    private function loadQuestionsAndAnswers($jenis)
    {
        $questionModel = new QuestionModel();
        $answerModel = new AnswerModel();

        $field = $jenis === 'ami' ? 'is_for_ami' : 'is_for_accreditation';
        $questions = $questionModel->where($field, 1)->orderBy('created_at', 'ASC')->findAll();

        $selectedQuestion = $this->request->getGet('question_id');
        $answers = [];

        if ($selectedQuestion) {
            $answers = $answerModel->getAnswersRaw(null, $selectedQuestion);
        }

        return [
            'questions' => $questions,
            'answers' => $answers,
            'selectedQuestion' => $selectedQuestion
        ];
    }

    public function detailAmi()
    {
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $prodiModel = new Prodi();
        $prodiList = $prodiModel->getWithJurusan();
        $data = $this->loadQuestionsAndAnswers('ami');

        return view('jabatan/detail_ami', array_merge($data, [
            'prodiList' => $prodiList
        ]));
    }

    public function detailAkreditasi()
    {
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $prodiModel = new Prodi();
        $prodiList = $prodiModel->getWithJurusan();
        $data = $this->loadQuestionsAndAnswers('akreditasi');

        return view('jabatan/detail_akreditasi', array_merge($data, [
            'prodiList' => $prodiList
        ]));
    }
}

<?php

namespace App\Controllers;

use App\Models\ResponseModel;
use App\Models\JurusanModel;
use App\Models\Prodi;
use App\Models\AlumniModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use CodeIgniter\I18n\Time;
use App\Models\AnswerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AdminRespon extends BaseController
{
    protected $responseModel;
    protected $alumniModel;
    protected $jurusanModel;
    protected $prodiModel;
    protected $answersModel;

    public function __construct()
    {
        $this->responseModel = new ResponseModel();
        $this->alumniModel   = new AlumniModel();
        $this->jurusanModel  = new JurusanModel();
        $this->prodiModel    = new Prodi();
        $this->answersModel  = new AnswerModel();

    }

    public function index()
    {
        $filters = $this->getFiltersFromRequest();
        $perPage = 10;
        $currentPage = (int) ($this->request->getVar('page') ?? 1);
        if ($currentPage < 1) $currentPage = 1;

        // Ambil semua Prodi + Jurusan
        $allProdi = $this->prodiModel
            ->select('prodi.id, prodi.nama_prodi, jurusan.nama_jurusan')
            ->join('jurusan', 'jurusan.id = prodi.id_jurusan', 'left')
            ->findAll();

        // Builder utama
        $builder = $this->alumniModel->db->table('detailaccount_alumni da')
            ->select("
            da.id_account,
            da.nim,
            da.nama_lengkap,
            da.angkatan,
            da.tahun_kelulusan,
            j.nama_jurusan,
            p.nama_prodi,
            r.id as response_id,
            r.questionnaire_id,
            r.submitted_at,
            r.status as response_status,
            q.title as judul_kuesioner
        ")
            ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->join('responses r', 'r.account_id = da.id_account', 'left')
            ->join('questionnaires q', 'q.id = r.questionnaire_id', 'left');

        // apply filter
        if (!empty($filters['prodi'])) $builder->where('da.id_prodi', $filters['prodi']);
        if (!empty($filters['jurusan'])) $builder->where('da.id_jurusan', $filters['jurusan']);
        if (!empty($filters['angkatan'])) $builder->where('da.angkatan', $filters['angkatan']);
        if (!empty($filters['tahun'])) $builder->where('da.tahun_kelulusan', $filters['tahun']);
        if (!empty($filters['nim'])) $builder->like('da.nim', $filters['nim']);
        if (!empty($filters['nama'])) $builder->like('da.nama_lengkap', $filters['nama']);
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'Belum') {
                $builder->where('r.status IS NULL');
            } else {
                $builder->where('r.status', $filters['status']);
            }
        }

        // Hitung total
        $totalData = $builder->countAllResults(false);

        // Ambil semua data untuk summary counter
        $allResponses = $builder->get()->getResultArray();

        $totalCompleted = count(array_filter($allResponses, fn($r) => ($r['response_status'] ?? '') === 'completed'));
        $totalDraft     = count(array_filter($allResponses, fn($r) => ($r['response_status'] ?? '') === 'draft'));
        $totalBelum     = count(array_filter($allResponses, fn($r) => empty($r['response_status'])));
        $totalOngoing   = $totalDraft; // alias saja

        // Ambil data untuk halaman sekarang
        $offset = ($currentPage - 1) * $perPage;
        $responses = $this->alumniModel->db->table('detailaccount_alumni da')
            ->select("
            da.id_account,
            da.nim,
            da.nama_lengkap,
            da.angkatan,
            da.tahun_kelulusan,
            j.nama_jurusan,
            p.nama_prodi,
            r.id as response_id,
            r.questionnaire_id,
            r.submitted_at,
            r.status as response_status,
            q.title as judul_kuesioner
        ")
            ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->join('responses r', 'r.account_id = da.id_account', 'left')
            ->join('questionnaires q', 'q.id = r.questionnaire_id', 'left')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        foreach ($responses as &$res) {
            $res['status'] = $res['response_status'] ?: 'belum';
            $res['submitted_at'] = $res['submitted_at']
                ? Time::parse($res['submitted_at'], 'Asia/Jakarta')->toDateTimeString()
                : '-';
        }

        $totalPages = ceil($totalData / $perPage);
        if ($currentPage > $totalPages && $totalPages > 0) {
            $currentPage = $totalPages;
        }

        $data = [
            'filters'        => $filters,
            'responses'      => $responses,
            'perPage'        => $perPage,
            'currentPage'    => $currentPage,
            'totalData'      => $totalData,
            'totalPages'     => $totalPages,
            'allYears'       => $this->alumniModel->getDistinctTahunKelulusan(),
            'allQuestionnaires' => $this->responseModel->getAllQuestionnaires(),
            'allJurusan'     => $this->jurusanModel->findAll(),
            'allProdi'       => $allProdi,
            'allAngkatan'    => $this->alumniModel->getDistinctAngkatan(),
            'totalCompleted' => $totalCompleted,
            'totalOngoing'   => $totalOngoing,
            'totalDraft'     => $totalDraft,
            'totalBelum'     => $totalBelum,

            // 🔹 untuk filter di view
            'selectedYear'          => $filters['tahun'] ?? '',
            'selectedStatus'        => $filters['status'] ?? '',
            'selectedQuestionnaire' => $filters['questionnaire'] ?? '',
            'selectedNim'           => $filters['nim'] ?? '',
            'selectedNama'          => $filters['nama'] ?? '',
            'selectedJurusan'       => $filters['jurusan'] ?? '',
            'selectedProdi'         => $filters['prodi'] ?? '',
            'selectedAngkatan'      => $filters['angkatan'] ?? '',
        ];

        return view('adminpage/respon/index', $data);
    }



    // ================== HAPUS JAWABAN ==================
    public function deleteAnswer($id)
    {
        $answerModel = new \App\Models\AnswerModel();
        $deleted = $answerModel->deleteAnswerAndCheckResponse($id);

        if ($deleted) {
            return redirect()->back()->with('success', 'Jawaban berhasil dihapus (beserta response jika kosong)');
        } else {
            return redirect()->back()->with('error', 'Jawaban tidak ditemukan');
        }
    }

    // ================== LANDING PAGE RESPON ==================
    public function responseLanding()
    {
        $responseModel = new \App\Models\ResponseModel();

        $yearsRaw = $responseModel->getAvailableYears() ?? [];
        $allYears = array_column($yearsRaw, 'tahun');

        $selectedYear = $this->request->getGet('tahun');
        if (!$selectedYear && !empty($allYears)) {
            $selectedYear = $allYears[0];
        }
        if (!$selectedYear) {
            $selectedYear = date('Y');
        }

        $data = [
            'selectedYear' => $selectedYear,
            'allYears'     => $allYears,
            'data'         => $responseModel->getSummaryByYear($selectedYear)
        ];

        return view('LandingPage/respon', $data);
    }






    // ================== EXPORT EXCEL ==================
    public function exportExcel()
    {
        $filters = $this->getFiltersFromRequest();
        $responses = $this->alumniModel->getWithResponses($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([
            'NIM',
            'Nama Alumni',
            'Jurusan',
            'Prodi',
            'Angkatan',
            'Tahun Lulusan',
            'Judul Kuesioner',
            'Status',
            'Tanggal Submit'
        ], null, 'A1');

        $row = 2;
        foreach ($responses as $res) {
            $status = match ($res['status']) {
                'completed' => 'Sudah',
                'draft'     => 'Belum',
                'ongoing'   => 'Ongoing',
                null        => 'Belum Mengisi',
                default     => ucfirst($res['status']),
            };

            $sheet->setCellValue("A{$row}", $res['nim'] ?? '-');
            $sheet->setCellValue("B{$row}", $res['nama_lengkap'] ?? '-');
            $sheet->setCellValue("C{$row}", $res['nama_jurusan'] ?? '-');
            $sheet->setCellValue("D{$row}", $res['nama_prodi'] ?? '-');
            $sheet->setCellValue("E{$row}", $res['angkatan'] ?? '-');
            $sheet->setCellValue("F{$row}", $res['tahun_kelulusan'] ?? '-');
            $sheet->setCellValue("G{$row}", $res['judul_kuesioner'] ?? '-');
            $sheet->setCellValue("H{$row}", $status);
            $sheet->setCellValue("I{$row}", $res['submitted_at'] ?? '-');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Respon_' . ($filters['tahun'] ?? 'all') . '.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // ================== DETAIL ==================
    public function detail($id)
    {
        $response = $this->responseModel->find($id);
        if (!$response) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                "Respon dengan ID $id tidak ditemukan"
            );
        }

        $questionnaireId = $response['questionnaire_id'];
        $accountId       = $response['account_id'];

        $questionnaireModel = new \App\Models\QuestionnairModel();
        $answerModel        = new \App\Models\AnswerModel();

        $answers = $answerModel->getUserAnswers($questionnaireId, $accountId);
        $structure = $questionnaireModel->getQuestionnaireStructure(
            $questionnaireId,
            ['id' => $accountId],
            $answers
        );

        if (empty($structure) || empty($structure['pages'])) {
            return redirect()->to('/admin/respon')
                ->with('error', 'Struktur kuesioner tidak ditemukan atau kosong.');
        }

        return view('adminpage/respon/detail', [
            'structure' => $structure,
            'answers'   => $answers,
            'response'  => $response,
        ]);
    }

    private function getFiltersFromRequest(): array
    {
        return [
            'tahun'         => $this->request->getGet('year'),
            'status'        => $this->request->getGet('status'),
            'questionnaire' => $this->request->getGet('questionnaire_id'),
            'nim'           => $this->request->getGet('nim'),
            'nama'          => $this->request->getGet('nama'),
            'jurusan'       => $this->request->getGet('jurusan'),
            'prodi'         => $this->request->getGet('prodi'),
            'angkatan'      => $this->request->getGet('angkatan'),
            'sort_by'       => $this->request->getGet('sort_by'),   // baru
            'sort_order'    => $this->request->getGet('sort_order') // baru
        ];
    }
    public function grafik()
    {
        $filters = $this->request->getGet();

        $allYears     = $this->alumniModel->getDistinctTahunKelulusan();
        $allAngkatan  = $this->alumniModel->getDistinctAngkatan();
        $allJurusan   = (new \App\Models\JurusanModel())->findAll();
        $allProdi     = (new \App\Models\Prodi())->findAll();

        $builder = $this->alumniModel->db->table('detailaccount_alumni da')
            ->select("
            p.nama_prodi,
            SUM(CASE WHEN r.status = 'completed' THEN 1 ELSE 0 END) AS total_completed,
            SUM(CASE WHEN r.status = 'draft' THEN 1 ELSE 0 END) AS total_draft,
            SUM(CASE WHEN r.id IS NULL THEN 1 ELSE 0 END) AS total_belum
        ")
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->join('responses r', 'r.account_id = da.id_account', 'left');

        if (!empty($filters['prodi'])) $builder->where('da.id_prodi', $filters['prodi']);
        if (!empty($filters['jurusan'])) $builder->where('da.id_jurusan', $filters['jurusan']);
        if (!empty($filters['angkatan'])) $builder->where('da.angkatan', $filters['angkatan']);
        if (!empty($filters['tahun'])) $builder->where('da.tahun_kelulusan', $filters['tahun']);
        if (!empty($filters['nim'])) $builder->like('da.nim', $filters['nim']);
        if (!empty($filters['nama'])) $builder->like('da.nama_lengkap', $filters['nama']);
        if (!empty($filters['status'])) $builder->where('r.status', $filters['status']);

        $builder->groupBy('p.nama_prodi')
            ->orderBy('p.nama_prodi', 'ASC');

        $summary = $builder->get()->getResultArray();

        return view('adminpage/respon/grafik', [
            'summary'      => $summary,
            'filters'      => $filters,
            'allYears'     => $allYears,
            'allAngkatan'  => $allAngkatan,
            'allJurusan'   => $allJurusan,
            'allProdi'     => $allProdi,
            'selectedYear'     => $filters['tahun'] ?? '',
            'selectedStatus'   => $filters['status'] ?? '',
            'selectedNim'      => $filters['nim'] ?? '',
            'selectedNama'     => $filters['nama'] ?? '',
            'selectedJurusan'  => $filters['jurusan'] ?? '',
            'selectedProdi'    => $filters['prodi'] ?? '',
            'selectedAngkatan' => $filters['angkatan'] ?? '',
        ]);
    }



    public function exportPdf($id)
    {
        $response = $this->responseModel->find($id);
        if (!$response) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                "Respon dengan ID $id tidak ditemukan"
            );
        }

        $questionnaireId = $response['questionnaire_id'];
        $accountId       = $response['account_id'];

        $questionnaireModel = new \App\Models\QuestionnairModel();
        $answerModel        = new \App\Models\AnswerModel();

        // 🔹 Ambil nama_lengkap + jurusan + prodi dari detailaccount_alumni
        $db = \Config\Database::connect();
        $builder = $db->table('detailaccount_alumni da')
            ->select('da.nama_lengkap, j.nama_jurusan, p.nama_prodi')
            ->join('account a', 'a.id = da.id_account', 'left')
            ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->where('a.id', $accountId)
            ->get()
            ->getRowArray();

        $namaLengkap = $builder['nama_lengkap'] ?? 'Alumni';
        $jurusan     = $builder['nama_jurusan'] ?? '-';
        $prodi       = $builder['nama_prodi'] ?? '-';

        // 🔹 Ambil jawaban
        $answers = $answerModel->getUserAnswers($questionnaireId, $accountId);
        $structure = $questionnaireModel->getQuestionnaireStructure(
            $questionnaireId,
            ['id' => $accountId],
            $answers
        );

        if (empty($structure) || empty($structure['pages'])) {
            return redirect()->to('/admin/respon')
                ->with('error', 'Struktur kuesioner tidak ditemukan atau kosong.');
        }

        // 🔹 Load view ke HTML
        $html = view('adminpage/respon/detail_pdf', [
            'structure' => $structure,
            'answers'   => $answers,
            'response'  => $response,
            'nama'      => $namaLengkap,
            'jurusan'   => $jurusan,
            'prodi'     => $prodi,
        ]);

        // 🔹 Konfigurasi Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 🔹 Nama file pakai nama lengkap
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $namaLengkap);
        $filename = "Jawaban_Alumni_" . $safeName . ".pdf";

        $dompdf->stream($filename, ["Attachment" => true]);
        exit;
    }

    public function allowEdit($questionnaire_id, $id_account)
    {
        log_message('debug', "allowEdit called with questionnaire_id: {$questionnaire_id}, id_account: {$id_account}");

        if (!is_numeric($questionnaire_id) || $questionnaire_id <= 0 || !is_numeric($id_account) || $id_account <= 0) {
            log_message('error', 'Invalid parameters');
            throw PageNotFoundException::forPageNotFound('Invalid parameters');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            log_message('debug', 'Checking response for questionnaire_id: ' . $questionnaire_id . ', account_id: ' . $id_account);
            $response = $this->responseModel->where([
                'questionnaire_id' => $questionnaire_id,
                'account_id' => $id_account
            ])->first();

            if (!$response) {
                log_message('error', 'Response not found');
                throw new \Exception('Data response tidak ditemukan.');
            }

            log_message('debug', 'Response found: ' . json_encode($response));

            if ($response['status'] === 'draft') {
                log_message('info', 'Response already in draft mode');
                $db->transRollback();
                session()->setFlashdata('info', 'Status sudah dalam mode draft.');
                return redirect()->back();
            }

            // Update responses
            log_message('debug', 'Updating responses status to draft');
            $updatedResponse = $this->responseModel->updateStatus($questionnaire_id, $id_account, 'draft');
            if (!$updatedResponse) {
                log_message('error', 'Failed to update responses table');
                throw new \Exception('Gagal update status di table responses.');
            }
            $affectedResponse = $db->affectedRows();
            log_message('debug', 'Responses updated, affected rows: ' . $affectedResponse);

            // Check answers
            $answerCount = $this->answersModel->where([
                'questionnaire_id' => $questionnaire_id,
                'user_id' => $id_account
            ])->countAllResults();
            log_message('debug', "Found {$answerCount} answers for questionnaire_id: {$questionnaire_id}, user_id: {$id_account}");

            if ($answerCount === 0) {
                log_message('warning', 'No answers found to update. Proceeding with response update only.');
            } else {
                // Update answers
                log_message('debug', 'Updating answers status to draft');
                $updatedAnswers = $this->answersModel->batchUpdateStatus($questionnaire_id, $id_account, 'draft');
                if (!$updatedAnswers) {
                    log_message('error', 'Failed to update answers table (query error)');
                    throw new \Exception('Gagal update status di table answers.');
                }
                $affectedAnswers = $db->affectedRows();
                log_message('debug', 'Answers updated, affected rows: ' . $affectedAnswers);
                if ($affectedAnswers === 0) {
                    log_message('warning', 'Answers update successful but no rows affected (status already draft?).');
                }
            }

            $db->transComplete();
            if ($db->transStatus() === false) {
                log_message('error', 'Transaction failed');
                throw new \Exception('Transaction gagal.');
            }

            log_message('debug', 'Transaction committed successfully');
            session()->setFlashdata('success', 'Status jawaban berhasil diubah menjadi draft.');
        } catch (\Exception $e) {
            log_message('error', 'Exception in allowEdit: ' . $e->getMessage());
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal mengubah status: ' . $e->getMessage());
        }

        return redirect()->back();
    }

}

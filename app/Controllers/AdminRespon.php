<?php

namespace App\Controllers;

use App\Models\ResponseModel;
use App\Models\JurusanModel;
use App\Models\Prodi;
use App\Models\AlumniModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminRespon extends BaseController
{
    protected $responseModel;
    protected $alumniModel;
    protected $jurusanModel;
    protected $prodiModel;

    public function __construct()
    {
        $this->responseModel = new ResponseModel();
        $this->alumniModel   = new AlumniModel();
        $this->jurusanModel  = new JurusanModel();
        $this->prodiModel    = new Prodi();
    }

    // ================== INDEX ==================
    public function index()
    {
        $filters = $this->getFiltersFromRequest();

        // Ambil semua Prodi beserta Jurusan
        $allProdi = $this->prodiModel
            ->select('prodi.id, prodi.nama_prodi, jurusan.nama_jurusan')
            ->join('jurusan', 'jurusan.id = prodi.id_jurusan', 'left')
            ->findAll();

        $data = [
            'filters'             => $filters,
            'selectedYear'        => $filters['tahun'] ?? '',
            'selectedStatus'      => $filters['status'] ?? '',
            'selectedQuestionnaire' => $filters['questionnaire'] ?? '',
            'selectedNim'         => $filters['nim'] ?? '',
            'selectedNama'        => $filters['nama'] ?? '',
            'selectedJurusan'     => $filters['jurusan'] ?? '',
            'selectedProdi'       => $filters['prodi'] ?? '',
            'selectedAngkatan'    => $filters['angkatan'] ?? '',

            // Dropdown
            'allYears'            => $this->alumniModel->getDistinctTahunKelulusan(),
            'allQuestionnaires'   => $this->responseModel->getAllQuestionnaires(),
            'allJurusan'          => $this->jurusanModel->findAll(),
            'allProdi'            => $allProdi,
            'allAngkatan'         => $this->alumniModel->getDistinctAngkatan(),

            // Data alumni sesuai filter
            'responses'           => $this->alumniModel->getWithResponses($filters),

            // Summary counter
            'totalCompleted'      => $this->responseModel->getTotalCompleted(),
            'totalDraft'          => $this->responseModel->getTotalDraft(),
            'totalBelum'          => $this->responseModel->getTotalBelum(),
        ];

        return view('adminpage/respon/index', $data);
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

        // Ambil data untuk dropdown
        $allYears     = $this->alumniModel->getDistinctTahunKelulusan();
        $allAngkatan  = $this->alumniModel->getDistinctAngkatan();
        $allJurusan   = (new \App\Models\JurusanModel())->findAll();
        $allProdi     = (new \App\Models\Prodi())->findAll();

        // Builder untuk grafik
        $builder = $this->alumniModel->db->table('detailaccount_alumni da')
            ->select("
            p.nama_prodi,
            SUM(CASE WHEN r.status = 'completed' THEN 1 ELSE 0 END) AS total_completed,
            SUM(CASE WHEN r.status = 'draft' THEN 1 ELSE 0 END) AS total_draft,
            SUM(CASE WHEN r.id IS NULL THEN 1 ELSE 0 END) AS total_belum
        ")
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->join('responses r', 'r.account_id = da.id_account', 'left');

        // Filter prodi
        if (!empty($filters['prodi'])) {
            $builder->where('da.id_prodi', $filters['prodi']);
        }

        // Filter jurusan
        if (!empty($filters['jurusan'])) {
            $builder->where('da.id_jurusan', $filters['jurusan']);
        }

        // Filter angkatan
        if (!empty($filters['angkatan'])) {
            $builder->where('da.angkatan', $filters['angkatan']);
        }

        // Filter tahun kelulusan
        if (!empty($filters['tahun'])) {
            $builder->where('da.tahun_kelulusan', $filters['tahun']);
        }

        // Filter NIM
        if (!empty($filters['nim'])) {
            $builder->like('da.nim', $filters['nim']);
        }

        // Filter nama
        if (!empty($filters['nama'])) {
            $builder->like('da.nama_lengkap', $filters['nama']);
        }

        // Filter status
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'Belum') {
                $builder->where('r.id IS NULL');
            } else {
                $builder->where('r.status', $filters['status']);
            }
        }

        $builder->groupBy('p.nama_prodi')
            ->orderBy('p.nama_prodi', 'ASC');

        $summary = $builder->get()->getResultArray();

        return view('adminpage/respon/grafik', [
            'summary'      => $summary,
            'filters'      => $filters,
            'allYears'     => $allYears,
            'allAngkatan'  => $allAngkatan,
            'allJurusan'   => $allJurusan,
            'allProdi'     => $allProdi
        ]);
    }
}

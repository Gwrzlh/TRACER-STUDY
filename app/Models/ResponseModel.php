<?php

namespace App\Models;

use CodeIgniter\Model;

class ResponseModel extends Model
{
    protected $table            = 'responses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'questionnaire_id',
        'account_id',
        'submitted_at',
        'status',
        'ip_address'
    ];

    protected bool $allowEmptyInserts = false;

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // ================== RESPONSE DETAIL ==================
    public function getResponseWithAnswers($responseId)
    {
        return $this->db->table('responses r')
            ->select('r.*, a.question_id, a.answer_text, q.question_text')
            ->join('answers a', 'r.questionnaire_id = a.questionnaire_id AND r.account_id = a.account_id', 'left')
            ->join('questions q', 'a.question_id = q.id', 'left')
            ->where('r.id', $responseId)
            ->get()
            ->getResultArray();
    }


    public function hasResponded($questionnaireId, $accountId): bool
    {
        return $this->where('questionnaire_id', $questionnaireId)
            ->where('account_id', $accountId)
            ->where('status', 'completed')
            ->countAllResults() > 0;
    }

    // ================== SUMMARY ==================
    public function getSummary()
    {
        return $this->select("status, COUNT(*) as total")
            ->groupBy("status")
            ->findAll();
    }

    // ================== FILTERED RESPONSES ==================
    public function getFilteredResponses(array $filters = [])
    {
        $builder = $this->db->table('detailaccount_alumni da')
            ->select("
                da.id as alumni_id,
                da.nama_lengkap,
                da.nim,
                da.angkatan,
                da.tahun_kelulusan,
                j.nama_jurusan,
                p.nama_prodi,
                r.id as response_id,
                r.status,
                r.submitted_at,
                q.title as judul_kuesioner
            ")
            ->join('account a', 'a.id = da.id_account', 'left')
            ->join('responses r', 'r.account_id = a.id', 'left')
            ->join('questionnaires q', 'q.id = r.questionnaire_id', 'left')
            ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left');

        // Filter dinamis
        if (!empty($filters['tahun']))      $builder->where('da.tahun_kelulusan', $filters['tahun']);
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'Belum') {
                $builder->where('r.id IS NULL');
            } else {
                $builder->where('r.status', $filters['status']);
            }
        }
        if (!empty($filters['questionnaire'])) $builder->where('r.questionnaire_id', $filters['questionnaire']);
        if (!empty($filters['nim']))           $builder->like('da.nim', $filters['nim']);
        if (!empty($filters['nama']))          $builder->like('da.nama_lengkap', $filters['nama']);
        if (!empty($filters['jurusan']))       $builder->where('da.id_jurusan', $filters['jurusan']);
        if (!empty($filters['prodi']))         $builder->where('da.id_prodi', $filters['prodi']);
        if (!empty($filters['angkatan']))      $builder->where('da.angkatan', $filters['angkatan']);

        $builder->orderBy('da.nama_lengkap', 'ASC');

        return $builder->get()->getResultArray();
    }

    // ================== SUMMARY COUNTER ==================
    public function getTotalCompleted(array $filters = []): int
    {
        return count(array_filter($this->getFilteredResponses($filters), fn($r) => $r['status'] === 'completed'));
    }

    public function getTotalDraft(array $filters = []): int
    {
        return count(array_filter($this->getFilteredResponses($filters), fn($r) => $r['status'] === 'draft'));
    }

    public function getTotalBelum(array $filters = []): int
    {
        $responses = $this->getFilteredResponses($filters);
        return count(array_filter($responses, fn($r) => empty($r['status'])));
    }

    // ================== DROPDOWN DATA ==================
    public function getAvailableYears()
    {
        return $this->db->table('detailaccount_alumni')
            ->distinct()
            ->select('tahun_kelulusan as tahun')
            ->orderBy('tahun', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAllYears()
    {
        return $this->db->table('detailaccount_alumni')
            ->select('tahun_kelulusan as year')
            ->where('tahun_kelulusan IS NOT NULL')
            ->groupBy('tahun_kelulusan')
            ->orderBy('tahun_kelulusan', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAllQuestionnaires()
    {
        return $this->db->table('questionnaires')
            ->select('id, title')
            ->orderBy('title', 'ASC')
            ->get()
            ->getResultArray();
    }

    // ================== SUMMARY PER PRODI ==================
    public function getSummaryByYear($tahun)
    {
        // Ambil semua alumni di tahun tertentu beserta prodi dan account_id
        $alumniData = $this->db->table('detailaccount_alumni da')
            ->select("da.id_account, p.nama_prodi")
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->where('da.tahun_kelulusan', $tahun)
            ->get()
            ->getResultArray();

        // Ambil jumlah kuesioner total
        $totalQuestionnaires = $this->db->table('questionnaires')->countAllResults();

        // Ambil jumlah responses completed per alumni
        $responses = $this->db->table('responses')
            ->select("account_id, COUNT(id) as completed_count")
            ->where('status', 'completed')
            ->groupBy('account_id')
            ->get()
            ->getResultArray();

        $responsesMap = [];
        foreach ($responses as $r) {
            $responsesMap[$r['account_id']] = $r['completed_count'];
        }

        // Inisialisasi summary per prodi
        $summary = [];
        foreach ($alumniData as $alumni) {
            $prodi = $alumni['nama_prodi'];
            $accountId = $alumni['id_account'];

            $completed = $responsesMap[$accountId] ?? 0;

            if ($completed == 0) {
                $status = 'belum';
            } elseif ($completed < $totalQuestionnaires) {
                $status = 'ongoing';
            } else {
                $status = 'finish';
            }

            if (!isset($summary[$prodi])) {
                $summary[$prodi] = [
                    'prodi' => $prodi,
                    'finish' => 0,
                    'ongoing' => 0,
                    'belum' => 0,
                    'jumlah' => 0
                ];
            }

            $summary[$prodi][$status]++;
            $summary[$prodi]['jumlah']++;
        }

        // Hitung persentase finish
        foreach ($summary as &$s) {
            $s['persentase'] = $s['jumlah'] > 0 ? round(($s['finish'] / $s['jumlah']) * 100, 2) : 0;
        }

        return array_values($summary);
    }


    // ================== SUMMARY DENGAN FILTER ==================
    public function getSummaryByFilters(array $filters = [])
    {
        // Ambil alumni sesuai filter
        $builder = $this->db->table('detailaccount_alumni da')
            ->select("da.id_account, p.nama_prodi")
            ->join('prodi p', 'p.id = da.id_prodi', 'left');

        if (!empty($filters['tahun']))      $builder->where('da.tahun_kelulusan', $filters['tahun']);
        if (!empty($filters['jurusan']))    $builder->where('da.id_jurusan', $filters['jurusan']);
        if (!empty($filters['prodi']))      $builder->where('da.id_prodi', $filters['prodi']);
        if (!empty($filters['angkatan']))   $builder->where('da.angkatan', $filters['angkatan']);

        $alumniData = $builder->get()->getResultArray();

        // Ambil jumlah kuesioner total
        $totalQuestionnaires = $this->db->table('questionnaires')->countAllResults();

        // Ambil jumlah responses completed per alumni
        $responses = $this->db->table('responses')
            ->select("account_id, COUNT(id) as completed_count")
            ->where('status', 'completed')
            ->groupBy('account_id')
            ->get()
            ->getResultArray();

        $responsesMap = [];
        foreach ($responses as $r) {
            $responsesMap[$r['account_id']] = $r['completed_count'];
        }

        // Hitung summary per prodi
        $summary = [];
        foreach ($alumniData as $alumni) {
            $prodi = $alumni['nama_prodi'];
            $accountId = $alumni['id_account'];
            $completed = $responsesMap[$accountId] ?? 0;

            if ($completed == 0) {
                $status = 'belum';
            } elseif ($completed < $totalQuestionnaires) {
                $status = 'ongoing';
            } else {
                $status = 'finish';
            }

            if (!isset($summary[$prodi])) {
                $summary[$prodi] = [
                    'prodi' => $prodi,
                    'finish' => 0,
                    'ongoing' => 0,
                    'belum' => 0,
                    'jumlah' => 0
                ];
            }

            $summary[$prodi][$status]++;
            $summary[$prodi]['jumlah']++;
        }

        // Hitung persentase finish
        foreach ($summary as &$s) {
            $s['persentase'] = $s['jumlah'] > 0 ? round(($s['finish'] / $s['jumlah']) * 100, 2) : 0;
        }

        return array_values($summary);
    }

    // ================== FILTERED RESPONSES BUILDER (untuk pagination) ==================
    public function getFilteredResponsesBuilder(array $filters = [])
    {
        $builder = $this->db->table('detailaccount_alumni da')
            ->select("
                da.id as alumni_id,
                da.nama_lengkap,
                da.nim,
                da.angkatan,
                da.tahun_kelulusan,
                j.nama_jurusan,
                p.nama_prodi,
                r.id as response_id,
                r.status,
                r.submitted_at,
                q.title as judul_kuesioner
            ")
            ->join('account a', 'a.id = da.id_account', 'left')
            ->join('responses r', 'r.account_id = a.id', 'left')
            ->join('questionnaires q', 'q.id = r.questionnaire_id', 'left')
            ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left');

        // Filter dinamis
        if (!empty($filters['tahun'])) $builder->where('da.tahun_kelulusan', $filters['tahun']);
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'Belum') {
                $builder->where('r.id IS NULL');
            } else {
                $builder->where('r.status', $filters['status']);
            }
        }
        if (!empty($filters['questionnaire'])) $builder->where('r.questionnaire_id', $filters['questionnaire']);
        if (!empty($filters['nim']))           $builder->like('da.nim', $filters['nim']);
        if (!empty($filters['nama']))          $builder->like('da.nama_lengkap', $filters['nama']);
        if (!empty($filters['jurusan']))       $builder->where('da.id_jurusan', $filters['jurusan']);
        if (!empty($filters['prodi']))         $builder->where('da.id_prodi', $filters['prodi']);
        if (!empty($filters['angkatan']))      $builder->where('da.angkatan', $filters['angkatan']);

        $builder->orderBy('da.nama_lengkap', 'ASC');

        return $builder;
    }
}

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
    protected $allowedFields    = [
        'questionnaire_id',
        'account_id',
        'submitted_at',
        'status',
        'ip_address'
    ];

    protected bool $allowEmptyInserts = false;

    /**
     * Ambil response + jawaban detail
     */
    public function getResponseWithAnswers($responseId)
    {
        return $this->db->table('responses r')
            ->select('r.*, a.question_id, a.answer_text, a.answer_value, q.question_text')
            ->join('answers a', 'r.id = a.response_id')
            ->join('questions q', 'a.question_id = q.id')
            ->where('r.id', $responseId)
            ->get()
            ->getResultArray();
    }

    /**
     * Cek apakah alumni sudah mengisi kuesioner
     */
    public function hasResponded($questionnaireId, $accountId): bool
    {
        return $this->where('questionnaire_id', $questionnaireId)
            ->where('account_id', $accountId)
            ->where('status', 'completed')
            ->countAllResults() > 0;
    }

    /**
     * Ringkasan semua status response
     */
    public function getSummary()
    {
        return $this->select("status, COUNT(*) as total")
            ->groupBy("status")
            ->findAll();
    }

    public function getTotalCompleted(): int
    {
        return $this->where('status', 'completed')->countAllResults();
    }

    public function getTotalDraft(): int
    {
        return $this->where('status', 'draft')->countAllResults();
    }

    /**
     * Ringkasan respon per prodi berdasarkan tahun kelulusan
     */
    public function getSummaryByYear($tahun)
    {
        return $this->db->table('detailaccount_alumni da')
            ->select("
                p.nama_prodi as prodi,
                SUM(CASE WHEN r.status='completed' THEN 1 ELSE 0 END) as finish,
                SUM(CASE WHEN r.status='draft' THEN 1 ELSE 0 END) as ongoing,
                SUM(CASE WHEN r.id IS NULL THEN 1 ELSE 0 END) as belum,
                COUNT(*) as jumlah,
                ROUND(
                    (SUM(CASE WHEN r.status='completed' THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as persentase
            ")
            ->join('prodi p', 'p.id = da.id_prodi')
            ->join('responses r', 'r.account_id = da.id_account', 'left')
            ->where('da.tahun_kelulusan', $tahun)
            ->groupBy('p.nama_prodi')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil semua tahun kelulusan yang tersedia
     */
    public function getAvailableYears()
    {
        return $this->db->table('detailaccount_alumni')
            ->distinct(true)
            ->select('tahun_kelulusan as tahun')
            ->orderBy('tahun_kelulusan', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil semua judul kuesioner
     */
    public function getAllQuestionnaires()
    {
        return $this->db->table('questionnaires')
            ->select('id, title')
            ->orderBy('title', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil response dengan filter tahun, status, dan judul kuesioner
     */
    public function getFilteredResponses($year = null, $status = null, $questionnaireId = null)
    {
        $builder = $this->db->table('responses r');
        $builder->select('r.*, a.username, da.nama_lengkap, da.tahun_kelulusan, q.title as judul_kuesioner');
        $builder->join('account a', 'a.id = r.account_id', 'left');
        $builder->join('detailaccount_alumni da', 'da.id_account = a.id', 'left');
        $builder->join('questionnaires q', 'q.id = r.questionnaire_id', 'left');


        if ($year) {
            $builder->where('da.tahun_kelulusan', $year);
        }

        if ($status) {
            $builder->where('r.status', $status);
        }

        if ($questionnaireId) {
            $builder->where('r.questionnaire_id', $questionnaireId);
        }

        return $builder->get()->getResultArray();
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
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ResponseAtasanModel;
use App\Models\DetailaccountAtasan;
use App\Models\QuestionnairModel;
use App\Models\AccountModel;
use App\Models\JabatanModels;

class AdminResponAtasan extends BaseController
{
    protected $responseAtasanModel;
    protected $detailAtasanModel;
    protected $questionnairModel;
    protected $accountModel;
    protected $jabatanModel;

    public function __construct()
    {
        $this->responseAtasanModel = new ResponseAtasanModel();
        $this->detailAtasanModel   = new DetailaccountAtasan();
        $this->questionnairModel   = new QuestionnairModel();
        $this->accountModel        = new AccountModel();
        $this->jabatanModel        = new JabatanModels();
    }

    public function index()
    {
        $filters = [
            'status'  => $this->request->getGet('status') ?? '',
            'jabatan' => $this->request->getGet('jabatan') ?? ''
        ];

        $builder = $this->responseAtasanModel
            ->select('responses_atasan.*, 
                      account.username, 
                      account.email, 
                      detailaccount_atasan.nama_lengkap, 
                      detailaccount_atasan.notlp, 
                      detailaccount_atasan.id_jabatan,
                      questionnaires.title AS nama_kuesioner,
                      jabatan.jabatan')
            ->join('account', 'account.id = responses_atasan.id_account', 'left')
            ->join('detailaccount_atasan', 'detailaccount_atasan.id_account = account.id', 'left')
            ->join('questionnaires', 'questionnaires.id = responses_atasan.id_questionnaire', 'left')
            ->join('jabatan', 'jabatan.id = detailaccount_atasan.id_jabatan', 'left');

        // ✅ Filter Dinamis
        if (!empty($filters['status'])) {
            $builder->where('responses_atasan.status', $filters['status']);
        }
        if (!empty($filters['jabatan'])) {
            $builder->where('detailaccount_atasan.id_jabatan', $filters['jabatan']);
        }

        $data = [
            'responses'   => $builder->orderBy('responses_atasan.updated_at', 'DESC')->findAll(),
            'filters'     => $filters,
            'jabatanList' => $this->jabatanModel->findAll(),
        ];

        return view('adminpage/respon_atasan/index', $data);
    }

    public function detail($id)
    {
        $response = $this->responseAtasanModel
            ->select('responses_atasan.*, 
                      account.username, 
                      account.email,
                      detailaccount_atasan.nama_lengkap,
                      detailaccount_atasan.notlp,
                      questionnaires.title AS nama_kuesioner,
                      jabatan.jabatan')
            ->join('account', 'account.id = responses_atasan.id_account', 'left')
            ->join('detailaccount_atasan', 'detailaccount_atasan.id_account = account.id', 'left')
            ->join('questionnaires', 'questionnaires.id = responses_atasan.id_questionnaire', 'left')
            ->join('jabatan', 'jabatan.id = detailaccount_atasan.id_jabatan', 'left')
            ->where('responses_atasan.id', $id)
            ->first();

        if (!$response) {
            return redirect()->to(base_url('admin/respon/atasan'))->with('error', 'Data tidak ditemukan.');
        }

        $answers = json_decode($response['answers'], true) ?? [];

        return view('adminpage/respon_atasan/detail', [
            'response' => $response,
            'answers'  => $answers
        ]);
    }

    public function delete($id)
    {
        $response = $this->responseAtasanModel->find($id);

        if (!$response) {
            return redirect()->to(base_url('admin/atasan'))->with('error', 'Data tidak ditemukan.');
        }

        $this->responseAtasanModel->delete($id);
        return redirect()->to(base_url('admin/respon/atasan'))->with('success', 'Data berhasil dihapus.');
    }
}

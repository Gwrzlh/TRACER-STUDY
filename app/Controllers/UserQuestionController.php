<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnswerModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\QuestionnairModel;
use App\Models\QuestionModel;
use App\Models\QuestionnairePageModel;
use App\Models\QuestionnairConditionModel;
use App\Models\SectionModel;


class UserQuestionController extends BaseController
{
    protected $questionnaireModel;
    protected $answerModel;
    protected $conditionModel;

    public function __construct()
    {
        $this->questionnaireModel = new QuestionnairModel();
        $this->answerModel        = new AnswerModel();
        $this->conditionModel     = new QuestionnairConditionModel();
    }
    public function index()
    {
        // ✅ cek session login
        if (! session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // ambil data user dari session
        $userId   = session()->get('id');
        $userData = session()->get();

        // ambil daftar kuesioner yg bisa diakses user
        $questionnaires = $this->questionnaireModel->getAccessibleQuestionnaires($userData);

        $data = [];
        foreach ($questionnaires as $q) {
            // ⚠️ skip kalau inactive
            if ($q['is_active'] === 'inactive') {
                continue;
            }

            // status pengerjaan oleh user
            $statusPengisian = $this->answerModel->getStatus($q['id'], $userId);
            $statusPengisian = $statusPengisian ?: 'Belum Mengisi';

            $data[] = [
                'id'         => $q['id'],
                'judul'      => $q['title'],
                'statusIsi'  => $statusPengisian,          // Belum Mengisi / On Going / Finish
                'is_active'  => $q['is_active'],           // draft / active / inactive
                'conditional' => $q['conditional_logic'] ?? '-', // sesuai field di DB
            ];
        }

        return view('alumni/questioner/index', ['data' => $data]);
    }
}

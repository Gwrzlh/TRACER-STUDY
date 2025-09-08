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

    public function __construct()
    {
        $this->questionnaireModel = new QuestionnairModel();
        $this->answerModel        = new AnswerModel();
    }

    public function index()
    {
        // ✅ cek session langsung
        if (! session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // ambil data user dari session
        $userId = session()->get('id');
        $userData = session()->get(); // semua data session

        // ambil daftar kuesioner yg bisa diakses user
        $questionnaires = $this->questionnaireModel->getAccessibleQuestionnaires($userData);



        $data = [];
        foreach ($questionnaires as $q) {
            $status = $this->answerModel->getStatus($q['id'], $userId);
            $data[] = [
                'id'     => $q['id'],
                'judul'  => $q['title'],
                'status' => $status ?: 'Belum Mengisi',
            ];
        }

        return view('alumni/questioner/index', ['data' => $data]);
    }
}

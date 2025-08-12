<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QuestionModel;
use App\Models\QuestionOptionModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\QuestionnairModel;

class QuestionnairController extends BaseController
{
    public function index()
    {
        $model = new QuestionnairModel();
        $data = [
            'questionnaires' => $model->findAll()
        ];

        return view('adminpage/questioner/index', $data);
    }

    public function create()
    {
        return view('adminpage/questioner/tambah');
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'title' => 'required|min_length[3]|max_length[255]',
            'deskripsi' => 'permit_empty|max_length[1000]',
            'is_active' => 'permit_empty|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $model = new QuestionnairModel();
        $model->insert([
            'title' => $this->request->getPost('title'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s') // Fixed typo: created_ad -> created_at
        ]);

        return redirect()->to('admin/questionnaire')->with('success', 'Kuesioner berhasil dibuat!');
    }

    public function manageQuestions($questionnaire_id)
    {
        $questionModel = new QuestionModel();
        $questions = $questionModel->where('questionnaires_id', $questionnaire_id)
                                  ->orderBy('order_no', 'ASC')
                                  ->findAll();

        // Get questionnaire info for breadcrumb/title
        $questionnairModel = new QuestionnairModel();
        $questionnaire = $questionnairModel->find($questionnaire_id);

        return view('adminpage/questioner/questions', [
            'questions' => $questions,
            'questionnaire' => $questionnaire,
            'questionnaire_id' => $questionnaire_id
        ]);
    }

    public function createQuestion($questionnaire_id)
    {
        // Get questionnaire info
        $questionnairModel = new QuestionnairModel();
        $questionnaire = $questionnairModel->find($questionnaire_id);

        // Get next order number
        $questionModel = new QuestionModel();
        $question_ready = $questionnairModel->find($questionnaire_id);
        
        $lastOrder = $questionModel->where('questionnaires_id', $questionnaire_id)
                                  ->selectMax('order_no')
                                  ->first();
        
        $nextOrder = ($lastOrder['order_no'] ?? 0) + 1;

        return view('adminpage/questioner/create_question', [
            'questionnaire' => $questionnaire,
            'questionnaire_id' => $questionnaire_id,
            'next_order' => $nextOrder,
            'question_ready' => $question_ready
        ]);
    }

    public function storeQuestion($questionnaire_id)
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'question_text' => 'required|min_length[5]', // Fixed field name
            'question_type' => 'required|in_list[text,textarea,radio,checkbox,dropdown,number,date,email]',
            'is_required' => 'permit_empty|in_list[0,1]',
            'order_no' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Handle checkbox value properly
        $isRequired = $this->request->getPost('is_required') ? 1 : 0;

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insert question
            $questionModel = new QuestionModel();
            $questionId = $questionModel->insert([
                'questionnaires_id' => $questionnaire_id,
                'question_text' => $this->request->getPost('question_text'), // Fixed field name
                'question_type' => $this->request->getPost('question_type'),
                'is_required' => $isRequired, // Use processed value
                'order_no' => $this->request->getPost('order_no')
            ]);

            // If question type is radio, checkbox, or dropdown, insert options
            $questionType = $this->request->getPost('question_type');
            if (in_array($questionType, ['radio', 'checkbox', 'dropdown'])) {
                $options = $this->request->getPost('options'); // Array of options
                $nextQuestions = $this->request->getPost('next_questions'); // Array of next question IDs
                
                if (!empty($options)) {
                    $optionModel = new QuestionOptionModel();
                    foreach ($options as $index => $optionText) {
                        if (!empty(trim($optionText))) {
                            $optionModel->insert([
                                'question_id' => $questionId,
                                'option_text' => trim($optionText),
                                'option_value' => strtolower(str_replace(' ', '_', trim($optionText))),
                                'next_question_id' => $nextQuestions[$index] ?? null,
                                'order_number' => $index + 1
                            ]);
                        }
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pertanyaan.');
            }

            return redirect()->to("/admin/questionnaire/{$questionnaire_id}/questions")
                           ->with('success', 'Pertanyaan berhasil ditambahkan.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editQuestion($questionnaire_id, $question_id)
    {
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();

        $question = $questionModel->find($question_id);
        $options = $optionModel->where('question_id', $question_id)
                              ->orderBy('order_number', 'ASC')
                              ->findAll();

        // Get all questions in this questionnaire for next_question dropdown
        $allQuestions = $questionModel->where('questionnaires_id', $questionnaire_id)
                                     ->where('id !=', $question_id)
                                     ->findAll();

        return view('adminpage/questioner/edit_question', [
            'question' => $question,
            'options' => $options,
            'all_questions' => $allQuestions,
            'questionnaire_id' => $questionnaire_id
        ]);
    }

    public function updateQuestion($questionnaire_id, $question_id)
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'question_text' => 'required|min_length[5]',
            'question_type' => 'required|in_list[text,textarea,radio,checkbox,dropdown,number,date,email]',
            'is_required' => 'permit_empty|in_list[0,1]',
            'order_no' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update question
            $questionModel = new QuestionModel();
            $questionModel->update($question_id, [
                'question_text' => $this->request->getPost('question_text'),
                'question_type' => $this->request->getPost('question_type'),
                'is_required' => $this->request->getPost('is_required') ? 1 : 0,
                'order_no' => $this->request->getPost('order_no')
            ]);

            // Delete existing options and recreate them
            $optionModel = new QuestionOptionModel();
            $optionModel->where('question_id', $question_id)->delete();

            // Insert new options
            $questionType = $this->request->getPost('question_type');
            if (in_array($questionType, ['radio', 'checkbox', 'dropdown'])) {
                $options = $this->request->getPost('options');
                $nextQuestions = $this->request->getPost('next_questions');
                
                if (!empty($options)) {
                    foreach ($options as $index => $optionText) {
                        if (!empty(trim($optionText))) {
                            $optionModel->insert([
                                'question_id' => $question_id,
                                'option_text' => trim($optionText),
                                'option_value' => strtolower(str_replace(' ', '_', trim($optionText))),
                                'next_question_id' => $nextQuestions[$index] ?? null,
                                'order_number' => $index + 1
                            ]);
                        }
                    }
                }
            }

            $db->transComplete();

            return redirect()->to("/admin/questionnaire/{$questionnaire_id}/questions")
                           ->with('success', 'Pertanyaan berhasil diperbarui.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteQuestion($questionnaire_id, $question_id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete options first
            $optionModel = new QuestionOptionModel();
            $optionModel->where('question_id', $question_id)->delete();

            // Delete question
            $questionModel = new QuestionModel();
            $questionModel->delete($question_id);

            $db->transComplete();

            return redirect()->to("/admin/questionnaire/{$questionnaire_id}/questions")
                           ->with('success', 'Pertanyaan berhasil dihapus.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menghapus pertanyaan.');
        }
    }

    // Preview questionnaire for testing
    public function preview($questionnaire_id)
    {
        $questionnairModel = new QuestionnairModel();
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();

        $questionnaire = $questionnairModel->find($questionnaire_id);
        $questions = $questionModel->where('questionnaires_id', $questionnaire_id)
                                  ->orderBy('order_no', 'ASC')
                                  ->findAll();

        // Get options for each question
        foreach ($questions as &$question) {
            if (in_array($question['question_type'], ['radio', 'checkbox', 'dropdown'])) {
                $question['options'] = $optionModel->where('question_id', $question['id'])
                                                  ->orderBy('order_number', 'ASC')
                                                  ->findAll();
            }
        }

        return view('adminpage/questioner/preview', [
            'questionnaire' => $questionnaire,
            'questions' => $questions
        ]);
    }
}
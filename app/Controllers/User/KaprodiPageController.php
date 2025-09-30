<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Questionnaire\QuestionnairePageModel;
use App\Models\Questionnaire\QuestionnairModel;
use App\Models\Questionnaire\QuestionModel; // Tambahkan model pertanyaan
use App\Models\Questionnaire\QuestionOptionModel;
use App\Models\Questionnaire\SectionModel;
use App\Models\Support\MatrixColumnModels;
use App\Models\Support\MatrixRowModel;

class KaprodiPageController extends BaseController
{
    public function index($questionnaire_id)
    {
        $pageModel = new QuestionnairePageModel();
        $pages = $pageModel->where('questionnaire_id', $questionnaire_id)
            ->orderBy('order_no', 'ASC')
            ->findAll();

        $questionnaireModel = new QuestionnairModel();
        $questionnaire = $questionnaireModel->find($questionnaire_id);

        $kaprodiId = session()->get('id_account');
        $prodiId = session()->get('id_prodi'); // ID prodi kaprodi

        log_message('debug', '[DEBUG] Kaprodi ID: ' . $kaprodiId);
        log_message('debug', '[DEBUG] Questionnaire created_by: ' . $questionnaire['created_by']);

        foreach ($pages as &$page) {
            // Default
            $page['canEdit'] = false;
            $page['canAddChild'] = false;

            // Halaman kaprodi sendiri → bisa edit
            if (!empty($page['created_by']) && $page['created_by'] == $kaprodiId) {
                $page['canEdit'] = true;
            }

            // Halaman admin → cek conditional logic untuk prodi
            if (!$page['canEdit'] && !empty($page['conditional_logic'])) {
                $logic = json_decode($page['conditional_logic'], true);
                if (is_array($logic) && !empty($logic['conditions']) && is_array($logic['conditions'])) {
                    foreach ($logic['conditions'] as $cond) {
                        if (isset($cond['value']) && $cond['value'] == $prodiId) {
                            $page['canAddChild'] = true;
                            break; // cukup satu kondisi terpenuhi
                        }
                    }
                }
            }

            log_message('debug', '[DEBUG] Page ID: ' . $page['id'] .
                ', created_by: ' . $page['created_by'] .
                ', canEdit: ' . ($page['canEdit'] ? 'true' : 'false') .
                ', canAddChild: ' . ($page['canAddChild'] ? 'true' : 'false'));
        }
        unset($page);

        return view('kaprodi/kuesioner/page/index', [
            'pages' => $pages,
            'questionnaire' => $questionnaire
        ]);
    }







    private function checkOwnership($questionnaire_id)
    {
        $questionnaireModel = new QuestionnairModel();
        $questionnaire = $questionnaireModel->find($questionnaire_id);

        if (!$questionnaire) {
            return false; // data tidak ada
        }

        return $questionnaire['created_by'] == session()->get('id_account');
    }


    public function create($questionnaire_id)
    {
        // Ambil semua pertanyaan untuk dropdown conditional logic
        $questionModel = new QuestionModel();
        $questions = $questionModel->where('questionnaires_id', $questionnaire_id)->findAll();

        $operators = [
            'is' => 'Is',
            'is_not' => 'Is Not',
            'contains' => 'Contains',
            'not_contains' => 'Not Contains',
            'greater' => 'Greater Than',
            'less' => 'Less Than'
        ];

        return view('kaprodi/kuesioner/page/create', [
            'questionnaire_id' => $questionnaire_id,
            'questions' => $questions,
            'operators' => $operators
        ]);
    }

    public function store($questionnaire_id)
    {
        $pageModel = new QuestionnairePageModel();
        $conditionalLogicEnabled = $this->request->getPost('conditional_logic');
        $conditionalLogic = null;

        if ($conditionalLogicEnabled) {
            $logic_type = $this->request->getPost('logic_type') ?? 'any';
            $conditionQuestionIds = $this->request->getPost('condition_question_id') ?? [];
            $operators = $this->request->getPost('operator') ?? [];
            $conditionValues = $this->request->getPost('condition_value') ?? [];
            $optionModel = new QuestionOptionModel();

            $conditions = [];
            for ($i = 0; $i < count($conditionQuestionIds); $i++) {
                if (!empty($conditionQuestionIds[$i]) && !empty($operators[$i]) && isset($conditionValues[$i])) {
                    $value = $conditionValues[$i];
                    // Translate option ID ke option_text
                    if (preg_match('/^\d+$/', $value)) {
                        $option = $optionModel->where([
                            'question_id' => $conditionQuestionIds[$i],
                            'id' => $value
                        ])->first();
                        $value = $option ? $option['option_text'] : $value;
                        log_message('debug', "[store] Translated option ID {$conditionValues[$i]} to text: $value");
                    }
                    $conditions[] = [
                        'field' => $conditionQuestionIds[$i],
                        'operator' => $operators[$i],
                        'value' => $value
                    ];
                }
            }

            if (!empty($conditions)) {
                $conditionalLogic = json_encode([
                    'logic_type' => $logic_type,
                    'conditions' => $conditions
                ]);
            }
        }

        $pageModel->insert([
            'questionnaire_id' => $questionnaire_id,
            'page_title' => $this->request->getPost('title'),
            'page_description' => $this->request->getPost('description'),
            'order_no' => $this->request->getPost('order_no'),
            'conditional_logic' => $conditionalLogic,
            'created_by' => session()->get('id_account'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to("/kaprodi/kuesioner/{$questionnaire_id}/pages")
            ->with('success', 'Halaman berhasil ditambahkan.');
    }


    public function edit($questionnaire_id, $page_id)
    {
        log_message('debug', "[EDIT] dipanggil edit() dengan questionnaire_id=$questionnaire_id, page_id=$page_id");
        // 🔒 Cek apakah kaprodi punya akses ke kuesioner ini
        // if (!$this->checkOwnership($questionnaire_id)) {
        //     return redirect()->to("/kaprodi/kuesioner/{$questionnaire_id}/pages")
        //         ->with('error', 'Halaman ini dibuat admin. Anda tidak bisa mengupdate.');
        // }

        $pageModel = new QuestionnairePageModel();
        $page = $pageModel->find($page_id);

        if (!$page) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Page tidak ditemukan');
        }

        // 🔍 Ambil semua pertanyaan dalam kuesioner ini
        $questionModel = new QuestionModel();
        $questions = $questionModel
            ->where('questionnaires_id', $questionnaire_id)
            ->findAll();

        // Operator yang bisa dipakai di conditional logic
        $operators = [
            'is'          => 'Is',
            'is_not'      => 'Is Not',
            'contains'    => 'Contains',
            'not_contains' => 'Not Contains',
            'greater'     => 'Greater Than',
            'less'        => 'Less Than'
        ];

        // Decode conditional logic (kalau ada)
        $conditionalLogic = [];
        if (!empty($page['conditional_logic'])) {
            $decoded = json_decode($page['conditional_logic'], true);
            $conditionalLogic = is_array($decoded) ? $decoded : [];
        }

        // Pastikan view path sesuai struktur folder kamu
        return view('kaprodi/kuesioner/page/edit', [
            'page'             => $page,
            'questionnaire_id' => $questionnaire_id,
            'questions'        => $questions,
            'operators'        => $operators,
            'conditionalLogic' => $conditionalLogic
        ]);
    }


    public function update($questionnaire_id, $page_id)
    {
        $pageModel = new QuestionnairePageModel();
        $conditionalLogicEnabled = $this->request->getPost('conditional_logic');
        $conditionalLogic = null;

        if ($conditionalLogicEnabled) {
            $logic_type = $this->request->getPost('logic_type') ?? 'any';
            $conditionQuestionIds = $this->request->getPost('condition_question_id') ?? [];
            $operators = $this->request->getPost('operator') ?? [];
            $conditionValues = $this->request->getPost('condition_value') ?? [];
            $optionModel = new QuestionOptionModel();

            $conditions = [];
            for ($i = 0; $i < count($conditionQuestionIds); $i++) {
                if (!empty($conditionQuestionIds[$i]) && !empty($operators[$i]) && isset($conditionValues[$i])) {
                    $value = $conditionValues[$i];
                    if (preg_match('/^\d+$/', $value)) {
                        $option = $optionModel->where([
                            'question_id' => $conditionQuestionIds[$i],
                            'id' => $value
                        ])->first();
                        $value = $option ? $option['option_text'] : $value;
                        log_message('debug', "[update] Translated option ID {$conditionValues[$i]} to text: $value");
                    }
                    $conditions[] = [
                        'field' => $conditionQuestionIds[$i],
                        'operator' => $operators[$i],
                        'value' => $value
                    ];
                }
            }

            if (!empty($conditions)) {
                $conditionalLogic = json_encode([
                    'logic_type' => $logic_type,
                    'conditions' => $conditions
                ]);
            }
        }

        $pageModel->update($page_id, [
            'page_title' => $this->request->getPost('title'),
            'page_description' => $this->request->getPost('description'),
            'order_no' => $this->request->getPost('order_no'),
            'conditional_logic' => $conditionalLogic,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to("/kaprodi/kuesioner/{$questionnaire_id}/pages")
            ->with('success', 'Halaman berhasil diperbarui.');
    }


    public function delete($questionnaire_id, $page_id)
    {
        // if (!$this->checkOwnership($questionnaire_id)) {
        //     return redirect()->back()->with('error', 'Halaman ini dibuat admin. Anda tidak bisa mengupdate.');
        // }
        $pageModel       = new QuestionnairePageModel();
        $sectionModel    = new SectionModel();
        $questionModel   = new QuestionModel();
        $optionModel     = new QuestionOptionModel();
        $matrixRowModel  = new MatrixRowModel();
        $matrixColModel  = new MatrixColumnModels();

        // cari semua pertanyaan di halaman ini
        $questions = $questionModel->where('page_id', $page_id)->findAll();

        foreach ($questions as $q) {
            // hapus semua opsi terkait pertanyaan
            $optionModel->where('question_id', $q['id'])->delete();

            // hapus matrix rows terkait pertanyaan
            $matrixRowModel->where('question_id', $q['id'])->delete();

            // hapus matrix columns terkait pertanyaan
            $matrixColModel->where('question_id', $q['id'])->delete();
        }

        // hapus semua pertanyaan di halaman
        $questionModel->where('page_id', $page_id)->delete();

        // hapus semua section di halaman
        $sectionModel->where('page_id', $page_id)->delete();

        // terakhir hapus page
        $pageModel->delete($page_id);

        return redirect()->to("/kaprodi/kuesioner/{$questionnaire_id}/pages")
            ->with('success', 'Halaman berhasil dihapus.');
    }


    // Fungsi AJAX untuk mengambil opsi jawaban pertanyaan
    public function getQuestionOptions()
    {
        $question_id = $this->request->getGet('question_id');

        // Validasi question_id
        if (!$question_id) {
            return $this->response->setJSON(['type' => 'text', 'options' => []]);
        }

        $questionModel = new QuestionModel();
        $question = $questionModel->find($question_id);

        $options = [];
        $type = 'text'; // default

        if ($question) {
            // Ambil tipe pertanyaan sebenarnya
            $type = $question['question_type'];

            // Ambil opsi jika tipe pertanyaan punya pilihan
            if (in_array($type, ['radio', 'checkbox', 'dropdown'])) {
                $optionModel = new QuestionOptionModel();
                $options = $optionModel
                    ->select('id, option_text')
                    ->where('question_id', $question_id)
                    ->findAll();
            }
        }

        // Format options agar JSON konsisten
        $formatted_options = array_map(function ($opt) {
            return [
                'id' => (string)$opt['id'],
                'option_text' => $opt['option_text']
            ];
        }, $options);

        return $this->response->setJSON([
            'type' => $type,        // radio, checkbox, dropdown, text, dll
            'options' => $formatted_options
        ]);
    }
}

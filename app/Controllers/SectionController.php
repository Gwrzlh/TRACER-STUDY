<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SectionModel;
use App\Models\QuestionnairePageModel;
use App\Models\QuestionnairModel;   
use App\Models\QuestionModel;

class SectionController extends BaseController
{
     public function index($questionnaire_id, $page_id)
    {
        $sectionModel = new SectionModel();
        $pageModel = new QuestionnairePageModel();
        $questionnaireModel = new QuestionnairModel();

        // Validasi
        $questionnaire = $questionnaireModel->find($questionnaire_id);
        $page = $pageModel->where('id', $page_id)
                         ->where('questionnaire_id', $questionnaire_id)
                         ->first();

        if (!$questionnaire || !$page) {
            return redirect()->to('admin/questionnaire')->with('error', 'Data tidak ditemukan.');
        }

        // Get sections dengan jumlah pertanyaan
        $sections = $sectionModel->getSectionsWithQuestionCount($page_id);

        return view('adminpage/questioner/section/index', [
            'questionnaire' => $questionnaire,
            'page' => $page,
            'sections' => $sections,
            'questionnaire_id' => $questionnaire_id,
            'page_id' => $page_id
        ]);
    }

    public function create($questionnaire_id, $page_id)
    {
        $questionnaireModel = new QuestionnairModel();
        $pageModel = new QuestionnairePageModel();
        $sectionModel = new SectionModel();
        
        $questionnaire = $questionnaireModel->find($questionnaire_id);
        $page = $pageModel->find($page_id);

        if (!$questionnaire || !$page) {
            return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages")
                           ->with('error', 'Data tidak ditemukan.');
        }

        $nextOrder = $sectionModel->getNextOrderNo($page_id);

        return view('adminpage/questioner/section/create', [
            'questionnaire' => $questionnaire,
            'page' => $page,
            'questionnaire_id' => $questionnaire_id,
            'page_id' => $page_id,
            'next_order' => $nextOrder
        ]);
    }

    public function store($questionnaire_id, $page_id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'section_title' => 'required|min_length[3]|max_length[255]',
            'section_description' => 'permit_empty|max_length[1000]',
            'show_section_title' => 'permit_empty|in_list[0,1]',
            'show_section_description' => 'permit_empty|in_list[0,1]',
            'order_no' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $sectionModel = new SectionModel();
        $sectionModel->insert([
            'questionnaire_id' => $questionnaire_id,
            'page_id' => $page_id,
            'section_title' => $this->request->getPost('section_title'),
            'section_description' => $this->request->getPost('section_description'),
            'show_section_title' => $this->request->getPost('show_section_title') ? 1 : 0,
            'show_section_description' => $this->request->getPost('show_section_description') ? 1 : 0,
            'order_no' => $this->request->getPost('order_no')
        ]);

        return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections")
                        ->with('success', 'Section berhasil ditambahkan.');
    }

    public function edit($questionnaire_id, $page_id, $section_id)
    {
        $sectionModel = new SectionModel();
        $pageModel = new QuestionnairePageModel();
        $questionnaireModel = new QuestionnairModel();

        $section = $sectionModel->find($section_id);
        $page = $pageModel->find($page_id);
        $questionnaire = $questionnaireModel->find($questionnaire_id);

        if (!$section || !$page || !$questionnaire) {
            return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections")
                           ->with('error', 'Data tidak ditemukan.');
        }

        return view('adminpage/questioner/section/edit', [
            'questionnaire' => $questionnaire,
            'page' => $page,
            'section' => $section,
            'questionnaire_id' => $questionnaire_id,
            'page_id' => $page_id,
            'section_id' => $section_id
        ]);
    }

    public function update($questionnaire_id, $page_id, $section_id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'section_title' => 'required|min_length[3]|max_length[255]',
            'section_description' => 'permit_empty|max_length[1000]',
            'show_section_title' => 'permit_empty|in_list[0,1]',
            'show_section_description' => 'permit_empty|in_list[0,1]',
            'order_no' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $sectionModel = new SectionModel();
        $sectionModel->update($section_id, [
            'section_title' => $this->request->getPost('section_title'),
            'section_description' => $this->request->getPost('section_description'),
            'show_section_title' => $this->request->getPost('show_section_title') ? 1 : 0,
            'show_section_description' => $this->request->getPost('show_section_description') ? 1 : 0,
            'order_no' => $this->request->getPost('order_no')
        ]);

        return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections")
                        ->with('success', 'Section berhasil diperbarui.');
    }

    public function delete($questionnaire_id, $page_id, $section_id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Cek apakah ada pertanyaan di section ini
            $questionModel = new QuestionModel();
            $questionCount = $questionModel->where('section_id', $section_id)->countAllResults();

            if ($questionCount > 0) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus section yang masih memiliki pertanyaan.');
            }

            // Hapus section
            $sectionModel = new SectionModel();
            $sectionModel->delete($section_id);

            $db->transComplete();

            return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections")
                           ->with('success', 'Section berhasil dihapus.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menghapus section.');
        }
    }
}

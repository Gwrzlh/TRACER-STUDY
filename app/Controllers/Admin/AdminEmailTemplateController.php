<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LandingPage\EmailTemplateModel;

class AdminEmailTemplateController extends BaseController
{
    protected $templateModel;

    public function __construct()
    {
        $this->templateModel = new EmailTemplateModel();
    }

    public function index()
    {
        $data['templates'] = $this->templateModel->findAll();
        return view('adminpage/emailtemplate/index', $data);
    }

    public function update($id)
    {
        $this->templateModel->update($id, [
            'status'  => $this->request->getPost('status'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message')
        ]);

        return redirect()->to('/admin/emailtemplate')->with('success', 'Template berhasil diupdate.');
    }
}

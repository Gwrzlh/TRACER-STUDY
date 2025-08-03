<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Accounts;
use App\Models\DetailaccountAdmins;
use App\Models\Roles;

class PenggunaController extends BaseController
{
    public function index()
    {
        $roles = new Roles();                                   
        $account = new Accounts();
        $detailaccountAdmin = new DetailaccountAdmins();
        $data = $account->getroleid();
        $dataDetailAdmin = $detailaccountAdmin->getaccountid();

        return view('adminpage\pengguna\index');

    }public function create(){

        $roles = new Roles();
        $data['roles'] = $roles->findAll();
        return view('adminpage\pengguna\tambahPengguna',$data);

    }public function store(){
       
       $validation = \Config\Services::validation();

       $validation->setRules([
            // 'nama'      => 'required|min_length[3]',
            'username'  => 'required|is_unique[account.username]',
            'email'     => 'required|valid_email',
            'password'  => 'required|min_length[6]',
            'Group'     => 'required',
            'nama_lengkap' => 'required|min_length[3]'
       ]);
       
        if (!$validation->withRequest($this->request)->run()) {
            // dd($validation->getError());
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            // 'nama'     => $this->request->getPost('nama'),                                                                   
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'id_role'  => $this->request->getPost('Group') 
        ];
        
        $accountModel = new Accounts();
        $accountModel->insert($data);
        
        $accountId = $accountModel->insertID();

        $detailData = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'id_account'   => $accountId    
        ];

        $detailAdmin = new DetailaccountAdmins();
        $detailAdmin->insert($detailData);

        
        return redirect()->to('/admin/pengguna')->with('success', 'Data pengguna berhasil disimpan.');
    }
}

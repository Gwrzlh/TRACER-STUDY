<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Accounts;
use App\Models\DetailaccountAdmins;
use App\Models\Roles;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Cities;
use App\Models\Provincies;
use App\Models\DetailaccountAlumni;

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

    }public function create()
    {
        $roles = new Roles();
        $jurusans = new Jurusan();
        $prodis = new Prodi();
        $cityModel = new Cities();
        $provincesModel = new Provincies(); // Tambahkan ini

        $data = [
            'roles'       => $roles->findAll(),
            'datajurusan' => $jurusans->findAll(),
            'dataProdi'   => $prodis->findAll(),
            'cities'      => $cityModel->getCitiesWithProvince(),
            'provinces'   => $provincesModel->findAll() // Perbaiki ini
        ];

        return view('adminpage\pengguna\tambahPengguna', $data);
    }

    public function getCitiesByProvince($province_id)
    {
        // Validasi input
       if (!$province_id || !is_numeric($province_id)) {
            return $this->response->setJSON([
                'error' => 'Province ID is required and must be numeric'
            ]);
        }

        try {
            $cityModel = new Cities();
            $cities = $cityModel->where('province_id', $province_id)
                               ->orderBy('name', 'ASC')
                               ->findAll();
            
            return $this->response->setJSON($cities);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Failed to fetch cities: ' . $e->getMessage()
            ]);
        }
    }
public function store()
{
    $validation = \Config\Services::validation();

    // Ambil group dulu
    $group = $this->request->getPost('group');
        // dd($this->request->getPost());

    // RULE VALIDASI DASAR
    $rules = [
        'username'      => 'required|is_unique[account.username]',
        'email'         => 'required|valid_email',
        'password'      => 'required|min_length[6]',
        'group'         => 'required|in_list[1,2,3]',
        'status'        => 'required',
        // 'nama_lengkap'  => 'required|min_length[3]',
    ];

    // RULE TAMBAHAN JIKA ALUMNI (Group == 1)
    if ($group == 1) {
        $rules = array_merge($rules, [
            'nim'             => 'required|numeric',
            'jurusan'         => 'required|numeric',
            'prodi'           => 'required|numeric',
            'angkatan'        => 'required|numeric',
            'tahun_lulus'     => 'required|numeric',
            'ipk'             => 'required|decimal',
            'jeniskelamin'    => 'required|in_list[Laki-Laki,Perempuan]',
            'notlp'           => 'required|min_length[10]',
            'kota'            => 'required|numeric',
            'province'        => 'required|numeric',
            'kode_pos'        => 'required|numeric',
            'alamat'          => 'required',
            'alamat2'         => 'permit_empty',
        ]);
    }

    // VALIDASI
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    // SIMPAN AKUN
    $accountModel = new Accounts();
    $accountData = [
        'username'  => $this->request->getPost('username'),
        'email'     => $this->request->getPost('email'),
        'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'status'    => $this->request->getPost('status'),
        'id_role'   => $group,
    ];
    $accountModel->insert($accountData);
    $accountId = $accountModel->insertID();

    // SIMPAN DETAIL BERDASARKAN ROLE
    if ($group == 2) {
        // Admin
        $adminDetail = new DetailaccountAdmins();
        $dataDetailAdmin = [
            'nama_lengkap' => $this->request->getPost('admin_nama_lengkap'),
            'id_account'   => $accountId,
        ];
        // dd($dataDetailAdmin);
        $adminDetail->insert($dataDetailAdmin);
    } elseif ($group == 1) {
        // Alumni
        $alumniDetail = new DetailaccountAlumni();
        $datadetailAlumni = [
            'nama_lengkap'     => $this->request->getPost('nama_lengkap'),
            'nim'              => $this->request->getPost('nim'),
            'id_jurusan'       => $this->request->getPost('jurusan'),
            'id_prodi'         => $this->request->getPost('prodi'),
            'angkatan'         => $this->request->getPost('angkatan'),
            'tahun_kelulusan'  => $this->request->getPost('tahun_lulus'),
            'ipk'              => $this->request->getPost('ipk'),
            'jenisKelamin'     => $this->request->getPost('jeniskelamin'),
            'notlp'            => $this->request->getPost('notlp'),
            'id_cities'        => $this->request->getPost('kota'),
            'id_provinsi'      => $this->request->getPost('province'),
            'kodepos'          => $this->request->getPost('kode_pos'),
            'alamat'           => $this->request->getPost('alamat'),
            'alamat2'          => $this->request->getPost('alamat2'),
            'id_account'       => $accountId,
        ];
        // dd($datadetailAlumni);
        $alumniDetail->insert($datadetailAlumni);
    }

    // REDIRECT BERHASIL
    return redirect()->to('/admin/pengguna')->with('success', 'Data pengguna berhasil disimpan.');
}

}

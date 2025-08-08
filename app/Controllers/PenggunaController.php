<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Accounts;
use App\Models\DetailaccountAdmins;
use App\Models\Roles;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Cities;
use App\Models\Provincies;
use App\Models\DetailaccountAlumni;
use App\Models\DetailaccountCompany;
use App\Models\JurusanModel;
use Exception;

class PenggunaController extends BaseController
{
    public function index()
    {
        $roleId = $this->request->getGet('role');

        $rolesModel = new Roles();      
        $roles = $rolesModel->findAll(); // ini hasil query berupa array

        $accountModel = new Accounts();
        
        if ($roleId) {
        $account = $accountModel->where('id_role', $roleId)->getroleid();
                       
        } else {
            $account = $accountModel->getroleid();
        }

        $detailaccountAdmin = new DetailaccountAdmins();
        $detailaccountAlumni = new DetailaccountAlumni();

        $data = [
            'roles' => $roles,
            'account' => $account,
            'detailaccountAdmin' => $detailaccountAdmin->getaccountid(),
            'detailaccountAlumni' => $detailaccountAlumni->getDetailWithRelations(),
            'roleId'  => $roleId
        
        ];   
        
   
    $roleId = $this->request->getGet('role');
    $keyword = $this->request->getGet('keyword'); // ambil keyword dari GET

    $rolesModel = new Roles();      
    $roles = $rolesModel->findAll();

    $accountModel = new Accounts();

    // Jika ada filter role
    if ($roleId) {
        $accountModel->where('id_role', $roleId);
    }

    // Jika ada pencarian nama
    if (!empty($keyword)) {
        $accountModel->like('username', $keyword); 
        // Kalau mau berdasarkan detail alumni/admin, ganti fieldnya
    }

    $account = $accountModel->getroleid(); // method custom di model Anda

    $detailaccountAdmin = new DetailaccountAdmins();
    $detailaccountAlumni = new DetailaccountAlumni();

    $data = [
        'roles' => $roles,
        'account' => $account,
        'detailaccountAdmin' => $detailaccountAdmin->getaccountid(),
        'detailaccountAlumni' => $detailaccountAlumni->getDetailWithRelations(),
        'roleId'  => $roleId,
        'keyword' => $keyword // kirim ke view supaya input tidak hilang
    ];   

        return view('adminpage\pengguna\index', $data);

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
          $roleId = $this->request->getGet('role');
    $keyword = $this->request->getGet('keyword');

    $rolesModel = new Roles();
    $roles = $rolesModel->findAll();

    $accountModel = new Accounts();

    // Jika ada filter role
    if ($roleId) {
        $accountModel->where('id_role', $roleId);
    }

    // Jika ada pencarian nama
    if (!empty($keyword)) {
        $accountModel->like('username', $keyword);
    }

    $account = $accountModel->getroleid();

    // Ambil jumlah akun per role
    $db = \Config\Database::connect();
    $counts = $db->table('account')
        ->select('id_role, COUNT(*) as total')
        ->groupBy('id_role')
        ->get()
        ->getResultArray();

    // Ubah jadi array [id_role => total]
    $countsPerRole = [];
    foreach ($counts as $c) {
        $countsPerRole[$c['id_role']] = $c['total'];
    }

    $detailaccountAdmin = new DetailaccountAdmins();
    $detailaccountAlumni = new DetailaccountAlumni();

    $data = [
        'roles' => $roles,
        'account' => $account,
        'detailaccountAdmin' => $detailaccountAdmin->getaccountid(),
        'detailaccountAlumni' => $detailaccountAlumni->getDetailWithRelations(),
        'roleId'  => $roleId,
        'keyword' => $keyword,
        'countsPerRole' => $countsPerRole // kirim ke view
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
public function edit($id)
{
    $accountModel = new Accounts();
    $detailAlumni = new DetailaccountAlumni();
    $detailAdmin = new DetailaccountAdmins();
    $detailPerusahaan = new DetailaccountCompany();
    $roleModels = new Roles();
    $jurusans = new Jurusan();
    $prodis = new Prodi();
    $cityModel = new Cities();
    $provincesModel = new Provincies();

    $roles = $roleModels->findAll();
    $dataAccount = $accountModel->find($id);
    
    if (!$dataAccount) {
        return redirect()->back()->with('error', 'Data akun tidak ditemukan.');
    }
    
    $role = $dataAccount['id_role'];
    $dataDetail = null;

    // Get detail data based on current role
    switch ($role) {
        case 1: // Alumni
            $dataDetail = $detailAlumni->where('id_account', $id)->first();
            break;
        case 2: // Admin
            $dataDetail = $detailAdmin->where('id_account', $id)->first();
            break;
        case 3: // Company
            $dataDetail = $detailPerusahaan->where('id_account', $id)->first();
            break;
        default:
            $dataDetail = null;
    }

    return view('adminpage\pengguna\edit', [
        'account' => $dataAccount,
        'detail' => $dataDetail,
        'role' => $role,
        'roles' => $roles,
        'datajurusan' => $jurusans->findAll(),
        'dataProdi' => $prodis->findAll(),
        'cities' => $cityModel->getCitiesWithProvince(),
        'provinces' => $provincesModel->findAll()
    ]); 
}

public function update($id)
{
    $accountModel = new Accounts();
    $detailAlumni = new DetailaccountAlumni();
    $detailAdmin = new DetailaccountAdmins();
    $detailPerusahaan = new DetailaccountCompany();

    // Get existing account data
    $account = $accountModel->find($id);
    if (!$account) {
        return redirect()->back()->with('error', 'Data akun tidak ditemukan.');
    }

    $existingRole = $account['id_role'];
    $newRole = $this->request->getPost('group');
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');
    $status = $this->request->getPost('status');

    // Basic validation rules
    $rules = [
        'username' => "required|is_unique[account.username,id,{$id}]",
        // 'email' => "required|valid_email|is_unique[account.email,id,{$id}]",
        'group' => 'required',
        'status' => 'required'
    ];

    // Password validation (only if filled)
    if (!empty($password)) {
        $rules['password'] = 'min_length[6]';
    }

    // Role-specific validation
    switch ($newRole) {
        case '1': // Alumni
            $rules = array_merge($rules, [
                'nama_lengkap' => 'required',
                'nim' => 'required|numeric',
                'jurusan' => 'required',
                'prodi' => 'required',
                'notlp' => 'required|numeric'
            ]);
            break;
        case '2': // Admin
            $rules['admin_nama_lengkap'] = 'required';
            break;
        case '3': // Company
            $rules = array_merge($rules, [
                'nama_perusahaan' => 'required',
                'alamat_perusahaan' => 'required'
            ]);
            break;
    }

    // Validate input
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Prepare account update data
    $updateData = [
        'username' => $username,
        'email' => $this->request->getPost('email'),
        'status' => $status,
        'id_role' => $newRole,
    ];

    // Add password to update data only if provided
    if (!empty($password)) {
        $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    // Start transaction
    $db = \Config\Database::connect();
    $db->transStart();

    try {
        // Update account data
        if (!$accountModel->update($id, $updateData)) {
            throw new Exception('Failed to update account data');
        }

        // Handle role change or update
        $this->handleDetailAccountUpdate($id, $existingRole, $newRole, $detailAlumni, $detailAdmin, $detailPerusahaan);

        $db->transCommit();
        return redirect()->to('/admin/pengguna')->with('success', 'Data pengguna berhasil diperbarui.');

    } catch (Exception $e) {
        $db->transRollback();
        log_message('error', 'Update user failed: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

private function handleDetailAccountUpdate($accountId, $existingRole, $newRole, $detailAlumni, $detailAdmin, $detailPerusahaan)
{
    // If role changed, delete old detail and create new one
    if ($existingRole != $newRole) {
        // Delete old detail
        $this->deleteDetailByRole($accountId, $existingRole, $detailAlumni, $detailAdmin, $detailPerusahaan);
        
        // Create new detail
        $this->createDetailByRole($accountId, $newRole, $detailAlumni, $detailAdmin, $detailPerusahaan);
    } else {
        // Update existing detail
        $this->updateDetailByRole($accountId, $existingRole, $detailAlumni, $detailAdmin, $detailPerusahaan);
    }
}

private function deleteDetailByRole($accountId, $role, $detailAlumni, $detailAdmin, $detailPerusahaan)
{
    switch ($role) {
        case 1:
            $detailAlumni->where('id_account', $accountId)->delete();
            break;
        case 2:
            $detailAdmin->where('id_account', $accountId)->delete();
            break;
        case 3:
            $detailPerusahaan->where('id_account', $accountId)->delete();
            break;
    }
}

private function createDetailByRole($accountId, $role, $detailAlumni, $detailAdmin, $detailPerusahaan)
{
    switch ($role) {
        case '1': // Alumni
            $alumniData = [
                'id_account' => $accountId,
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'nim' => $this->request->getPost('nim'),
                'id_jurusan' => $this->request->getPost('jurusan'),
                'id_prodi' => $this->request->getPost('prodi'),
                'angkatan' => $this->request->getPost('angkatan'),
                'tahun_kelulusan' => $this->request->getPost('tahun_lulus'), // Fixed field name
                'ipk' => $this->request->getPost('ipk'),
                'alamat' => $this->request->getPost('alamat'),
                'alamat2' => $this->request->getPost('alamat2'),
                'kodepos' => $this->request->getPost('kode_pos'),
                'jenisKelamin' => $this->request->getPost('jeniskelamin'),
                'notlp' => $this->request->getPost('notlp'),
                'id_provinsi' => $this->request->getPost('province'),
                'id_cities' => $this->request->getPost('kota'),
            ];
            if (!$detailAlumni->insert($alumniData)) {
                throw new Exception('Failed to create alumni detail');
            }
            break;

        case '2': // Admin
            $adminData = [
                'id_account' => $accountId,
                'nama_lengkap' => $this->request->getPost('admin_nama_lengkap'),
            ];
            if (!$detailAdmin->insert($adminData)) {
                throw new Exception('Failed to create admin detail');
            }
            break;

        case '3': // Company
            $companyData = [
                'id_account' => $accountId,
                'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
                'alamat_perusahaan' => $this->request->getPost('alamat_perusahaan'),
            ];
            if (!$detailPerusahaan->insert($companyData)) {
                throw new Exception('Failed to create company detail');
            }
            break;
    }
}

private function updateDetailByRole($accountId, $role, $detailAlumni, $detailAdmin, $detailPerusahaan)
{
    switch ($role) {
        case 1: // Alumni
            $alumniData = [
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'nim' => $this->request->getPost('nim'),
                'id_jurusan' => $this->request->getPost('jurusan'),
                'id_prodi' => $this->request->getPost('prodi'),
                'angkatan' => $this->request->getPost('angkatan'),
                'tahun_kelulusan' => $this->request->getPost('tahun_lulus'), // Fixed field name
                'ipk' => $this->request->getPost('ipk'),
                'alamat' => $this->request->getPost('alamat'),
                'alamat2' => $this->request->getPost('alamat2'),
                'kodepos' => $this->request->getPost('kode_pos'),
                'jenisKelamin' => $this->request->getPost('jeniskelamin'),
                'notlp' => $this->request->getPost('notlp'),
                'id_provinsi' => $this->request->getPost('province'),
                'id_cities' => $this->request->getPost('kota'),
            ];
            if (!$detailAlumni->where('id_account', $accountId)->set($alumniData)->update()) {
                throw new Exception('Failed to update alumni detail');
            }
            break;

        case 2: // Admin
            $adminData = [
                'nama_lengkap' => $this->request->getPost('admin_nama_lengkap'),
            ];
            if (!$detailAdmin->where('id_account', $accountId)->set($adminData)->update()) {
                throw new Exception('Failed to update admin detail');
            }
            break;

        case 3: // Company
            $companyData = [
                'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
                'alamat_perusahaan' => $this->request->getPost('alamat_perusahaan'),
            ];
            if (!$detailPerusahaan->where('id_account', $accountId)->set($companyData)->update()) {
                throw new Exception('Failed to update company detail');
            }
            break;
    }
} 
public function delete($id)
{
    $model = new DetailaccountAlumni();
    $modeladmin = new DetailaccountAdmins();
    $accountModel = new Accounts();
    $account = $accountModel->find($id);

    if ($account['id_role'] == 1) {
        $model->where('id_account', $id)->delete();
    } else if ($account['id_role'] == 2) {
        $modeladmin->where('id_account', $id)->delete();
    }

    $accountModel->delete($id); // Jangan lupa hapus juga akun utama
    return redirect()->to('/admin/pengguna')->with('success', 'Data dihapus.');
}


}

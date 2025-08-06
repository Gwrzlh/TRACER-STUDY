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
use App\Models\DetailaccountCompany;
use App\Models\JurusanModel;

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
     $jurusans= new Jurusan();
     $prodis= new Prodi();
     $cityModel = new Cities();
     $provincesModel = new Provincies();

     
     $roles = $roleModels->findAll();
     $dataAccount = $accountModel->find($id);
     $role = $dataAccount['id_role'];

    if ($role == 2) {
        $dataDetail = $detailAdmin->where('id_account', $id)->first();
    } elseif ($role == 1) {
        $dataDetail = $detailAlumni->where('id_account', $id)->first();
    } 
     return view('adminpage\pengguna\edit', [
        'account' => $dataAccount,
        'detail' => $dataDetail,
        'role' => $role,
        'roles' => $roles,
        'datajurusan' => $jurusans->findAll(),
        'dataProdi'   => $prodis->findAll(),
        'cities'      => $cityModel->getCitiesWithProvince(),
        'provinces'   => $provincesModel->findAll()
     ]); 
}
public function update($id)
{
    $accountModel = new Accounts();
    $detailAlumni = new DetailaccountAlumni();
    $detailAdmin = new DetailaccountAdmins();
    $detailPerusahaan = new DetailaccountCompany();

    $validation = \Config\Services::validation();

    // Ambil data account lama berdasarkan ID
    $account = $accountModel->find($id);
    if (!$account) {
        return redirect()->back()->with('error', 'Data akun tidak ditemukan.');
    }
    


    $existingRole = $account['id_role'];
    $group = $this->request->getPost('group');
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');
    $status = $this->request->getPost('status');

    $rules = [
        'username' => "required|is_unique[account.username,id,{$id}]",
        'group'    => 'required'
    ];

    // Validasi password hanya jika diisi
    if (!empty($password)) {
        $rules['password'] = 'min_length[6]';
    }

    // Validasi berdasarkan role
    if ($group == 1) { // Alumni
        $rules['nama_lengkap'] = 'required';
        $rules['prodi'] = 'required';
        $rules['jurusan'] = 'required';
        $rules['alamat'] = 'required';
    } elseif ($group == 2) { // Admin
        $rules['nama_lengkap'] = 'required';
    } elseif ($group == 3) { // Perusahaan
        $rules['nama_perusahaan'] = 'required';
        $rules['alamat_perusahaan'] = 'required';
    }

    if (!$this->validate($rules)) {
    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
}

    // Data update akun
    $updateData = [
        'username' => $username,
        'status'   => $status,
        'id_role' => $group,
    ];

    if (!$accountModel->update($id, $updateData)) {
    dd($accountModel->errors()); // tampilkan error dari model
}

    if (!empty($password)) {
        $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    // Update data akun utama
    $accountModel->update($id, $updateData);

   $newRole = $group;

// Jika role berubah, hapus data lama
if ($existingRole != $newRole) {
    if ($existingRole == 1) {
        $detailAlumni->where('id_account', $id)->delete();
    } elseif ($existingRole == 2) {
        $detailAdmin->where('id_account', $id)->delete();
    } elseif ($existingRole == 3) {
        $detailPerusahaan->where('id_account', $id)->delete();
    }

    // INSERT detail baru berdasarkan $newRole
    if ($newRole == 1) {
        $alumniData = [
            'id_account' => $id,
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'id_prodi'     => $this->request->getPost('prodi'),
            'id_jurusan'   => $this->request->getPost('jurusan'),
            'jenisKelamin' => $this->request->getPost('jeniskelamin'),
            'nim'          => $this->request->getPost('nim'),
            'notlp'        => $this->request->getPost('notlp'),
            'ipk'          => $this->request->getPost('ipk'),
            'angkatan'     => $this->request->getPost('angkatan'),
            'tahun_kelulus'=> $this->request->getPost('tahun_lulus'),
            'id_cities'    => $this->request->getPost('kota'),
            'id_provinsi'  => $this->request->getPost('province'),
            'kodepos'      => $this->request->getPost('kode_pos'),
            'alamat'       => $this->request->getPost('alamat'),
            'alamat2'      => $this->request->getPost('alamat2'),
        ];
        $detailAlumni->insert($alumniData);

    } elseif ($newRole == 2) {
        $adminData = [
            'id_account' => $id,
            'nama_lengkap' => $this->request->getPost('admin_nama_lengkap'),
        ];
        $detailAdmin->insert($adminData);

    } elseif ($newRole == 3) {
        $companyData = [
            'id_account' => $id,
            'nama_perusahaan'   => $this->request->getPost('nama_perusahaan'),
            'alamat_perusahaan' => $this->request->getPost('alamat_perusahaan'),
        ];
        $detailPerusahaan->insert($companyData);
    }

} else {
    // Jika role tidak berubah, lakukan UPDATE
    if ($existingRole == 1) {
        $alumniData = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'id_prodi'     => $this->request->getPost('prodi'),
            'id_jurusan'   => $this->request->getPost('jurusan'),
            'jenisKelamin' => $this->request->getPost('jeniskelamin'),
            'nim'          => $this->request->getPost('nim'),
            'notlp'        => $this->request->getPost('notlp'),
            'ipk'          => $this->request->getPost('ipk'),
            'angkatan'     => $this->request->getPost('angkatan'),
            'tahun_kelulus'=> $this->request->getPost('tahun_lulus'),
            'id_cities'    => $this->request->getPost('kota'),
            'id_provinsi'  => $this->request->getPost('province'),
            'kodepos'      => $this->request->getPost('kode_pos'),
            'alamat'       => $this->request->getPost('alamat'),
            'alamat2'      => $this->request->getPost('alamat2'),
        ];
        $detailAlumni->where('id_account', $id)->set($alumniData)->update();

    } elseif ($existingRole == 2) {
        $adminData = [
            'nama_lengkap' => $this->request->getPost('admin_nama_lengkap'),
        ];
        $detailAdmin->where('id_account', $id)->set($adminData)->update();

    } elseif ($existingRole == 3) {
        $companyData = [
            'nama_perusahaan'   => $this->request->getPost('nama_perusahaan'),
            'alamat_perusahaan' => $this->request->getPost('alamat_perusahaan'),
        ];
        $detailPerusahaan->where('id_account', $id)->set($companyData)->update();
    }
}
    return redirect()->to('/admin/pengguna')->with('success', 'Data pengguna berhasil disimpan.');

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

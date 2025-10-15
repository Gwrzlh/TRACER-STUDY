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
use App\Models\JabatanModels;
use App\Models\JurusanModel;
use App\Models\DetailaccountPerusahaan;
use App\Models\DetailaccountKaprodi;
use App\Models\DetailaccountAtasan;
use App\Models\DetailaccountJabatanLLnya;
use App\Models\LogActivityModel;
use App\Models\ResponseModel;
use App\Models\AnswerModel;

use Exception;

class PenggunaController extends BaseController
{
   public function index()
{
    $accountModel = new \App\Models\Accounts();
    $roleModel    = new \App\Models\Roles();
    $alumniModel  = new \App\Models\DetailaccountAlumni();
    $roles        = $roleModel->findAll();

    // 🔹 Ambil parameter filter
    $roleId      = $this->request->getGet('role');
    $keyword     = $this->request->getGet('keyword');
    $angkatan    = $this->request->getGet('angkatan');
    $tahunLulus  = $this->request->getGet('tahun_lulus');

    // 🔹 Ambil pagination
    $perPage      = get_setting('pengguna_perpage_default', 5);
    $currentPage  = (int) ($this->request->getVar('page') ?? 1);
    $offset       = ($currentPage - 1) * $perPage;

    // 🔹 Build query utama
    $builder = $accountModel->builder();
    $builder->select('account.*, role.nama AS nama_role, da.angkatan, da.tahun_kelulusan')
            ->join('role', 'role.id = account.id_role', 'left')
            ->join('detailaccount_alumni da', 'da.id_account = account.id', 'left');

    // 🔹 Filter Role
    if (!empty($roleId) && is_numeric($roleId)) {
        $builder->where('account.id_role', $roleId);
    }

    // 🔹 Filter Tahun Masuk (angkatan)
    if (!empty($angkatan)) {
        $builder->where('da.angkatan', $angkatan);
    }

    // 🔹 Filter Tahun Lulus
    if (!empty($tahunLulus)) {
        $builder->where('da.tahun_kelulusan', $tahunLulus);
    }

    // 🔹 Filter Keyword
    if (!empty($keyword)) {
        $roleName = '';
        if (!empty($roleId)) {
            $roleData = $roleModel->find($roleId);
            $roleName = strtolower($roleData['nama'] ?? '');
        }

        if ($roleName === 'alumni') {
            $builder->groupStart()
                ->like('da.nim', $keyword)
                ->orLike('da.nama_lengkap', $keyword)
                ->groupEnd();
        } else {
            $builder->groupStart()
                ->like('account.username', $keyword)
                ->orLike('account.email', $keyword)
                ->orLike('account.status', $keyword)
                ->orLike('role.nama', $keyword)
                ->groupEnd();
        }
    }

    // 🔹 Urutkan terbaru
    $builder->orderBy('account.id', 'DESC');

    // 🔹 Hitung total data untuk pagination
    $totalRecords = $builder->countAllResults(false);

    // 🔹 Ambil data sesuai halaman
    $accounts = $builder->limit($perPage, $offset)->get()->getResultArray();

    // 🔹 Buat pagination
    $pager = \Config\Services::pager();
    $pagerLinks = $pager->makeLinks($currentPage, $perPage, $totalRecords, 'bootstrap5');

    // 🔹 Hitung jumlah akun per role
    $counts = [];
    foreach ($roles as $r) {
        $counts[$r['id']] = $accountModel->where('id_role', $r['id'])->countAllResults();
        $accountModel->builder()->resetQuery();
    }
    $counts['all'] = $accountModel->countAllResults();

    // 🔹 Ambil detail tambahan (opsional)
    $detailaccountAdmin  = new \App\Models\DetailaccountAdmins();
    $adminDetails = method_exists($detailaccountAdmin, 'getaccountid')
        ? $detailaccountAdmin->getaccountid()
        : [];

    $alumniDetails = method_exists($alumniModel, 'getDetailWithRelations')
        ? $alumniModel->getDetailWithRelations()
        : [];

    // 🔹 Ambil tahun unik dari database
    $angkatanList   = $alumniModel->select('angkatan')->distinct()->orderBy('angkatan', 'DESC')->findAll();
    $tahunLulusList = $alumniModel->select('tahun_kelulusan')->distinct()->orderBy('tahun_kelulusan', 'DESC')->findAll();

    // 🔹 Kirim data ke view
    $data = [
        'roles'               => $roles,
        'counts'              => $counts,
        'accounts'            => $accounts,
        'pager'               => $pager,
        'pagerLinks'          => $pagerLinks,
        'detailaccountAdmin'  => $adminDetails,
        'detailaccountAlumni' => $alumniDetails,
        'roleId'              => $roleId,
        'keyword'             => $keyword,
        'angkatan'            => $angkatan,
        'tahunLulus'          => $tahunLulus,
        'angkatanList'        => $angkatanList,
        'tahunLulusList'      => $tahunLulusList,
        'perPage'             => $perPage,
        'currentPage'         => $currentPage,
        'totalRecords'        => $totalRecords,
    ];

    return view('adminpage/pengguna/index', $data);
}


    public function create()
    {
        $roles = new Roles();
        $jurusans = new Jurusan();
        $prodis = new Prodi();
        $cityModel = new Cities();
        $provincesModel = new Provincies();
        $jabatanModel = new JabatanModels();
        // Tambahkan ini

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
        $datajurusan = $jurusans->findAll();
        $dataprodi = $prodis->findAll();
        $provinces = $provincesModel->findAll();
        //solve daus
        $jabatan = $jabatanModel->findAll();



        $data = [
            'roles' => $roles,
            'account' => $account,
            'detailaccountAdmin' => $detailaccountAdmin->getaccountid(),
            'detailaccountAlumni' => $detailaccountAlumni->getDetailWithRelations(),
            'roleId'  => $roleId,
            'keyword' => $keyword,
            'countsPerRole' => $countsPerRole,
            'datajurusan'   => $datajurusan,
            'dataProdi'   => $dataprodi,
            'provinces'  => $provinces,
            'jabatan'   => $jabatan

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
        $detailPerusahaan = new DetailaccountPerusahaan();
        $detailAtasan = new DetailaccountAtasan();
        $detailKaprodi = new DetailaccountKaprodi();
        $detailJabatanll = new DetailaccountJabatanLLnya();

        $group = $this->request->getPost('group');

        // RULE VALIDASI DASAR
        $rules = [
            'username' => 'required|is_unique[account.username]',
            'email'    => 'required|valid_email|is_unique[account.email]',
            'password' => 'required|min_length[6]',
            'group'    => 'required|in_list[1,2,6,7,8,9]',
            'status'   => 'required',
        ];

        // RULE TAMBAHAN BERDASARKAN ROLE
        if ($group == 1) {
            $rules = array_merge($rules, [
                'alumni_nama_lengkap' => 'required|min_length[3]',
                'alumni_nim'          => 'required',
                'alumni_notlp'        => 'required|min_length[10]',
            ]);
        } elseif ($group == 2) {
            $rules['admin_nama_lengkap'] = 'required|min_length[3]';
        } elseif ($group == 6) {
            $rules = array_merge($rules, [
                'kaprodi_nama_lengkap' => 'required|min_length[3]',
                'kaprodi_notlp'        => 'required|min_length[10]',
                'kaprodi_jurusan'      => 'required|numeric',
                'kaprodi_prodi'        => 'required|numeric',
            ]);
        } elseif ($group == 7) {
            $rules = array_merge($rules, [
                'perusahaan_nama_perusahaan' => 'required|min_length[3]',
                'perusahaan_notlp'           => 'required|min_length[10]',
            ]);
        } elseif ($group == 8) {
            $rules = array_merge($rules, [
                'atasan_nama_lengkap' => 'required|min_length[3]',
                'atasan_jabatan'      => 'required|numeric',
                'atasan_notlp'        => 'required|min_length[10]',
            ]);
        } elseif ($group == 9) {
            $rules = array_merge($rules, [
                'lainnya_nama_lengkap' => 'required|min_length[3]',
                'lainnya_jabatan'      => 'required|numeric',
                // 'lainnya_jurusan'      => 'required|numeric',
                // 'lainnya_prodi'        => 'required|numeric',
                'lainnya_notlp'        => 'required|min_length[10]',
            ]);
        }

        // VALIDASI
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Email telah terdaftarx. Gagal menambahkan akun.');
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to('/admin/pengguna'); // redirect ke index
        }

        try {
            $accountModel = new Accounts();

            // SIMPAN AKUN
            $accountData = [
                'username'    => $this->request->getPost('username'),
                'email'       => $this->request->getPost('email'),
                'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'status'      => $this->request->getPost('status'),
                'id_role'     => $group,
                'id_surveyor' => $this->request->getPost($this->getHakFieldName($group)) ? 1 : null
            ];

            if (!$accountModel->insert($accountData)) {
                throw new Exception('Gagal menyimpan akun.');
            }

            $accountId = $accountModel->insertID();

            // SIMPAN DETAIL SESUAI ROLE
            switch ($group) {
                case 2: // Admin
                    (new DetailaccountAdmins())->insert([
                        'nama_lengkap' => $this->request->getPost('admin_nama_lengkap'),
                        'id_account'   => $accountId,
                    ]);
                    break;
                case 1: // Alumni
                    (new DetailaccountAlumni())->insert([
                        'nama_lengkap'    => $this->request->getPost('alumni_nama_lengkap'),
                        'nim'             => $this->request->getPost('alumni_nim'),
                        'id_jurusan'      => $this->request->getPost('alumni_jurusan'),
                        'id_prodi'        => $this->request->getPost('alumni_prodi'),
                        'angkatan'        => $this->request->getPost('alumni_angkatan'),
                        'tahun_kelulusan' => $this->request->getPost('alumni_tahun_lulus'),
                        'ipk'             => $this->request->getPost('alumni_ipk'),
                        'jenisKelamin'    => $this->request->getPost('alumni_jeniskelamin'),
                        'notlp'           => $this->request->getPost('alumni_notlp'),
                        'id_cities'       => $this->request->getPost('alumni_kota'),
                        'id_provinsi'     => $this->request->getPost('alumni_province'),
                        'kodepos'         => $this->request->getPost('alumni_kode_pos'),
                        'alamat'          => $this->request->getPost('alumni_alamat'),
                        'alamat2'         => $this->request->getPost('alumni_alamat2'),
                        'id_account'      => $accountId,
                    ]);
                    break;
                case 6: // Kaprodi
                    $detailKaprodi->insert([
                        'nama_lengkap' => $this->request->getPost('kaprodi_nama_lengkap'),
                        'id_jurusan'   => $this->request->getPost('kaprodi_jurusan'),
                        'id_prodi'     => $this->request->getPost('kaprodi_prodi'),
                        'notlp'        => $this->request->getPost('kaprodi_notlp'),
                        'id_account'   => $accountId,
                    ]);
                    break;
                case 7: // Perusahaan
                    $detailPerusahaan->insert([
                        'nama_perusahaan' => $this->request->getPost('perusahaan_nama_perusahaan'),
                        'id_provinsi'     => $this->request->getPost('perusahaan_province'),
                        'id_kota'         => $this->request->getPost('perusahaan_kota'),
                        'alamat1'         => $this->request->getPost('perusahaan_alamat1'),
                        'alamat2'         => $this->request->getPost('perusahaan_alamat2'),
                        'kodepos'         => $this->request->getPost('perusahaan_kode_pos'),
                        'noTlp'           => $this->request->getPost('perusahaan_notlp'),
                        'id_account'      => $accountId,
                    ]);
                    break;
                case 8: // Atasan
                    $detailAtasan->insert([
                        'nama_lengkap' => $this->request->getPost('atasan_nama_lengkap'),
                        'id_jabatan'   => $this->request->getPost('atasan_jabatan'),
                        'notlp'        => $this->request->getPost('atasan_notlp'),
                        'id_account'   => $accountId,
                    ]);
                    break;
                case 9: // Jabatan Lainnya
                    $detailJabatanll->insert([
                        'nama_lengkap' => $this->request->getPost('lainnya_nama_lengkap'),
                        'id_jabatan'   => $this->request->getPost('lainnya_jabatan'),
                        // 'id_jurusan'   => $this->request->getPost('lainnya_jurusan'),
                        // 'id_prodi'     => $this->request->getPost('lainnya_prodi'),
                        'notlp'        => $this->request->getPost('lainnya_notlp'),
                        'id_account'   => $accountId,
                    ]);
                    break;
            }

            session()->setFlashdata('success', 'Data pengguna berhasil disimpan.');
            return redirect()->to('/admin/pengguna');
        } catch (\Exception $e) {
            log_message('error', 'Error saving user: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->to('/admin/pengguna'); // redirect ke index kalau gagal
        }
    }


    // Helper method untuk menentukan field name hak supervisi
    private function getHakFieldName($group)
    {
        switch ($group) {
            case '1':
                return 'alumni_hak';
            case '6':
                return 'kaprodi_hak';
            case '9':
                return 'lainnya_hak';
            default:
                return 'hak';
        }
    }
    public function edit($id)
    {
        $accountModel = new Accounts();
        $detailAlumni = new DetailaccountAlumni();
        $detailAdmin = new DetailaccountAdmins();
        $detailPerusahaan = new DetailaccountPerusahaan();
        // Tambahkan model untuk role baru
        $detailKaprodi = new DetailaccountKaprodi(); // Pastikan model ini ada
        $detailAtasan = new DetailaccountAtasan(); // Pastikan model ini ada  
        $detailLainnya = new DetailaccountJabatanLLnya(); // Pastikan model ini ada

        $roleModels = new Roles();
        $jurusans = new Jurusan();
        $prodis = new Prodi();
        $cityModel = new Cities();
        $provincesModel = new Provincies();
        $jabatanModels = new JabatanModels();

        $roles = $roleModels->findAll();
        $dataAccount = $accountModel->find($id);


        if (!$dataAccount) {
            return redirect()->back()->with('error', 'Data akun tidak ditemukan.');
        }

        $role = $dataAccount['id_role'];
        $dataDetail = null;


        $cities = [];
        $kotaPerusahaan = [];

        // Get detail data based on current role
        switch ($role) {
            case 1: // Alumni
                $dataDetail = $detailAlumni->where('id_account', $id)->first();

                if (!empty($dataDetail['id_provinsi'])) {
                    $cities = $cityModel
                        ->where('province_id', $dataDetail['id_provinsi'])
                        ->findAll();
                }
                break;
            case 2: // Admin
                $dataDetail = $detailAdmin->where('id_account', $id)->first();
                break;
            case 6: // Kaprodi
                $dataDetail = $detailKaprodi->where('id_account', $id)->first();
                // dd($dataDetail);
                break;
            case 7: // Perusahaan

                $dataDetail = $detailPerusahaan->where('id_account', $id)->first();

                log_message('debug', 'Data Detail Perusahaan: ' . json_encode($dataDetail));

                if (!empty($dataDetail) && !empty($dataDetail['id_provinsi'])) {
                    $kotaPerusahaan = $cityModel
                        ->where('province_id', $dataDetail['id_provinsi'])
                        ->findAll();

                    // Debug untuk melihat data kota yang diambil
                    log_message('debug', 'Kota Perusahaan: ' . json_encode($kotaPerusahaan));
                }
                break;

            case 8: // Atasan
                $dataDetail = $detailAtasan->where('id_account', $id)->first();
                break;
            case 9: // Jabatan Lainnya
                $dataDetail = $detailLainnya->where('id_account', $id)->first();
                break;
            default:
                $dataDetail = null;
        }



        // dd($cities);

        return view('adminpage\pengguna\edit', [
            'account' => $dataAccount,
            'detail' => $dataDetail,
            'role' => $role,
            'roles' => $roles,
            'datajurusan' => $jurusans->findAll(),
            'dataProdi' => $prodis->findAll(),
            'cities' => $cities,
            'kotaPerusahaan' => $kotaPerusahaan,
            'provinces' => $provincesModel->findAll(),
            'jabatan' => $jabatanModels->findAll(), // Tambahkan data jabatan
        ]);
    }

    public function update($id)
    {
        $accountModel    = new Accounts();
        $detailAlumni    = new DetailaccountAlumni();
        $detailAdmin     = new DetailaccountAdmins();
        $detailPerusahaan = new DetailaccountPerusahaan();
        $detailKaprodi   = new DetailaccountKaprodi();
        $detailAtasan    = new DetailaccountAtasan();
        $detailLainnya   = new DetailaccountJabatanLLnya();

        $account = $accountModel->find($id);
        if (!$account) {
            return redirect()->back()->with('error', 'Data akun tidak ditemukan.');
        }

        $existingRole = $account['id_role'];
        $newRole      = $this->request->getPost('group'); // pastikan sesuai dengan form
        $username     = $this->request->getPost('username');
        $email        = $this->request->getPost('email');
        $password     = $this->request->getPost('password');
        $status       = $this->request->getPost('status');

        // Validasi umum
        $rules = [
            'username' => "required|is_unique[account.username,id,{$id}]",
            'email'    => "required|valid_email|is_unique[account.email,id,{$id}]",
            'group'    => 'required',
            'status'   => 'required',
        ];

        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        // Validasi tambahan berdasarkan role
        switch ($newRole) {
            case '1': // Alumni
                $rules = array_merge($rules, [
                    'alumni_nama_lengkap' => 'required',
                    'alumni_nim'          => 'required|numeric',
                    'alumni_jurusan'      => 'required',
                    'alumni_prodi'        => 'required',
                    'alumni_notlp'        => 'required|numeric',
                ]);
                break;
            case '2': // Admin
                $rules['admin_nama_lengkap'] = 'required';
                break;
            case '6': // Kaprodi
                $rules = array_merge($rules, [
                    'kaprodi_nama_lengkap' => 'required',
                    'kaprodi_jurusan'      => 'required',
                    'kaprodi_prodi'        => 'required',
                    'kaprodi_notlp'        => 'required|numeric',
                ]);
                break;
            case '7': // Perusahaan
                $rules = array_merge($rules, [
                    'perusahaan_nama_perusahaan' => 'required',
                    'perusahaan_notlp'           => 'required|numeric',
                ]);
                break;
            case '8': // Atasan
                $rules = array_merge($rules, [
                    'atasan_nama_lengkap' => 'required',
                    'atasan_jabatan'      => 'required',
                    'atasan_notlp'        => 'required|numeric',
                ]);
                break;
            case '9': // Jabatan Lainnya
                $rules = array_merge($rules, [
                    'lainnya_nama_lengkap' => 'required',
                    'lainnya_jabatan'      => 'required',
                    // 'lainnya_jurusan'      => 'required',
                    // 'lainnya_prodi'        => 'required',
                    'lainnya_notlp'        => 'required|numeric',
                ]);
                break;
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Hak surveyor
        $hakSurveyor = null;
        switch ($newRole) {
            case '1':
                $hakSurveyor = $this->request->getPost('alumni_hak') ? 1 : null;
                break;
            case '6':
                $hakSurveyor = $this->request->getPost('kaprodi_hak') ? 1 : null;
                break;
            case '9':
                $hakSurveyor = $this->request->getPost('lainnya_hak') ? 1 : null;
                break;
        }

        // Data utama
        $updateData = [
            'username'    => $username,
            'email'       => $email,
            'status'      => $status,
            'id_role'     => $newRole,
            'id_surveyor' => $hakSurveyor,
        ];
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            if (!$accountModel->update($id, $updateData)) {
                throw new \Exception('Gagal update akun utama.');
            }

            // Update detail sesuai role
            $this->handleDetailAccountUpdate(
                $id,
                $existingRole,
                $newRole,
                $detailAlumni,
                $detailAdmin,
                $detailPerusahaan,
                $detailKaprodi,
                $detailAtasan,
                $detailLainnya
            );

            $db->transCommit();
            return redirect()->to('/admin/pengguna')->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Update user gagal: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function handleDetailAccountUpdate($accountId, $existingRole, $newRole, $detailAlumni, $detailAdmin, $detailPerusahaan, $detailKaprodi, $detailAtasan, $detailLainnya)
    {
        // If role changed, delete old detail and create new one
        if ($existingRole != $newRole) {
            // Delete old detail
            $this->deleteDetailByRole($accountId, $existingRole, $detailAlumni, $detailAdmin, $detailPerusahaan, $detailKaprodi, $detailAtasan, $detailLainnya);

            // Create new detail
            $this->createDetailByRole($accountId, $newRole, $detailAlumni, $detailAdmin, $detailPerusahaan, $detailKaprodi, $detailAtasan, $detailLainnya);
        } else {
            // Update existing detail
            $this->updateDetailByRole($accountId, $existingRole, $detailAlumni, $detailAdmin, $detailPerusahaan, $detailKaprodi, $detailAtasan, $detailLainnya);
        }
    }

    private function deleteDetailByRole($accountId, $role, $detailAlumni, $detailAdmin, $detailPerusahaan, $detailKaprodi, $detailAtasan, $detailLainnya)
    {
        switch ($role) {
            case 1:
                $detailAlumni->where('id_account', $accountId)->delete();
                break;
            case 2:
                $detailAdmin->where('id_account', $accountId)->delete();
                break;

            case 7: // Perusahaan
                $detailPerusahaan->where('id_account', $accountId)->delete();
                break;
            case 6: // Kaprodi
                $detailKaprodi->where('id_account', $accountId)->delete();
                break;
            case 8: // Atasan
                $detailAtasan->where('id_account', $accountId)->delete();
                break;
            case 9: // Jabatan Lainnya
                $detailLainnya->where('id_account', $accountId)->delete();
                break;
        }
    }

    private function createDetailByRole($accountId, $role, $detailAlumni, $detailAdmin, $detailPerusahaan, $detailKaprodi, $detailAtasan, $detailLainnya)
    {
        switch ($role) {
            case '1': // Alumni
                $alumniData = [
                    'id_account' => $accountId,
                    'nama_lengkap' => $this->request->getPost('alumni_nama_lengkap'),
                    'nim' => $this->request->getPost('alumni_nim'),
                    'id_jurusan' => $this->request->getPost('alumni_jurusan'),
                    'id_prodi' => $this->request->getPost('alumni_prodi'),
                    'angkatan' => $this->request->getPost('alumni_angkatan'),
                    'tahun_kelulusan' => $this->request->getPost('alumni_tahun_lulus'),
                    'ipk' => $this->request->getPost('alumni_ipk'),
                    'alamat' => $this->request->getPost('alumni_alamat'),
                    'alamat2' => $this->request->getPost('alumni_alamat2'),
                    'kodepos' => $this->request->getPost('alumni_kode_pos'),
                    'jenisKelamin' => $this->request->getPost('alumni_jeniskelamin'),
                    'notlp' => $this->request->getPost('alumni_notlp'),
                    'id_provinsi' => $this->request->getPost('alumni_province'),
                    'id_cities' => $this->request->getPost('alumni_kota'),
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

            case '6': // Kaprodi
                $kaprodiData = [
                    'id_account' => $accountId,
                    'nama_lengkap' => $this->request->getPost('kaprodi_nama_lengkap'),
                    'id_jurusan' => $this->request->getPost('kaprodi_jurusan'),
                    'id_prodi' => $this->request->getPost('kaprodi_prodi'),
                    'notlp' => $this->request->getPost('kaprodi_notlp'),
                ];
                if (!$detailKaprodi->insert($kaprodiData)) {
                    throw new Exception('Failed to create kaprodi detail');
                }
                break;

            case '7': // Perusahaan
                $perusahaanData = [
                    'id_account' => $accountId,
                    'nama_perusahaan' => $this->request->getPost('perusahaan_nama_perusahaan'),
                    'noTlp' => $this->request->getPost('perusahaan_notlp'),
                    'alamat' => $this->request->getPost('perusahaan_alamat'),
                    'alamat2' => $this->request->getPost('perusahaan_alamat2'),
                    'kodepos' => $this->request->getPost('perusahaan_kode_pos'),
                    'id_provinsi' => $this->request->getPost('perusahaan_province'),
                    'id_cities' => $this->request->getPost('perusahaan_kota')
                ];
                if (!$detailPerusahaan->insert($perusahaanData)) {
                    throw new \Exception('Failed to create perusahaan detail');
                }
                break;

            case '8': // Atasan
                $atasanData = [
                    'id_account' => $accountId,
                    'nama_lengkap' => $this->request->getPost('atasan_nama_lengkap'),
                    'id_jabatan' => $this->request->getPost('atasan_jabatan'),
                    'notlp' => $this->request->getPost('atasan_notlp'),

                ];
                if (!$detailAtasan->insert($atasanData)) {
                    throw new \Exception('Failed to create atasan detail');
                }
                break;

            case '9': // Jabatan Lainnya
                $lainnyaData = [
                    'id_account' => $accountId,
                    'nama_lengkap' => $this->request->getPost('lainnya_nama_lengkap'),
                    'jabatan' => $this->request->getPost('lainnya_jabatan'),
                    // 'id_jurusan' => $this->request->getPost('lainnya_jurusan'),
                    // 'id_prodi' => $this->request->getPost('lainnya_prodi'),
                    'notlp' => $this->request->getPost('lainnya_notlp'),
                    'alamat' => $this->request->getPost('lainnya_alamat'),
                    'alamat2' => $this->request->getPost('lainnya_alamat2'),
                    'kodepos' => $this->request->getPost('lainnya_kode_pos'),
                    'id_provinsi' => $this->request->getPost('lainnya_province'),
                    'id_cities' => $this->request->getPost('lainnya_kota')
                ];
                if (!$detailLainnya->insert($lainnyaData)) {
                    throw new \Exception('Failed to create jabatan lainnya detail');
                }
                break;
        }
    }
    public function updateDetailByRole($accountId, $role, $detailAlumni, $detailAdmin, $detailPerusahaan, $detailKaprodi, $detailAtasan, $detailLainnya)
    {
        switch ($role) {
            case '1': // Alumni
                $alumniData = [
                    'nama_lengkap' => $this->request->getPost('alumni_nama_lengkap'),
                    'nim' => $this->request->getPost('alumni_nim'),
                    'id_jurusan' => $this->request->getPost('alumni_jurusan'),
                    'id_prodi' => $this->request->getPost('alumni_prodi'),
                    'angkatan' => $this->request->getPost('alumni_angkatan'),
                    'tahun_kelulusan' => $this->request->getPost('alumni_tahun_lulus'),
                    'ipk' => $this->request->getPost('alumni_ipk'),
                    'alamat' => $this->request->getPost('alumni_alamat'),
                    'alamat2' => $this->request->getPost('alumni_alamat2'),
                    'kodepos' => $this->request->getPost('alumni_kode_pos'),
                    'jenisKelamin' => $this->request->getPost('alumni_jeniskelamin'),
                    'notlp' => $this->request->getPost('alumni_notlp'),
                    'id_provinsi' => $this->request->getPost('alumni_province'),
                    'id_cities' => $this->request->getPost('alumni_kota'),
                ];
                if (!$detailAlumni->where('id_account', $accountId)->set($alumniData)->update()) {
                    throw new Exception('Failed to update alumni detail');
                }
                break;

            case '2': // Admin
                $adminData = [
                    'nama_lengkap' => $this->request->getPost('admin_nama_lengkap'),
                ];
                if (!$detailAdmin->where('id_account', $accountId)->set($adminData)->update()) {
                    throw new Exception('Failed to update admin detail');
                }
                break;

            case '7': // Perusahaan
                $perusahaanData = [
                    'nama_perusahaan' => $this->request->getPost('perusahaan_nama_perusahaan'),
                    'alamat1' => $this->request->getPost('perusahaan_alamat1'),
                    'alamat2' => $this->request->getPost('perusahaan_alamat2'),
                    'kodepos' => $this->request->getPost('perusahaan_kode_pos'),
                    'id_provinsi' => $this->request->getPost('perusahaan_province'),
                    'id_kota' => $this->request->getPost('perusahaan_kota'),
                    'notlp' => $this->request->getPost('perusahaan_notlp'),
                ];
                if (!$detailPerusahaan->where('id_account', $accountId)->set($perusahaanData)->update()) {
                    throw new Exception('Failed to update perusahaan detail');
                }
                break;

            case '6': // Kaprodi
                $kaprodiData = [
                    'nama_lengkap' => $this->request->getPost('kaprodi_nama_lengkap'),
                    'id_jurusan'   => $this->request->getPost('kaprodi_jurusan'),
                    'id_prodi'     => $this->request->getPost('kaprodi_prodi'),
                    'notlp'       => $this->request->getPost('kaprodi_notlp'),
                ];
                if (!$detailKaprodi->where('id_account', $accountId)->set($kaprodiData)->update()) {
                    throw new Exception('Failed to update kaprodi detail');
                }
                break;

            case '8': // Atasan
                $atasanData = [
                    'nama_lengkap' => $this->request->getPost('atasan_nama_lengkap'),
                    'id_jabatan'   => $this->request->getPost('atasan_jabatan'),
                    'notlp'       => $this->request->getPost('atasan_notlp'),
                ];
                if (!$detailAtasan->where('id_account', $accountId)->set($atasanData)->update()) {
                    throw new Exception('Failed to update atasan detail');
                }
                break;

            case '9': // Jabatan Lainnya
                $lainnyaData = [
                    'nama_lengkap' => $this->request->getPost('lainnya_nama_lengkap'),
                    'id_jurusan'   => $this->request->getPost('lainnya_jurusan'),
                    // 'id_prodi'     => $this->request->getPost('lainnya_prodi'),
                    // 'id_jabatan'   => $this->request->getPost('lainnya_jabatan'),
                    'notlp'       => $this->request->getPost('lainnya_notlp'),
                ];
                if (!$detailLainnya->where('id_account', $accountId)->set($lainnyaData)->update()) {
                    throw new Exception('Failed to update jabatan lainnya detail');
                }
                break;
        }
    }
    public function delete($id)
{
    // ====== Inisialisasi semua model yang mungkin dipakai ======
    $accountModel = new Accounts();
    $logModel = new LogActivityModel();
    $answersModel = new AnswerModel();
    $responsesModel = new ResponseModel();

    // Detail akun per-role
    $detailAlumni = new DetailaccountAlumni();
    $detailAdmins = new DetailaccountAdmins();
    $detailPerusahaan = new DetailaccountPerusahaan();
    $detailKaprodi = new DetailaccountKaprodi();
    $detailAtasan = new DetailaccountAtasan();
    $detailJabatanLainnya = new DetailaccountJabatanLLnya();

    // ====== Cek apakah akun ada ======
    $account = $accountModel->find($id);
    if (!$account) {
        return redirect()->back()->with('error', 'Akun tidak ditemukan.');
    }

    // ====== Mulai transaksi database ======
    $db = \Config\Database::connect();
    $db->transStart();

    try {
        // ====== (1) Hapus data turunan yang umum ======
        $answersModel->where('user_id', $id)->delete();
        $responsesModel->where('account_id', $id)->delete();
        $logModel->where('user_id', $id)->delete();

        // ====== (2) Hapus detail akun sesuai role ======
        switch ($account['id_role']) {
            case 1: // Alumni
                $detailAlumni->where('id_account', $id)->delete();
                break;
            case 2: // Admin
                $detailAdmins->where('id_account', $id)->delete();
                break;
            case 6: // Kaprodi
                $detailKaprodi->where('id_account', $id)->delete();
                break;
            case 7: // Perusahaan
                $detailPerusahaan->where('id_account', $id)->delete();
                break;
            case 8: // Atasan
                $detailAtasan->where('id_account', $id)->delete();
                break;
            case 9: // Jabatan Lainnya
                $detailJabatanLainnya->where('id_account', $id)->delete();
                break;
            default:
                // jika role baru tidak dikenali, aman tidak melakukan apa pun
                log_message('warning', "[deleteAccount] Tidak ada detail table untuk role {$account['id_role']}");
                break;
        }

        // ====== (3) Terakhir hapus akun utama ======
        $accountModel->delete($id);

        // ====== (4) Commit transaksi ======
        $db->transComplete();

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menghapus akun. Transaksi dibatalkan.');
        }

        // ====== (5) Sukses ======
        return redirect()->to('/admin/pengguna')->with('success', 'Akun dan data terkait berhasil dihapus.');

    } catch (\Exception $e) {
        // Rollback kalau ada error
        $db->transRollback();
        log_message('error', "[deleteAccount] Error: " . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function getProdiByJurusan($id_jurusan)
    {
        $prodiModel = new Prodi();
        $data = $prodiModel->where('id_jurusan', $id_jurusan)->findAll();

        return $this->response->setJSON($data);
    }

public function deleteMultiple()
{
    $ids = $this->request->getPost('ids');

    if (!$ids) {
        return redirect()->back()->with('error', 'Tidak ada akun yang dipilih untuk dihapus.');
    }

    $accountModel = new \App\Models\Accounts();
    $alumniModel = new \App\Models\DetailaccountAlumni();
    $adminModel = new \App\Models\DetailaccountAdmins();
    $kaprodiModel = new \App\Models\DetailaccountKaprodi();
    $perusahaanModel = new \App\Models\DetailaccountPerusahaan();
    $atasanModel = new \App\Models\DetailaccountAtasan();
    $jabatanLainModel = new \App\Models\DetailaccountJabatanLLnya();
    $logModel = new \App\Models\LogActivityModel();
    $responsesModel = new ResponseModel();
    $answersModel = new AnswerModel();

    // Hapus dulu semua data turunan (child)
    $alumniModel->whereIn('id_account', $ids)->delete();
    $adminModel->whereIn('id_account', $ids)->delete();
    $kaprodiModel->whereIn('id_account', $ids)->delete();
    $perusahaanModel->whereIn('id_account', $ids)->delete();
    $atasanModel->whereIn('id_account', $ids)->delete();
    $jabatanLainModel->whereIn('id_account', $ids)->delete();
    $logModel->whereIn('user_id', $ids)->delete();
    $responsesModel->whereIn('account_id', $ids)->delete();
    $answersModel->whereIn('user_id',$ids)->delete();

    
    // Setelah semua child terhapus, baru hapus akun utama
    $deleted = $accountModel->whereIn('id', $ids)->delete();

    if ($deleted) {
        return redirect()->back()->with('success', 'Akun yang dipilih berhasil dihapus.');
    } else {
        return redirect()->back()->with('error', 'Gagal menghapus akun yang dipilih.');
    }
}


public function exportSelected()
{
    $ids = $this->request->getPost('ids');
    if (empty($ids)) {
        return redirect()->back()->with('error', 'Tidak ada pengguna yang dipilih untuk diexport.');
    }

    $model = new \App\Models\AccountModel();
    $data = $model->whereIn('id', $ids)->findAll();

    if (!$data) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // buat file CSV
    $filename = 'pengguna_terpilih_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Username', 'Email', 'Status', 'Role' ]);

    foreach ($data as $row) {
        fputcsv($output, [
            $row['id'],
            $row['username'],
            $row['email'],
            $row['status'],
            $row['id_role'],
        ]);
    }

    fclose($output);
    exit;
}



}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Accounts;
use App\Models\DetailaccountAdmins;
use App\Models\DetailaccountAlumni;
use App\Models\DetailaccountAtasan;
use App\Models\DetailaccountJabatanLLnya;
use App\Models\DetailaccountKaprodi;
use App\Models\DetailaccountPerusahaan;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Cities;
use App\Models\Provincies;
use App\Models\Roles;
use App\Models\JabatanModels;

class ImportAccount extends BaseController
{
    public function index()
    {
        return view('adminpage/pengguna/import');
    }

  public function import()
{
    $file = $this->request->getFile('file');
    $role = $this->request->getPost('role');

    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('error', 'File tidak valid atau tidak ditemukan.');
    }

    try {
        $spreadsheet = IOFactory::load($file->getTempName());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $accountModel = new Accounts();
        $countSuccess = 0;
        $errorLogs = [];

        foreach (array_slice($rows, 1) as $i => $row) {
            if (empty($row[0])) continue; // skip baris kosong

            $email    = trim($row[0]);
            $password = password_hash(trim($row[1]), PASSWORD_DEFAULT);
            $username = trim($row[2]);
            $nama     = trim($row[3]);

            // Cek duplikasi email
            if ($accountModel->where('email', $email)->first()) {
                $errorLogs[] = "Baris " . ($i + 2) . ": Email $email sudah terdaftar.";
                continue;
            }

            try {
                // Insert akun utama
                $accountId = $accountModel->insert([
                    'username' => $username,
                    'email'    => $email,
                    'password' => $password,
                    'status'   => 'active',
                    'id_role'  => $this->mapRoleId($role)
                ], true);

                // Insert detail sesuai role
                switch ($role) {
                    case 'alumni':
                        $idJurusan = $this->mapJurusan($row[5] ?? null);
                        if (!$idJurusan) {
                            $errorLogs[] = "Baris " . ($i + 2) . ": Jurusan '" . ($row[5] ?? '-') . "' tidak ditemukan.";
                            $accountModel->delete($accountId); // rollback akun
                            continue 2;
                        }

                        (new DetailaccountAlumni())->insert([
                            'nama_lengkap'    => $nama,
                            'nim'             => $row[4] ?? null,
                            'id_jurusan'      => $idJurusan,
                            'id_prodi'        => $this->mapProdi($row[6] ?? null),
                            'angkatan'        => $row[7] ?? null,
                            'ipk'             => $row[8] ?? null,
                            'alamat'          => $row[9] ?? null,
                            'alamat2'         => $row[10] ?? null,
                            'id_cities'       => $this->mapCity($row[11] ?? null),
                            'id_provinsi'     => $this->mapProvinsi($row[12] ?? null),
                            'kodepos'         => $row[13] ?? null,
                            'tahun_kelulusan' => $row[14] ?? null,
                            'jenisKelamin'    => $row[15] ?? null,
                            'notlp'           => $row[16] ?? null,
                            'id_account'      => $accountId,
                        ]);
                        break;

                    case 'admin':
                        (new DetailaccountAdmins())->insert([
                            'nama_lengkap' => $nama,
                            'no_hp'        => $row[16] ?? null,
                            'id_account'   => $accountId,
                        ]);
                        break;

                    case 'perusahaan':
                        (new DetailaccountPerusahaan())->insert([
                            'nama_perusahaan' => $nama,
                            'alamat1'         => $row[9] ?? null,
                            'alamat2'         => $row[10] ?? null,
                            'id_provinsi'     => $this->mapProvinsi($row[12] ?? null),
                            'id_kota'         => $this->mapCity($row[11] ?? null),
                            'kodepos'         => $row[13] ?? null,
                            'noTlp'           => $row[16] ?? null,
                            'id_account'      => $accountId,
                        ]);
                        break;

                     case 'kaprodi':
    // Ambil jurusan dari kolom ke-5 (index 4)
    $idJurusan = $this->mapJurusan($row[4] ?? null);
    if (!$idJurusan) {
        $errorLogs[] = "Baris " . ($i + 2) . ": Jurusan '" . ($row[4] ?? '-') . "' tidak ditemukan.";
        $accountModel->delete($accountId);
        continue 2;
    }

    // Insert data kaprodi sesuai kolom CSV
    (new DetailaccountKaprodi())->insert([
        'nama_lengkap' => $row[3] ?? null,                 // kolom ke-4 = nama lengkap
        'id_jurusan'   => $idJurusan,                      // hasil map jurusan
        'id_prodi'     => $this->mapProdi($row[5] ?? null),// kolom ke-6 = program studi
        'notlp'        => $row[6] ?? null,                 // kolom ke-7 = nomor telepon
        'id_account'   => $accountId,                      // relasi akun
    ]);
    break;


                    case 'atasan':
                        (new DetailaccountAtasan())->insert([
                            'nama_lengkap' => $nama,
                            'id_jabatan'   => $this->mapJabatan($row[6] ?? null),
                            'notlp'        => $row[16] ?? null,
                            'id_account'   => $accountId,
                        ]);
                        break;

                    case 'jabatan lainnya':
                        $idJurusan = $this->mapJurusan($row[5] ?? null);
                        if (!$idJurusan) {
                            $errorLogs[] = "Baris " . ($i + 2) . ": Jurusan '" . ($row[5] ?? '-') . "' tidak ditemukan di database.";
                            $accountModel->delete($accountId);
                            continue 2;
                        }

                        (new DetailaccountJabatanLLnya())->insert([
                            'nama_lengkap' => $nama,
                            'id_prodi'     => $this->mapProdi($row[6] ?? null),
                            'id_jurusan'   => $idJurusan,
                            'id_jabatan'   => $this->mapJabatan($row[6] ?? null),
                            'notlp'        => $row[16] ?? null,
                            'id_account'   => $accountId,
                        ]);
                        break;
                }

                $countSuccess++;
            } catch (\Exception $e) {
                $errorLogs[] = "Baris " . ($i + 2) . ": Gagal insert - " . $e->getMessage();
            }
        }

        // ✅ Simpan juga ke tabel error_logs
        if (!empty($errorLogs)) {
            foreach ($errorLogs as $msg) {
                log_error('import', $msg, $file->getName());
            }
        }

        // ✅ Kembalikan ke halaman pengguna
        return redirect()->to('/admin/pengguna')
            ->with('success', "Import selesai. Berhasil: $countSuccess, Gagal: " . count($errorLogs))
            ->with('errorLogs', $errorLogs);

    } catch (\Throwable $e) {
        // ✅ Simpan log fatal ke DB
        log_error('import', $e->getMessage(), $file->getName());

        // ✅ Tampilkan flash ke user
        session()->setFlashdata('error', 'Import gagal, lihat riwayat error untuk detailnya.');
        return redirect()->back();
    }
}

    // =========================
    // Mapping Helper Functions
    // =========================

    private function mapRoleId($role)
    {
        $roleModel = new Roles();
        $roleData = $roleModel->where('nama', $role)->first();
        return $roleData['id'] ?? null;
    }

    private function mapJurusan($nama)
{
    if (!$nama) return null;

    $model = new \App\Models\Jurusan();

    // Cari berdasarkan singkatan
    $data = $model->where('singkatan', strtoupper(trim($nama)))->first();
    if ($data) return $data['id'];

    // Cari berdasarkan nama jurusan full
    $data = $model->where('nama_jurusan', $nama)->first();
    if ($data) return $data['id'];

    // Fallback LIKE
    $data = $model->like('nama_jurusan', $nama)->first();
    return $data['id'] ?? null;
}



    private function mapProdi($nama)
    {
        if (!$nama) return null;
        $model = new Prodi();
        $data = $model->like('nama_prodi', $nama)->first();
        return $data['id'] ?? null;
    }

    private function mapCity($nama)
    {
        if (!$nama) return null;
        $nama = trim(str_ireplace(['Kabupaten', 'Kota'], '', $nama));

        $model = new Cities();
        $data = $model->like('name', $nama)->first();

        if (!$data) {
            $data = $model->where('name', $nama)->first();
        }

        if (!$data) {
            log_message('error', "City not found for: " . $nama);
        }

        return $data['id'] ?? null;
    }

    private function mapProvinsi($nama)
    {
        if (!$nama) return null;
        $model = new Provincies();
        $data = $model->like('name', $nama)->first();
        return $data['id'] ?? null;
    }

    private function mapJabatan($nama)
    {
        if (!$nama) return null;
        $model = new JabatanModels();
        $data = $model->like('jabatan', $nama)->first();
        return $data['id'] ?? null;
    }
}

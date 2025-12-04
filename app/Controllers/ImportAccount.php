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
                $email    = trim($row[0] ?? '');
                $passwordRaw = trim($row[1] ?? '');
                $username = trim($row[2] ?? '');
                $nama     = trim($row[3] ?? '');
                $jurusan  = trim($row[4] ?? '');
                $prodi    = trim($row[5] ?? '');
                $notlp    = trim($row[6] ?? '');

                // ✅ Validasi data wajib (email, password, notlp, jurusan, program_study)
                if (empty($email) || empty($passwordRaw) || empty($notlp) || empty($jurusan) || empty($prodi)) {
                    session()->setFlashdata(
                        'errorWajib',
                        'Mohon isi semua data wajib (email, no_tlp, password, jurusan, dan program_study) pada baris ke-' . ($i + 2)
                    );
                    return redirect()->to(base_url('admin/pengguna'));
                }

                $password = password_hash($passwordRaw, PASSWORD_DEFAULT);

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
                            $idJurusan = $this->mapJurusan($jurusan);
                            if (!$idJurusan) {
                                $errorLogs[] = "Baris " . ($i + 2) . ": Jurusan '$jurusan' tidak ditemukan.";
                                $accountModel->delete($accountId); // rollback akun
                                continue 2;
                            }

                            (new DetailaccountAlumni())->insert([
                                'nama_lengkap'    => $nama,
                                'nim'             => $row[4] ?? null,
                                'id_jurusan'      => $idJurusan,
                                'id_prodi'        => $this->mapProdi($prodi),
                                'angkatan'        => $row[7] ?? null,
                                'ipk'             => $row[8] ?? null,
                                'alamat'          => $row[9] ?? null,
                                'alamat2'         => $row[10] ?? null,
                                'id_cities'       => $this->mapCity($row[11] ?? null),
                                'id_provinsi'     => $this->mapProvinsi($row[12] ?? null),
                                'kodepos'         => $row[13] ?? null,
                                'tahun_kelulusan' => $row[14] ?? null,
                                'jenisKelamin'    => $row[15] ?? null,
                                'notlp'           => $notlp,
                                'id_account'      => $accountId,
                            ]);
                            break;

                        case 'admin':
                            (new DetailaccountAdmins())->insert([
                                'nama_lengkap' => $nama,
                                'no_hp'        => $notlp,
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
                                'noTlp'           => $notlp,
                                'id_account'      => $accountId,
                            ]);
                            break;

                        case 'kaprodi':
                            $idJurusan = $this->mapJurusan($jurusan);
                            if (!$idJurusan) {
                                $errorLogs[] = "Baris " . ($i + 2) . ": Jurusan '$jurusan' tidak ditemukan.";
                                $accountModel->delete($accountId);
                                continue 2;
                            }

                            (new DetailaccountKaprodi())->insert([
                                'nama_lengkap' => $nama,
                                'id_jurusan'   => $idJurusan,
                                'id_prodi'     => $this->mapProdi($prodi),
                                'notlp'        => $notlp,
                                'id_account'   => $accountId,
                            ]);
                            break;

                        case 'atasan':
                            (new DetailaccountAtasan())->insert([
                                'nama_lengkap' => $nama,
                                'id_jabatan'   => $this->mapJabatan($row[6] ?? null),
                                'notlp'        => $notlp,
                                'id_account'   => $accountId,
                            ]);
                            break;

                        case 'jabatan lainnya':
                            $idJurusan = $this->mapJurusan($jurusan);
                            if (!$idJurusan) {
                                $errorLogs[] = "Baris " . ($i + 2) . ": Jurusan '$jurusan' tidak ditemukan di database.";
                                $accountModel->delete($accountId);
                                continue 2;
                            }

                            (new DetailaccountJabatanLLnya())->insert([
                                'nama_lengkap' => $nama,
                                'id_prodi'     => $this->mapProdi($prodi),
                                'id_jurusan'   => $idJurusan,
                                'id_jabatan'   => $this->mapJabatan($row[6] ?? null),
                                'notlp'        => $notlp,
                                'id_account'   => $accountId,
                            ]);
                            break;
                    }

                    $countSuccess++;
                } catch (\Exception $e) {
                    $errorLogs[] = "Baris " . ($i + 2) . ": Gagal insert - " . $e->getMessage();
                }
            }

            // Simpan juga ke tabel error_logs
            if (!empty($errorLogs)) {
                foreach ($errorLogs as $msg) {
                    log_error('import', $msg, $file->getName());
                }
            }

            // Kembalikan ke halaman pengguna
            return redirect()->to('/admin/pengguna')
                ->with('success', "Import selesai. Berhasil: $countSuccess, Gagal: " . count($errorLogs))
                ->with('errorLogs', $errorLogs);

        } catch (\Throwable $e) {
            log_error('import', $e->getMessage(), $file->getName());
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

        $model = new Jurusan();

        $data = $model->where('singkatan', strtoupper(trim($nama)))->first();
        if ($data) return $data['id'];

        $data = $model->where('nama_jurusan', $nama)->first();
        if ($data) return $data['id'];

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

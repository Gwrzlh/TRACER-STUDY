<?php namespace App\Controllers;

use App\Models\Accounts;
use App\Models\DetailaccountAlumni;
use App\Models\DetailaccountAdmins;
use App\Models\DetailaccountKaprodi;
use App\Models\DetailaccountPerusahaan;
use App\Models\DetailaccountAtasan;
use App\Models\DetailaccountJabatanLLnya;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportAccount extends BaseController
{
    public function upload()
    {
        $file   = $this->request->getFile('file');
        $roleId = $this->request->getPost('id_role');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $ext = $file->getClientExtension();

        if ($ext == 'csv') {
            $reader = IOFactory::createReader('Csv');
        } elseif ($ext == 'xls') {
            $reader = IOFactory::createReader('Xls');
        } else {
            $reader = IOFactory::createReader('Xlsx');
        }

        $spreadsheet = $reader->load($file->getTempName());
        $sheetData   = $spreadsheet->getActiveSheet()->toArray();

        $accountModel = new Accounts();

        for ($i = 1; $i < count($sheetData); $i++) {
            $username = $sheetData[$i][0] ?? '';
            $email    = $sheetData[$i][1] ?? '';
            $password = isset($sheetData[$i][2]) ? password_hash($sheetData[$i][2], PASSWORD_DEFAULT) : '';
            $status   = $sheetData[$i][3] ?? '';

            if (!$email || !$password) continue;

            if ($accountModel->where('email', $email)->first()) continue;

            $accountId = $accountModel->insert([
                'username' => $username,
                'email'    => $email,
                'password' => $password,
                'status'   => $status,
                'id_role'  => $roleId,
            ], true);

            // Insert ke detail sesuai role
            switch ($roleId) {
                case 1: // Alumni
                    (new DetailaccountAlumni())->insert([
                        'nama_lengkap'    => $sheetData[$i][4] ?? $username,
                        'nim'             => $sheetData[$i][5] ?? null,
                        'id_jurusan'      => $sheetData[$i][6] ?? null,
                        'id_prodi'        => $sheetData[$i][7] ?? null,
                        'angkatan'        => $sheetData[$i][8] ?? null,
                        'tahun_kelulusan' => $sheetData[$i][9] ?? null,
                        'ipk'             => $sheetData[$i][10] ?? null,
                        'alamat'          => $sheetData[$i][11] ?? null,
                        'alamat2'         => $sheetData[$i][12] ?? null,
                        'id_cities'       => $sheetData[$i][13] ?? null,
                        'id_provinsi'     => $sheetData[$i][14] ?? null,
                        'kodepos'         => $sheetData[$i][15] ?? null,
                        'jenisKelamin'    => $sheetData[$i][16] ?? null,
                        'notlp'           => $sheetData[$i][17] ?? null,
                        'id_account'      => $accountId,
                    ]);
                    break;

                case 2: // Admin
                    (new DetailaccountAdmins())->insert([
                        'nama_lengkap' => $sheetData[$i][4] ?? $username,
                        'id_account'   => $accountId,
                    ]);
                    break;

                case 6: // Kaprodi
                    (new DetailaccountKaprodi())->insert([
                        'nama_lengkap' => $sheetData[$i][4] ?? $username,
                        'id_prodi'     => $sheetData[$i][5] ?? null,
                        'id_jurusan'   => $sheetData[$i][6] ?? null,
                        'notlp'        => $sheetData[$i][7] ?? null,
                        'id_account'   => $accountId,
                    ]);
                    break;

                case 7: // Perusahaan
                    (new DetailaccountPerusahaan())->insert([
                        'nama_perusahaan' => $sheetData[$i][4] ?? $username,
                        'alamat1'         => $sheetData[$i][5] ?? null,
                        'alamat2'         => $sheetData[$i][6] ?? null,
                        'id_provinsi'     => $sheetData[$i][7] ?? null,
                        'id_kota'         => $sheetData[$i][8] ?? null,
                        'noTlp'           => $sheetData[$i][9] ?? null,
                        'kodepos'         => $sheetData[$i][10] ?? null,
                        'id_account'      => $accountId,
                    ]);
                    break;

                case 8: // Atasan
                    (new DetailaccountAtasan())->insert([
                        'nama_lengkap' => $sheetData[$i][4] ?? $username,
                        'id_jabatan'   => $sheetData[$i][5] ?? null,
                        'notlp'        => $sheetData[$i][6] ?? null,
                        'id_account'   => $accountId,
                    ]);
                    break;

                case 9: // Jabatan lainnya
                    (new DetailaccountJabatanLLnya())->insert([
                        'nama_lengkap' => $sheetData[$i][4] ?? $username,
                        'id_prodi'     => $sheetData[$i][5] ?? null,
                        'id_jurusan'   => $sheetData[$i][6] ?? null,
                        'notlp'        => $sheetData[$i][7] ?? null,
                        'id_jabatan'   => $sheetData[$i][8] ?? null,
                        'id_account'   => $accountId,
                    ]);
                    break;

                default:
                    break;
            }
        }

        return redirect()->to(base_url('admin/pengguna'))->with('success', 'Import akun berhasil!');
    }
}

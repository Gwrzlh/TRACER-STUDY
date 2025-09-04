<?php
namespace App\Controllers;

use App\Models\Accounts;
use App\Models\DetailaccountAdmins;
use App\Models\DetailaccountAlumni;
use App\Models\DetailaccountKaprodi;
use App\Models\DetailaccountAtasan;
use App\Models\DetailaccountPerusahaan;
use App\Models\DetailaccountJabatanLLnya;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportAccount extends BaseController
{
    public function form()
    {
        return view('adminpage/pengguna/import');
    }

    public function process()
{
    $file = $this->request->getFile('file');
    $selectedRole = $this->request->getPost('role');

    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('error', 'File tidak valid!');
    }

    $ext = $file->getClientExtension();
    $reader = IOFactory::createReader($ext === 'csv' ? 'Csv' : ($ext === 'xls' ? 'Xls' : 'Xlsx'));
    $spreadsheet = $reader->load($file->getTempName());
    $rows = $spreadsheet->getActiveSheet()->toArray();

    $accountModel = new Accounts();
    $countSuccess = 0;
    $errorLogs = [];

    foreach ($rows as $i => $row) {
        if ($i == 0) continue; // skip header

        $username = $row[0] ?? null;
        $email    = $row[1] ?? null;
        $password = isset($row[2]) ? password_hash($row[2], PASSWORD_DEFAULT) : null;
        $status   = $row[3] ?? 'Aktif';
        $role     = $selectedRole ?: strtolower(trim($row[4] ?? ''));

        // --- Validasi umum ---
        if (!$email) { $errorLogs[] = "Baris $i: Email wajib diisi"; continue; }
        if (!$password) { $errorLogs[] = "Baris $i: Password wajib diisi"; continue; }
        if ($accountModel->where('email', $email)->first()) {
            $errorLogs[] = "Baris $i: Email $email sudah terdaftar";
            continue;
        }

        // --- Validasi khusus per role ---
        switch ($role) {
            case 'alumni':
                if (empty($row[6])) { $errorLogs[] = "Baris $i: NIM wajib diisi"; continue 2; }
                if (empty($row[7])) { $errorLogs[] = "Baris $i: Jurusan wajib diisi"; continue 2; }
                if (empty($row[8])) { $errorLogs[] = "Baris $i: Prodi wajib diisi"; continue 2; }
                if (empty($row[9])) { $errorLogs[] = "Baris $i: Angkatan wajib diisi"; continue 2; }
                if (empty($row[10])) { $errorLogs[] = "Baris $i: Tahun Kelulusan wajib diisi"; continue 2; }
                if (empty($row[11])) { $errorLogs[] = "Baris $i: IPK wajib diisi"; continue 2; }
                if (empty($row[14])) { $errorLogs[] = "Baris $i: No Telepon wajib diisi"; continue 2; }
                break;

            case 'admin':
                if (empty($row[5])) { $errorLogs[] = "Baris $i: Nama Lengkap wajib diisi"; continue 2; }
                break;

            case 'perusahaan':
                if (empty($row[15])) { $errorLogs[] = "Baris $i: Nama Perusahaan wajib diisi"; continue 2; }
                if (empty($row[12])) { $errorLogs[] = "Baris $i: Alamat wajib diisi"; continue 2; }
                if (empty($row[14])) { $errorLogs[] = "Baris $i: No Telepon wajib diisi"; continue 2; }
                break;

            case 'kaprodi':
                if (empty($row[5])) { $errorLogs[] = "Baris $i: Nama Lengkap wajib diisi"; continue 2; }
                if (empty($row[7]) || empty($row[8])) { $errorLogs[] = "Baris $i: Jurusan & Prodi wajib diisi"; continue 2; }
                if (empty($row[14])) { $errorLogs[] = "Baris $i: No Telepon wajib diisi"; continue 2; }
                break;

            case 'atasan':
                if (empty($row[5])) { $errorLogs[] = "Baris $i: Nama Lengkap wajib diisi"; continue 2; }
                if (empty($row[20])) { $errorLogs[] = "Baris $i: Jabatan wajib diisi"; continue 2; }
                if (empty($row[14])) { $errorLogs[] = "Baris $i: No Telepon wajib diisi"; continue 2; }
                break;

            case 'jabatan lainnya':
                if (empty($row[5])) { $errorLogs[] = "Baris $i: Nama Lengkap wajib diisi"; continue 2; }
                if (empty($row[7]) || empty($row[8])) { $errorLogs[] = "Baris $i: Jurusan & Prodi wajib diisi"; continue 2; }
                if (empty($row[20])) { $errorLogs[] = "Baris $i: Jabatan wajib diisi"; continue 2; }
                if (empty($row[14])) { $errorLogs[] = "Baris $i: No Telepon wajib diisi"; continue 2; }
                break;

            default:
                $errorLogs[] = "Baris $i: Role '$role' tidak dikenali";
                continue 2;
        }

        // --- Insert akun utama ---
        $id_account = $accountModel->insert([
            'username' => $username,
            'email'    => $email,
            'password' => $password,
            'status'   => $status,
            'id_role'  => $this->mapRole($role),
        ], true);

        // --- Insert detail sesuai role ---
        switch ($role) {
            case 'alumni':
                (new DetailaccountAlumni())->insert([
                    'nama_lengkap'    => $row[5] ?? $username,
                    'nim'             => $row[6],
                    'id_jurusan'      => $row[7],
                    'id_prodi'        => $row[8],
                    'angkatan'        => $row[9],
                    'tahun_kelulusan' => $row[10],
                    'ipk'             => $row[11],
                    'alamat'          => $row[12] ?? null,
                    'jenisKelamin'    => $row[13] ?? null,
                    'notlp'           => $row[14],
                    'id_account'      => $id_account,
                ]);
                break;

            case 'admin':
                (new DetailaccountAdmins())->insert([
                    'nama_lengkap' => $row[5],
                    'id_account'   => $id_account,
                ]);
                break;

            case 'perusahaan':
                (new DetailaccountPerusahaan())->insert([
                    'nama_perusahaan' => $row[15],
                    'alamat1'         => $row[12],
                    'alamat2'         => $row[16] ?? null,
                    'id_provinsi'     => $row[17] ?? null,
                    'id_kota'         => $row[18] ?? null,
                    'kodepos'         => $row[19] ?? null,
                    'noTlp'           => $row[14],
                    'id_account'      => $id_account,
                ]);
                break;

            case 'kaprodi':
                (new DetailaccountKaprodi())->insert([
                    'nama_lengkap' => $row[5],
                    'id_prodi'     => $row[8],
                    'id_jurusan'   => $row[7],
                    'notlp'        => $row[14],
                    'id_account'   => $id_account,
                ]);
                break;

            case 'atasan':
                (new DetailaccountAtasan())->insert([
                    'nama_lengkap' => $row[5],
                    'id_jabatan'   => $row[20],
                    'notlp'        => $row[14],
                    'id_account'   => $id_account,
                ]);
                break;

            case 'jabatan lainnya':
                (new DetailaccountJabatanLLnya())->insert([
                    'nama_lengkap' => $row[5],
                    'id_prodi'     => $row[8],
                    'id_jurusan'   => $row[7],
                    'id_jabatan'   => $row[20],
                    'notlp'        => $row[14],
                    'id_account'   => $id_account,
                ]);
                break;
        }

        $countSuccess++;
    }

    return redirect()->to('/admin/pengguna')
        ->with('success', "Import selesai. Sukses: $countSuccess, Skip: " . count($errorLogs))
        ->with('errorLogs', $errorLogs);
}


    private function mapRole($role)
    {
        $map = [
            'alumni' => 1,
            'admin' => 2,
            'kaprodi' => 6,
            'perusahaan' => 7,
            'atasan' => 8,
            'jabatan lainnya' => 9,
        ];
        return $map[$role] ?? null;
    }

    private function validateRequiredFields($role, $row, $line)
    {
        switch ($role) {
            case 'alumni':
                if (empty($row[6])) return "Baris $line dilewati: NIM wajib diisi";
                if (empty($row[8])) return "Baris $line dilewati: ID Prodi wajib diisi";
                if (empty($row[9])) return "Baris $line dilewati: Angkatan wajib diisi";
                break;

            case 'perusahaan':
                if (empty($row[15])) return "Baris $line dilewati: Nama Perusahaan wajib diisi";
                if (empty($row[17])) return "Baris $line dilewati: ID Provinsi wajib diisi";
                if (empty($row[18])) return "Baris $line dilewati: ID Kota wajib diisi";
                break;

            case 'admin':
                if (empty($row[5])) return "Baris $line dilewati: Nama lengkap wajib diisi";
                break;

            case 'kaprodi':
                if (empty($row[5])) return "Baris $line dilewati: Nama lengkap wajib diisi";
                if (empty($row[7]) || empty($row[8])) return "Baris $line dilewati: Jurusan & Prodi wajib diisi";
                break;

            case 'atasan':
                if (empty($row[5])) return "Baris $line dilewati: Nama lengkap wajib diisi";
                if (empty($row[20])) return "Baris $line dilewati: ID Jabatan wajib diisi";
                break;

            case 'jabatan lainnya':
                if (empty($row[5])) return "Baris $line dilewati: Nama lengkap wajib diisi";
                if (empty($row[7]) || empty($row[8])) return "Baris $line dilewati: Jurusan & Prodi wajib diisi";
                if (empty($row[20])) return "Baris $line dilewati: ID Jabatan wajib diisi";
                break;
        }

        return null;
    }
}

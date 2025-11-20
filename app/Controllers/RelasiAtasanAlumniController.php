<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RelasiAtasanAlumniModel;
use App\Models\DetailaccountAlumni;
use App\Models\DetailaccountAtasan;
use App\Models\Prodi;   

class RelasiAtasanAlumniController extends BaseController
{
    public function index()
    {
        $atasanModel = new DetailaccountAtasan();
        $prodiModel = new Prodi();
        $relasiModel = new RelasiAtasanAlumniModel();
        
        // Query dengan JOIN - PENTING: ambil atasan_alumni.id sebagai id_relasi
        $relasi = $relasiModel
            ->select('
                atasan_alumni.id as id_relasi,
                atasan_alumni.id_atasan,
                detailaccount_atasan.nama_lengkap as nama_atasan,
                detailaccount_alumni.nama_lengkap as nama_alumni,
                detailaccount_alumni.nim as nim_alumni
            ')
            ->join('detailaccount_atasan', 'atasan_alumni.id_atasan = detailaccount_atasan.id')
            ->join('detailaccount_alumni', 'atasan_alumni.id_alumni = detailaccount_alumni.id')
            ->orderBy('detailaccount_atasan.nama_lengkap', 'ASC')
            ->findAll();
        
        // Grouping by atasan

        $atasanList = $atasanModel->findAll();
        $prodiList = $prodiModel->findAll();
        $grouped = [];
        foreach ($relasi as $row) {
            $id_atasan = $row['id_atasan'];
            
            if (!isset($grouped[$id_atasan])) {
                $grouped[$id_atasan] = [
                    'nama_atasan' => $row['nama_atasan'],
                    'alumni' => []
                ];
            }
            
            // Simpan id_relasi untuk setiap alumni
            $grouped[$id_atasan]['alumni'][] = [
                'id_relasi' => $row['id_relasi'],
                'nama_alumni' => $row['nama_alumni'],
                'nim_alumni' => $row['nim_alumni'] ?? '-'
            ];
        }
        
        // Data lainnya (sesuaikan dengan controller Anda)
        $data = [
            'grouped' => $grouped,
            'atasan' => $atasanList,
            'prodiList' => $prodiList
        ];
        
        return view('adminpage/atasan_alumni/index', $data);
    }

    // AJAX: return list alumni JSON sesuai filter
    public function fetchAlumni()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $angkatan = $this->request->getPost('tahun_kelulusan');
        $idProdi  = $this->request->getPost('id_prodi');
        $q        = $this->request->getPost('q');

        $alumniModel = new DetailaccountAlumni();

        $builder = $alumniModel->builder();
        $builder->select('id, nama_lengkap, nim')
                ->orderBy('nama_lengkap', 'ASC');

        if (!empty($angkatan)) {
            $builder->where('', $angkatan);
        }
        if (!empty($idProdi)) {
            $builder->where('id_prodi', $idProdi);
        }
        if (!empty($q)) {
            $builder->groupStart()
                    ->like('nama_lengkap', $q)
                    ->orLike('nim', $q)
                    ->groupEnd();
        }

        $results = $builder->get()->getResultArray();

        // Format simple untuk dropdown: {id, text}
        $out = [];
        foreach ($results as $r) {
            $out[] = [
                'id' => $r['id'],
                'text' => $r['nama_lengkap'] . ' (' . ($r['nim'] ?? '-') . ')'
            ];
        }

        return $this->response->setJSON($out);
    }

    // store: menerima id_atasan dan id_alumni[] atau single id
    public function store()
    {
        $relasiModel = new RelasiAtasanAlumniModel();

        $idAtasan = $this->request->getPost('id_atasan');
        $alumniIds = $this->request->getPost('id_alumni'); // array expected

        if (empty($idAtasan) || empty($alumniIds)) {
            return redirect()->back()->with('error', 'Pilih atasan dan minimal 1 alumni.');
        }

        if (!is_array($alumniIds)) {
            $alumniIds = [$alumniIds];
        }

        $insertData = [];
        foreach ($alumniIds as $id) {
            // optional: skip if already ada relasi
            $exists = $relasiModel->where('id_atasan', $idAtasan)
                                  ->where('id_alumni', $id)
                                  ->countAllResults();
            if ($exists == 0) {
                $insertData[] = [
                    'id_atasan' => $idAtasan,
                    'id_alumni' => $id
                ];
            }
        }

        if (!empty($insertData)) {
            $relasiModel->insertBatch($insertData);
        }

        return redirect()->back()->with('success', 'Relasi berhasil ditambahkan.');
    }

    public function delete($id = null)
    {
        $id = $id ?? $this->request->getPost('id');

        if (empty($id)) {
            return redirect()->back()->with('error', 'ID relasi tidak ditemukan.');
        }

        $relasiModel = new RelasiAtasanAlumniModel();
        
        // Cek apakah relasi ada
        $relasi = $relasiModel->find($id);
        if (!$relasi) {
            return redirect()->back()->with('error', 'Relasi tidak ditemukan di database.');
        }
        
        // Hapus relasi
        if ($relasiModel->delete($id)) {
            return redirect()->back()->with('success', 'Relasi berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus relasi.');
        }
    }

    // ------ kayaknya ini ga perlu ------
    // public function update($id)
    // {
    //     $this->db->table('atasan_alumni')->where('id', $id)->update([
    //         'id_atasan' => $this->request->getPost('id_atasan'),
    //         'id_alumni' => $this->request->getPost('id_alumni'),
    //     ]);

    //     return redirect()->back()->with('success', 'Relasi berhasil diperbarui.');
    // }


}

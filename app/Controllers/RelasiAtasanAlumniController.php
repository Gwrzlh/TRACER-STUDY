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
        $alumniModel = new DetailaccountAlumni();
        $relasiModel = new RelasiAtasanAlumniModel();
        $PordiModel = new Prodi();


        $db = \Config\Database::connect(); // hubungkan DB sekali

        // pastikan builder pakai alias yang benar
        $builder = $db->table('atasan_alumni AS aa');

        $builder->select('aa.id_atasan, at.nama_lengkap AS nama_atasan, al.nama_lengkap AS nama_alumni');
        $builder->join('detailaccount_atasan AS at', 'aa.id_atasan = at.id', 'left');
        $builder->join('detailaccount_alumni AS al', 'aa.id_alumni = al.id', 'left');
        $builder->orderBy('at.nama_lengkap', 'ASC');

        $relasi = $builder->get()->getResultArray();

        // Grouping alumni berdasarkan atasan
        $grouped = [];
        foreach ($relasi as $row) {
            $grouped[$row['id_atasan']]['nama_atasan'] = $row['nama_atasan'];
            $grouped[$row['id_atasan']]['alumni'][] = $row['nama_alumni'];
        }
        $prodiList = $PordiModel->findAll();
        return view('adminpage/atasan_alumni/index', [
            'atasan' => $atasanModel->findAll(),
            // kita tidak perlu menampilkan semua alumni awalnya; tapi untuk fallback:
            'alumni' => [],
            'grouped' => $grouped,
            'prodiList' => $prodiList
        ]);
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

    public function delete($id)
    {
        // Allow id to be passed either as a route parameter or via POST (e.g., AJAX/form)
        $id = $id ?? $this->request->getPost('id');

        if (empty($id)) {
            return redirect()->back()->with('error', 'ID relasi tidak ditemukan.');
        }

        $relasiModel = new RelasiAtasanAlumniModel();
        $relasiModel->delete($id);
        return redirect()->back()->with('success', 'Relasi berhasil dihapus.');
    }
    public function update($id)
    {
        $this->db->table('atasan_alumni')->where('id', $id)->update([
            'id_atasan' => $this->request->getPost('id_atasan'),
            'id_alumni' => $this->request->getPost('id_alumni'),
        ]);

        return redirect()->back()->with('success', 'Relasi berhasil diperbarui.');
    }


}

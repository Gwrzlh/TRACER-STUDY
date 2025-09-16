<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumniModel extends Model
{
    protected $table            = 'detailaccount_alumni';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Tambahkan semua kolom yang ada di tabel (sesuaikan bila berbeda)
    protected $allowedFields = [
        'id_account',
        'nama_lengkap',
        'foto',
        'nim',
        'id_jurusan',
        'id_prodi',
        'angkatan',
        'tahun_kelulusan',
        'ipk',
        'alamat',
        'alamat2',
        'kodepos',
        'jenisKelamin',
        'notlp',
        'id_provinsi',
        'id_cities'
    ];

    // Timestamp handling (sesuaikan kalau tabel punya created/updated)
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Ambil daftar angkatan unik (untuk dropdown filter)
     * @return array
     */
    public function getDistinctAngkatan(): array
    {
        return $this->select('angkatan')
            ->where('angkatan IS NOT NULL')
            ->groupBy('angkatan')
            ->orderBy('angkatan', 'DESC')
            ->findAll();
    }

    /**
     * Ambil daftar tahun kelulusan unik (untuk dropdown)
     * @return array
     */
    public function getDistinctTahunKelulusan(): array
    {
        return $this->select('tahun_kelulusan')
            ->where('tahun_kelulusan IS NOT NULL')
            ->groupBy('tahun_kelulusan')
            ->orderBy('tahun_kelulusan', 'DESC')
            ->findAll();
    }

    /**
     * Ambil data alumni by account id
     */
    public function getByAccountId(int $accountId): ?array
    {
        return $this->where('id_account', $accountId)->first();
    }

    /**
     * Ambil data alumni lengkap (join ke account, jurusan, prodi).
     * Bisa diberi filter: ['tahun' => , 'anggkatan' => , 'jurusan' => , 'prodi' => , 'nim' => , 'nama' => ]
     */
    public function getWithRelations(array $filters = []): array
    {
        $builder = $this->db->table('detailaccount_alumni da');
        $builder->select('
            da.*,
            a.username,
            j.nama_jurusan,
            p.nama_prodi
        ');
        $builder->join('account a', 'a.id = da.id_account', 'left');
        $builder->join('jurusan j', 'j.id = da.id_jurusan', 'left');
        $builder->join('prodi p', 'p.id = da.id_prodi', 'left');

        if (!empty($filters['tahun'])) {
            $builder->where('da.tahun_kelulusan', $filters['tahun']);
        }
        if (!empty($filters['angkatan'])) {
            $builder->where('da.angkatan', $filters['angkatan']);
        }
        if (!empty($filters['jurusan'])) {
            $builder->where('da.id_jurusan', $filters['jurusan']);
        }
        if (!empty($filters['prodi'])) {
            $builder->where('da.id_prodi', $filters['prodi']);
        }
        if (!empty($filters['nim'])) {
            $builder->like('da.nim', $filters['nim']);
        }
        if (!empty($filters['nama'])) {
            $builder->like('da.nama_lengkap', $filters['nama']);
        }

        $builder->orderBy('da.nama_lengkap', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Simple search (contoh utility)
     */
    public function search(string $term, int $limit = 50): array
    {
        return $this->like('nama_lengkap', $term)
            ->orLike('nim', $term)
            ->orderBy('nama_lengkap', 'ASC')
            ->findAll($limit);
    }
    public function getWithResponses(array $filters = []): array
    {
        $builder = $this->db->table('detailaccount_alumni da');
        $builder->select("
        da.id as alumni_id,
        da.nama_lengkap,
        da.nim,
        da.angkatan,
        da.tahun_kelulusan,
        j.nama_jurusan,
        p.nama_prodi,
        r.id as response_id,
        r.status,
        r.submitted_at,
        q.title as judul_kuesioner
    ");
        $builder->join('jurusan j', 'j.id = da.id_jurusan', 'left');
        $builder->join('prodi p', 'p.id = da.id_prodi', 'left');
        $builder->join('account a', 'a.id = da.id_account', 'left');
        $builder->join('responses r', 'r.account_id = a.id', 'left');
        $builder->join('questionnaires q', 'q.id = r.questionnaire_id', 'left');

        // ======================
        // FILTERS
        // ======================

        // NIM
        if (!empty($filters['nim'])) {
            $nim = trim($filters['nim']);
            if (is_numeric($nim)) {
                $builder->where('da.nim', $nim);
            } else {
                $builder->like('da.nim', $nim);
            }
        }

        // Nama
        if (!empty($filters['nama'])) {
            $builder->like('da.nama_lengkap', trim($filters['nama']));
        }

        // Jurusan
        if (!empty($filters['jurusan'])) {
            $builder->where('da.id_jurusan', $filters['jurusan']);
        }

        // Prodi
        if (!empty($filters['prodi'])) {
            $builder->where('da.id_prodi', $filters['prodi']);
        }

        // Angkatan
        if (!empty($filters['angkatan'])) {
            $builder->where('da.angkatan', $filters['angkatan']);
        }

        // Tahun Lulusan
        if (!empty($filters['tahun'])) {
            $builder->where('da.tahun_kelulusan', $filters['tahun']);
        }

        // Status
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'Belum') {
                $builder->where('r.id IS NULL');
            } else {
                $builder->where('r.status', $filters['status']);
            }
        }

        // Kuesioner
        if (!empty($filters['questionnaire'])) {
            $builder->where('r.questionnaire_id', $filters['questionnaire']);
        }

        // ======================
        // SORTING DINAMIS
        // ======================
        $sortBy = $filters['sort_by'] ?? 'nama_lengkap'; // default urut berdasarkan nama
        $sortOrder = strtoupper($filters['sort_order'] ?? 'ASC'); // default ASC

        // Validasi kolom supaya aman dari SQL Injection
        $allowedSortColumns = ['nim', 'nama_lengkap', 'angkatan', 'tahun_kelulusan', 'status'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'nama_lengkap';
        }

        $sortOrder = in_array($sortOrder, ['ASC', 'DESC']) ? $sortOrder : 'ASC';
        $builder->orderBy("da.$sortBy", $sortOrder);

        return $builder->get()->getResultArray();
    }
    public function getWithResponsesBuilder(array $filters = [])
    {
        $builder = $this->db->table('detailaccount_alumni da');
        $builder->select("
        da.id as alumni_id,
        da.nama_lengkap,
        da.nim,
        da.angkatan,
        da.tahun_kelulusan,
        j.nama_jurusan,
        p.nama_prodi,
        r.id as response_id,
        r.status,
        r.submitted_at,
        q.title as judul_kuesioner
    ");
        $builder->join('jurusan j', 'j.id = da.id_jurusan', 'left');
        $builder->join('prodi p', 'p.id = da.id_prodi', 'left');
        $builder->join('account a', 'a.id = da.id_account', 'left');
        $builder->join('responses r', 'r.account_id = a.id', 'left');
        $builder->join('questionnaires q', 'q.id = r.questionnaire_id', 'left');

        // Filters
        if (!empty($filters['nim'])) $builder->like('da.nim', trim($filters['nim']));
        if (!empty($filters['nama'])) $builder->like('da.nama_lengkap', trim($filters['nama']));
        if (!empty($filters['jurusan'])) $builder->where('da.id_jurusan', $filters['jurusan']);
        if (!empty($filters['prodi'])) $builder->where('da.id_prodi', $filters['prodi']);
        if (!empty($filters['angkatan'])) $builder->where('da.angkatan', $filters['angkatan']);
        if (!empty($filters['tahun'])) $builder->where('da.tahun_kelulusan', $filters['tahun']);
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'Belum') $builder->where('r.id IS NULL');
            else $builder->where('r.status', $filters['status']);
        }
        if (!empty($filters['questionnaire'])) $builder->where('r.questionnaire_id', $filters['questionnaire']);

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'nama_lengkap';
        $sortOrder = strtoupper($filters['sort_order'] ?? 'ASC');
        $allowedSortColumns = ['nim', 'nama_lengkap', 'angkatan', 'tahun_kelulusan', 'status'];
        if (!in_array($sortBy, $allowedSortColumns)) $sortBy = 'nama_lengkap';
        if (!in_array($sortOrder, ['ASC', 'DESC'])) $sortOrder = 'ASC';
        $builder->orderBy("da.$sortBy", $sortOrder);

        return $builder;
    }
    public function getWithResponsesPaginated(array $filters = [], int $perPage = 10)
    {
        $builder = $this->getWithResponsesBuilder($filters);
        return $this->pager = $this->pager ?? \Config\Services::pager(); // pastikan pager tersedia
        return $builder->get()->getResultArray(); // pagination manual nanti
    }
}

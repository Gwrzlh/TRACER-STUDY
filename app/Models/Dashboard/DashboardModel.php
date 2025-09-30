<?php
namespace App\Models\Dashboard;
use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $table = 'account';
    protected $primaryKey = 'id';

    // Hitung jumlah akun berdasarkan role
    public function getTotalByRole($roleId)
    {
        return $this->where('id_role', $roleId)->countAllResults();
    }

    // Hitung total survei dari tabel answers
    public function getTotalSurvei()
    {
        return $this->db->table('answers')->countAllResults();
    }

    // Hitung response rate dari tabel answers
    public function getResponseRate()
    {
        $completed = $this->db->table('answers')
                              ->where('status', 'completed')
                              ->countAllResults();
        $total = $this->db->table('answers')->countAllResults();
        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    // Data untuk chart user role
    public function getUserRoleData()
    {
        $query = $this->db->table('account')
            ->select('id_role, COUNT(*) as total')
            ->groupBy('id_role')
            ->get()
            ->getResultArray();

        $roleNames = [
            1 => 'Alumni',
            2 => 'Admin',
            6 => 'Kaprodi',
            7 => 'Perusahaan',
            8 => 'Atasan',
            9 => 'Jabatan Lainnya',
        ];

        $labels = [];
        $data = [];
        foreach ($query as $row) {
            $labels[] = $roleNames[$row['id_role']] ?? 'Role ' . $row['id_role'];
            $data[]   = $row['total'];
        }

        return ['labels' => $labels, 'data' => $data];
    }
}

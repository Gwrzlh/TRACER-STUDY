<?php


namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;

class ExportAccount extends BaseController
{
    public function index()
    {
        $accountModel = new AccountModel();
        $accounts = $accountModel
            ->select('account.username, account.email, account.status, role.nama as role')
            ->join('role', 'role.id = account.id_role', 'left')
            ->findAll();

        if (empty($accounts)) {
            return redirect()->back()->with('error', 'Tidak ada data untuk diexport.');
        }

        // Siapkan header CSV
        $filename = 'data_pengguna_' . date('Y-m-d_His') . '.csv';
        $headers = ['Username', 'Email', 'Status', 'Role'];

        // Buka output buffer
        $output = fopen('php://output', 'w');

        // Header CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Tulis baris pertama (header)
        fputcsv($output, $headers);

        // Isi data
        foreach ($accounts as $acc) {
            fputcsv($output, [
                $acc['username'],
                $acc['email'],
                ($acc['status'] == 1 || strtolower($acc['status']) == 'active') ? 'Active' : 'Inactive',
                $acc['role'] ?? 'No Role'
            ]);
        }

        fclose($output);
        exit;
    }
}

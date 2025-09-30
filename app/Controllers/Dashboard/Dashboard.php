<?php
namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\Dashboard\DashboardModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $model = new DashboardModel();

        $data = [
            'totalSurvei'     => $model->getTotalSurvei(),
            'totalAlumni'     => $model->getTotalByRole(1),
            'totalAdmin'      => $model->getTotalByRole(2),
            'totalKaprodi'    => $model->getTotalByRole(6),
            'totalPerusahaan' => $model->getTotalByRole(7),
            'totalAtasan'     => $model->getTotalByRole(8),
            'totalJabatan'    => $model->getTotalByRole(9),
            'responseRate'    => $model->getResponseRate(),
            'userRoleData'    => $model->getUserRoleData(),
        ];

        return view('dashboard/admin', $data);
    }
}

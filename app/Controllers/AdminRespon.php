<?php

namespace App\Controllers;

use App\Models\ResponseModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminRespon extends BaseController
{
    protected $responseModel;

    public function __construct()
    {
        $this->responseModel = new ResponseModel();
    }

    public function index()
    {
        $year            = $this->request->getGet('year');
        $status          = $this->request->getGet('status');
        $questionnaireId = $this->request->getGet('questionnaire_id');

        $data['selectedYear']         = $year ?? '';
        $data['selectedStatus']       = $status ?? '';
        $data['selectedQuestionnaire'] = $questionnaireId ?? '';

        $data['allYears']        = $this->responseModel->getAllYears();
        $data['allQuestionnaires'] = $this->responseModel->getAllQuestionnaires();
        $data['responses']       = $this->responseModel->getFilteredResponses($year, $status, $questionnaireId);

        return view('adminpage/respon/index', $data);
    }

    public function exportExcel()
    {
        $year            = $this->request->getGet('year');
        $status          = $this->request->getGet('status');
        $questionnaireId = $this->request->getGet('questionnaire_id');

        $responses = $this->responseModel->getFilteredResponses($year, $status, $questionnaireId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Nama Alumni');
        $sheet->setCellValue('B1', 'Tahun Kelulusan');
        $sheet->setCellValue('C1', 'Judul Kuesioner');
        $sheet->setCellValue('D1', 'Status');
        $sheet->setCellValue('E1', 'Tanggal Submit');

        // Isi data
        $row = 2;
        foreach ($responses as $res) {
            $nama    = $res['nama_lengkap'] ?? ($res['username'] ?? '-');
            $tahun   = $res['tahun_kelulusan'] ?? '-';
            $judul   = $res['judul_kuesioner'] ?? '-';
            $statusRes = $res['status'] ?? '-';
            $tanggal = $res['submitted_at'] ?? '-';

            $sheet->setCellValue('A' . $row, $nama);
            $sheet->setCellValue('B' . $row, $tahun);
            $sheet->setCellValue('C' . $row, $judul);
            $sheet->setCellValue('D' . $row, $statusRes);
            $sheet->setCellValue('E' . $row, $tanggal);

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Respon_' . ($year ?? 'all') . '.xlsx';

        // Output download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}

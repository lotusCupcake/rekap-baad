<?php

namespace App\Controllers;

use App\Models\CamaCbtModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class CamaCbt extends BaseController
{
    protected $camaCbtModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->camaCbtModel = new CamaCbtModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Data Cama CBT",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Cama CBT'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'tahunAngkatan' => null,
            'tanggalDaftar' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/camaCbt', $data);
    }

    public function proses()
    {
        if (!$this->validate([
            'tahunAngkatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Angkatan Harus Dipilih!',
                ]
            ],
            'tanggalDaftar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Daftar Ulang Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('camaCbt')->withInput();
        }

        $data = array(
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
            'tanggalDaftar' => trim($this->request->getPost('tanggalDaftar')),
        );

        $lapCamaCbt = $this->camaCbtModel->getLapCamaCbt($data);
        $data = [
            'title' => "Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'tahunAngkatan' => $data['tahunAngkatan'],
            'tanggalDaftar' => $data['tanggalDaftar'],
            'dataResult' => $lapCamaCbt
        ];
        // dd($lapCamaCbt);
        session()->setFlashdata('success', '<strong>' . count($lapCamaCbt) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/camaCbt', $data);
    }

    public function exportCamaCbt()
    {
        $data = array(
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
            'tanggalDaftar' => trim($this->request->getPost('tanggalDaftar')),
        );

        $lapCamaCbt = $this->camaCbtModel->getLapCamaCbt($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'username')
            ->setCellValue('B' . $row, 'password')
            ->setCellValue('C' . $row, 'firstname')
            ->setCellValue('D' . $row, 'lastname')
            ->setCellValue('E' . $row, 'email')
            ->setCellValue('F' . $row, 'course1')
            ->setCellValue('G' . $row, 'role1');
        $row++;
        foreach ($lapCamaCbt as $camaCbt) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $camaCbt->username)
                ->setCellValue('B' . $row, $camaCbt->lastname)
                ->setCellValue('C' . $row, $camaCbt->firstname)
                ->setCellValue('D' . $row, $camaCbt->lastname)
                ->setCellValue('E' . $row, $camaCbt->email)
                ->setCellValue('F' . $row, $camaCbt->course1)
                ->setCellValue('G' . $row, $camaCbt->role1);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);
        $fileName = 'Mahasiswa Baru Elearning Tanggal ' . $this->request->getVar('tanggalDaftar');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

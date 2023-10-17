<?php

namespace App\Controllers;

use App\Models\DosenModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Dosen extends BaseController
{
    protected $dosenModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Penugasan Dosen",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Penugasan Dosen'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->dosenModel->getFakultas(),
            'listTermYear' => $this->dosenModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/dosen', $data);
    }

    public function proses()
    {
        if (!$this->validate([
            'fakultas' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Fakultas Harus Dipilih!',
                ]
            ],
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('dosen')->withInput();
        }

        // dd($_POST);
        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );


        $lapDosen = $this->dosenModel->getLapDosen($data);
        $data = [
            'title' => "Penugasan Dosen",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Penugasan Dosen'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->dosenModel->getFakultas(),
            'listTermYear' => $this->dosenModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'dataResult' => $lapDosen
        ];
        // dd($lapDosen);
        session()->setFlashdata('success', '<strong>' . count($lapDosen) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/dosen', $data);
    }

    public function exportDosen()
    {
        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );

        $lapDosen = $this->dosenModel->getLapDosen($data);
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
        foreach ($lapDosen as $dosen) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $dosen->username)
                ->setCellValue('B' . $row, $dosen->password)
                ->setCellValue('C' . $row, $dosen->firstname)
                ->setCellValue('D' . $row, $dosen->lastname)
                ->setCellValue('E' . $row, $dosen->email)
                ->setCellValue('F' . $row, $dosen->course1)
                ->setCellValue('G' . $row, $dosen->role1);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);
        $fileName = 'Dosen Elearning ' . $this->request->getVar('fakultas') . ' ' . $this->request->getVar('tahunAjar') . ' - ' . date("dmY");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

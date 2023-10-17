<?php

namespace App\Controllers;

use App\Models\MatkulModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Matkul extends BaseController
{
    protected $matkulModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->matkulModel = new MatkulModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Mata Kuliah",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Mata Kuliah'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->matkulModel->getFakultas(),
            'listTermYear' => $this->matkulModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/matkul', $data);
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
            return redirect()->to('matkul')->withInput();
        }

        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );

        $lapMatkul = $this->matkulModel->getLapMatkul($data);
        $data = [
            'title' => "Mata Kuliah",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Mata Kuliah'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->matkulModel->getFakultas(),
            'listTermYear' => $this->matkulModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'dataResult' => $lapMatkul
        ];
        // dd($lapMatkul);
        session()->setFlashdata('success', '<strong>' . count($lapMatkul) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/matkul', $data);
    }

    public function exportMatkul()
    {
        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );

        $lapMatkul = $this->matkulModel->getLapMatkul($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'fullname')
            ->setCellValue('B' . $row, 'shortname')
            ->setCellValue('C' . $row, 'category')
            ->setCellValue('D' . $row, 'startdate')
            ->setCellValue('E' . $row, 'enddate');
        $row++;
        foreach ($lapMatkul as $matkul) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $matkul->fullname)
                ->setCellValue('B' . $row, $matkul->shortname)
                ->setCellValue('C' . $row, $matkul->category)
                ->setCellValue('D' . $row, $matkul->startdate)
                ->setCellValue('E' . $row, $matkul->enddate);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);


        $fileName = 'Mata Kuliah Elearning ' . $this->request->getVar('fakultas') . ' ' . $this->request->getVar('tahunAjar') . ' - ' . date("dmY");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

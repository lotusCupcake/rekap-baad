<?php

namespace App\Controllers;

use App\Models\MhsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Mhs extends BaseController
{
    protected $mhsModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->mhsModel = new MhsModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'tahunAngkatan' => null,
            'tanggalDaftar' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/mhs', $data);
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
            return redirect()->to('mhs')->withInput();
        }

        $data = array(
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
            'tanggalDaftar' => trim($this->request->getPost('tanggalDaftar')),
        );

        $lapMhs = $this->mhsModel->getLapMhs($data);
        $data = [
            'title' => "Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'tahunAngkatan' => $data['tahunAngkatan'],
            'tanggalDaftar' => $data['tanggalDaftar'],
            'dataResult' => $lapMhs
        ];
        // dd($lapMhs);
        session()->setFlashdata('success', '<strong>' . count($lapMhs) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/mhs', $data);
    }

    public function exportMhs()
    {
        $data = array(
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
            'tanggalDaftar' => trim($this->request->getPost('tanggalDaftar')),
        );

        $lapMhs = $this->mhsModel->getLapMhs($data);
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
        foreach ($lapMhs as $mhs) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $mhs->username)
                ->setCellValue('B' . $row, $mhs->lastname)
                ->setCellValue('C' . $row, $mhs->firstname)
                ->setCellValue('D' . $row, $mhs->lastname)
                ->setCellValue('E' . $row, $mhs->email)
                ->setCellValue('F' . $row, $mhs->course1)
                ->setCellValue('G' . $row, $mhs->role1);
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

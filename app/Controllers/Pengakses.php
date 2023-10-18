<?php

namespace App\Controllers;

use App\Models\PengaksesModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Pengakses extends BaseController
{
    protected $pengaksesModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->pengaksesModel = new PengaksesModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Jumlah Pengakses",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Jumlah Pengakses'],
            'validation' => \Config\Services::validation(),
            'dataProdi' => $this->pengaksesModel->getProdi(),
            'dataType' => ['dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa', 'mahasiswaluar' => 'Mahasiswa Luar'],
            'prodi' => null,
            'type' => null,
            'tanggalAwal' => null,
            'tanggalAkhir' => null,
            'dataResult' => []
        ];

        return view('pages/pengakses', $data);
    }

    public function proses()
    {
        if (!$this->validate([
            'prodi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Prodi Harus Dipilih!',
                ]
            ],
            'type' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pengguna Harus Dipilih!',
                ]
            ]
        ])) {
            return redirect()->to('pages/pengakses')->withInput();
        }

        $pass = array(
            'prodi' => trim($this->request->getVar('prodi')),
            'type' => trim($this->request->getVar('type')),
            'tanggalAwal' => trim($this->request->getVar('tanggalAwal')),
            'tanggalAkhir' => trim($this->request->getVar('tanggalAkhir')),
        );

        $lapPengakses = json_decode($this->pengaksesModel->getLapPengakses($pass));
        $data = [
            'menu' => $this->fetchMenu(),
            'title' => "Jumlah Pengakses",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Jumlah Pengakses'],
            'validation' => \Config\Services::validation(),
            'dataProdi' => $this->pengaksesModel->getProdi(),
            'dataType' => ['dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa', 'mahasiswaluar' => 'Mahasiswa Luar'],
            'prodi' => $pass['prodi'],
            'type' => $pass['type'],
            'tanggalAwal' => $pass['tanggalAwal'],
            'tanggalAkhir' => $pass['tanggalAkhir'],
            'dataResult' => ($lapPengakses->status == true) ? $lapPengakses->data : []
        ];
        session()->setFlashdata((($lapPengakses->status == true) ? 'success' : 'failed'), $lapPengakses->message);
        return view('pages/pengakses', $data);
    }

    // public function exportPengakses()
    // {
    //     $data = array(
    //         'tahunAngkatan' => trim($this->request->getVar('tahunAngkatan')),
    //         'tanggalDaftar' => trim($this->request->getVar('tanggalDaftar')),
    //     );

    //     $lapPengakses = $this->pengaksesModel->getLapPengakses($data);
    //     $row = 1;
    //     $this->spreadsheet->setActiveSheetIndex(0)
    //         ->setCellValue('A' . $row, 'username')
    //         ->setCellValue('B' . $row, 'password')
    //         ->setCellValue('C' . $row, 'firstname')
    //         ->setCellValue('D' . $row, 'lastname')
    //         ->setCellValue('E' . $row, 'email')
    //         ->setCellValue('F' . $row, 'course1')
    //         ->setCellValue('G' . $row, 'role1');
    //     $row++;
    //     foreach ($lapPengakses as $pengakses) {
    //         $this->spreadsheet->setActiveSheetIndex(0)
    //             ->setCellValue('A' . $row, $pengakses->username)
    //             ->setCellValue('B' . $row, $pengakses->lastname)
    //             ->setCellValue('C' . $row, $pengakses->firstname)
    //             ->setCellValue('D' . $row, $pengakses->lastname)
    //             ->setCellValue('E' . $row, $pengakses->email)
    //             ->setCellValue('F' . $row, $pengakses->course1)
    //             ->setCellValue('G' . $row, $pengakses->role1);
    //         $row++;
    //     }
    //     $writer = new Xls($this->spreadsheet);
    //     $fileName = 'Mahasiswa Baru Elearning Tanggal ' . $this->request->getVar('tanggalDaftar');

    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
    //     header('Cache-Control: max-age=0');

    //     // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
    //     $writer->save('php://output');
    // }
}

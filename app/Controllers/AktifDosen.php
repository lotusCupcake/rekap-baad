<?php

namespace App\Controllers;

use App\Models\AktifDosenModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class AktifDosen extends BaseController
{
    protected $aktifDosenModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->aktifDosenModel = new AktifDosenModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Keaktifan Dosen",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Keaktifan Dosen'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->aktifDosenModel->getFakultas(),
            'listTermYear' => $this->aktifDosenModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/aktifDosen', $data);
    }

    public function proses()
    {
        // dd($_POST);
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
            return redirect()->to('aktifDosen')->withInput();
        }

        // dd($_POST);
        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );


        $lapAktifDosen = $this->aktifDosenModel->getLapAktifDosen($data);
        // dd($lapAktifDosen);
        $data = [
            'title' => "Keaktifan Dosen",
            'appName' => "UMSU",
            'breadcrumb' => ['Elearning', 'Keaktifan Dosen'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->aktifDosenModel->getFakultas(),
            'listTermYear' => $this->aktifDosenModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'dataResult' => $lapAktifDosen
        ];
        // dd($lapAktifDosen);
        session()->setFlashdata('success', 'Data Keaktifan Dosen Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/aktifDosen', $data);
    }

    public function exportAktifDosen()
    {
        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );

        $lapAktifDosen = $this->aktifDosenModel->getLapAktifDosen($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Keaktifan Dosen Elearning ' . $this->request->getVar('fakultas'))->mergeCells("A" . $row . ":M" . $row)->getStyle("A" . $row . ":M" . $row)->getFont()->setBold(true);
        $row++;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Nama Dosen')
            ->setCellValue('C' . $row, 'Last Access')
            ->setCellValue('D' . $row, 'Mata Kuliah')
            ->setCellValue('E' . $row, 'Absensi')
            ->setCellValue('F' . $row, 'Materi')
            ->setCellValue('G' . $row, 'Tugas')
            ->setCellValue('H' . $row, 'Forum')
            ->setCellValue('I' . $row, 'Quiz')
            ->setCellValue('J' . $row, 'GMeet')
            ->setCellValue('K' . $row, 'URL')
            ->setCellValue('L' . $row, 'Book')
            ->setCellValue('M' . $row, 'Page')->getStyle("A2:M2")->getFont()->setBold(true);;
        $row++;
        $no = 1;
        foreach ($lapAktifDosen as $aktifDosen) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $aktifDosen->Nama_Dosen)
                ->setCellValue('C' . $row, $aktifDosen->Terakhir_Akses)
                ->setCellValue('D' . $row, $aktifDosen->Matakuliah)
                ->setCellValue('E' . $row, $aktifDosen->Jumlah_Absensi)
                ->setCellValue('F' . $row, $aktifDosen->Jumlah_Materi)
                ->setCellValue('G' . $row, $aktifDosen->Jumlah_Tugas)
                ->setCellValue('H' . $row, $aktifDosen->Jumlah_Forum)
                ->setCellValue('I' . $row, $aktifDosen->Jumlah_Quiz)
                ->setCellValue('J' . $row, $aktifDosen->Jumlah_Gmeet)
                ->setCellValue('K' . $row, $aktifDosen->Jumlah_URL)
                ->setCellValue('L' . $row, $aktifDosen->Jumlah_Book)
                ->setCellValue('M' . $row, $aktifDosen->Jumlah_Page);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);
        $fileName = 'Keaktifan Dosen Elearning ' . $this->request->getVar('fakultas');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

<?php

namespace App\Controllers;

use App\Models\FeederModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Feeder extends BaseController
{
    protected $feederModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->feederModel = new FeederModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Feeder', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listProdi' => $this->feederModel->getProdi(),
            'listTermYear' => $this->feederModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/feeder', $data);
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
            'tahunAngkatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Angkatan Harus Dipilih!',
                ]
            ],
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('feeder')->withInput();
        }

        $data = array(
            'prodi' => trim($this->request->getPost('prodi')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapFeeder = $this->feederModel->getLapFeeder($data);
        // dd($lapFeeder);
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Feeder', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listProdi' => $this->feederModel->getProdi(),
            'listTermYear' => $this->feederModel->getTermYear(),
            'filter' => $data['prodi'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapFeeder
        ];
        session()->setFlashdata('success', '<strong>' . count($lapFeeder) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/feeder', $data);
    }

    public function exportFeeder()
    {
        $data = array(
            'prodi' => trim($this->request->getPost('prodi')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapFeeder = $this->feederModel->getLapFeeder($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Data Feeder Mahasiswa')->mergeCells("A" . $row . ":Q" . $row)->getStyle("A" . $row . ":P" . $row)->getFont()->setBold(true);
        $row++;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Nomor Registrasi')
            ->setCellValue('C' . $row, 'NPM')
            ->setCellValue('D' . $row, 'Nama Mahasiswa')
            ->setCellValue('E' . $row, 'Kode Prodi')
            ->setCellValue('F' . $row, 'Nama Prodi')
            ->setCellValue('G' . $row, 'Nama Fakultas')
            ->setCellValue('H' . $row, 'Angkatan')
            ->setCellValue('I' . $row, 'Kelas')
            ->setCellValue('J' . $row, 'Semester')
            ->setCellValue('K' . $row, 'Kode Matkul')
            ->setCellValue('L' . $row, 'Matkul')
            ->setCellValue('M' . $row, 'SKS')
            ->setCellValue('N' . $row, 'Nilai Angka')
            ->setCellValue('O' . $row, 'Nilai Huruf')
            ->setCellValue('P' . $row, 'Nama Dosen')
            ->setCellValue('Q' . $row, 'NIDN Dosen')->getStyle("A2:Q2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapFeeder as $feeder) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $feeder->NOREG)
                ->setCellValue('C' . $row, $feeder->NPM)
                ->setCellValue('D' . $row, $feeder->NAMA_LENGKAP)
                ->setCellValue('E' . $row, $feeder->KODE_PRODI)
                ->setCellValue('F' . $row, $feeder->PRODI)
                ->setCellValue('G' . $row, $feeder->FAKULTAS)
                ->setCellValue('H' . $row, $feeder->ANGKATAN)
                ->setCellValue('I' . $row, $feeder->KELAS)
                ->setCellValue('J' . $row, $feeder->SEMESTER)
                ->setCellValue('K' . $row, $feeder->KODE_MATKUL)
                ->setCellValue('L' . $row, $feeder->MATKUL)
                ->setCellValue('M' . $row, $feeder->SKS)
                ->setCellValue('N' . $row, $feeder->NILAI_ANGKA)
                ->setCellValue('O' . $row, $feeder->NILAI_HURUF)
                ->setCellValue('P' . $row, $feeder->DOSEN)
                ->setCellValue('Q' . $row, $feeder->NIDN);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);
        $fileName = 'Data Feeder ' . $feeder->PRODI . ' TA ' . $feeder->SEMESTER;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

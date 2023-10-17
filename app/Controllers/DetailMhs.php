<?php

namespace App\Controllers;

use App\Models\DetailMhsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class DetailMhs extends BaseController
{
    protected $detailMhsModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->detailMhsModel = new DetailMhsModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Detail Mahasiswa Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'Detail Mahasiswa Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->detailMhsModel->getFakultas(),
            'listTermYear' => $this->detailMhsModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'detailMhs' => []
        ];
        // dd($data);

        return view('pages/detailMhs', $data);
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
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('detailMhs')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );
        // dd($data);

        $lapDetailMhs = $this->detailMhsModel->getDetailMhs($data);
        // dd($lapDetailMhs);

        $fakultas = [];
        foreach ($lapDetailMhs as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi = [];
        foreach ($lapDetailMhs as $k) {
            array_push($prodi, [
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->NAMA_PRODI
            ]);
        }

        $angkatan = [];
        foreach ($lapDetailMhs as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }

        $data = [
            'title' => "Detail Mahasiswa Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'Detail Mahasiswa Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->detailMhsModel->getFakultas(),
            'listTermYear' => $this->detailMhsModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'detailMhs' => $lapDetailMhs
        ];
        // dd($lapDetailMhs);
        session()->setFlashdata('success', '<strong>' . count($lapDetailMhs) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/detailMhs', $data);
    }

    public function exportDetailMhs()
    {
        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapDetailMhs = $this->detailMhsModel->getDetailMhs($data);
        foreach ($lapDetailMhs as $mhsAktif) {
            // $fakultas = $mhsAktif->FAKULTAS;
            $tahunAjaran = $mhsAktif->TAHUN_AJAR;
        }
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Detail Mahasiswa Aktif TA. ' . $tahunAjaran)->mergeCells("A" . $row . ":H" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
        $row++;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'NPM')
            ->setCellValue('C' . $row, 'Nama Mahasiswa')
            ->setCellValue('D' . $row, 'Fakultas')
            ->setCellValue('E' . $row, 'Nama Prodi')
            ->setCellValue('F' . $row, 'Angkatan')
            ->setCellValue('G' . $row, 'Tahun Ajaran')
            ->setCellValue('H' . $row, 'Status Aktif')->getStyle("A2:H2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapDetailMhs as $detailMhs) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $detailMhs->NPM)
                ->setCellValue('C' . $row, $detailMhs->NAMA_LENGKAP)
                ->setCellValue('D' . $row, $detailMhs->FAKULTAS)
                ->setCellValue('E' . $row, $detailMhs->NAMA_PRODI)
                ->setCellValue('F' . $row, $detailMhs->ANGKATAN)
                ->setCellValue('G' . $row, $detailMhs->TAHUN_AJAR)
                ->setCellValue('H' . $row, $detailMhs->STATUS);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);
        $fileName = 'Detail Mahasiswa Aktif TA ' . $detailMhs->TAHUN_AJAR;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

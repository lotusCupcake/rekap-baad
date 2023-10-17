<?php

namespace App\Controllers;

use App\Models\PendaftarModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Pendaftar extends BaseController
{
    protected $pendaftarModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Pendaftar', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->pendaftarModel->getFakultas(),
            'listTermYear' => $this->pendaftarModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/pendaftar', $data);
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
            return redirect()->to('pendaftar')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapPendaftar = $this->pendaftarModel->getLapPendaftar($data);
        // dd($lapPendaftar);
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Pendaftar', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->pendaftarModel->getFakultas(),
            'listTermYear' => $this->pendaftarModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapPendaftar
        ];
        session()->setFlashdata('success', '<strong>' . count($lapPendaftar) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/pendaftar', $data);
    }

    public function exportPendaftar()
    {
        // dd($_POST);
        $data = array(
            'fakultas' => trim($this->request->getVar('fakultas')),
            'tahunAjar' => trim($this->request->getVar('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getVar('tahunAngkatan')),
        );

        $lapPendaftar = $this->pendaftarModel->getLapPendaftar($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Data Pendaftar')->mergeCells("A" . $row . ":I" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
        $row++;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Nomor Registrasi')
            ->setCellValue('C' . $row, 'Nama Lengkap')
            ->setCellValue('D' . $row, 'Email')
            ->setCellValue('E' . $row, 'Kode Prodi')
            ->setCellValue('F' . $row, 'Nama Prodi')
            ->setCellValue('G' . $row, 'Nomor Hp')
            ->setCellValue('H' . $row, 'Nama Ayah')
            ->setCellValue('I' . $row, 'Nama Ibu')->getStyle("A2:I2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapPendaftar as $pendaftar) {
            $noHp = (substr($pendaftar->regNoHp, 0, 3) == "+62") ? "0" . substr($pendaftar->regNoHp, 3, strlen($pendaftar->regNoHp)) : $pendaftar->regNoHp;
            $mobile = (substr($noHp, 0, 2) == "62") ? "0" . substr($noHp, 2, strlen($noHp)) : $noHp;
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $pendaftar->regNoRegistrasi)
                ->setCellValue('C' . $row, $pendaftar->regNamaLengkap)
                ->setCellValue('D' . $row, $pendaftar->regEmail)
                ->setCellValue('E' . $row, $pendaftar->prodiBankId)
                ->setCellValue('F' . $row, $pendaftar->prodiNamaResmi)
                ->setCellValue('G' . $row, $mobile)
                ->setCellValue('H' . $row, $pendaftar->regNamaAyah)
                ->setCellValue('I' . $row, $pendaftar->regNamaIbu);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);
        $fileName = 'Data Pendaftar';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

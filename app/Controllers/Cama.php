<?php

namespace App\Controllers;

use App\Models\CamaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Cama extends BaseController
{
    protected $camaModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->camaModel = new CamaModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Calon Mahasiswa', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->camaModel->getFakultas(),
            'listTermYear' => $this->camaModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/cama', $data);
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
            return redirect()->to('cama')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapCama = $this->camaModel->getLapCama($data);
        // dd($lapCama);
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Calon Mahasiswa', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->camaModel->getFakultas(),
            'listTermYear' => $this->camaModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapCama
        ];
        session()->setFlashdata('success', '<strong>' . count($lapCama) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/cama', $data);
    }

    public function exportCama()
    {
        $data = array(
            'fakultas' => trim($this->request->getVar('fakultas')),
            'tahunAjar' => trim($this->request->getVar('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getVar('tahunAngkatan')),
        );

        $lapCama = $this->camaModel->getLapCama($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Data Calon Mahasiswa')->mergeCells("A" . $row . ":I" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
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
            ->setCellValue('I' . $row, 'Nama Ibu')
            ->setCellValue('J' . $row, 'Alamat')
            ->setCellValue('K' . $row, 'Angkatan')->getStyle("A2:K2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapCama as $cama) {
            $noHp = (substr($cama->camaNoHp, 0, 3) == "+62") ? "0" . substr($cama->camaNoHp, 3, strlen($cama->camaNoHp)) : $cama->camaNoHp;
            $mobile = (substr($noHp, 0, 2) == "62") ? "0" . substr($noHp, 2, strlen($noHp)) : $noHp;
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $cama->camaNoRegistrasi)
                ->setCellValue('C' . $row, $cama->camaNamaLengkap)
                ->setCellValue('D' . $row, $cama->camaEmail)
                ->setCellValue('E' . $row, $cama->prodiBankId)
                ->setCellValue('F' . $row, $cama->prodiNamaResmi)
                ->setCellValue('G' . $row, $mobile)
                ->setCellValue('H' . $row, $cama->camaNamaAyah)
                ->setCellValue('I' . $row, $cama->camaNamaIbu)
                ->setCellValue('J' . $row, $cama->camaAlamat)
                ->setCellValue('K' . $row, $cama->camaAngkatan);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);
        $fileName = 'Data Calon Mahasiswa ' . $cama->camaAngkatan;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

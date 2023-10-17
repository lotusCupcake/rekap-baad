<?php

namespace App\Controllers;

use App\Models\RegulangModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Regulang extends BaseController
{
    protected $regulangModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->regulangModel = new RegulangModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Regitrasi Ulang', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->regulangModel->getFakultas(),
            'listTermYear' => $this->regulangModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/regulang', $data);
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
            return redirect()->to('regulang')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapRegulang = $this->regulangModel->getLapRegulang($data);
        // dd($lapRegulang);
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Regitrasi Ulang', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->regulangModel->getFakultas(),
            'listTermYear' => $this->regulangModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapRegulang
        ];
        session()->setFlashdata('success', '<strong>' . count($lapRegulang) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/regulang', $data);
    }

    public function exportRegulang()
    {
        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapRegulang = $this->regulangModel->getLapRegulang($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Data Mahasiswa')->mergeCells("A" . $row . ":L" . $row)->getStyle("A" . $row . ":L" . $row)->getFont()->setBold(true);
        $row++;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Nomor Registrasi')
            ->setCellValue('C' . $row, 'NPM')
            ->setCellValue('D' . $row, 'Nama Lengkap')
            ->setCellValue('E' . $row, 'Email')
            ->setCellValue('F' . $row, 'Kode Prodi')
            ->setCellValue('G' . $row, 'Nama Prodi')
            ->setCellValue('H' . $row, 'Nomor Hp')
            ->setCellValue('I' . $row, 'Nama Ayah')
            ->setCellValue('J' . $row, 'Nama Ibu')
            ->setCellValue('K' . $row, 'Alamat')
            ->setCellValue('L' . $row, 'Angkatan')->getStyle("A2:L2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapRegulang as $mhs) {
            $noHp = (substr($mhs->mhsNoHp, 0, 3) == "+62") ? "0" . substr($mhs->mhsNoHp, 3, strlen($mhs->mhsNoHp)) : $mhs->mhsNoHp;
            $mobile = (substr($noHp, 0, 2) == "62") ? "0" . substr($noHp, 2, strlen($noHp)) : $noHp;
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $mhs->mhsNomorRegistrasi)
                ->setCellValue('C' . $row, $mhs->mhsNpm)
                ->setCellValue('D' . $row, $mhs->mhsNamaLengkap)
                ->setCellValue('E' . $row, $mhs->mhsEmail)
                ->setCellValue('F' . $row, $mhs->mhsProdiBankId)
                ->setCellValue('G' . $row, $mhs->prodiNamaResmi)
                ->setCellValue('H' . $row, $mobile)
                ->setCellValue('I' . $row, $mhs->mhsNamaAyah)
                ->setCellValue('J' . $row, $mhs->mhsNamaIbu)
                ->setCellValue('K' . $row, $mhs->mhsAlamat)
                ->setCellValue('L' . $row, $mhs->mhsAngkatan);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);
        $fileName = 'Data Mahasiswa ' . $mhs->mhsAngkatan;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

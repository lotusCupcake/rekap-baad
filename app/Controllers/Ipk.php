<?php

namespace App\Controllers;

use App\Models\IpkModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Ipk extends BaseController
{
    protected $ipkModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->ipkModel = new IpkModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "IPK Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data KRS Dan Nilai', 'IPK Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listTermYear' => $this->ipkModel->getTermYear(),
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/ipk', $data);
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
            return redirect()->to('ipk')->withInput();
        }

        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapIpk = $this->ipkModel->getLapIpk($data);
        // dd($lapIpk);
        $data = [
            'title' => "IPK Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data KRS Dan Nilai', 'IPK Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listTermYear' => $this->ipkModel->getTermYear(),
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapIpk
        ];
        session()->setFlashdata('success', '<strong>' . count($lapIpk) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/ipk', $data);
    }

    public function exportIpk()
    {
        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapIpk = $this->ipkModel->getLapIpk($data);
        foreach ($lapIpk as $ipkMahasiswa) {
            $stambuk = $ipkMahasiswa->ANGKATAN;
            $tahunAjaran = $ipkMahasiswa->TAHUN_AKADEMIK;
        }
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'IPK Mahasiswa Stambuk ' . $stambuk . ' TA. ' . $tahunAjaran)->mergeCells("A" . $row . ":K" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
        $row++;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Nomor Registrasi')
            ->setCellValue('C' . $row, 'NPM')
            ->setCellValue('D' . $row, 'Nama Mahasiswa')
            ->setCellValue('E' . $row, 'Nama Prodi')
            ->setCellValue('F' . $row, 'Kelas')
            ->setCellValue('G' . $row, 'SKS Diambil')
            ->setCellValue('H' . $row, 'SKS Diperoleh')
            ->setCellValue('I' . $row, 'IPS')
            ->setCellValue('J' . $row, 'IPK')
            ->setCellValue('K' . $row, 'TAHUN AJARAN')->getStyle("A2:K2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapIpk as $ipk) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $ipk->Register_Number)
                ->setCellValue('C' . $row, $ipk->NPM)
                ->setCellValue('D' . $row, $ipk->NAMA_LENGKAP)
                ->setCellValue('E' . $row, $ipk->PRODI)
                ->setCellValue('F' . $row, $ipk->KELAS . $ipk->WAKTU)
                ->setCellValue('G' . $row, $ipk->SKS_DIAMBIL)
                ->setCellValue('H' . $row, $ipk->SKS_DIPEROLEH)
                ->setCellValue('I' . $row, $ipk->IPS)
                ->setCellValue('J' . $row, $ipk->IPK)
                ->setCellValue('K' . $row, $ipk->TAHUN_AKADEMIK);
            $row++;
        }
        $writer = new Xls($this->spreadsheet);
        $fileName = 'IPK Mahasiswa Stambuk ' . $ipk->ANGKATAN . ' TA. ' . $ipk->TAHUN_AKADEMIK;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}

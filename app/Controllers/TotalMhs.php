<?php

namespace App\Controllers;

use App\Models\TotalMhsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class TotalMhs extends BaseController
{
    protected $totalMhsModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->totalMhsModel = new TotalMhsModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Total Mahasiswa Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'Total Mahasiswa Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->totalMhsModel->getFakultas(),
            'listTermYear' => $this->totalMhsModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'totalMhs' => [],
            'prodi' => [],
            'fakultas' => [],
            'angkatan' => [],
        ];
        // dd($data);

        return view('pages/totalMhs', $data);
    }

    public function proses()
    {
        if (!$this->validate([
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('totalMhs')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
        );
        // dd($data);

        $lapTotalMhs = $this->totalMhsModel->getTotalMhs($data);
        // dd($lapTotalMhs);

        $fakultas = [];
        foreach ($lapTotalMhs as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi = [];
        foreach ($lapTotalMhs as $k) {
            array_push($prodi, [
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->NAMA_PRODI
            ]);
        }

        $angkatan = [];
        foreach ($lapTotalMhs as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }

        $data = [
            'title' => "Total Mahasiswa Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'Total Mahasiswa Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->totalMhsModel->getFakultas(),
            'listTermYear' => $this->totalMhsModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'totalMhs' => $lapTotalMhs,
            'prodi' => array_unique($prodi, SORT_REGULAR),
            'fakultas' => $fakultas,
            'angkatan' => $angkatan,
        ];
        // dd($lapTotalMhs);
        session()->setFlashdata('success', '<strong>' . count($lapTotalMhs) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/totalMhs', $data);
    }

    public function exportTotalMhs()
    {
        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
        );

        $lapTotalMhs = $this->totalMhsModel->getTotalMhs($data);

        foreach ($lapTotalMhs as $totalMahasiswa) {
            $tahunAjaran = $totalMahasiswa->TAHUN_AJAR;
        }

        $fakultas = [];
        foreach ($lapTotalMhs as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi = [];
        foreach ($lapTotalMhs as $k) {
            array_push($prodi, [
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->NAMA_PRODI
            ]);
        }

        $angkatan = [];
        foreach ($lapTotalMhs as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }

        $spreadsheet = new Spreadsheet();
        $col =   array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $row = 1;

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, "Jumlah KRS Aktif TA. " . $tahunAjaran)->mergeCells("A" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getStyle("A" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getAlignment()->setHorizontal('center');
        $row = $row + 1;
        $no = 0;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Fakultas / Prodi')->getStyle("A" . $row . ":" . "B" . $row)->getFont()->setBold(true);

        $a = [];
        foreach ($angkatan as $ang) {
            $a[$ang] = 0;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, $ang)->getStyle($col[2 + ($no)] . $row)->getFont()->setBold(true);
            $no++;
        }

        $row = $row + 1;

        foreach ($fakultas as $fak) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, '')
                ->setCellValue('B' . $row, $fak)->getStyle("A" . $row . ":" . "B" . $row)->getFont()->setBold(true);
            $no = 0;
            foreach ($angkatan as $ang) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, '')->getStyle($col[2 + ($no)] . $row)->getFont()->setBold(true);
                $no++;
            }
            $row++;

            $urut = 1;
            foreach (array_unique($prodi, SORT_REGULAR) as $prd) {
                if ($fak == $prd['fakultas']) {
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $urut)
                        ->setCellValue('B' . $row, $prd['prodi']);

                    $no = 0;
                    foreach ($angkatan as $ang) {
                        $nilai = 0;
                        foreach ($lapTotalMhs as $krsAkt) {
                            ($ang == $krsAkt->ANGKATAN && $prd['prodi'] == $krsAkt->NAMA_PRODI) ? $nilai = $krsAkt->JUMLAH : $nilai = $nilai;
                            // $a[$ang] = $a[$ang] + $krsAkt->JUMLAH;
                            if ($ang == $krsAkt->ANGKATAN && $prd['prodi'] == $krsAkt->NAMA_PRODI) {
                                $a[$ang] = $a[$ang] + $krsAkt->JUMLAH;
                            }
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, $nilai);
                        $no++;
                    }
                    $urut++;
                    $row++;
                }
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, "")
                ->setCellValue('B' . $row, "Jumlah per Fakultas")->getStyle("A" . $row . ":" . "B" . $row)->getFont()->setBold(true);
            $no = 0;
            foreach ($angkatan as $ang) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, $a[$ang])->getStyle($col[2 + ($no)] . $row)->getFont()->setBold(true);
                $no++;
                $a[$ang] = 0;
            }
            $row++;
        }

        $writer = new Xls($spreadsheet);
        $fileName = 'Data Jumlah KRS Aktif';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xls');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        // return $this->index('tunggakan');
    }
}

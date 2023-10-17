<?php

namespace App\Controllers;

use App\Models\PendaftarModel;

class Home extends BaseController
{
    protected $pendaftarModel;
    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
    }

    public function index()
    {
        $data = array(
            'fakultas' => 'faperta',
            'tahunAjar' => 20201,
            'tahunAngkatan' => 2020,
        );
        // dd($data);
        // $lapPendaftar = $this->pendaftarModel->getLapPendaftar($data);
        $data = [
            'title' => "Home",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Dashboard'],
            'menu' => $this->fetchMenu(),
            // 'dataResultPendaftar' => $lapPendaftar
        ];
        return view('pages/home', $data);
    }
}

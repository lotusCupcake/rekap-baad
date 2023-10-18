<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaksesModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }

    public function getProdi()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/baad/prodi", [
            "headers" => [
                "Accept" => "application/json"
            ],
        ]);
        return json_decode($response->getBody())->data;
    }

    public function getLapPengakses($pass)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/Elearning/aksesCurrentDate",
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                "form_params" => [
                    "prodi" => $pass['prodi'],
                    "type" => $pass['type'],
                    "tanggalAwal" => $pass['tanggalAwal'],
                    "tanggalAkhir" => $pass['tanggalAkhir'],
                ]
            ]
        );
        return $response->getBody();
    }
}

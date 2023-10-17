<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailMhsModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }

    public function getFakultas()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getFakultas", [
            "headers" => [
                "Accept" => "application/json"
            ],

        ]);
        return json_decode($response->getBody())->data;
    }

    public function getTermYear()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getTermYear", [
            "headers" => [
                "Accept" => "application/json"
            ],
        ]);
        return json_decode($response->getBody())->data;
    }

    public function getDetailMhs($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/Laporankeu/getMhsKrsAktif",
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                "form_params" => [
                    "filter" => $data['fakultas'],
                    "tahunAjar" => $data['tahunAjar'],
                    "entryYearId" => $data['tahunAngkatan'],
                ]
            ]
        );

        return json_decode($response->getBody())->data;
    }
}

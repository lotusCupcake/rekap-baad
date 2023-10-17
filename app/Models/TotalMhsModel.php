<?php

namespace App\Models;

use CodeIgniter\Model;

class TotalMhsModel extends Model
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

    public function getTotalMhs($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/Laporankeu/getKrsAktif",
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                "form_params" => [
                    "filter" => $data['fakultas'],
                    "tahunAjar" => $data['tahunAjar'],
                ]
            ]
        );

        return json_decode($response->getBody())->data;
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class AktifDosenModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }

    public function getFakultas()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/baad/filter", [
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

    public function getLapAktifDosen($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/elearning/keaktifanDosen",
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                "form_params" => [
                    "fakultas" => $data['fakultas'],
                    "termYear" => $data['tahunAjar'],
                ]
            ]
        );
        // dd($response);

        return json_decode($response->getBody())->data;
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class MatkulModel extends Model
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

    public function getLapMatkul($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/Elearning/course",
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                "form_params" => [
                    "filter" => $data['fakultas'],
                    "tahunajar" => $data['tahunAjar'],
                ]
            ]
        );
        return json_decode($response->getBody())->data;
    }
}

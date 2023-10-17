<?php

namespace App\Models;

use CodeIgniter\Model;

class IpkModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
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

    public function getLapIpk($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/baad/lapIpk",
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                "form_params" => [
                    "termYearId" => $data['tahunAjar'],
                    "entryYearId" => $data['tahunAngkatan'],
                ]
            ]
        );

        return json_decode($response->getBody())->data;
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class MhsModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }

    public function getLapMhs($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/elearning/mahasiswa",
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                "form_params" => [
                    "entryYear" => $data['tahunAngkatan'],
                    "tglDaftar" => $data['tanggalDaftar'],
                ]
            ]
        );
        return json_decode($response->getBody())->data;
    }
}

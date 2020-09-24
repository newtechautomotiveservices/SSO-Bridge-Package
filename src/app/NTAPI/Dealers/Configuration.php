<?php

namespace Newtech\NTAPIBridge\NTAPI\Dealers;

use Newtech\NTAPIBridge\Models\NTAPIModel;

class Configuration extends NTAPIModel
{
    // protected $base_url = "";
    protected $path = "/v2/dealers/configuration";

    public static function get($id, $meta = [])
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::url() . "/" . $meta['store_number'] . "/" . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = $data["data"];
        return new self($id, $data, $meta);
    }

    public function save() {
        $url = self::url() . "/" . $this->meta['store_number'] . "/" . $this->id;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('PUT', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json'
            ],
            'query' => [
                'replaceArrays' => 1
            ],
            'json' => $this->attributes
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return true;
    }
}




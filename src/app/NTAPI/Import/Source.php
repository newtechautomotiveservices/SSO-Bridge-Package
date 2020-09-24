<?php

namespace Newtech\NTAPIBridge\NTAPI\Import;

use Newtech\NTAPIBridge\Models\NTAPIModel;

class Source extends NTAPIModel
{
    // protected $base_url = "";
    protected $path = "/v2/import/source";

    public static function get($id, $meta = [])
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::url() . "/" . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = $data["data"][$id];
        return new self($id, $data, $meta);
    }

    public static function all($meta = [])
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::url(), [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = $data["data"];
        $collection = collect([]);
        foreach ($data as $index => $data_map) {
            $collection->push(new self($index, $data_map, $meta));
        }
        return $collection;
    }

    public function save() {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', self::url(), [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/x-www-form-urlencoded',
                'Content-Type'    => 'application/x-www-form-urlencoded',
            ],
            'form_params' => $this->attributes
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return true;
    }

    public function delete() {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('DELETE', self::url() . "/" . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/x-www-form-urlencoded',
                'Content-Type'    => 'application/x-www-form-urlencoded',
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return true;
    }
}





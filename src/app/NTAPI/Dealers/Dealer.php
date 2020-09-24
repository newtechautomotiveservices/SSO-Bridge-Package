<?php

namespace Newtech\NTAPIBridge\NTAPI\Dealers;

use Newtech\NTAPIBridge\Models\NTAPIModel;

class Dealer extends NTAPIModel
{
    // protected $base_url = "";
    protected $path = "/v2/dealers/dealer";

    public static function get($id, $meta = [])
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::url() . "/" . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ],
            'query' => []
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = $data["data"];
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
}




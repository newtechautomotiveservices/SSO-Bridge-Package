<?php

namespace Newtech\NTAPIBridge\NTAPI\Import;

use Newtech\NTAPIBridge\Models\NTAPIModel;

class Downloads extends NTAPIModel
{
    // protected $base_url = "";
    protected $path = "/v2/import/downloads";

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





<?php

namespace Newtech\NTAPIBridge\NTAPI;

use Newtech\NTAPIBridge\Models\NTAPIModel;

class ExampleModel extends NTAPIModel
{
    // protected $base_url = ""; // For overriding the normal url in the configuration. if you need this dynamic use the model_intialize function.
    // protected $api_key = ""; // For overriding the normal api key in the configuration. if you need this dynamic use the model_intialize function.

    protected $path = "/v2/dealers/dealer";

    protected $validation = [
        "normal" => [
            "name" => "required"
        ]
    ];

    public function model_initialize() {
        $this->base_url = config('nt-api.external_keys.cdn.url');
        $this->api_key = config('nt-api.external_keys.cdn.key');
    }

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

    public function all($meta = [])
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
        $this->collection = collect([]);
        foreach ($data as $index => $data_map) {
            $this->collection->push(new self($index, $data_map, $meta));
        }
        return $this->collection;
    }
}




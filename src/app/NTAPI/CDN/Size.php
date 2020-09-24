<?php

namespace Newtech\NTAPIBridge\NTAPI\CDN;

use Newtech\NTAPIBridge\Models\NTAPIModel;

class Size extends NTAPIModel
{
    protected $path = "/api/size";

    public function model_initialize() {
        $this->base_url = config('nt-api.external_keys.cdn.url');
        $this->api_key = config('nt-api.external_keys.cdn.key');
    }

    public static function create($attributes, $meta = []) {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', self::url(), [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ],
            'json' => $file,
            'query' => [
                'category' => $meta['category']
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return new self($id, $attributes, $meta);
    }

    public function save() {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('PUT', self::url() . "/" . $this->meta['category'] . "/" . $this->id, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ],
            'json' => $this->attributes
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return new self($this->id, $this->attributes, $this->meta);
    }


    public function delete() {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('DELETE', self::url() . "/" . $this->meta['category'] . "/" . $this->id, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }
}
<?php

namespace Newtech\NTAPIBridge\NTAPI\CDN;

use Newtech\NTAPIBridge\Models\NTAPIModel;

class Category extends NTAPIModel
{
    protected $path = "/api/category";

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
        foreach ($data as $index => $category) {
            $collection->push(new self($index, $category, $meta));
        }
        return $collection;
    }

    public static function create($attributes, $meta = []) {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', self::url(), [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ],
            'json' => $attributes
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return new self($id, $attributes, $meta);
    }


    public function delete() {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('DELETE', self::url(), [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ],
            'query' => [
                'category' => $this->id
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }

    public function save() {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('PUT', self::url() . "/" . $this->id, [
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

    public function createFile($file, $meta = []) {
        $meta['category'] = $this->id;
        $file = Files::create($file, $meta);
        return $file;
    }

    public function createSize($attributes, $meta = []) {
        $meta['category'] = $this->id;
        $file = Size::create($attributes, $meta);
        return $file;
    }
}
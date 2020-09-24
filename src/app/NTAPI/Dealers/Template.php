<?php

namespace Newtech\NTAPIBridge\NTAPI\Dealers;

use Newtech\NTAPIBridge\Models\NTAPIModel;

class Template extends NTAPIModel
{
    // protected $base_url = "https://url.com";
    protected $path = "/v2/dealers/template";

    public static function get($id, $meta = [])
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::url() . "/" . $meta['store_number'], [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ],
            'query' => []
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = $data["data"][$meta['type']][$id];
        return new self($id, $data, $meta);
    }

    public static function all($meta = [])
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', self::url() . "/" . $meta['store_number'], [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = $data["data"];
        $collection = collect([]);
        if(isset($meta['type'])) {
            if(isset($data[$meta['type']])) {
                foreach ($data[$meta['type']] as $index => $template) {
                    $template['type'] = $meta['type'];
                    $collection->push(new self($index, $template, $meta));
                }
            }
        } else {
            foreach ($data as $type => $templates) {
                if($type !== "storeNumber") {
                    foreach ($templates as $index => $template) {
                        $template['type'] = $type;
                        $collection->push(new self($index, $template, $meta));
                    }
                }
            }
        }
        return $collection;
    }

    public static function create($attributes, $meta = []) {
        $client = new \GuzzleHttp\Client();
        $templates = [
            "storeNumber" => $meta['store_number'],
        ];
        $id = \Carbon\Carbon::now()->timestamp . \Illuminate\Support\Str::random(6);
        $templates[$meta['type']][$id] = $attributes;
        $response = $client->request('PUT', self::url() . "/" . $meta['store_number'], [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ],
            'json' => $templates
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return new self($id, $attributes, $meta = []);
    }


    public function delete() {
        $client = new \GuzzleHttp\Client();
        $templates = [
            "storeNumber" => $this->meta['store_number'],
        ];
        $templates[$this->meta['type']][$this->id] = null;
        $response = $client->request('PUT', self::url() . "/" . $this->meta['store_number'], [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ],
            'json' => $templates
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }

    public function save() {
        $client = new \GuzzleHttp\Client();
        $templates = [
            "storeNumber" => $this->meta['store_number'],
            "ebrochure" => []
        ];
        $templates['ebrochure'][$this->id] = $this->attributes;
        $response = $client->request('PUT', self::url() . "/" . $this->meta['store_number'], [
            'headers' => [
                'Authorization' => 'Bearer ' . self::getAPIKey(),
                'Accept'    => 'application/json',
                'Content-Type'    => 'application/json',
            ],
            'json' => $templates,
            'query' => [
                "replaceArrays" => true,
                "ignoreEmpty" => false
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        return new self($this->id, $this->attributes, $meta = []);
    }
}
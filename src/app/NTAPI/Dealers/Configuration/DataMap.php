<?php

namespace Newtech\NTAPIBridge\NTAPI\Dealers\Configuration;

use Newtech\NTAPIBridge\Models\NTAPIModel;

use Newtech\NTAPIBridge\NTAPI\Dealers\Configuration;

class DataMap extends NTAPIModel
{
    // protected $base_url = "";
    protected $path = "/v2/dealers/configuration";

    public static $configuration_section = "dataMap";

    public static function all($meta = [])
    {
        $data_maps = Configuration::get(self::$configuration_section, $meta);
        $collection = [];
        foreach ($data_maps->toArray() as $index => $data_map) {
            $collection[$index] = new self($index, $data_map, $meta);
        }
        return $collection;
    }

	public static function get($id, $meta = []) {
		$data_maps = Configuration::get(self::$configuration_section, $meta);
        $data_map = $data_maps[$id];
        return new self($id, $data_map, $meta);
	}

    public static function create($attributes, $meta = []) {
        $id = \Carbon\Carbon::now()->timestamp . \Illuminate\Support\Str::random(6);
        $data_maps[$id] = $attributes;
        $config = new Configuration(self::$configuration_section, $data_maps, $meta);
        $config->save();
        return new DataMap($id, $attributes, $meta = []);
    }

    public function save() {
        $this->id = ($this->id == null) ? (\Carbon\Carbon::now()->timestamp . \Illuminate\Support\Str::random(6)) : $this->id;
        $data_maps[$this->id] = $this->attributes;
        $config = new Configuration(self::$configuration_section, $data_maps, ["store_number" => $this->meta["store_number"]]);
        $config->save();
        return true;
    }

    public function update($attributes) {
        $this->id = ($this->id == null) ? (\Carbon\Carbon::now()->timestamp . \Illuminate\Support\Str::random(6)) : $this->id;
        $this->attributes = array_replace_recursive($this->attributes, $attributes);
        $data_maps[$this->id] = $this->attributes;
        $config = new Configuration(self::$configuration_section, $data_maps, ["store_number" => $this->meta["store_number"]]);
        $config->save();
        return true;
    }

    public function delete() {
        $data_maps[$this->id] = null;
        $config = new Configuration(self::$configuration_section, $data_maps, ["store_number" => $this->meta["store_number"]]);
        $config->save();
        return true;
    }
}

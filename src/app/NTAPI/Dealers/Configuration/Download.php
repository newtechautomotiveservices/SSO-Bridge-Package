<?php

namespace Newtech\NTAPIBridge\NTAPI\Dealers\Configuration;

use Newtech\NTAPIBridge\Models\NTAPIModel;

use Newtech\NTAPIBridge\NTAPI\Dealers\Configuration;

class Download extends NTAPIModel
{
    // protected $base_url = "";
    protected $path = "/v2/dealers/configuration";

    public static $configuration_section = "download";

    public static function all($meta = [])
    {
        $downloads = Configuration::get(self::$configuration_section, $meta);
        $download = Configuration::get(self::$configuration_section, $meta);
        $collection = collect([]);
        foreach ($downloads->toArray() as $index => $download) {
            $collection->push(new self($index, $download, $meta));
        }
        return $collection;
    }

    public static function get($id, $meta = []) {
        $downloads = Configuration::get(self::$configuration_section, $meta);
        $download = $downloads[$id];
        return new self($id, $download, $meta);
    }

    public static function create($attributes, $meta = []) {
        $id = \Carbon\Carbon::now()->timestamp . \Illuminate\Support\Str::random(6);
        $downloads[$id] = $attributes;
        $config = new Configuration(self::$configuration_section, $downloads, $meta);
        $config->save();
        return new self($id, $attributes, $meta = []);
    }

    public function save() {
        $this->id = ($this->id == null) ? (\Carbon\Carbon::now()->timestamp . \Illuminate\Support\Str::random(6)) : $this->id;
        $downloads[$this->id] = $this->attributes;
        $config = new Configuration(self::$configuration_section, $downloads, ["store_number" => $this->meta["store_number"]]);
        $config->save();
        return true;
    }

    public function update($attributes) {
        $this->id = ($this->id == null) ? (\Carbon\Carbon::now()->timestamp . \Illuminate\Support\Str::random(6)) : $this->id;
        $this->attributes = array_replace_recursive($this->attributes, $attributes);
        $downloads[$this->id] = $this->attributes;
        $config = new Configuration(self::$configuration_section, $downloads, ["store_number" => $this->meta["store_number"]]);
        $config->save();
        return true;
    }

    public function delete() {
        $downloads[$this->id] = null;
        $config = new Configuration(self::$configuration_section, $downloads, ["store_number" => $this->meta["store_number"]]);
        $config->save();
        return true;
    }
}

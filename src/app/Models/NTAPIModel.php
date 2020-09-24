<?php

namespace Newtech\NTAPIBridge\Models;

use ArrayAccess;
use Exception;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Str;
use JsonSerializable;

abstract class NTAPIModel implements Arrayable, ArrayAccess, Jsonable, JsonSerializable
{
    protected $base_url;
    protected $api_key;

    protected $path;

    protected $id;

    protected $attributes;

    protected $collection;

    protected $meta;

    protected $call_stack = [];

    protected $isCollection = false;

    protected $messages = [
        "save" => "Successfuly saved the resource."
    ];

    protected $validation = [
        "normal" => []
    ];

    /**
     * Create a new model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct($id = "", array $attributes = [], array $meta = array())
    {
        $this->initialize($id, $attributes, $meta);
        $this->model_initialize();
    }
    public function initialize($id, array $attributes, array $meta = []) {
        $this->collection = collect([]);
        if(!isset($this->base_url)) {
            $this->base_url = config('nt-api.api_url');
        }
        if(!isset($this->api_key)) {
            $this->api_key = config('nt-api.api_key');
        }

        $this->id = $id;
        $this->attributes = $attributes;
        $this->meta = $meta;
        return $this;
    }


    public function model_initialize() {
        // The child class will override this.
    }

    // -----------------------------------------------------------------------------------
    // Collection Methods
    // -----------------------------------------------------------------------------------
    public function first()
    {
        $this->runCallStack();
        $this->isCollection = false;
        $this->attributes = $this->collection->first();
        return $this->attributes;
    }
    public function live_pluck($field, $key = null)
    {
        $this->isCollection = true;
        if(isset($key)) {
            $this->collection = $this->collection->pluck($field, $key);
            return $this->collection;
        } else {
            $this->collection = $this->collection->pluck($field);
            return $this->collection;
        }
        
    }
    public function live_where($field, $operator, $value, $meta = [])
    {
        $this->isCollection = true;
        $this->collection = $this->collection->where($field, $operator, $value);
    }
    public function live_all($meta = [])
    {
        $class = get_called_class();
        $this->isCollection = true;
        $this->collection = $class::all($meta);
        return $this->collection;
    }
    public function toArray()
    {
        $this->runCallStack();
        if($this->isCollection) {
            return $this->collection;
        }
        $attributes = $this->attributes;
        $attributes['_meta'] = $this->meta;
        return $attributes;
        
    }
    public function toJson($options = 0)
    {
        $json = null;
        if($this->isCollection) {
            $json = json_encode($this->collection->toArray(), $options);
        } else {
            $json = json_encode($this->attributes->toArray(), $options);
        }
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    // -----------------------------------------------------------------------------------
    // Operations
    // -----------------------------------------------------------------------------------
    public function save()
    {
        return collect([
            "code" => 500,
            "message" => "The save function doesn't exist on this model."
        ]);
    }
    public function delete()
    {
        return collect([
            "code" => 500,
            "message" => "The delete function doesn't exist on this model."
        ]);
    }

    public function refresh()
    {
        return collect([
            "code" => 500,
            "message" => "The refresh function doesn't exist on this model."
        ]);
    }
    public function update($attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this->save();
    }

    // -----------------------------------------------------------------------------------
    // Operations for static
    // -----------------------------------------------------------------------------------
    public static function all($meta = [])
    {
        return collect([
            "code" => 500,
            "message" => "The all function doesn't exist on this model."
        ]);
    }

    public static function get($id, $meta = [])
    {
        return collect([
            "code" => 500,
            "message" => "The get function doesn't exist on this model."
        ]);
    }

    // -----------------------------------------------------------------------------------
    // Overloads -- https://www.php.net/manual/en/language.oop5.overloading.php
    // -----------------------------------------------------------------------------------
    public function __call($fname, $args) {
        switch($fname) {
            case ("pluck" || "where"):
                $this->addToCallStack($fname, $args);
                return $this;
                break;
            case "all":
                $this->addToCallStack("live_" . $fname, $args);
                return $this;
                break;
        }
    }
    public static function __callStatic($fname, $args) {
        $class = get_called_class();
        $model = new $class;
        switch($fname) {
            case ("pluck" || "where"):
                $model->addToCallStack("all", [], true);
                $model->addToCallStack($fname, $args);
                return $model;
                break;
        }
    }
    public function __toString()
    {
        return $this->toJson();
    }
    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        if($name == "id" && $this->id !== "") {
            return $this->id;
        }
        return null;
    }
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    // -----------------------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------------------
    public static function getBaseURL() {
        $class = get_called_class();
        $model = new $class;
        $model->model_initialize();
        return $model->base_url;
    }
    public static function getPath() {
        $class = get_called_class();
        $model = new $class;
        $model->model_initialize();
        return $model->path;
    }
    public static function getAPIKey() {
        $class = get_called_class();
        $model = new $class;
        $model->model_initialize();
        return $model->api_key;
    }
    public static function url() {
        return self::getBaseURL() . self::getPath();
    }

    // -----------------------------------------------------------------------------------
    // Call Stack Management
    // -----------------------------------------------------------------------------------
    public function addToCallStack($function, $arguments, $new = false) {
        if($new || !isset($this->call_stack)) {
            $this->call_stack = [];
        }
        $this->call_stack[] = ["function" => $function, "arguments" => $arguments];
    }
    public function runCallStack() {

        if(count($this->call_stack) > 0) {
            foreach ($this->call_stack as $index => $call) {
                switch($call['function']) {
                    case ("all" || "where" || "pluck"):
                        call_user_func_array(array($this, "live_" . $call['function']), $call['arguments']);
                        break;
                    default:
                        call_user_func_array(array($this, $call['function']), $call['arguments']);
                }
            }
            $this->call_stack = [];
        }
    }


    // -----------------------------------------------------------------------------------
    // Implements Overrides
    // -----------------------------------------------------------------------------------
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return true;
    }
    public function getAttribute($key)
    {
        return $this->attributes[$key];
    }
    public function offsetSet($offset, $value) {
        $this->attributes[$offset] = $value;
        return true;
    }
    public function offsetExists($offset) {
        return isset($this->attributes[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->attributes[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null;
    }
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}

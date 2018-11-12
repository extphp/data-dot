<?php

namespace ExtPHP\DataDot;


class Dot
{
    protected $data = null;

    protected $separator = '.';

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->data;
        }

        $data = $this->data;

        $segments = explode($this->separator, $key);
        foreach ($segments as $segment) {
            if (!$this->hasChild($data, $segment)) {
                return $default;
            }
            $data = $this->getChild($data, $segment);
        }
        return $data;
    }

    public function has($key = null)
    {
        if (is_null($key)) {
            return ! is_null($this->data);
        }

        $data = $this->data;

        $segments = explode($this->separator, $key);
        foreach ($segments as $segment) {
            if (!$this->hasChild($data, $segment)) {
                return false;
            }
            $data = $this->getChild($data, $segment);
        }
        return true;       
    }

    protected function hasChild($data, $key)
    {
        if (is_array($data) && array_key_exists($key, $data)) {
            return true;
        }
        if (is_object($data) && property_exists($data, $key)) {
            return true;
        }
        return false;
    }

    protected function getChild($data, $key)
    {
        if (is_array($data) && array_key_exists($key, $data)) {
            return $data[$key];
        }
        if (is_object($data) && property_exists($data, $key)) {
            return $data->{$key};
        }
        return null;
    }
}
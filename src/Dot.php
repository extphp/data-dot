<?php

namespace ExtPHP\DataDot;


class Dot implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    protected $data = null;

    protected $separator = '.';

    /**
     * @param mixed $data any type of array, object or even scalar
     */
    public function __construct($data = null)
    {
        /**
         * We achieve two things:
         *  - clone the resource so that changes inside Dot will not affect it
         *  - transform any objects into associative arrays
         */
        $this->data = json_decode(json_encode($data), true);
    }

    public function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->data;
        }
        $key = $this->sanitizeKey($key);
        $data = $this->data;

        $segments = explode($this->separator, $key);
        foreach ($segments as $segment) {
            if (!$this->exists($data, $segment)) {
                return $default;
            }
            $data = $this->retrieve($data, $segment);
        }
        return $data;
    }

    public function has($key = null)
    {
        if (is_null($key)) {
            return ! is_null($this->data);
        }
        $key = $this->sanitizeKey($key);
        $data = $this->data;

        $segments = explode($this->separator, $key);
        foreach ($segments as $segment) {
            if (!$this->exists($data, $segment)) {
                return false;
            }
            $data = $this->retrieve($data, $segment);
        }
        return true;       
    }

    public function set($key, $value)
    {
        if (is_null($key)) {
            $this->data = $value;
            return;
        }
        $key = $this->sanitizeKey($key);
        $segments = explode($this->separator, $key);
        $data =& $this->data;
        foreach ($segments as $segment) {
            if  (!$this->exists($data, $segment)) {
                if (is_object($data)) {
                    $data->{$segment} = null;
                } else {
                    $data[$segment] = null;
                }
            }
            $data =& $this->reference($data, $segment);
        }
        $data = $value;
    }

    public function delete($key)
    {
        if (is_null($key)) {
            $this->data = null;
            return;
        }
        $key = $this->sanitizeKey($key);
        if (!$this->has($key)) {
            return;
        }
        $segments = explode($this->separator, $key);
        $last = array_pop($segments);
        $data =& $this->data;
        foreach ($segments as $segment) {
            $data =& $this->reference($data, $segment);
        }
        unset($data[$last]);
    }

    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    public function dot($key = null)
    {
        return new Dot($this->get($key));
    }

    protected function exists($data, $key)
    {
        if (is_array($data) && array_key_exists($key, $data)) {
            return true;
        }
        return false;
    }

    protected function retrieve($data, $key)
    {
        if (is_array($data) && array_key_exists($key, $data)) {
            return $data[$key];
        }
        return null;
    }

    protected function & reference(& $data, $key)
    {
        if (is_array($data) && array_key_exists($key, $data)) {
            return $data[$key];
        }
        return null;
    }

    protected function sanitizeKey($key)
    {
        $sanitized = trim($key, $this->separator);
        if (strpos($sanitized, $this->separator . $this->separator) !== false) {
            throw new \InvalidArgumentException("Invalid key: $key => Cannot handle duplicate separator: " . $this->separator);
        }
        return $sanitized;
    }

    /*
     * --------------------------------------------------------------
     * ArrayAccess interface
     * --------------------------------------------------------------
     */

     public function offsetExists($offset)
     {
         return $this->has($offset);
     }

     public function offsetGet($offset)
     {
         return $this->get($offset);
     }

     public function offsetSet($offset, $value)
     {
         $this->set($offset, $value);
     }

     public function offsetUnset($offset)
     {
         $this->delete($offset);
     }

    /*
     * --------------------------------------------------------------
     * Countable interface
     * --------------------------------------------------------------
     */

    public function count($key = null)
    {
        return count($this->get($key));
    }

    /*
     * --------------------------------------------------------------
     * IteratorAggregate interface
     * --------------------------------------------------------------
     */

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /*
     * --------------------------------------------------------------
     * JsonSerializable interface
     * --------------------------------------------------------------
     */

    public function jsonSerialize()
    {
        return $this->data;
    }
}
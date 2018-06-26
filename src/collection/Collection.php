<?php

namespace net\collection;

/**
 * Class Collection
 * @package net\collection
 */
class Collection implements CollectionInterface
{

    /**
     * @var array
     */
    protected $binds = [];

    /**
     * Collection constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->binds = $data;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->binds);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function offsetGet($key)
    {
        return array_key_exists($key, $this->binds) ? $this->binds[$key] : null;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function offsetSet($key, $value)
    {
        $this->binds[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function offsetUnset($key)
    {
        unset($this->binds[$key]);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return array_key_exists($key, $this->binds) ? $this->binds[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->binds[$key] = $value;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->binds;
    }

    /**
     * @param $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->binds) ? $this->binds[$key] : $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->binds[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->binds);
    }

    /**
     * @param string $key
     */
    public function del($key)
    {
        unset($this->binds[$key]);
    }

    /**
     *
     */
    public function clear()
    {
        $this->binds = [];
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->binds);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->binds);
    }
}
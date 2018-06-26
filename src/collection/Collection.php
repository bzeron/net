<?php

namespace net\collection;

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
        return $this->offsetExists($key) ? $this->binds[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->binds[$key] = $value;
    }

    /**
     * @param string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->binds[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->binds;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exists($key): bool
    {
        return array_key_exists($key, $this->binds);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $this->offsetGet($key) ?? $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * @param string $key
     * @return void
     */
    public function del(string $key): void
    {
        $this->offsetUnset($key);
    }

    /**
     * @return void
     */
    public function clear(): void
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
<?php

namespace net\http;

use net\collection\Collection;

class Header extends Collection
{
    /**
     * @var Collection
     */
    public $headers;

    /**
     * Collection constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct(array_change_key_case($data, CASE_LOWER));
        $this->headers = new  Collection();
    }

    /**
     * @param $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return parent::get(strtolower($key), is_null($default) ? [] : $default);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return parent::has(strtolower($key));
    }


    /**
     * @deprecated
     * @param string $key
     * @param string|array $value
     */
    public function set(string $key, $value)
    {
        $this->setHeader($key, $value);
    }

    /**
     * @param string $key
     * @param string|array $value
     */
    public function __set($key, $value)
    {
        $this->setHeader($key, $value);
    }

    /**
     * @param string $key
     * @param string|array $value
     */
    public function offsetSet($key, $value)
    {
        $this->setHeader($key, $value);
    }


    /**
     * @deprecated
     * @param string $key
     * @return void
     */
    public function del(string $key): void
    {
        $this->headers->del($key);
    }

    /**
     * @param string $key
     * @param array|string $value
     * @return Header
     */
    public function setHeader(string $key, $value): Header
    {
        $key = strtolower($key);
        $value = is_array($value) ? $value : [$value];
        if ($this->headers->exists($key)) {
            $header = $this->headers->get($key);
            array_push($header, ...$value);
        }
        $this->headers->set($key, $value);
        return $this;
    }
}
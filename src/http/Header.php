<?php

namespace net\http;

use net\collection\Collection;

/**
 * Class Header
 * @package net\header
 */
class Header extends Collection
{
    /**
     * Collection constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct(array_change_key_case($data, CASE_UPPER));
    }


    /**
     * @param mixed $value
     * @return array
     */
    private function trimHeader($value)
    {
        if (is_array($value)) {
            return array_map(function ($item) {
                return trim($item, " \t");
            }, $value);
        } else {
            return [trim($value, " \t")];
        }
    }

    /**
     * @param $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get($key, $default = [])
    {
        return parent::get(strtoupper($key), $default);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return parent::has(strtoupper($key));
    }


    /**
     * @param string $key
     * @param string|array $value
     */
    public function set($key, $value)
    {
        parent::set(strtoupper($key), $this->trimHeader($value));
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function add($key, $value)
    {
        if ($this->has($key)) {
            $oldHeader = $this->get($key);
            if (is_array($oldHeader)) {
                array_push($oldHeader, $value);
                $this->set($key, $oldHeader);
            } else {
                $oldHeader = [$oldHeader];
                array_push($oldHeader, $value);
                $this->set($key, $oldHeader);
            }
        } else {
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key
     */
    public function del($key)
    {
        parent::del(strtoupper($key));
    }


    /**
     * @param $key
     * @return string
     */
    public function HeaderLine($key)
    {
        $headerLine = $this->get($key, []);
        if (is_array($headerLine)) {
            return implode(', ', $headerLine);
        } else {
            return $headerLine;
        }
    }

}
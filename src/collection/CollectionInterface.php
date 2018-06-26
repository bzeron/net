<?php

namespace net\collection;

/**
 * Interface CollectionInterface
 * @package net\collection
 */
interface CollectionInterface extends \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @return array
     */
    public function all();

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * @param string $key
     * @param string|array $value
     */
    public function set($key, $value);

    /**
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * @param string $key
     */
    public function del($key);

    /**
     *
     */
    public function clear();

}
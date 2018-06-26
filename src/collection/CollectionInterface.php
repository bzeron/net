<?php

namespace net\collection;

interface CollectionInterface extends \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param string $key
     * @return bool
     */
    public function exists($key): bool;

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, $value);

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @return void
     */
    public function del(string $key): void;

    /**
     * @return void
     */
    public function clear(): void;

}
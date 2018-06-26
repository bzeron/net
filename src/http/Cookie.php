<?php

namespace net\http;

use net\collection\Collection;

class Cookie extends Collection
{
    /**
     * @var Collection
     */
    public $cookies;

    /**
     * Cookie constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->cookies = new  Collection();
    }


    /**
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return $this
     */
    public function setCookie(string $name, string $value, int $expire, string $path = "", string $domain = "", bool $secure = false, bool $httpOnly = false): Cookie
    {
        $cookie = new \stdClass();
        $cookie->name = $name;
        $cookie->value = $value;
        $cookie->expire = $expire;
        $cookie->path = $path;
        $cookie->domain = $domain;
        $cookie->secure = $secure;
        $cookie->httpOnly = $httpOnly;
        $this->cookies->set($name, $cookie);
        return $this;
    }

    /**
     * @see setcookie()
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $this->setCookie(...func_get_args());
    }

    /**
     * @see setcookie()
     *
     * @param string $key
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        $this->setCookie(...func_get_args());
    }

    /**
     * @see setcookie()
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->setCookie(...func_get_args());
    }
}
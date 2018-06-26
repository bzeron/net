<?php

namespace net\http;

use net\collection\Collection;

/**
 * Class Cookie
 * @package net\cookie
 */
class Cookie extends Collection
{
    /**
     * @var array
     */
    protected $setCookie = [];

    /**
     * @param string $key
     * @param mixed $value
     * @param int $expires
     * @param string $domain
     * @param bool $hostonly
     * @param string $path
     * @param bool $secure
     * @param bool $httponly
     * @return $this
     */
    public function SetCookie($key, $value, $expires = 0, $domain = "", $hostonly = false, $path = "/", $secure = false, $httponly = false)
    {
        if ($expires < 0) {
            unset($this->setCookie[$key]);
            $this->del($key);
        } else {
            $this->setCookie[$key] = [
                "key"      => $key,
                'value'    => $value,
                'domain'   => $domain,
                'hostonly' => $hostonly,
                'path'     => $path,
                'expires'  => $expires,
                'secure'   => $secure,
                'httponly' => $httponly,
            ];
            $this->set($key, $value);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param mixed|array $default
     * @return mixed|null
     */
    public function GetCookie($key, $default = [])
    {
        return $this->get($key, $default);
    }

    /**
     * @return array
     */
    public function ToHeader()
    {
        $cookies = [];
        foreach ($this->setCookie as $key => $item) {
            $cookies[$key] = implode("", [
                "key"      => urlencode($item['key']),
                'value'    => sprintf("=%s", urlencode($item['value'])),
                'domain'   => empty($item['domain']) ? "" : sprintf("; domain=%s", $item['domain']),
                'hostonly' => $item['hostonly'] ? "; HostOnly" : "",
                'path'     => sprintf("; path=%s", $item['path']),
                'expires'  => empty($item['expires']) ? "" : sprintf("; expires=%s", gmdate('D, d-M-Y H:i:s e', time() + $item['expires'])),
                'secure'   => $item['secure'] ? "; secure" : "",
                'httponly' => $item['httponly'] ? "; HttpOnly" : "",
            ]);
        }
        return $cookies;
    }
}
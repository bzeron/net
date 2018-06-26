<?php

namespace net\http;

class Uri
{
    /**
     * @var Scheme
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var string
     */
    protected $fragment;

    /**
     * @var UserInfo
     */
    protected $userinfo;


    /**
     * Uri constructor.
     * @param Scheme $scheme
     * @param string $host
     * @param int $port
     * @param string $path
     * @param Query|null $query
     * @param string $fragment
     * @param UserInfo|null $userinfo
     */
    public function __construct(
        Scheme $scheme = null,
        $host = "localhost",
        $port = 80,
        $path = "/",
        Query $query = null,
        $fragment = "",
        UserInfo $userinfo = null
    )
    {
        $this->scheme = is_null($scheme) ? new Scheme() : $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->path = empty($path) ? "/" : $path;
        $this->query = $query;
        $this->fragment = $fragment;
        $this->userinfo = $userinfo;
    }

    /**
     * @return Scheme
     */
    public function Scheme()
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function Host()
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function Port()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function Path()
    {
        return $this->path;
    }

    /**
     * @return Query
     */
    public function Query()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function Fragment()
    {
        return $this->fragment;
    }

    /**
     * @return UserInfo
     */
    public function UserInfo()
    {
        return $this->userinfo;
    }

    /**
     * @return string
     */
    public function Authority()
    {
        $userinfo = $port = "";
        if (!empty($this->userinfo)) {
            $info = $this->userinfo->Info();
            if (!empty($info)) {
                $userinfo = sprintf("%s@", $this->userinfo->Info());
            }
        }
        if (!empty($this->port) && $this->port !== 80 && $this->port !== 443) {
            $port = sprintf(":%s", $this->port);
        }
        return sprintf("%s%s%s", $userinfo, $this->host, $port);
    }

    /**
     * @return string
     */
    public function Target()
    {
        $scheme = $query = $path = $fragment = "";
        if (!empty($this->scheme)) {
            $info = $this->scheme->String();
            if (!empty($info)) {
                $scheme = sprintf("%s://", $this->scheme->String());
            }
        }
        if (!empty($this->query)) {
            $info = $this->query->QueryString();
            if (!empty($info)) {
                $query = sprintf("?%s", $info);
            }
        }
        if (!empty($this->path)) {
            $path = sprintf("/%s", ltrim($this->path, "/"));
        }
        if (!empty($this->fragment)) {
            $fragment = sprintf("#%s", $this->fragment);
        }
        return sprintf("%s%s%s%s%s", $scheme, $this->Authority(), $path, $query, $fragment);
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->Target();
    }
}
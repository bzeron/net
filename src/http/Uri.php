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
    protected $userInfo;

    /**
     * Uri constructor.
     * @param Scheme $scheme
     * @param string $host
     * @param int $port
     * @param string $path
     * @param Query $query
     * @param string $fragment
     * @param UserInfo $userInfo
     */
    public function __construct(Scheme $scheme, string $host, int $port, string $path, Query $query, string $fragment, UserInfo $userInfo)
    {
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
        $this->userInfo = $userInfo;
    }

    /**
     * @return Scheme
     */
    public function scheme(): Scheme
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function host(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function port(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return Query
     */
    public function query(): Query
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function fragment(): string
    {
        return $this->fragment;
    }

    /**
     * @return UserInfo
     */
    public function userInfo(): UserInfo
    {
        return $this->userInfo;
    }

    /**
     * @return string
     */
    public function authority(): string
    {
        return sprintf("%s%s%s",
            $this->userInfo->info() ? sprintf("%s@", $this->userInfo->info()) : "",
            $this->host,
            ($this->port !== 80 && $this->port !== 443) ? sprintf(":%s", $this->port) : ""
        );
    }

    /**
     * @return string
     */
    public function target(): string
    {
        return sprintf("%s%s%s%s%s",
            sprintf("%s://", $this->scheme->string()),
            $this->authority(),
            sprintf("/%s", ltrim($this->path, "/")),
            $this->query->queryString() ? sprintf("?%s", $this->query->queryString()) : "",
            $this->fragment ? sprintf("#%s", $this->fragment) : ""
        );
    }
}
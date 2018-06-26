<?php

namespace net\http;

class Request extends Message
{
    /**
     * @var Uri
     */
    protected $uri;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $methods = [
        'HEAD'    => 'HEAD',
        'GET'     => 'GET',
        'POST'    => 'POST',
        'PUT'     => 'PUT',
        'DELETE'  => 'DELETE',
        'CONNECT' => 'CONNECT',
        'OPTIONS' => 'OPTIONS',
        'TRACE'   => 'TRACE',
    ];

    /**
     * Request constructor.
     * @param Protocol $protocol
     * @param Header $header
     * @param Body $body
     * @param Uri $uri
     * @param string $method
     */
    public function __construct(Protocol $protocol, Header $header, Body $body, Uri $uri, string $method)
    {
        $this->uri = $uri;
        $method = strtoupper($method);
        if (!in_array($method, $this->methods, true)) {
            throw new \InvalidArgumentException("invalid method");
        }
        $this->method = $method;
        parent::__construct($protocol, $header, $body);
    }

    /**
     * @return Uri
     */
    public function uri(): Uri
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }
}
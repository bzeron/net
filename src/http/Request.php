<?php

namespace net\http;

/**
 * Class Request
 * @package net\http
 */
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
     * Request constructor.
     * @param Protocol $protocol
     * @param Header $header
     * @param Body $body
     * @param Uri $uri
     * @param string $method
     */
    public function __construct(
        Protocol $protocol,
        Header $header,
        Body $body = null,
        Uri $uri,
        $method
    )
    {
        $this->uri = $uri;
        $this->method = $method;
        parent::__construct($protocol, $header, $body);
    }

    /**
     * @return Uri
     */
    public function Uri()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function Method()
    {
        return $this->method;
    }


    /**
     * @return string
     */
    public function Send()
    {
        $socket = fsockopen($this->uri->Host(), $this->uri->Port(), $errno, $errstr, 30);
        if (!$socket) {
            throw new \RuntimeException($errstr, $errno);
        }
        $request = sprintf("%s %s HTTP/%s\r\nHost: %s\r\nConnection: Close\r\n\r\n%s",
            $this->method,
            $this->uri->Path(),
            $this->protocol->Version(),
            $this->uri->Host(),
            $this->uri->Query()->QueryString()
        );
        fwrite($socket, $request);
        $buffer = "";
        while (!feof($socket)) {
            $buffer .= fgets($socket, 128);
        }
        fclose($socket);
        return $buffer;
    }
}
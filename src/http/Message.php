<?php

namespace net\http;

/**
 * Class Message
 * @package net\http
 */
class Message
{
    /**
     * @var Protocol
     */
    protected $protocol;

    /**
     * @var Header
     */
    protected $header;

    /**
     * @var Body
     */
    protected $body;

    /**
     * Message constructor.
     * @param Protocol $protocol
     * @param Header $header
     * @param Body $body
     */
    public function __construct(Protocol $protocol, Header $header, Body $body = null)
    {
        $this->protocol = $protocol;
        $this->header = $header;
        $this->body = $body;
    }

    /**
     * @return Protocol
     */
    public function Protocol()
    {
        return $this->protocol;
    }

    /**
     * @return Header
     */
    public function Header()
    {
        return $this->header;
    }

    /**
     * @return Body
     */
    public function Body()
    {
        return $this->body;
    }
}
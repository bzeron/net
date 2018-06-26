<?php

namespace net\http;

/**
 * Class Protocol
 * @package net\http
 */
class Protocol
{

    /**
     * @var array
     */
    protected $versions = [
        "1.0", "1.1", "2.0", "cli",
    ];

    /**
     * @var string
     */
    protected $currentVersion = "1.1";

    /**
     * Protocol constructor.
     * @param string $version
     */
    public function __construct($version = "1.1")
    {
        if (in_array($version, $this->versions, true)) {
            $this->currentVersion = $version;
        } else {
            throw new \InvalidArgumentException("无效的协议版本号");
        }
    }

    /**
     * @return string
     */
    public function Version()
    {
        return $this->currentVersion;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s", $this->currentVersion);
    }
}
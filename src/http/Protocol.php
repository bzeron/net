<?php

namespace net\http;

class Protocol
{

    /**
     * @var array
     */
    protected $versions = [
        "1.0", "1.1", "2.0"
    ];

    /**
     * @var string
     */
    protected $currentVersion = "1.1";

    /**
     * Protocol constructor.
     * @param string $version
     */
    public function __construct(string $version)
    {
        if (!in_array($version, $this->versions, true)) {
            throw new \InvalidArgumentException("invalid scheme version");
        }
        $this->currentVersion = $version;
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return $this->currentVersion;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->currentVersion;
    }
}
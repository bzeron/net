<?php

namespace net\http;

/**
 * Class Scheme
 * @package net\http
 */
class Scheme
{
    /**
     * @var array
     */
    protected $schemes = [
        'https', 'http', "cli"
    ];

    /**
     * @var string
     */
    protected $currentScheme = "http";

    /**
     * Scheme constructor.
     * @param string $scheme
     */
    public function __construct($scheme = "http")
    {
        if (in_array($scheme, $this->schemes, true)) {
            $this->currentScheme = $scheme;
        } else {
            throw new \InvalidArgumentException("无效的策略");
        }
    }

    /**
     * @return string
     */
    public function String()
    {
        return $this->currentScheme;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s", $this->currentScheme);
    }

}
<?php

namespace net\http;

class Scheme
{
    /**
     * @var array
     */
    protected $schemes = [
        'https', 'http'
    ];

    /**
     * @var string
     */
    protected $currentScheme = "http";

    /**
     * Scheme constructor.
     * @param string $scheme
     */
    public function __construct(string $scheme)
    {
        if (!in_array($scheme, $this->schemes, true)) {
            throw new \InvalidArgumentException("invalid scheme");
        }
        $this->currentScheme = $scheme;
    }

    /**
     * @return string
     */
    public function string(): string
    {
        return $this->currentScheme;
    }
}
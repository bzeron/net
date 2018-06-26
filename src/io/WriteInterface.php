<?php

namespace net\io;

/**
 * Interface WriteInterface
 * @package net\io
 */
interface WriteInterface
{
    /**
     * @param string $string
     * @return int
     */
    public function Write($string);
}
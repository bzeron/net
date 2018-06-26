<?php

namespace net\io;

/**
 * Interface ReadInterface
 * @package net\io
 */
interface ReadInterface
{
    /**
     * @param int $length
     * @return string
     */
    public function Read($length);

}
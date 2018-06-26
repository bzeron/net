<?php

namespace net\io;

interface WriteInterface
{
    /**
     * @param string $string
     * @return int
     */
    public function write(string $string): int;
}
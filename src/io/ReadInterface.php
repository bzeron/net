<?php

namespace net\io;

interface ReadInterface
{

    /**
     * @param int $length
     * @return string
     */
    public function read(int $length): string;

}
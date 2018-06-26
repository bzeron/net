<?php

namespace net\io;

/**
 * Interface SeekInterface
 * @package net\io
 */
interface SeekInterface
{
    /**
     * @param int $offset
     * @param int $whence
     */
    public function Seek($offset, $whence = SEEK_SET);

    /**
     * @return int
     */
    public function Tell();

    /**
     *
     */
    public function Rewind();

    /**
     * @return bool
     */
    public function Eof();

}
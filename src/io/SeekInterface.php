<?php

namespace net\io;

interface SeekInterface
{
    /**
     * @param int $offset
     * @param int|null $whence
     */
    public function seek(int $offset, ?int $whence = SEEK_SET): void;

    /**
     * @return int
     */
    public function tell(): int;

    /**
     * @return void
     */
    public function rewind(): void;

    /**
     * @return bool
     */
    public function eof(): bool;

}
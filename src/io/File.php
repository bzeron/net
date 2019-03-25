<?php

namespace net\io;

use net\collection\Collection;

class File implements CloseInterface, ReadInterface, WriteInterface, SeekInterface
{
    /**
     * @var resource
     */
    protected $resource = null;

    /**
     * @var bool
     */
    protected $seekable = false;

    /**
     * @var bool
     */
    protected $readable = false;

    /**
     * @var bool
     */
    protected $writable = false;

    /**
     * @var bool
     */
    protected $close = false;

    /**
     * @var Collection
     */
    protected $metas;

    /**
     * File constructor.
     * @param resource $resource
     */
    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException("invalid resource");
        }
        $this->resource = $resource;

        $this->metas = new Collection(stream_get_meta_data($this->resource));

        $this->seekable = $this->metas->get("seekable");
        $this->readable = in_array(
            $this->metas->get("mode"),
            ["r", "w+", "r+", "x+", "c+", "rb", "w+b", "r+b", "x+b", "c+b", "rt", "w+t", "r+t", "x+t", "c+t", "a+"]
        );
        $this->writable = in_array(
            $this->metas->get("mode"),
            ["w", "w+", "rw", "r+", "x+", "c+", "wb", "w+b", "r+b", "x+b", "c+b", "w+t", "r+t", "x+t", "c+t", "a", "a+"]
        );
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        if (!$this->close) {
            $this->close();
        }
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        $close = $this->resource ? fclose($this->resource) : true;

        $this->metas = [];
        $this->resource = null;
        $this->seekable = $this->readable = $this->writable = false;
        $this->close = true;
        return $close;
    }

    /**
     * @return void
     */
    private function checkClose(): void
    {
        if ($this->close) {
            throw new \RuntimeException("resource close");
        }
    }

    /**
     * @return void
     */
    private function checkRead(): void
    {
        if (!$this->readable) {
            throw new \RuntimeException("resource can't read");
        }
    }

    /**
     * @return void
     */
    private function checkWrite(): void
    {
        if (!$this->writable) {
            throw new \RuntimeException("resource can't write");
        }
    }

    /**
     * @return void
     */
    private function checkSeek(): void
    {
        if (!$this->seekable) {
            throw new \RuntimeException("resource can't seek");
        }
    }

    /**
     * @param int $length
     * @return string
     */
    public function read(int $length): string
    {
        $this->checkClose();
        $this->checkRead();
        $result = fread($this->resource, $length);
        if ($result === false) {
            throw new \RuntimeException("resource read fail");
        }
        return $result;
    }

    /**
     * @param string $string
     * @return int
     */
    public function write(string $string): int
    {
        $this->checkClose();
        $this->checkWrite();
        $result = fwrite($this->resource, $string);
        if ($result === false) {
            throw new \RuntimeException("resource write fail");
        }
        return $result;
    }

    /**
     * @param int $offset
     * @param int $whence
     * @return void
     */
    public function seek(int $offset, ?int $whence = SEEK_SET): void
    {
        $this->checkClose();
        $this->checkSeek();
        $result = fseek($this->resource, $offset, $whence);
        if ($result === -1) {
            throw new \RuntimeException("resource seek fail");
        }
    }

    /**
     * @return int
     */
    public function tell(): int
    {
        $this->checkClose();
        $result = ftell($this->resource);
        if ($result === false) {
            throw new \RuntimeException("resource seek fail");
        }
        return $result;
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * @return bool
     */
    public function eof(): bool
    {
        $this->checkClose();
        return feof($this->resource);
    }

    /**
     * @return int
     */
    public function size(): int
    {
        $this->checkClose();
        if ($this->metas->exists("uri")) {
            clearstatcache(true, $this->metas->get("uri"));
        }
        $result = fstat($this->resource);
        if (isset($result["size"])) {
            return $result["size"];
        }
        throw new \RuntimeException("unknown resource size");
    }

    /**
     * @param string $key
     * @return null|string
     */
    public function meta(string $key): ?string
    {
        return $this->metas->get($key);
    }

    /**
     * @return Collection
     */
    public function metas(): Collection
    {
        return $this->metas;
    }

    /**
     * @return string
     */
    public function content(): string
    {
        $this->checkClose();
        $this->checkRead();
        $this->rewind();
        $result = stream_get_contents($this->resource);
        if ($result === false) {
            throw new \RuntimeException("resource read fail");
        }
        return $result;
    }

    /**
     * @return File
     */
    public function detach(): File
    {
        $this->checkClose();
        $this->metas = [];
        $this->resource = null;
        $this->seekable = $this->readable = $this->writable = false;
        $this->close = true;
        return new static($this->resource);
    }

    /**
     * @param string $src
     * @return File
     */
    public function copy(string $src): File
    {
        if (file_exists($src)) {
            throw new \InvalidArgumentException(sprintf("%s exist", $src));
        }
        if (!is_writable(dirname($src))) {
            throw new \InvalidArgumentException(sprintf("%s can't write", $src));
        }
        if (!copy($this->meta("uri"), $src)) {
            throw new \RuntimeException("copy file fail");
        }
        return new static(fopen($src, 'w+'));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->content();
    }
}
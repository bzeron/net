<?php

namespace net\http;

use net\io\File;

class UploadedFile extends File
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var bool
     */
    protected $move = false;

    /**
     * UploadedFile constructor.
     * @param $temp
     * @param string $name
     * @param string $type
     * @param int $size
     */
    public function __construct($temp, string $name, string $type, int $size)
    {
        if (!is_uploaded_file($temp)) {
            throw new \InvalidArgumentException("invalid upload file");
        }
        $stream = fopen($temp, "r");
        if ($stream === false) {
            throw new \RuntimeException("upload file can't read");
        }
        parent::__construct($stream);
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->move = false;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * @param string $src
     * @param string $fileName
     * @return File
     */
    public function move(string $src, string $fileName): File
    {
        if ($this->move) {
            throw new \RuntimeException("upload file moved");
        }
        if (!is_dir($src)) {
            mkdir($src);
        }
        if (!is_writable(dirname($src))) {
            throw new \RuntimeException(sprintf("%s can't write", $src));
        }
        $new = sprintf("%s/%s", rtrim($src, "/"),
            $fileName ? sprintf("%s.%s", $fileName, pathinfo($this->name, PATHINFO_EXTENSION)) : $this->name
        );
        if (file_exists($new)) {
            throw new \RuntimeException(sprintf("%s exist", $new));
        }
        $old = $this->metas->get("uri");
        if (!move_uploaded_file($old, $new)) {
            throw new \RuntimeException(sprintf("upload file move fail"));
        }
        $this->move = true;
        return new File(fopen($new, 'w+'));
    }
}
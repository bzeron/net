<?php

namespace net\http;

use net\io\File;

/**
 * Class UploadedFile
 * @package net\http
 */
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
    protected $moved = false;

    /**
     * UploadedFile constructor.
     * @param resource $temp
     * @param string $name
     * @param string $type
     * @param int $size
     */
    public function __construct($temp, $name, $type, $size)
    {
        if (!is_uploaded_file($temp)) {
            throw new \RuntimeException(sprintf("无效的上传文件"));
        }
        $stream = fopen($temp, "r");
        if ($stream === false) {
            throw new \RuntimeException("打开上传文件出错");
        }
        parent::__construct($stream);
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->moved = false;
    }

    /**
     * @return string
     */
    public function Name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function Type()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function Size()
    {
        return $this->size;
    }

    /**
     * @param string $targetDir
     * @param string|null $fileName
     */
    public function Move($targetDir, $fileName = null)
    {
        if ($this->moved) {
            throw new \RuntimeException("文件已经被移走");
        }
        if (!is_dir($targetDir)) {
            mkdir($targetDir);
        }
        if (!is_writable(dirname($targetDir))) {
            throw new \InvalidArgumentException(sprintf("%s 不可写", $targetDir));
        }
        $newFile = sprintf(
            "%s/%s",
            rtrim($targetDir, "/"),
            empty($fileName) ? $this->name : sprintf("%s.%s", $fileName, pathinfo($this->name, PATHINFO_EXTENSION))
        );
        if (file_exists($newFile)) {
            throw new \InvalidArgumentException(sprintf("%s已经存在", $newFile));
        }
        $oldFile = $this->Info("uri");
        if (!move_uploaded_file($oldFile, $newFile)) {
            throw new \RuntimeException(sprintf("移动文件失败"));
        }
        $this->moved = true;
    }

}
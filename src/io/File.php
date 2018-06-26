<?php

namespace net\io;

/**
 * Class File
 * @package net\io
 */
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
    protected $isClosed = false;

    /**
     * @var array
     */
    protected $info = [];


    /**
     * @var array
     */
    private $canReadWrite = [
        "read"  => [
            "r", "w+", "r+", "x+", "c+", "rb", "w+b", "r+b", "x+b",
            "c+b", "rt", "w+t", "r+t", "x+t", "c+t", "a+"
        ],
        "write" => [
            "w", "w+", "rw", "r+", "x+", "c+", "wb", "w+b", "r+b",
            "x+b", "c+b", "w+t", "r+t", "x+t", "c+t", "a", "a+"
        ]
    ];

    /**
     * File constructor.
     * @param resource $resource
     */
    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException("无效的资源句柄。");
        }
        $this->resource = $resource;
        $this->info = stream_get_meta_data($this->resource);
        $this->seekable = $this->info["seekable"];
        $this->readable = in_array($this->info["mode"], $this->canReadWrite["read"]);
        $this->writable = in_array($this->info["mode"], $this->canReadWrite["write"]);
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->Close();
    }

    /**
     *
     */
    public function Close()
    {
        fclose($this->resource);
        $this->Detach();
        $this->isClosed = true;
    }

    /**
     * @param int $length
     * @return string
     */
    public function Read($length)
    {
        if ($this->isClosed) {
            throw new \RuntimeException("资源句柄已经关闭。");
        }
        if (!$this->readable) {
            throw new \RuntimeException(sprintf("资源句柄不可读。"));
        }
        $result = fread($this->resource, $length);
        if ($result === false) {
            throw new \RuntimeException(sprintf("读取资源句柄失败。"));
        } else {
            return $result;
        }
    }

    /**
     * @param string $string
     * @return int
     */
    public function Write($string)
    {
        if ($this->isClosed) {
            throw new \RuntimeException(sprintf("资源句柄已经关闭。"));
        }
        if (!$this->writable) {
            throw new \RuntimeException(sprintf("资源句柄不可写。"));
        }
        $result = fwrite($this->resource, $string);
        if ($result === false) {
            throw new \RuntimeException(sprintf("写入资源句柄失败。"));
        } else {
            return $result;
        }
    }

    /**
     * @param int $offset
     * @param int $whence
     */
    public function Seek($offset, $whence = SEEK_SET)
    {
        if ($this->isClosed) {
            throw new \RuntimeException(sprintf("资源句柄已经关闭。"));
        }
        if (!$this->seekable) {
            throw new \RuntimeException(sprintf("资源句柄不可定位。"));
        }
        $result = fseek($this->resource, $offset, $whence);
        if ($result === -1) {
            throw new \RuntimeException(sprintf("定位资源句柄失败。"));
        }
    }

    /**
     * @return int
     */
    public function Tell()
    {
        if ($this->isClosed) {
            throw new \RuntimeException(sprintf("资源句柄已经关闭。"));
        }
        $result = ftell($this->resource);
        if ($result === false) {
            throw new \RuntimeException(sprintf("获取资源句柄定位失败。"));
        } else {
            return $result;
        }
    }

    /**
     *
     */
    public function Rewind()
    {
        $this->Seek(0);
    }


    /**
     * @return bool
     */
    public function Eof()
    {
        if ($this->isClosed) {
            throw new \RuntimeException(sprintf("资源句柄已经关闭。"));
        }
        return feof($this->resource);
    }

    /**
     * @return int
     */
    public function Size()
    {
        if ($this->isClosed) {
            throw new \RuntimeException(sprintf("资源句柄已经关闭。"));
        }
        if (isset($this->meta["uri"])) {
            clearstatcache(true, $this->info["uri"]);
        }
        $result = fstat($this->resource);
        if (isset($result["size"])) {
            return $result["size"];
        } else {
            throw new \RuntimeException(sprintf("获取资源句柄大小失败。"));
        }
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function Info($key = null)
    {
        if (is_null($key)) {
            return $this->info;
        } else {
            return isset($this->info[$key]) ? $this->info[$key] : null;
        }
    }


    /**
     * @return string
     */
    public function Content()
    {
        if ($this->isClosed) {
            throw new \RuntimeException(sprintf("资源句柄已经关闭。"));
        }
        if (!$this->readable) {
            throw new \RuntimeException(sprintf("资源句柄不可读。"));
        }
        $this->Rewind();
        $result = stream_get_contents($this->resource);
        if ($result === false) {
            throw new \RuntimeException(sprintf("读取资源句柄失败。"));
        } else {
            return $result;
        }
    }

    /**
     * @return resource
     */
    public function Detach()
    {
        if ($this->isClosed) {
            throw new \RuntimeException(sprintf("资源句柄不可读。"));
        }
        $detach = $this->resource;
        $this->info = [];
        $this->resource = null;
        $this->isClosed = $this->seekable = $this->readable = $this->writable = $this->moved = false;
        return $detach;
    }

    /**
     * @param string $targetPath
     */
    public function CopyTo($targetPath)
    {
        if (file_exists($targetPath)) {
            throw new \InvalidArgumentException(sprintf("[ %s ] 已经存在", $targetPath));
        }
        if (!is_writable(dirname($targetPath))) {
            throw new \InvalidArgumentException(sprintf("[ %s ] 不可写", $targetPath));
        }
        if (!copy($this->Info("uri"), $targetPath)) {
            throw new \RuntimeException(sprintf('拷贝文件失败'));
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->Content();
    }


}
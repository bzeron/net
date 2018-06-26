<?php

namespace net\context;

use net\collection\Collection;
use net\http\Body;
use net\http\Cookie;
use net\http\Header;
use net\http\Protocol;
use net\http\Query;
use net\http\Response;
use net\http\Scheme;
use net\http\Server;
use net\http\ServerRequest;
use net\http\UploadedFile;
use net\http\UploadedFiles;
use net\http\Uri;
use net\http\UserInfo;

/**
 * Trait Environment
 * @package net\context
 */
trait Environment
{

    /**
     * @var Server
     */
    protected $server;

    /**
     * @var Cookie
     */
    protected $cookie;

    /**
     * Environment constructor.
     */
    public function __construct()
    {
        $this->server = new Server($_SERVER);
    }

    /**
     * @return ServerRequest
     */
    protected function createRequest()
    {
        return new ServerRequest(
            $this->createProtocol(),
            $this->createHeader(),
            $this->createBody(),
            $this->createUri(),
            $this->createMethod(),
            $this->createServer(),
            $this->createCookie(),
            $this->createForm(),
            $this->createUploadedFiles(),
            $this->createAttributes()
        );
    }

    /**
     * @return Response
     */
    protected function createResponse()
    {
        return new Response(
            200,
            $this->createProtocol(),
            new Header(),
            $this->createCookie(),
            $this->NewBody()
        );
    }

    /**
     * @return Protocol
     */
    protected function createProtocol()
    {
        $version = substr($this->server->get("SERVER_PROTOCOL", "HTTP/cli"), 5);
        return new Protocol($version);
    }

    /**
     * @return Header
     */
    protected function createHeader()
    {
        $headers = [];
        if (is_callable('getallheaders')) {
            $headers = getallheaders();
            $headers = array_change_key_case($headers, CASE_UPPER);
        }
        foreach ($this->server as $key => $value) {
            if ('HTTP_' == substr($key, 0, 5)) {
                $headers[str_replace('_', '-', substr($key, 5))] = $value;
            }
        }
        return new Header($headers);
    }

    /**
     * @return Body
     */
    protected function createBody()
    {
        $temp = fopen("php://input", "r+");
        if ($temp === false) {
            throw new \RuntimeException("打开资源失败，请检查权限和环境信息。");
        }
        return new Body($temp);
    }

    /**
     * @return Uri
     */
    protected function createUri()
    {
        $path = preg_replace('#^(.*)(\.php){1}#iU', '', $this->server->get("PHP_SELF"));
        $path = rtrim($path, '/');
        $path = empty($path) ? "/" : $path;
        return new Uri(
            new Scheme($this->server->get("REQUEST_SCHEME", "cli")),
            $this->server->get("HTTP_HOST"),
            $this->server->get("SERVER_PORT"),
            $path,
            Query::PaserQuery($this->server->get("QUERY_STRING")),
            "",
            new UserInfo($this->server->get("PHP_AUTH_USER"), $this->server->get("PHP_AUTH_PW"))
        );
    }

    /**
     * @return string
     */
    protected function createMethod()
    {
        return $this->server->get("REQUEST_METHOD");
    }

    /**
     * @return Server
     */
    protected function createServer()
    {
        return $this->server;
    }

    /**
     * @return Cookie
     */
    protected function createCookie()
    {
        if (is_null($this->cookie)) {
            $this->cookie = new Cookie($_COOKIE);
        }
        return $this->cookie;
    }

    /**
     * @return Collection
     */
    protected function createForm()
    {
        return new Collection(array_merge($_GET, $_POST));
    }

    /**
     * @return UploadedFiles
     */
    protected function createUploadedFiles()
    {
        $uploadedFiles = [];
        $files = $_FILES;
        foreach ($files as $key => $file) {
            if (is_array($file["name"])) {
                for ($i = 0; $i < count($file["name"]); $i++) {
                    if ($file["error"][$i] === 4) {
                        $uploadedFiles[$key][$i] = null;
                        continue;
                    }
                    if ($file["error"][$i] !== 0) {
                        throw new \RuntimeException("上传文件出错");
                    }
                    $uploadedFiles[$key][$i] = new UploadedFile(
                        $file['tmp_name'][$i],
                        $file['name'][$i],
                        $file['type'][$i],
                        $file['size'][$i]
                    );
                }
            } else {
                if ($file["error"] === 4) {
                    $uploadedFiles[$key] = null;
                    continue;
                }
                if ($file["error"] !== 0) {
                    throw new \RuntimeException("上传文件出错");
                }
                $uploadedFiles[$key][] = new UploadedFile(
                    $file['tmp_name'],
                    $file['name'],
                    $file['type'],
                    $file['size']
                );
            }
        }
        return new UploadedFiles($uploadedFiles);
    }

    /**
     * @return Collection
     */
    protected function createAttributes()
    {
        return new Collection();
    }


    /**
     * @param resource|string|null $resource
     * @return Body
     */
    public function NewBody($resource = null)
    {
        switch (gettype($resource)) {
            case "resource":
                return new Body($resource);
            case "string":
                $temp = fopen("php://temp", "r+");
                if ($temp === false) {
                    throw new \RuntimeException("创建临时资源失败，请检查权限和环境信息。");
                }
                fwrite($temp, $resource);
                rewind($temp);
                return new Body($temp);
            case "NULL":
                // 临时文件
                $temp = fopen("php://temp", "r+");
                if ($temp === false) {
                    throw new \RuntimeException("创建临时资源失败，请检查权限和环境信息。");
                }
                return new Body($temp);
            default:
                throw new \InvalidArgumentException("无效的资源句柄。");
        }
    }
}
<?php

namespace net\context;

use net\http\Response;
use net\http\ServerRequest;

/**
 * Class Context
 * @package net\context
 */
class Context
{
    use Environment;
    /**
     * @var ServerRequest
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @return $this
     */
    public function CreateByEnvironment()
    {
        $this->request = $this->createRequest();
        $this->response = $this->createResponse();
        return $this;
    }

    /**
     * @return ServerRequest
     */
    public function Request()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function Response()
    {
        return $this->response;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $expires
     * @param string $domain
     * @param bool $hostonly
     * @param string $path
     * @param bool $secure
     * @param bool $httponly
     * @return $this
     */
    public function SetCookie($key, $value, $expires = 0, $domain = "", $hostonly = false, $path = "/", $secure = false, $httponly = false)
    {
        $this->response->Cookie()->SetCookie($key, $value, $expires, $domain, $hostonly, $path, $secure, $httponly);
        return $this;
    }

    /**
     * @param string $string
     * @param int $status
     * @return int
     */
    public function Write($string, $status = 200)
    {
        $this->response->SetCode($status);
        return $this->response->Body()->Write($string);
    }

    /**
     * @param mixed $data
     * @param int $status
     * @param int $encodingOptions
     */
    public function WriteJson($data, $status = 200, $encodingOptions = JSON_UNESCAPED_UNICODE)
    {
        $json = json_encode($data, $encodingOptions);
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }
        $this->response->Body()->Write($json);
        $this->response->Header()->set('Content-Type', 'application/json;charset=utf-8');
        $this->response->SetCode($status);
    }

    /**
     * @param string $key
     * @param string|array $value
     */
    public function WriteHeader($key, $value)
    {
        $this->response->Header()->set($key, $value);
    }

    /**
     * @param string $url
     * @param int $status
     * @return $this
     */
    public function Redirect($url, $status = 301)
    {
        if ($status < 300 && $status > 308) {
            throw new \InvalidArgumentException('无效的Http状态码');
        }
        $this->response->Header()->set('Location', (string)$url);
        $this->response->SetCode($status);
        return $this;
    }
}
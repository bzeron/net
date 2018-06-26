<?php

namespace net\context;

use net\http\Response;
use net\http\ServerRequest;

class Context
{
    /**
     * @var ServerRequest
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;


    /**
     * Context constructor.
     * @param ServerRequest $request
     * @param Response $response
     */
    public function __construct(ServerRequest $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return ServerRequest
     */
    public function request(): ServerRequest
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function response(): Response
    {
        return $this->response;
    }

    /**
     * @param int $status
     */
    public function statusCode(int $status)
    {
        $this->response->SetCode($status);
    }

    /**
     * @param string $key
     * @param string|array $value
     * @return $this
     */
    public function writeHeader(string $key, $value): Context
    {
        $this->response->Header()->setHeader($key, $value);
        return $this;
    }

    /**
     * @param string $string
     */
    public function writeString(string $string)
    {
        $this->response->Body()->Write($string);
    }

    /**
     * @param mixed $data
     * @param int $encodingOptions
     */
    public function writeJsonString($data, $encodingOptions = JSON_UNESCAPED_UNICODE)
    {
        $json = json_encode($data, $encodingOptions);
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }
        $this->response->Header()->setHeader('Content-Type', 'application/json;charset=utf-8');
        $this->response->Body()->Write($json);
    }

    /**
     * @param int $status
     * @param string $url
     */
    public function redirect(int $status, string $url)
    {
        if ($status < 300 && $status > 308) {
            throw new \InvalidArgumentException('invalid http status code');
        }
        $this->response->Header()->setHeader('Location', (string)$url);
        $this->response->SetCode($status);
    }
}
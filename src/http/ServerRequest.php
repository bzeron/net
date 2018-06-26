<?php

namespace net\http;

use net\collection\Collection;


class ServerRequest extends Request
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
     * @var Collection
     */
    protected $form;

    /**
     * @var UploadedFiles
     */
    protected $uploadedFiles;

    /**
     * @var Collection
     */
    protected $attributes;


    /**
     * ServerRequest constructor.
     * @param Protocol $protocol
     * @param Header $header
     * @param Body $body
     * @param Uri $uri
     * @param string $method
     * @param Server $server
     * @param Cookie $cookie
     * @param Collection $form
     * @param UploadedFiles $uploadedFiles
     * @param Collection $attributes
     */
    public function __construct(Protocol $protocol, Header $header, Body $body, Uri $uri, string $method, Server $server, Cookie $cookie, Collection $form, UploadedFiles $uploadedFiles, Collection $attributes)
    {
        $this->server = $server;
        $this->cookie = $cookie;
        $this->form = $form;
        $this->uploadedFiles = $uploadedFiles;
        $this->attributes = $attributes;
        parent::__construct($protocol, $header, $body, $uri, $method);
    }

    /**
     * @return Server
     */
    public function server(): Server
    {
        return $this->server;
    }

    /**
     * @return Cookie
     */
    public function cookie(): Cookie
    {
        return $this->cookie;
    }

    /**
     * @return Collection
     */
    public function form(): Collection
    {
        return $this->form;
    }

    /**
     * @return UploadedFiles
     */
    public function uploadedFiles(): UploadedFiles
    {
        return $this->uploadedFiles;
    }

    /**
     * @return Collection
     */
    public function attributes(): Collection
    {
        return $this->attributes;
    }
}
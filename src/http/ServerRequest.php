<?php

namespace net\http;

use net\collection\Collection;

/**
 * Class ServerRequest
 * @package net\http
 */
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
     * @var bool
     */
    protected $parseBody = false;

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
    public function __construct(
        Protocol $protocol,
        Header $header,
        Body $body,
        Uri $uri,
        $method,
        Server $server,
        Cookie $cookie,
        Collection $form,
        UploadedFiles $uploadedFiles,
        Collection $attributes
    )
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
    public function Server()
    {
        return $this->server;
    }

    /**
     * @return Cookie
     */
    public function Cookie()
    {
        return $this->cookie;
    }

    /**
     * @return Collection
     */
    public function Form()
    {
        if (!$this->parseBody) {
            $this->parseBody();
        }
        return $this->form;
    }

    /**
     * @return UploadedFiles
     */
    public function UploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * @return Collection
     */
    public function Attributes()
    {
        return $this->attributes;
    }

    /**
     * parseBody when body is json or xml
     * and merge in Form
     */
    private function parseBody()
    {
        $input = $this->body->Content();
        switch (strtolower($this->header->HeaderLine("CONTENT-TYPE"))) {
            case 'application/json':
                $result = json_decode($input, true);
                if (is_array($result)) {
                    foreach ($result as $key => $value) {
                        $this->form->set($key, $value);
                    }
                }
                break;
            case 'application/xml':
                $backup = libxml_disable_entity_loader(true);
                $backup_errors = libxml_use_internal_errors(true);
                $result = (array)simplexml_load_string($input);
                libxml_disable_entity_loader($backup);
                libxml_clear_errors();
                libxml_use_internal_errors($backup_errors);
                foreach ($result as $key => $value) {
                    $this->form->set($key, $value);
                }
                break;
            case 'text/xml':
                $backup = libxml_disable_entity_loader(true);
                $backup_errors = libxml_use_internal_errors(true);
                $result = (array)simplexml_load_string($input);
                libxml_disable_entity_loader($backup);
                libxml_clear_errors();
                libxml_use_internal_errors($backup_errors);
                foreach ($result as $key => $value) {
                    $this->form->set($key, $value);
                }
                break;
        }
        $this->parseBody = true;
    }
}
<?php

namespace net\http;

class Response extends Message
{
    /**
     * @var int
     */
    protected $chunkSize = 1024;

    /**
     * @var int
     */
    protected $status = 200;

    /**
     * @var Cookie
     */
    protected $cookie;

    /**
     * @var bool
     */
    private $coded = false;

    /**
     * @var array
     */
    protected $messages = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'Connection Closed Without Response',
        451 => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error',
    ];

    /**
     * Response constructor.
     * @param int $status
     * @param Protocol $protocol
     * @param Header $header
     * @param Cookie $cookie
     * @param Body $body
     */
    public function __construct(int $status, Protocol $protocol, Header $header, Cookie $cookie, Body $body)
    {
        if (!is_integer($status) || $status < 100 || $status > 599) {
            throw new \InvalidArgumentException('invalid http status code');
        }
        $this->status = $status;
        $this->cookie = $cookie;
        parent::__construct($protocol, $header, $body);
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->status;
    }

    /**
     * @param int $code
     * @return void
     */
    public function setCode(int $code): void
    {
        if ($this->coded) {
            throw new \RuntimeException("http status code already exist");
        }
        $this->status = $code;
        $this->coded = true;
    }

    /**
     * @return Cookie
     */
    public function cookie(): Cookie
    {
        return $this->cookie;
    }

    /**
     * @return void
     */
    public function Send(): void
    {
        $sendBufferSize = ob_get_length();
        $bodySize = $this->body->Size();
        // add content length
        $this->header->setHeader("Content-Length", $sendBufferSize + $bodySize);
        if (!headers_sent()) {
            // http status code
            header(sprintf('HTTP/%s %s %s', $this->protocol->version(), $this->status, $this->messages[$this->status]));
            // set cookie
            foreach ($this->cookie->cookies as $cookie) {
                setcookie($cookie->name, $cookie->value, $cookie->expire, $cookie->path, $cookie->domain, $cookie->secure, $cookie->httpOnly);
            }
            // set header
            foreach ($this->header->headers as $key => $value) {
                header(sprintf('%s: %s', ucwords($key), implode(', ', $value)), false);
            }
        }
        // write body
        $this->body->rewind();
        while (!$this->body->eof()) {
            echo $this->body->read($this->chunkSize);
            if (connection_status() != CONNECTION_NORMAL) {
                break;
            }
        }
        // finish request
        if (\function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }
}
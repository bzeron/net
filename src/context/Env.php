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


class Env
{
    /**
     * cli or test mod method has no result
     * @return string
     */
    private static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * cli or test mod protocol has no result
     * @return Protocol
     */
    private static function protocol(): Protocol
    {
        $protocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
        $protocolVersion = $protocol ? substr($protocol, 5) : "1.1";
        return new Protocol($protocolVersion);
    }

    /**
     * @return Header
     */
    private static function header(): Header
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if ('HTTP_' == substr($key, 0, 5)) {
                $headers[str_replace('_', '-', substr($key, 5))] = $value;
            }
        }
        return new Header($headers);
    }

    /**
     * @param $resource
     * @return Body
     */
    private static function newBody($resource = null): Body
    {
        switch (gettype($resource)) {
            case "resource":
                return new Body($resource);
            case "string":
                $body = new Body(fopen("php://temp", "r+"));
                $body->write($resource);
                return $body;
            case "NULL":
                return new Body(fopen("php://temp", "r+"));
            default:
                throw new \InvalidArgumentException("resource error");
        }
    }

    /**
     * @return Server
     */
    private static function server(): Server
    {
        return new Server($_SERVER);
    }

    /**
     * @return Cookie
     */
    private static function cookie(): Cookie
    {
        return new Cookie($_COOKIE);
    }

    /**
     * @return Scheme
     */
    private static function scheme(): Scheme
    {
        return new Scheme($_SERVER['REQUEST_SCHEME'] ?? 'http');
    }

    /**
     * @return string
     */
    private static function host(): string
    {
        return $_SERVER['HTTP_HOST'] ?? "localhost";
    }

    /**
     * @return int
     */
    private static function port(): int
    {
        return $_SERVER['SERVER_PORT'] ?? 80;
    }

    /**
     * @return string
     */
    private static function path(): string
    {
        $path = rtrim(preg_replace('#^(.*)(\.php){1}#iU', '', $_SERVER['PHP_SELF']), '/');
        return $path ?? "/";
    }

    /**
     * @return Query
     */
    private static function query(): Query
    {
        return Query::parseQuery($_SERVER['QUERY_STRING'] ?? "");
    }

    /**
     * @return string
     */
    private static function fragment(): string
    {
        return "";
    }

    /**
     * @return UserInfo
     */
    private static function userInfo(): UserInfo
    {
        return new UserInfo($_SERVER['PHP_AUTH_USER'] ?? "", $_SERVER['PHP_AUTH_PW'] ?? "");
    }

    /**
     * @return Uri
     */
    private static function uri(): Uri
    {
        return new Uri(self::scheme(), self::host(), self::port(), self::path(), self::query(), self::fragment(), self::userInfo()
        );
    }

    /**
     * @return UploadedFiles
     */
    private static function uploadedFiles(): UploadedFiles
    {
        $files = [];
        foreach ($_FILES as $key => $file) {
            if (is_array($file["name"])) {
                for ($i = 0; $i < count($file["name"]); $i++) {
                    if (($code = $file["error"][$i]) !== 0) {
                        throw new \RuntimeException(sprintf("upload file error[%d]", $code));
                    }
                    $files[$key][$i] = new UploadedFile($file['tmp_name'][$i], $file['name'][$i], $file['type'][$i], $file['size'][$i]);
                }
            } else {
                if (($code = $file["error"]) !== 0) {
                    throw new \RuntimeException(sprintf("upload file error[%d]", $code));
                }
                $files[$key][] = new UploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['size']);
            }
        }
        return new UploadedFiles($files);
    }

    /**
     * @return Collection
     */
    private static function form(): Collection
    {
        return new Collection(array_merge($_GET, $_POST));
    }

    /**
     * @return Collection
     */
    private static function attributes(): Collection
    {
        return new Collection();
    }

    /**
     * you should create new request
     * @return ServerRequest
     */
    public static function serverRequest()
    {
        return new ServerRequest(self::protocol(), self::header(), self::newBody(fopen("php://input", "r+")), self::uri(), self::method(), self::server(), self::cookie(), self::form(), self::uploadedFiles(), self::attributes());
    }

    /**
     * you should create new response
     * @return Response
     */
    public static function response()
    {
        return new Response(200, self::protocol(), new Header(), new Cookie(), self::newBody());
    }
}
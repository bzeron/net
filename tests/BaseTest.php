<?php

namespace tests;

use net\context\Context;
use net\http\Body;
use net\http\Header;
use net\http\Protocol;
use net\http\Query;
use net\http\Scheme;
use net\http\Uri;
use net\http\UserInfo;

/**
 * Class BaseTestCase
 * @package Tests
 */
class BaseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var App
     */
    public $app;

    /**
     * BaseTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->app = new App();
    }


    public function testCheckVersion()
    {
        $version = "1.0.0";
        $this->assertEquals($version, '1.0.0');
    }


    public function testServer()
    {
        $router = function (Context $context) {
            $context->statusCode(200);
            $context->writeString("hello context");
        };

        $this->app->run($router);

        $this->assertEquals($this->app->context->response()->body()->content(), 'hello context');

        $this->assertEquals($this->app->context->response()->getCode(), 200);
    }
}

<?php

namespace tests;


use net\context\Context;
use net\context\Env;


class App
{
    /**
     * @var Context
     */
    public $context;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->context = new Context(Env::serverRequest(), Env::response());
    }

    /**
     * need create a  router handler the request
     * @param $router
     */
    public function run($router)
    {
        $router($this->context);
    }
}
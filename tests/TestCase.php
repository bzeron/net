<?php

namespace tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Class BaseTestCase
 * @package Tests
 */
class TestCase extends BaseTestCase
{

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
}
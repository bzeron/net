<?php

namespace net\http;

use net\collection\Collection;

/**
 * Class Query
 * @package net\http
 */
class Query extends Collection
{
    /**
     * @param $queryString
     * @return static
     */
    public static function PaserQuery($queryString)
    {
        $query = [];
        parse_str($queryString, $query);
        return new static($query);
    }

    /**
     * @return string
     */
    public function QueryString()
    {
        return http_build_query($this->all());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->QueryString();
    }

}
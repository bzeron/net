<?php

namespace net\http;

use net\collection\Collection;

class Query extends Collection
{

    /**
     * @param string $queryString
     * @return Query
     */
    public static function parseQuery(string $queryString): Query
    {
        $query = [];
        parse_str($queryString, $query);
        return new static($query);
    }


    /**
     * @return string
     */
    public function queryString(): string
    {
        return http_build_query($this->all());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->queryString();
    }
}
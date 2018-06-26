<?php

namespace net\http;

/**
 * Class UserInfo
 * @package net\http
 */
class UserInfo
{
    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * UserInfo constructor.
     * @param string $user
     * @param string|null $password
     */
    public function __construct($user, $password = null)
    {
        $this->user = $user;
        $this->password = is_null($password) ? "" : $password;
    }

    /**
     * @return string
     */
    public function Info()
    {
        $password = "";
        if (!empty($this->password)) {
            $password = sprintf(":%s", $this->password);
        }
        return sprintf("%s%s", $this->user, $password);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->Info();
    }

}
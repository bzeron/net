<?php

namespace net\http;

class UserInfo
{
    /**
     * @var string
     */
    protected $user = "";

    /**
     * @var string
     */
    protected $password = "";

    /**
     * UserInfo constructor.
     * @param string $user
     * @param string $password
     */
    public function __construct(string $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function info(): string
    {
        return sprintf("%s%s", $this->user, $this->password ? sprintf(":%s", $this->password) : "");
    }
}
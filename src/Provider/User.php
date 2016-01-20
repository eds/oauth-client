<?php
/**
 * Created by PhpStorm.
 * User: cyoo
 * Date: 16/1/19
 * Time: 下午2:58
 */

namespace Jzyuchen\OAuthClient\Provider;


use Jzyuchen\OAuthClient\AbstractUser;

class User extends AbstractUser {

    /**
     * access token
     *
     * @var object
     */
    public $token;

    /**
     * 设置 access token。
     *
     * @param object $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

}
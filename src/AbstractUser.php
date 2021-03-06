<?php
/**
 * Created by PhpStorm.
 * User: cyoo
 * Date: 16/1/19
 * Time: 下午2:46
 */

namespace Jzyuchen\OAuthClient;


use ArrayAccess;

abstract class AbstractUser implements ArrayAccess
{
    /**
     * 唯一标识符
     * @var string
     */
    public $id;

    /**
     * 用户名/昵称
     * @var string
     */
    public $nickname;

    /**
     * 姓名
     * @var string
     */
    public $name;

    /**
     * 邮箱
     * @var string
     */
    public $email;

    /**
     * 头像
     * @var string
     */
    public $avatar;

    public $gender;

    /**
     * access token
     *
     * @var object
     */
    public $token;

    /**
     * 获取唯一标识符。
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 获取用户名/昵称。
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * 获取姓名。
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 获取邮箱。
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * 获取头像。
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * 获取性别。
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * 从提供者设置原始用户。
     *
     * @param array $user
     * @return $this
     */
    public function setRaw(array $user)
    {
        $this->user = $user;
        return $this;
    }

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

    /**
     * 将给定的数组映射为用户的属性。
     *
     * @param array $attributes
     * @return $this
     */
    public function map(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->user);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->user[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->user[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->user[$offset]);
    }
}
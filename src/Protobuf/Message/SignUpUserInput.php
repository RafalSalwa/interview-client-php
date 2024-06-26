<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: rpc_signin.proto

namespace App\Protobuf\Message;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>intrvproto.SignUpUserInput</code>
 */
class SignUpUserInput extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string email = 1;</code>
     */
    protected $email = '';
    /**
     * Generated from protobuf field <code>string password = 2;</code>
     */
    protected $password = '';
    /**
     * Generated from protobuf field <code>string passwordConfirm = 3;</code>
     */
    protected $passwordConfirm = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $email
     *     @type string $password
     *     @type string $passwordConfirm
     * }
     */
    public function __construct($data = NULL) {
        \App\Protobuf\GPBMetadata\RpcSignin::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string email = 1;</code>
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Generated from protobuf field <code>string email = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setEmail($var)
    {
        GPBUtil::checkString($var, True);
        $this->email = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string password = 2;</code>
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Generated from protobuf field <code>string password = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setPassword($var)
    {
        GPBUtil::checkString($var, True);
        $this->password = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string passwordConfirm = 3;</code>
     * @return string
     */
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * Generated from protobuf field <code>string passwordConfirm = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setPasswordConfirm($var)
    {
        GPBUtil::checkString($var, True);
        $this->passwordConfirm = $var;

        return $this;
    }

}


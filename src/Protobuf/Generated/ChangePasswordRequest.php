<?php

# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: user.proto

namespace App\Protobuf\Generated;

use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>intrvproto.ChangePasswordRequest</code>
 */
class ChangePasswordRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int64 Id = 1;</code>
     */
    protected $Id = 0;
    /**
     * Generated from protobuf field <code>string Password = 2;</code>
     */
    protected $Password = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $Id
     *     @type string $Password
     * }
     */
    public function __construct($data = null)
    {
        \App\Protobuf\Generated\GPBMetadata\User::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int64 Id = 1;</code>
     * @return int|string
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Generated from protobuf field <code>int64 Id = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkInt64($var);
        $this->Id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string Password = 2;</code>
     * @return string
     */
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * Generated from protobuf field <code>string Password = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setPassword($var)
    {
        GPBUtil::checkString($var, true);
        $this->Password = $var;

        return $this;
    }
}

<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: user.proto

namespace App\grpc\Intrvproto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>intrvproto.UserResponse</code>
 */
class UserResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.intrvproto.User user = 1;</code>
     */
    protected $user = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \App\grpc\php\Intrvproto\User $user
     * }
     */
    public function __construct($data = NULL) {
        \App\grpc\GPBMetadata\User::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.intrvproto.User user = 1;</code>
     * @return \App\grpc\php\Intrvproto\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Generated from protobuf field <code>.intrvproto.User user = 1;</code>
     * @param \App\grpc\php\Intrvproto\User $var
     * @return $this
     */
    public function setUser($var)
    {
        GPBUtil::checkMessage($var, \App\grpc\php\Intrvproto\User::class);
        $this->user = $var;

        return $this;
    }

}


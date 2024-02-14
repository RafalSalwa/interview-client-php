<?php

declare(strict_types=1);

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: user.proto

namespace App\Protobuf\Generated;

use App\Protobuf\Generated\GPBMetadata\User;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\Message;

/**
 * Generated from protobuf message <code>intrvproto.GetUsersRequest</code>.
 */
class GetUsersRequest extends Message
{
    /**
     * Generated from protobuf field <code>repeated .intrvproto.GetUserRequest users = 1;</code>.
     */
    private $users;

    /**
     * Constructor.
     *
     * @param array $data {
     *                    Optional. Data for populating the Message object.
     *
     * @var \App\Protobuf\Generated\GetUserRequest[]|\Google\Protobuf\Internal\RepeatedField $users
     *                                                                                       }
     */
    public function __construct(array $data = null)
    {
        User::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .intrvproto.GetUserRequest users = 1;</code>.
     */
    public function getUsers(): \Google\Protobuf\Internal\RepeatedField
    {
        return $this->users;
    }

    /**
     * Generated from protobuf field <code>repeated .intrvproto.GetUserRequest users = 1;</code>.
     *
     * @param \App\Protobuf\Generated\GetUserRequest[]|\Google\Protobuf\Internal\RepeatedField $var
     *
     * @return $this
     */
    public function setUsers(array|\Google\Protobuf\Internal\RepeatedField $var)
    {
        $arr = GPBUtil::checkRepeatedField(
            $var,
            GPBType::MESSAGE,
            GetUserRequest::class
        );
        $this->users = $arr;

        return $this;
    }
}

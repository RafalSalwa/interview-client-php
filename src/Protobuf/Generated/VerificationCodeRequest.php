<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: rpc_signin.proto

namespace App\Protobuf\Generated;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>intrvproto.VerificationCodeRequest</code>
 */
class VerificationCodeRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string email = 1;</code>
     */
    protected $email = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $email
     * }
     */
    public function __construct($data = NULL) {
        \App\Protobuf\Generated\GPBMetadata\RpcSignin::initOnce();
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

}


<?php

# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: auth_service.proto

namespace App\Protobuf\Generated\GPBMetadata;

class AuthService
{
    public static $is_initialized = false;

    public static function initOnce()
    {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
            return;
        }
        \App\Protobuf\Generated\GPBMetadata\RpcSignin::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            "0af5020a12617574685f736572766963652e70726f746f120a696e74727670726f746f328a020a0b4175746853657276696365124b0a0a5369676e557055736572121b2e696e74727670726f746f2e5369676e557055736572496e7075741a1e2e696e74727670726f746f2e5369676e557055736572526573706f6e73652200124b0a0a5369676e496e55736572121b2e696e74727670726f746f2e5369676e496e55736572496e7075741a1e2e696e74727670726f746f2e5369676e496e55736572526573706f6e7365220012610a12476574566572696669636174696f6e4b657912232e696e74727670726f746f2e566572696669636174696f6e436f6465526571756573741a242e696e74727670726f746f2e566572696669636174696f6e436f6465526573706f6e73652200423eca02164170705c50726f746f6275665c47656e657261746564e202224170705c50726f746f6275665c47656e6572617465645c4750424d65746164617461620670726f746f33"
        ), true);

        static::$is_initialized = true;
    }
}

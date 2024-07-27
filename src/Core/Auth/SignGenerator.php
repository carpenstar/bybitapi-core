<?php

namespace Carpenstar\ByBitAPI\Core\Auth;

class SignGenerator
{
    public const ENCRYPT_METHOD = 'sha256';

    public static function make(Credentials $credentials, string $params, int $timestamp, int $recvWindow)
    {
        return hash_hmac(self::ENCRYPT_METHOD, "{$timestamp}{$credentials->getApiKey()}{$recvWindow}{$params}", $credentials->getSecret());
    }
}

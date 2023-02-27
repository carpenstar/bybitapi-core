<?php
namespace Carpenstar\ByBitAPI\Core\Auth;

class SignGenerator
{
    public static function make(string $apiKey, string $secretKey, string $params, int $timestamp, int $recvWindow)
    {
        return hash_hmac('sha256', $timestamp . $apiKey . $recvWindow . $params, $secretKey);
    }
}
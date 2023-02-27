<?php
namespace Carpenstar\ByBitAPI\Core\Auth;

class Credentials
{
    private static string $host;

    private static string $apiKey;

    private static string $secret;

    public static function setHost(string $host): void
    {
        static::$host = $host;
    }

    public static function getHost(): string
    {
        return static::$host;
    }

    public static function setApiKey(string $apiKey): void
    {
        static::$apiKey = $apiKey;
    }

    public static function getApiKey(): string
    {
        return static::$apiKey;
    }

    public static function setSecret(string $secret): void
    {
        static::$secret = $secret;
    }

    public static function getSecret(): string
    {
        return static::$secret;
    }
}
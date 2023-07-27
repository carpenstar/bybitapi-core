<?php
namespace Carpenstar\ByBitAPI\Core\Helpers;

class StringHelper
{
    public static function clearInterval(string $interval): int
    {
        preg_match('#([0-9]+)#', $interval, $matches);
        return $matches[1] ?? $interval;
    }
}
<?php
namespace Carpenstar\ByBitAPI\Core\Helpers;

class DateTimeHelper
{
    public static function makeFromTimestamp(int $timestamp): \DateTime
    {
        return (new \DateTime())->setTimestamp(intdiv($timestamp, 1000));
    }

    public static function makeTimestampFromDateString(string $datetime): int
    {
        return (new \DateTime($datetime))->getTimestamp() * 1000;
    }

}
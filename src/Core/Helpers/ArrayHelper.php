<?php

namespace Carpenstar\ByBitAPI\Core\Helpers;

use Carpenstar\ByBitAPI\Core\Exceptions\SDKException;

class ArrayHelper
{
    public static function checkValueWithStack(string $value, array $stack): void
    {
        if (!in_array($value, $stack)) {
            throw new SDKException("Value {$value} should be one of " . implode(",", $stack));
        }
    }
}

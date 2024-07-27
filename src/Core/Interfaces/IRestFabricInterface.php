<?php

namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Auth\Credentials;

interface IRestFabricInterface
{
    public static function make(string $className, Credentials $credentials);
}

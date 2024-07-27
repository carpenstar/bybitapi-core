<?php

namespace Carpenstar\ByBitAPI\Core\Interfaces;

use Carpenstar\ByBitAPI\Core\Enums\EnumHttpMethods;

interface IPostEndpointInterface
{
    public const HTTP_METHOD = EnumHttpMethods::POST;
}

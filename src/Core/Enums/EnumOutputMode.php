<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumOutputMode
{
    const DEFAULT_MODE = self::MODE_ENTITY;
    const MODE_ENTITY = 0;

    const MODE_ARRAY = 1;

    const MODE_JSON = 2;
}
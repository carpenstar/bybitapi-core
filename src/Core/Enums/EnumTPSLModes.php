<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumTPSLModes
{
    public const MODE_FULL = "Full";

    public const MODE_PARTIAL = "Partial";

    public const MODE_LIST = [
        self::MODE_FULL,
        self::MODE_PARTIAL
    ];
}
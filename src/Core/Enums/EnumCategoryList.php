<?php

namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumCategoryList
{
    public const CATEGORY_SPOT = 'spot';
    public const CATEGORY_PERPETUAL = 'linear';
    public const CATEGORY_INVERSE = 'inverse';
    public const CATEGORY_OPTION = 'option';

    public const CATEGORY_LIST = [
        self::CATEGORY_INVERSE,
        self::CATEGORY_OPTION,
        self::CATEGORY_PERPETUAL,
        self::CATEGORY_SPOT,
    ];
}

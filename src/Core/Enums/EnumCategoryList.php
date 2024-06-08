<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumCategoryList
{
    const CATEGORY_SPOT = 'spot';
    const CATEGORY_PERPETUAL = 'linear';
    const CATEGORY_INVERSE = 'inverse';
    const CATEGORY_OPTION = 'option';

    const CATEGORY_LIST = [
        self::CATEGORY_INVERSE,
        self::CATEGORY_OPTION,
        self::CATEGORY_PERPETUAL,
        self::CATEGORY_SPOT,
    ];
}
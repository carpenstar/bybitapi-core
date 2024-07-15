<?php
namespace Carpenstar\ByBitAPI\Core\Enums;

interface EnumIntervals
{
    /** 1 minute */
    const MINUTE1 = '1';

    /** 3 minutes */
    const MINUTE_3 = '3';

    /** 5 minutes */
    const MINUTE_5 = '5';

    /** 15 minutes */
    const MINUTE_15 = '15';

    /** 30 minutes */
    const MINUTE_30 = '30';

    /** 1 hour */
    const HOUR_1 = '60';

    /** 2 hours */
    const HOUR_2 = '120';

    /** 4 hours */
    const HOUR_4 = '240';

    /** 6 hours */
    const HOUR_6 = '360';

    /** 12 hours */
    const HOUR_12 = '720';

    /** 1 day */
    const DAY_1 = 'D';

    /** 1 week */
    const WEEK_1 = 'W';

    /** 1 month */
    const MONTH_1 = 'M';

    const INTERVALS_LIST = [
        self::MINUTE1,
        self::MINUTE_3,
        self::MINUTE_5,
        self::MINUTE_15,
        self::MINUTE_30,
        self::HOUR_1,
        self::HOUR_2,
        self::HOUR_4,
        self::HOUR_6,
        self::HOUR_12,
        self::DAY_1,
        self::WEEK_1,
        self::MONTH_1
    ];
}
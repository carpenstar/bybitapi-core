<?php

namespace Carpenstar\ByBitAPI\Core\Enums;

class EnumIntervals
{
    /** 1 minute */
    const MINUTE1 = '1m';

    /** 3 minutes */
    const MINUTE_3 = '3m';

    /** 5 minutes */
    const MINUTE_5 = '5m';

    /** 15 minutes */
    const MINUTE_15 = '15m';

    /** 30 minutes */
    const MINUTE_30 = '30m';

    /** 1 hour */
    const HOUR_1 = '1h';

    /** 2 hours */
    const HOUR_2 = '2h';

    /** 4 hours */
    const HOUR_4 = '4h';

    /** 6 hours */
    const HOUR_6 = '6h';

    /** 12 hours */
    const HOUR_12 = '12h';

    /** 1 day */
    const DAY_1 = '1d';

    /** 1 week */
    const WEEK_1 = '1w';

    /** 1 month */
    const MONTH_1 = '1m';

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
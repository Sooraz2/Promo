<?php

namespace Infrastructure;

use Language\Georgian\Logs as GeorgianLog;
use Language\Russian\Logs as RussianLog;
use Language\English\Logs as EnglishLog;
use Language\French\Logs as FrenchLog;

class LanguageLog
{
    /**
     * @var $English EnglishLog
     * */

    public static $English;

    /**
     * @var $French FrenchLog
     * */

    public static $French;

    /**
     * @var $Russian RussianLog
     * */

    public static $Russian;

    /**
     * @var $Georgian GeorgianLog
     * */

    public static $Georgian;

    public static function SetLog()
    {
        if (self::$English == null)
            self::$English = new EnglishLog();

        if (self::$French == null)
            self::$French = new FrenchLog();

        if (self::$Russian == null)
            self::$Russian = new RussianLog();

        if(self::$Georgian == null)
            self::$Georgian = new GeorgianLog();
    }

} 
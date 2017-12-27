<?php

namespace Infrastructure;

use Language\English\English;
use Language\French\French;
use Language\Georgian\Georgian;
use Language\Russian\Russian;

class MessageLog{

    /**
     *
     * @var $English English
     *
     * */

    public static $English;

    /**
     *
     * @var $English French
     *
     * */

    public static $French;

    /**
     *
     * @var $English Russian
     *
     * */
    public static $Russian;

    /**
     *
     * @var $English Georgian
     *
     * */
    public static $Georgian;

    public static function SetMessage(){
        if(self::$English == null)
            self::$English = new English();

        if(self::$French == null)
            self::$French = new French();

        if(self::$Russian == null)
            self::$Russian = new Russian();

        if(self::$Georgian == null)
            self::$Georgian = new Georgian();
    }

}
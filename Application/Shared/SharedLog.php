<?php

namespace Shared;

use Infrastructure\LanguageLog;
use Infrastructure\SessionVariables;
use WebInterface\Models\LoginUserLog;

class SharedLog
{

    public static function LoggedIn(){

        $userLoginLog = new LoginUserLog();

        $userLoginLog->UserID = $_SESSION[SessionVariables::$UserID];

        $userLoginLog->DateTime = GetCurrentDateTime();

        $userLoginLog->Action = replaceString(LanguageLog::$English->LoggedIn, array(":user"=>$_SESSION[SessionVariables::$Username]));

        $userLoginLog->ActionFR = replaceString(LanguageLog::$Russian->LoggedIn, array(":user"=>$_SESSION[SessionVariables::$Username]) );

        return $userLoginLog;

    }

    public static function LoggedOut(){

        $userLoginLog = new LoginUserLog();

        $userLoginLog->UserID = $_SESSION[SessionVariables::$UserID];

        $userLoginLog->DateTime = GetCurrentDateTime();

        $userLoginLog->Action = replaceString(LanguageLog::$English->LoggedOut, array(":user"=>$_SESSION[SessionVariables::$Username]));

        $userLoginLog->ActionFR = replaceString(LanguageLog::$Russian->LoggedOut, array(":user"=>$_SESSION[SessionVariables::$Username]) );

        return $userLoginLog;

    }


}
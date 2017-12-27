<?php

namespace Admin;

use Infrastructure\LanguageLog;
use Infrastructure\SessionVariables;
use WebInterface\Models\LoginUserLog;

class AdminLog
{


    public static function EditedUser($loginUser){

        $userLoginLog = new LoginUserLog();

        $userLoginLog->UserID = $_SESSION[SessionVariables::$UserID];

        $userLoginLog->DateTime = GetCurrentDateTime();

        $userLoginLog->Action = replaceString(LanguageLog::$English->EditedUser,  array(":userlogin" => $loginUser->Username));

        $userLoginLog->ActionFR = replaceString(LanguageLog::$Russian->EditedUser,  array(":userlogin" => $loginUser->Username) );

        return $userLoginLog;

    }

    public static function SavedUser($loginUser){

        $userLoginLog = new LoginUserLog();

        $userLoginLog->UserID = $_SESSION[SessionVariables::$UserID];

        $userLoginLog->DateTime = GetCurrentDateTime();

        $userLoginLog->Action = replaceString(LanguageLog::$English->CreatedUser, array(":userlogin" => $loginUser->Username));

        $userLoginLog->ActionFR = replaceString(LanguageLog::$Russian->CreatedUser,  array(":userlogin" => $loginUser->Username));

        return $userLoginLog;

    }

    public static function DeletedUser($loginUser){

        $userLoginLog = new LoginUserLog();

        $userLoginLog->UserID = $_SESSION[SessionVariables::$UserID];

        $userLoginLog->DateTime = GetCurrentDateTime();

        $userLoginLog->Action = replaceString(LanguageLog::$English->DeletedUser, array(":userlogin" => $loginUser->Username));

        $userLoginLog->ActionFR = replaceString(LanguageLog::$Russian->DeletedUser,  array(":userlogin" => $loginUser->Username));

        return $userLoginLog;

    }

    public static function GrantedAccessToModerator($pageId){

        $userLoginLog = new LoginUserLog();

        $userLoginLog->UserID = $_SESSION[SessionVariables::$UserID];

        $userLoginLog->DateTime = GetCurrentDateTime();

        $userLoginLog->Action = replaceString(LanguageLog::$English->UserGrantedAccessToModeratorToPageID, array(":PageID" => $pageId));

        $userLoginLog->ActionFR = replaceString(LanguageLog::$Russian->UserGrantedAccessToModeratorToPageID,  array(":PageID" => $pageId));

        return $userLoginLog;

    }


    public static function RevokeAccessToModerator($pageId){

        $userLoginLog = new LoginUserLog();

        $userLoginLog->UserID = $_SESSION[SessionVariables::$UserID];

        $userLoginLog->DateTime = GetCurrentDateTime();

        $userLoginLog->Action = replaceString(LanguageLog::$English->UserRevokeAccessToModeratorToPageID, array(":PageID" => $pageId));

        $userLoginLog->ActionFR = replaceString(LanguageLog::$Russian->UserRevokeAccessToModeratorToPageID,  array(":PageID" => $pageId));

        return $userLoginLog;

    }




}
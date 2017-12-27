<?php
/**
 * Created by PhpStorm.
 * User: Anup
 * Date: 12/22/2015
 * Time: 11:10 AM
 */

namespace Infrastructure;

use Libraries\Validation\ConfirmationMessage;

class ConfirmationDisplay
{
    public static function SetConfirmation(ConfirmationMessage $confirmation)
    {
        if (is_array($confirmation->Message))
            $_SESSION[SessionVariables::$ConfirmationMessage] = implode("<br />", $confirmation->Message);
        else
            $_SESSION[SessionVariables::$ConfirmationMessage] = $confirmation->Message;

        $_SESSION[SessionVariables::$ConfirmationMessageType] = $confirmation->MessageType;
    }
}
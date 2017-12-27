<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/1/2016
 * Time: 2:45 PM
 */

namespace Admin;

use Infrastructure\ConfirmationDisplay;
use Libraries\Validation\ConfirmationMessage;
class AdminConfirmation
{
    public static function UpdatedUser($status){

        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $status ? $confirmationMessage->language->SuccessfullyUpdatedUser : $confirmationMessage->language->FailedUpdatingUser;

        $confirmationMessage->MessageType = $status ? "Success" : "Failed";

        ConfirmationDisplay::SetConfirmation($confirmationMessage);

    }

    public static function SavedUser($status){

        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $status ? $confirmationMessage->language->SuccessfullySavedUser : $confirmationMessage->language->FailedSavingUser;

        $confirmationMessage->MessageType = $status ? "Success" : "Failed";

        ConfirmationDisplay::SetConfirmation($confirmationMessage);

    }


    public static function DeletedUser($status){

        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $status ? $confirmationMessage->language->SuccessfullyDeletedUser : $confirmationMessage->language->FailedDeletingUser;

        $confirmationMessage->MessageType = $status ? "Success" : "Failed";

        ConfirmationDisplay::SetConfirmation($confirmationMessage);

    }


    public static function CannotDeleteUser(){

        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message  = $confirmationMessage->language->CannotDeleteAdmin;

        $confirmationMessage->MessageType = "Failed";

        ConfirmationDisplay::SetConfirmation($confirmationMessage);

    }

    public static function UpdatedMenuControl($status){

        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message  = $status ? $confirmationMessage->language->SuccessfullySaved :  $confirmationMessage->language->FailedSaving;

        $confirmationMessage->MessageType =  $status ? "Success" : "Failed";

        ConfirmationDisplay::SetConfirmation($confirmationMessage);

    }


}
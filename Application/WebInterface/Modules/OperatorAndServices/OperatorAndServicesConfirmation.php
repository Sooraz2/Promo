<?php

namespace WebInterface\Modules\BroadcastingCalendar;

use Infrastructure\ConfirmationDisplay;
use Libraries\Validation\ConfirmationMessage;

class OperatorAndServicesConfirmation
{

    public static function Save($status)
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $status ? $confirmationMessage->language->SuccessfullyAddedPromotion :$confirmationMessage->language->FailedSavingPromotion ;

        $confirmationMessage->MessageType = $status ? $confirmationMessage->language->Success : $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function SaveUpdateDuplicateOccurred($dupString)
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = replaceString($confirmationMessage->language->DuplicateOccurred, array(":duplicateString" => $dupString));

        $confirmationMessage->MessageType = $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function Update($status)
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $status ? $confirmationMessage->language->SuccessfullyUpdatedBlacklist :$confirmationMessage->language->FailedUpdatingBlacklist ;

        $confirmationMessage->MessageType = $status ? $confirmationMessage->language->Success : $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function Delete($status)
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $status ? $confirmationMessage->language->SuccessfullyDeletedBroadcastingPromotion :$confirmationMessage->language->FailedDeletingBroadcastingPromotion ;

        $confirmationMessage->MessageType = $status ? $confirmationMessage->language->Success : $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }


    public static function DeleteAll($status)
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = is_int($status) && $status > 0 ? replaceString($confirmationMessage->language->MSISDNSuccessRemove, array("{qty}" => $status)) :$confirmationMessage->language->FailedDeletingAllBlacklist ;

        $confirmationMessage->MessageType = is_int($status) && $status > 0 ? $confirmationMessage->language->Success : $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function DeleteSelection($status)
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = is_int($status) && $status > 0 ? replaceString($confirmationMessage->language->MSISDNSuccessRemove, array("{qty}" => $status)) :$confirmationMessage->language->FailedDeletingSelectedBlacklist ;

        $confirmationMessage->MessageType = is_int($status) && $status > 0 ? $confirmationMessage->language->Success : $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }


    public static function FileUploadedToBlackListGeneral($rows)
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = replaceString($confirmationMessage->language->MSISDNSuccessUpload, array("{qty}" => $rows));

        $confirmationMessage->MessageType = $confirmationMessage->language->Success;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function SuccessfullyUploadedFileDuplicatesOccurredCSV()
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $confirmationMessage->language->SuccessfullyUploadedFileDuplicatesOccurredCSV;

        $confirmationMessage->MessageType = $confirmationMessage->language->Success;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function WrongFileFormat()
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $confirmationMessage->language->MSISDNWrongFileType;

        $confirmationMessage->MessageType = $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function CouldNotUploadFileUploadError()
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $confirmationMessage->language->CouldNotUploadFileUploadError;

        $confirmationMessage->MessageType = $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function CouldNotUploadWrongFileExtension()
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $confirmationMessage->language->MSISDNWrongFileType;

        $confirmationMessage->MessageType = $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function SuccessfullyUploadedBlacklist()
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $confirmationMessage->language->SuccessfullyUploadedBlacklist;

        $confirmationMessage->MessageType = $confirmationMessage->language->Success;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function CouldNotUploadBlacklistFileNotFound()
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $confirmationMessage->language->CouldNotUploadBlacklistFileNotFound;

        $confirmationMessage->MessageType = $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }

    public static function CouldNotUploadBlacklist()
    {
        $confirmationMessage = new ConfirmationMessage();

        $confirmationMessage->Message = $confirmationMessage->language->CouldNotUploadBlacklist;

        $confirmationMessage->MessageType = $confirmationMessage->language->Failed;

        ConfirmationDisplay::SetConfirmation($confirmationMessage);
    }



} 
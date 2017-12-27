<?php

namespace Language\French;


use Infrastructure\InterfaceVariables;

class Message
{
    /*new*/
    public $SuccessfullySavedUser = "L’utilisateur est enregistré";

    public $FailedSavingUser = "On ne peut pas enregistrer les données d’utilisateur";

    public $SuccessfullyUpdatedUser = "Les données d’utilisateur sont mise à jour";

    public $FailedUpdatingUser = "On ne peut pas mettre à jour les données d’utilisateur";

    public $SuccessfullyDeletedUser = "Les données d’utilisateur sont supprimé";

    public $FailedDeletingUser = "On ne peut pas supprimer les données d’utilisateur";

    public $OldPasswordDoesNotMatch = "Le mot de passe est incorrect";

    public $SuccessfullyChangedPassword = "Le mot de passe est rechanger";

    public $FailedChangingPassword = "On ne peut pas rechanger le mot de passe";

    public $ConfirmPasswordDoesNotMatch = "Les mots de passe ne sont pas identique";

    public $SuccessfullyAddedWhitelist = "La liste blanche est ajouté";

    public $SuccessfullyUpdatedWhitelist = "La liste blanche est renouvelé";

    public $SuccessfullyDeletedWhitelist = "La liste blanche est supprimer";

    public $SuccessfullyUploadedWhitelist = "La liste blanche est charger";

    public $CouldNotUploadWhitelistFileUploadError = "On ne réussit pas charger la liste blanche. On ne réussit pas charger le fichier.";

    public $CouldNotUploadWhitelistFileNotFound = "On ne réussit pas charger la liste blanc. On ne réussit pas trouver le fichier.";

    public $CouldNotUploadWhitelist = "On ne réussit pas charger la liste blanc.";

    public $SuccessfullyLaunchedTeaser = "Le teaseur est lancé";

    public $FailedLaunchingTeaser = "On ne réussit pas lancer le teaseur";

    public $FailedLaunchingTeaserRequiredBroadcastingDates = "On ne réussit pas lancé le teaseur car il faut choisir des dates de publication";

    public $SuccessfullySavedTeaser = "Le teaseur est enregistré";

    public $FailedSavingTeaser = "On ne réussit pas enregistrer le teaseur.";

    public $SuccessfullyUpdatedTeaser = "Le teaseur est renouvelé";

    public $FailedUpdatingTeaser = "On ne réussit pas renouveler le teaseur";

    public $SuccessfullyDeletedTeaser = "Le teaseur est supprimé";

    public $FailedDeletingTeaser = "On ne réussit pas supprimer le teaseur";

    public $SuccessfullyUpdated = "C’est renouvelé";

    public $FailedUpdating = "On ne réussit pas renouveler";

    public $SuccessfullySaved = "C’est enregistré";

    public $FailedSaving = "On ne réussit pas enregistrer";

    public $SuccessfullyDeleted = "C’est supprimé";

    public $CannotDeleteAsAssociated = "FF Could not delete the Record as it is associated with some Teaser";

    public $FailedDeleting = "On ne réussit pas supprimer";

    public $SuccessfullySavedOption = "L’option est enregistré";

    public $FailedSavingOption = "On ne réussit pas enregistrer l’option";

    public $SuccessfullyUpdatedOption = "L’option est renouvelé";

    public $FailedUpdatingOption = "On ne réussit pas renouveler l’option";

    public $SuccessfullyDeletedOption = "L’option est supprimé";

    public $FailedDeletingOption = "On ne réussit pas supprime l’option";

    public $SuccessfullyUpdatedTemplate = "La mode de message est renouvelé";

    public $FailedUpdatingTemplate = "On ne réussit pas renouveler la mode de message";

    public $SuccessfullyDeletedTemplate = "La mode de message est supprimé";

    public $FailedDeletingTemplate = "On ne réussit pas supprimer la mode de message";

    public $SuccessfullySavedTemplate = "La mode de message est enregistré";

    public $FailedSavingTemplate = "On ne réussit pas enregistrer la mode de message";

    public $SuccessfullyRestoredTeaser = "Le teaseur est restaurée";

    public $FailedRestoringTeaser = "On ne réussit pas restaurer le teaseur";

    public $FailedRestoringTeaserDefaultTeaserAlreadyExists = "FF Failed restoring teaser. :maxDefaultTeasers Default teasers already exist.";

    public $NoDuplicateWhitelistAllowedDuplicateFoundInWL = "On ne réussit pas enregistrer la liste blanc car des doubles sont interdites et il y a une double dans la liste blanche.";

    public $NoDuplicateWhitelistAllowedDuplicateFoundInBL = "On ne réussit pas enregistrer la liste blanc car des doubles sont interdites et il y a une double dans la liste noire";

    public $CannotDeleteAdmin = "Cannot Delete Admin";

    public $SuccessfullyStartedTeaser = "Le Teaseur est lancé";

    public $FailedStartingTeaser = "On ne réussit pas lancer le Teaseur";

    public $SuccessfullyPausedTeaser = "Le teaseur est suspendu";

    public $FailedPausingTeaser = "On ne réussit pas suspendre le teaseur";

    public $FailedtostarttheTeaserPleaseindicateBroadcastingdates = "On ne réussit pas lancer le teaseur. Programmer les dates de diffusion s'il vous plaît.";

    //change these to french
    public $SuccessfullyUpdatedTeaserPriority = "Priority Updated Successfully";

    public $FailedTeaserPriorityUpdate = "Failed to Update Teaser Priority";

    public $SuccessfullyUploadedFile = 'File Uploaded Successfully';

    public $CouldNotUploadFileUploadError = 'FF Failed Uploading File. Uploading Error';

    public $CouldNotUploadWrongFileExtension = 'FF Failed Uploading File. Please Upload CSV file';

    public $CouldNotUploadFileNotFound = 'FF Failed Uploading File. File not found';

    public $CouldNotUploadFile = 'FF Failed Uploading File';

    public $SuccessfullyDeletedSubscriberGroupMsisdn = "FF Successfully Deleted Subscriber Group Msisdn";

    public $FailedDeletingSubscriberGroupMsisdn = "FF Failed Deleting Subscriber Group Msisdn";

    public $SuccessfullyUploadedFileDuplicatesOccurred = 'FF File Uploaded Successfully. Duplicate MSISDNs were found in :duplicateGroup';

    public $DuplicateOccurred = "FF Failed saving. Duplicate found in :duplicateString";

    public $SuccessfullyUploadedFileDuplicatesOccurredCSV = 'FF Some number were ignored because there were duplicates during addition';

    public $SuccessfullySavedSubscriber = "FF Successfully saved subscriber";

    public $FailedSavingNameAlreadyExists = 'FF Failed Saving! Subscriber group already exists';

    public $SuccessfullyAddedSoapService = "Soap Service added successfully";

    public $SuccessfullyUpdatedSoapService = "Soap Service updated successfully";

    public $SuccessfullyDeletedSoapService = "Soap Service deleted successfully";

    public $NoDuplicateVariableNameAllowedInSoapService = "FF Duplicate variable name is not allowed";


    //Tariff Plan

    public $SuccessfullySavedTariffPlan="F Tariff Plan Saved Sucessfully";

    public $FailedSavingTariffPlan="F Failed Saving Tariff Plan";

    public $SuccessfullyDeletedTariffPlan="F Tariff Plan Deleted Sucessfully";

    public $FailedDeletingTariffPlan="F Failed Deleting Tariff Plan";

    public $SuccessfullyUpdatedTariffPlan="F Tariff Plan Updated Sucessfully";

    public $FailedUpdatingTariffPlan="F Failed Updating Tariff Plan";

    public $SuccessfullyDeletedAllTariffPlan="F All Tariff Plans Deleted Sucessfully";

    public $FailedDeletingAllTariffPlan="F Failed Deleting All Tariff Plans";

    public $SuccessfullyDeletedSelectedTariffPlan="F Selected Tariff Plans Deleted Sucessfully";

    public $FailedDeletingSelectedTariffPlan="F Failed Deleting Selected Tariff Plans";


    //Time Criteria

    public $FailedToRemoveCriterionOptionUsedForTeaserID = "F Failed to remove criterion option, it is used for Teaser ID: :teaserIDString";

    public $FailedToRemoveCriterionUsedOptionsUsedForTeaserID = "F Failed to remove following criterion option, are used for Teaser ID: :teaserIDString<br>:optionsString";



    //Blacklist


    public $NoDuplicateBlacklistAllowedDuplicateFoundInWL = "On ne réussit pas enregistrer la liste noire car des doubles sont interdites et il y a une double dans la liste blanche.";

    public $NoDuplicateBlacklistAllowedDuplicateFoundInBL = "On ne réussit pas enregistrer la liste noire car des doubles sont interdites et il y a une double dans la liste noire.";

    public $SuccessfullyAddedBlacklist = "La liste noire est ajouté";

    public $SuccessfullyUpdatedBlacklist = "La liste noire est renouvelé";

    public $SuccessfullyDeletedBlacklist = "La liste noire est supprimé";

    public $SuccessfullyUploadedBlacklist = "La liste noire est charger";

    public $CouldNotUploadBlacklistFileUploadError = "On ne réussit pas charger la liste noire. On ne réussit pas charger le fichier.";

    public $CouldNotUploadBlacklistFileNotFound = "On ne réussit pas charger la liste noire. On ne réussit pas trouver le fichier.";

    public $CouldNotUploadBlacklist = "On ne réussit pas charger la liste noire.";

    public $CouldNotUploadBlacklistWrongFileExtension = "On ne réussit pas charger la liste noire. Choisissez le fichier *.csv";

    public $FailedDeletingBlacklist = "Fr Failed Deleting BlackList";

    public $NoDuplicateBlacklistAllowedDuplicateFoundInSL = "Fr Failed saving blacklist. No duplicates allowed. Duplicate found in Subscriber List";

    public $BlacklistGroupAlreadyExist = "Fr Blacklist group already exist";

    public $SuccessfullySavedBlackList = "Fr Blacklist Saved Sucessfully";

    public $FailedSavingBlacklist = "Fr Failed Saving Blacklist";

    public $FailedUpdatingBlacklist = "Fr Failed Updating Blacklist";

    public $SuccessfullyDeletedAllBlacklist = "Fr All Blacklists Deleted Sucessfully";

    public $FailedDeletingAllBlacklist = "Fr Failed Deleting All Blacklists";

    public $SuccessfullyDeletedSelectedBlacklist = "Fr Selected Blacklists Deleted Sucessfully";

    public $FailedDeletingSelectedBlacklist = "Fr Failed Deleting Selected Blacklists";

    public $SuccessfullyUploadedActiveService = "FF Successfully uploaded Active Services";

    public $FailedUploadingActiveService = "FF Failed Uploading Active Services";

    public  $FailedUploadingActiveServiceFileNotFound = "FF Failed Uploading Active Services File Not Found";

    public  $FailedUploadingActiveServiceWrongFileFormat = "FF Failed Uploading Active Services Wrong File Format";

    public $SuccessfullyDeleteAllActiveServices = "FF SuccessFully Deleted All Active Services";

    public $FailedDeletingAllActiveServices = "FF Failed Deleting All Active Services";

    public $SuccessfullyDeleteSelectedActiveServices = "FF SuccessFully Deleted Selected Active Services";

    public $FailedDeletingSelectedActiveServices = "FF Failed Deleting Selected Active Services";

    public $Success = "Fr Success";

    public $Failed = "Fr Failed";

    public $SuccessfullyUploadedSubscriberRegion = "Fr Successfully uploaded Subscriber Region.";

    public $FailedUploadingSubscriberRegion = "Fr Failed Uploading Subscriber Region.";

    public $CouldNotUploadSubscriberRegionFileNotFound = "Fr Failed uploading Subscriber Region. Couldn't find file.";

    public $CouldNotUploadSubscriberRegionWrongFileExtension = "Fr Failed uploading Subscriber Region. Please select *.CSV (MS-DOS) file.";

    public $UploadErrorWrongFileFormat = "Fr Upload error, wrong file format";

}
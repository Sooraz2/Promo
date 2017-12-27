<?php

namespace Language\French;


class Logs
{
    //actions

    public $Add = "Ajouter";

    public $Delete = "Supprimer";

    public $Update = "Actualiser";

    public $Login = "Se connecter";

    public $Logout = "Se déconnecter";
    //logs
    public $LoggedOut = ":user déconnecté";

    public $LoggedIn = ":user connecté";

    public $CreatedUser = "Utilisateur créer :userlogin";

    public $DeletedUser = "Utilisateur supprimé :userlogin";

    public $EditedUser = "Utilisateur modifié :userlogin";

    public $createdTeaser = "Teaseur créé :teaserID";

    public $changedTeaserText = "Teaseur :teaserID le texte du \":oldText\" a été changé par \":newText\"";

    public $changedTeaserDefault = "Teaseur :teaserID le caractère du :oldTeaseType a été changé par :newTeaserType";

    public $changedTeaserCriteria = "Teaseur :teaserID le critère :criteriaType a été changé";

    public $changedTeaser = "Les données du teaseur changées :teaserID";

    public $movedTeaser = "Le teaseur déplacé :teaserID ";

    public $deletedTeaser = "Teaseur supprimé :teaserID ";

    public $restoreTeaser = "Teaseur restauré :teaserID ";

    public $launchedTeaser = "Teaseur lancé :teaserID";

    public $pausedTeaser = "Régler le teaseur :teaserID sur pause";

    public $clonedTeaser = "Le teaseur est copié:teaserID";

    public $generatedStatistics = "Générer des statistiques pour les dates :date de départ à :finishDate";

    public $generatedDetailedStatistics = "Générer des statistiques détaillées pour les dates :beginDate à :finishDate";

    public $changedMessagePattern = "Modèle des messages changé :oldMessageTest en :newMessageText";

    public $addedBlacklist = "Ajouté(s) :qtyOfNumbers numéro(s) à la liste noire";

    public $updatedBlacklist = "Actualisé(s) :qtyOfNumbers numéro(s) de la liste blanche";

    public $deletedBlacklist = "Supprimé(s) :qtyOfNumbers numéro(s) de la liste blanche";

    public $deletedAllBlacklist = "Supprimés (:qtyOfNumbers) numéro(s) de la liste noire";

    public $deletedAllWhitelist = "supprimés (:qtyOfNumbers) numéro(s) de la liste blanche";

    public $addedWhitelist = "Ajouté :qtyOfNumbers numéro(s) de la liste blanche";

    public $updatedWhitelist = "Actualisé(s) :qtyOfNumbers numéro(s) de la liste blanche";

    public $deletedWhitelist = "Supprimé(s) :qtyOfNumbers numéro(s) de la liste blanche";

    public $deletedAllArchiveTeaser = "Supprimés (:qtyOfNumbers) numéro(s) de liste de teaseur de l'archive";

    public $addedMessageTemplate = "ID du modèle des messages ajouté :messageTemplateID :newMessageTemplate, caractère du message :newMessageType, Langue :newLanguage ";

    public $updatedMessageTemplate = "ID du modèle des messages actualisé :messageTemplateID :oldMessageTemplate, caractère du message :oldMessageType, Langue :oldLanguage à :newMessageTemplate, caractère de message :newMessageType, Langue :newLanguage  ";

    public $deletedMessageTemplate = "ID du modèle des messages supprimé :messageTemplateID :oldMessageTemplate, caractère du message :oldMessageType, Langue :oldLanguage ";

    public $fileUploadedToBlacklist = ":filename du fichier rangé avec :qtyOfNumbers numéro(s) actualisés de la liste noire";

    public $fileUploadedToWhitelist = ":filename du fichier rangé avec :qtyOfNumbers numéro(s) actualisé de la liste blanche";

    public $addedAllocationCriteria = "Critère de rangement :criteriaName  ID :criteriaID a été ajouté";

    public $updatedAllocationCriteria = "Critère de rangement :criteriaName ID :criteriaID, :oldCriteriaValue a été actualisé en :newCriteriaValue";

    public $deletedAllocationCriteria = "Critère de rangement :criteriaName ID :criteriaID, :oldCriteriaValue a été supprimé";

    public $UpdatedBroadcastingDate = "Teaseur :teaserID la date de diffusion :BroadcastingDates a été actualisée";


    public $UpdatedTeaserStatus = "Teaseur :teaserID :TeaserStatus de :PageName page";


    public $addedInteractivityAcivationOption = "Option d’activation d’interactivité est créé :interactivityID";

    public $updatededInteractivityAcivationOption = "Option d’activation d’interactivité est actualisé :interactivityID";

    public $deletedInteractivityAcivationOption = "Option d’activation d’interactivité est supprimé :interactivityID";

    public $UserGrantedAccessToModeratorToPageID = "Accès autorisé à modérateur pour la page :PageID";

    public $UserRevokeAccessToModeratorToPageID = "Accès  révoqué à modérateur pour la page :PageID";

    //Use proper french translation
    public $UpdatedTeaserPriority = "Teaseur :teaserID la priorité :TeaserPriority de :PageName page";


    /*Blacklist delete selected*/

    public $deletedSelectedBlacklist = "Deleted selected (:qtyOfNumbers) number(s) from the blacklist";

    /*Archive delete selected*/
    public $deletedSelectedArchiveTeaser = "Deleted selected (:qtyOfNumbers) number(s) from the Archive Teaser List";

    public $fileUploadedToCriterionGroup = ":filename file with :qtyOfNumbers number(s) uploaded to :CriterionGroupName";

    /*SubscriberGroupMsisdn  delete */

    public $deletedSelectedSubscriberGroupMsisdn = "Deleted selected (:qtyOfNumbers) number(s) from :CriterionGroupName";

    public $deletedAllSubscriberGroupMsisdn = "Deleted all (:qtyOfNumbers) number(s) from :CriterionGroupName";

    public $termlessOption = "Teaser :teaserId Termless option is set";

    /* Time Criteria */
    public $deletedAllTimeCriteria = "Deleted all (:qtyOfNumbers) number(s) from the Time Criteria List";
    public $deletedSelectedTimeCriteria = "Deleted selected (:qtyOfNumbers) number(s) from the Time Criteria List";

    /* subBalance Criteria*/
    public $deletedAllSubscriberBalance = "Deleted all (:qtyOfNumbers) number(s) from the Subscriber Balance Criteria List";
    public $deletedSelectedSubscriberBalance = "Deleted selected (:qtyOfNumbers) number(s) from the Subscriber Balance Criteria List";

    /*Allocation Criteria->TariffPlan delete selected*/
    public $deletedAllTariffPlan = "Deleted all (:qtyOfNumbers) number(s) from the tariff plan";
    public $deletedSelectedTariffPlan = "Deleted selected (:qtyOfNumbers) number(s) from the tariff plan";

    /*Allocation Criteria->SubscriberClub delete selected*/
    public $deletedAllSubscriberClub = "Deleted all (:qtyOfNumbers) number(s) from the subscriber club";
    public $deletedSelectedSubscriberClub = "Deleted selected (:qtyOfNumbers) number(s) from the subscriber club";

    /*Allocation Criteria->ActiveServices delete selected*/
    public $deletedAllActiveServices = "Deleted all (:qtyOfNumbers) number(s) from the Active Services";
    public $deletedSelectedActiveServices = "Deleted selected (:qtyOfNumbers) number(s) from the Active Services";

    public $addedSoapService = "Soap Service Added Successfully";

    public $updatedSoapService = "Soap Service Updated Successfully";

    public $deletedSoapService = "Soap Service Deleted Successfully";
}
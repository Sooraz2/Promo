<?php

namespace Language\English;


class Logs
{
    public $LoggedOut = ":user logged out";

    public $LoggedIn = ":user logged in";

    public $CreatedUser = "Created User :userlogin";

    public $DeletedUser = "Removed User :userlogin";

    public $EditedUser = "Edited User :userlogin";

    public $createdTeaser = "Created the teaser :teaserID";

    public $changedTeaserText = "Teaser :teaserID Text From \":oldText\" was changed to \":newText\"";

    public $changedTeaserDefault = "Teaser :teaserID Type From :oldTeaserType was changed to :newTeaserType";

    public $changedTeaserCriteria = "Teaser :teaserID criteria :criteriaType was changed";

    public $changedTeaser = "Changed teasers data :teaserID";

    public $deletedTeaser = "Deleted the teaser :teaserID";

    public $clonedTeaser = "Cloned the teaser :teaserID";

    public $restoreTeaser = "Restored the teaser :teaserID";

    public $generatedStatistics = "Generated the statistics for the dates :beginDate to :finishDate";

    public $generatedDetailedStatistics = "Generated detailed statistics for the dates :beginDate to :finishDate";

    public $changedMessagePattern = "Changed the message pattern :oldMessageTest into :newMessageText";

    public $TeaserIdChanged = "Teaser ID changed from :fromID to :toID";

    /*BlackList General*/

    public $addedBlacklistGeneral = "Added :qtyOfNumbers number(s) to the blacklist general";

    public $updatedBlacklistGeneral = "Updated :qtyOfNumbers number(s) to the blacklist general";

    public $deletedBlacklistGeneral = " Deleted :qtyOfNumbers number(s) from the blacklist general";

    public $deletedAllBlacklistGeneral = "Deleted all (:qtyOfNumbers) number(s) from the blacklist general";

    public $fileUploadedToBlacklistGeneral = ":filename file with :qtyOfNumbers number(s) uploaded to blacklist general";

    public $deletedSelectedBlacklistGeneral = "Deleted selected (:qtyOfNumbers) number(s) from the blacklist general";


    /*BlackList Group*/

    public $addedBlacklistGroup = "Added blacklist group :groupName";

    public $deletedBlacklistGroup = "Deleted blacklist group :groupName";

    public $updatedBlacklistGroup = "Updated blacklist group :groupName";

    public $addedNumberBlacklistGroup = "Added :qtyOfNumbers number(s) to the blacklist group :groupName";

    public $deletedNumberBlacklistGroup = " Deleted :qtyOfNumbers number(s) from the blacklist group :groupName";

    public $updatedNumberBlacklistGroup = "Updated :qtyOfNumbers number(s) to the blacklist group :groupName";

    public $deletedAllNumberBlacklistGroup = "Deleted all (:qtyOfNumbers) number(s) from the blacklist group :groupName";

    public $fileUploadedToBlacklistGroup = ":filename file with :qtyOfNumbers number(s) uploaded to black list group :groupName";

    public $deletedSelectedNumberBlacklistGroup = "Deleted selected (:qtyOfNumbers) number(s) from the blacklist group :groupName";


    /*Subscriber List*/

    public $addedSubscriberList = "Added subscriber list :listName";

    public $deletedSubscriberList = "Deleted subscriber list :listName";

    public $updateSubscriberList = "Updated subscriber list :listName";

    public $addedNumberSubscriberList = "Added :qtyOfNumbers number(s) to the subscriber list :listName";

    public $updatedNumberSubscriberList = "Updated :qtyOfNumbers number(s) to the subscriber list :listName";

    public $deletedNumberSubscriberList = "Deleted :qtyOfNumbers number(s) from the subscriber list :listName";

    public $deletedAllNumberSubscriberList = "Deleted all (:qtyOfNumbers) number(s) from the subscriber list :listName";

    public $fileUploadedToSubscriberList = ":filename file with :qtyOfNumbers number(s) uploaded to subscriber list :listName";

    public $deletedSelectedSubscriberList = "Deleted selected (:qtyOfNumbers) number(s) from subscriber list :listName";


    public $deletedAllArchiveTeaser = "Deleted all (:qtyOfNumbers) number(s) from the Archive Teaser List";

    public $addedMessageTemplate = "Added Message Template  :newMessageTemplate , MessageType :newMessageType ,Language :newLanguage ";

    public $updatedMessageTemplate = "Updated Message Template ID :messageTemplateID :oldMessageTemplate , MessageType :oldMessageType ,Language :oldLanguage to :newMessageTemplate , MessageType :newMessageType, Language :newLanguage ";

    public $deletedMessageTemplate = "Deleted Message Template ID :messageTemplateID :oldMessageTemplate , MessageType :oldMessageType, Language :oldLanguage ";

    public $addedAllocationCriteria = "Allocation Criteria :criteriaName  ID :criteriaID has been added";

    public $deletedAllocationCriteria = "Allocation Criteria :criteriaName ID :criteriaID , :oldCriteriaValue has been deleted";

    public $UpdatedBroadcastingDate = "Teaser :teaserID broadcasting dates :BroadcastingDates has been updated";

    public $UpdatedBroadcastingDateMiniCalendar = "Teaser :teaserID broadcasting dates has been updated";

    public $UpdatedTeaserStatus = "Teaser :teaserID :TeaserStatus from :PageName page";

    public $addedInteractivityAcivationOption = "Created the Interactivity Activation Option :interactivityID";

    public $updatededInteractivityAcivationOption = "Updated the Interactivity Activation Option :interactivityID";

    public $deletedInteractivityAcivationOption = "Deleted the Interactivity Activation Option :interactivityID";

    public $UserGrantedAccessToModeratorToPageID = "Granted Access To Moderator To :PageID";

    public $UserRevokeAccessToModeratorToPageID = "Revoked Access To Moderator To :PageID";

    public $launchedTeaser = "Launched the teaser :teaserID";

    public $pausedTeaser = "Set the teaser :teaserID on pause";

    public $UpdatedTeaserPriority = "Teaser :teaserID's Priority is set to :TeaserPriority from :PageName Page";

    public $deletedSelectedArchiveTeaser = "Deleted selected (:qtyOfNumbers) number(s) from the Archive Teaser List";

    public $termlessOption = "Teaser :teaserID Termless option is set";

    public $failedLogin = ":datetime | :ip  |  :marker  |  :login  |  :password";


    /* Time Criteria */
    public $deletedAllTimeCriteria = "Deleted all (:qtyOfNumbers) number(s) from the Time Criteria List";

    public $deletedSelectedTimeCriteria = "Deleted selected (:qtyOfNumbers) number(s) from the Time Criteria List";

    /* subBalance Criteria*/
    public $deletedAllSubscriberBalance = "Deleted all (:qtyOfNumbers) number(s) from the Subscriber Balance Criteria List";

    public $deletedSelectedSubscriberBalance = "Deleted selected (:qtyOfNumbers) number(s) from the Subscriber Balance Criteria List";

    /* subBalance Criteria*/
    public $deletedAllARPU = "Deleted all (:qtyOfNumbers) number(s) from the ARPU Criteria List";

    public $deletedSelectedARPU = "Deleted selected (:qtyOfNumbers) number(s) from the ARPU Criteria List";

    /* subRegion Criteria*/
    public $deletedAllSubscribersRegion = "Deleted all (:qtyOfNumbers) number(s) from the Subscriber Region Criteria List";

    public $deletedSelectedSubscribersRegion = "Deleted selected (:qtyOfNumbers) number(s) from the Subscriber Region Criteria List";

    /*Allocation Criteria->TariffPlan delete selected*/
    public $deletedAllTariffPlan = "Deleted all (:qtyOfNumbers) number(s) from the tariff plan";

    public $deletedSelectedTariffPlan = "Deleted selected (:qtyOfNumbers) number(s) from the tariff plan";

    /*Allocation Criteria->SubscriberClub delete selected*/
    public $deletedAllSubscriberClub = "Deleted all (:qtyOfNumbers) number(s) from the subscriber club";

    public $deletedSelectedSubscriberClub = "Deleted selected (:qtyOfNumbers) number(s) from the subscriber club";

    /*Allocation Criteria->ActiveServices delete selected*/
    public $deletedAllActiveServices = "Deleted all (:qtyOfNumbers) number(s) from the Active Services";

    public $deletedSelectedActiveServices = "Deleted selected (:qtyOfNumbers) number(s) from the Active Services";

    /*Allocation Criteria MSISDN Prefix*/

    public $deletedAllMsisdnPrefix = "Deleted all (:qtyOfNumbers) option(s) from the MSISDN Prefix";

    public $deletedSelectedMsisdnPrefix = "Deleted selected (:qtyOfNumbers) option(s) from the MSISDN Prefix";

    public $deletedAllPricePlan = "Deleted all (:qtyOfNumbers) number(s) from the price plan";

    public $deletedSelectedPricePlan = "Deleted selected (:qtyOfNumbers) number(s) from the price plan";


    public $fileUploadedToCriterionGroup = ":filename file with :qtyOfNumbers number(s) uploaded to :CriterionGroupName";

    public $deletedAllSubscriberGroupMsisdn = "Deleted all (:qtyOfNumbers) number(s) from :CriterionGroupName";

    public $deletedSelectedSubscriberGroupMsisdn = "Deleted selected (:qtyOfNumbers) number(s) from :CriterionGroupName";

    public $addedSoapService = "Soap Service Added Successfully";

    public $updatedSoapService = "Soap Service Updated Successfully";

    public $deletedSoapService = "Soap Service Deleted Successfully";

    public  $addedActiveServices = ":User Added the Active Services";

    public  $updatededActiveServices = ":User Updated the Active Services  ID :activeServicesID";

    public  $deletedActiveServices = ":User Deleted the Active Services ID :activeServicesID";

    public  $addedLastRecharge = ":User Added LastRecharge  :lastRechargeID ";

    public  $updatedLastRecharge = ":User Updated LastRecharge :lastRechargeID";

    public  $deletedLastRecharge = ":User Deleted LastRecharge  :lastRechargeID ";


    /* Valid Till*/
    public  $deletedAllValidTill = "Deleted all (:qtyOfNumbers) number(s) from the Valid Till Criteria List";

    public  $deletedSelectedValidTill = "Deleted selected (:qtyOfNumbers) number(s) from the Valid Till Criteria List";

    public $createdNewServiceOptions = "Created new Service option \":name\" by user :userID";

    public $updatedServiceOptions = "Updated Service option to \":name\" from \":fromName\" by user :userID";

    public $deletedServiceOptions = "Deleted Service option :id by user :userID";



}
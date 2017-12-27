<?php

namespace Language\Georgian;


class Logs
{
    //actions
    public $Add = "Add";

    public $Delete = "Delete";

    public $Update = "Update";

    public $Login = "Login";

    public $Logout = "Logout";
    //logs
    public $LoggedOut = ":user logged out";

    public $LoggedIn = ":user logged in";

    public $CreatedUser = "Created User :userlogin";

    public $DeletedUser = "Removed User :userlogin";

    public $EditedUser = "Edited User :userlogin";

    public $createdTeaser = "Created a teaser :teaserID";

    public $changedTeaserText = "Teaser :teaserID Text From \":oldText\" was changed to \":newText\"";

    public $changedTeaserDefault = "Teaser :teaserID Type From :oldTeaseType was changed to :newTeaserType";

    public $changedTeaserCriteria = "Teaser :teaserID criteria :criteriaType was changed";

    public $changedTeaser = "Changed teasers data :teaserID";

    public $movedTeaser = "Moved the teaser :teaserID";

    public $deletedTeaser = "Deleted the teaser :teaserID";

    public $clonedTeaser = "Cloned the teaser :teaserID";

    public $restoreTeaser = "Restored the teaser :teaserID";

    public $generatedStatistics = "Generated the statistics for the dates :beginDate to :finishDate";

    public $generatedDetailedStatistics = "Generated detailed statistics for the dates :beginDate to :finishDate";

    public $changedMessagePattern = "Changed the message pattern :oldMessageTest into :newMessageText";

    public $addedBlacklist = "Added :qtyOfNumbers number(s) to the blacklist";

    public $updatedBlacklist = "Updated :qtyOfNumbers number(s) to the blacklist";

    public $deletedBlacklist = " Deleted :qtyOfNumbers number(s) from the blacklist";

    public $deletedAllBlacklist = "Deleted all (:qtyOfNumbers) number(s) from the blacklist";

    public $deletedAllWhitelist = "Deleted all (:qtyOfNumbers) number(s) from the whitelist";

    public $addedWhitelist = "Added :qtyOfNumbers number(s) to the whitelist";

    public $updatedWhitelist = " Updated :qtyOfNumbers number(s) to the whitelist";

    public $deletedWhitelist = "Deleted :qtyOfNumbers number(s) from the whitelist";

    public $deletedAllArchiveTeaser = "Deleted all (:qtyOfNumbers) number(s) from the Archive Teaser List";


    public $addedMessageTemplate = "Added Message Template ID :messageTemplateID :newMessageTemplate , MessageType :newMessageType ,Language :newLanguage ";

    public $updatedMessageTemplate = "Updated Message Template ID :messageTemplateID :oldMessageTemplate , MessageType :oldMessageType ,Language :oldLanguage to :newMessageTemplate , MessageType :newMessageType, Language :newLanguage ";

    public $deletedMessageTemplate = "Deleted Message Template ID :messageTemplateID :oldMessageTemplate , MessageType :oldMessageType, Language :oldLanguage ";


    public $fileUploadedToBlacklist = ":filename file with :qtyOfNumbers number(s) uploaded to black list";

    public $fileUploadedToWhitelist = ":filename file with :qtyOfNumbers number(s) uploaded to white list";


    public $addedAllocationCriteria = "Allocation Criteria :criteriaName  ID :criteriaID has been added";

    public $updatedAllocationCriteria = "Allocation Criteria :criteriaName ID :criteriaID, :oldCriteriaValue  has been updated to :newCriteriaValue";

    public $deletedAllocationCriteria = "Allocation Criteria :criteriaName ID :criteriaID , :oldCriteriaValue has been deleted";


    public $UpdatedBroadcastingDate = "Teaser :teaserID broadcasting dates :BroadcastingDates has been updated";

    public $UpdatedTeaserStatus = "Teaser :teaserID :TeaserStatus from :PageName page";

    public $addedInteractivityAcivationOption = "Created the Interactivity Activation Option :interactivityID";

    public $updatededInteractivityAcivationOption = "Updated the Interactivity Activation Option :interactivityID";

    public $deletedInteractivityAcivationOption = "Deleted the Interactivity Activation Option :interactivityID";

    public $UserGrantedAccessToModeratorToPageID = "Granted Access To Moderator To :PageID";

    public $UserRevokeAccessToModeratorToPageID = "Revoked Access To Moderator To :PageID";

    public $launchedTeaser = "Launched the teaser :teaserID";

    public $pausedTeaser = "Set the teaser :teaserID on pause";

    public $UpdatedTeaserPriority = "Teaser :teaserID's Priority is set to :TeaserPriority from :PageName Page";

    /*Blacklist delete selected*/

    public $deletedSelectedBlacklist = "Deleted selected (:qtyOfNumbers) number(s) from the blacklist";

    /*Archive delete selected*/
    public $deletedSelectedArchiveTeaser = "Deleted selected (:qtyOfNumbers) number(s) from the Archive Teaser List";


    //Criterion MSISDN Upload

    public $fileUploadedToCriterionGroup = ":filename file with :qtyOfNumbers number(s) uploaded to :CriterionGroupName";


    /*SubscriberGroupMsisdn  delete */

    public $deletedSelectedSubscriberGroupMsisdn = "Deleted selected (:qtyOfNumbers) number(s) from :CriterionGroupName";

    public $deletedAllSubscriberGroupMsisdn = "Deleted all (:qtyOfNumbers) number(s) from :CriterionGroupName";

    public $termlessOption = "Teaser :teaserID Termless option is set";

    public $addedSoapService = "Soap Service Added Successfully";

    public $updatedSoapService = "Soap Service Updated Successfully";

    public $deletedSoapService = "Soap Service Deleted Successfully";
}
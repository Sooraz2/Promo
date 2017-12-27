<?php

namespace Language\Russian;


class Logs
{
    public $LoggedOut = ":user Вышел";

    public $LoggedIn = ":user Вошел";

    public $CreatedUser = "Создан пользователь :userlogin";

    public $DeletedUser = "Удален пользователь :userlogin";

    public $EditedUser = "Изменен пользователь :userlogin";

    public $createdTeaser = "Создан тизер :teaserID";

    public $changedTeaserText = "Текст тизера :teaserID был изменен с \":oldText\" на \":newText\"";

    public $changedTeaserDefault = "Тип тизера :teaserID был изменен с :oldTeaseType на :newTeaserType";

    public $changedTeaserCriteria = "Критерий :criteriaType тизера :teaserID был изменен";

    public $changedTeaser = "Тизер :teaserID был изменен";

    public $deletedTeaser = "Тизер :teaserID был изменен";

    public $clonedTeaser = "Тизер :teaserID был клонирован";

    public $restoreTeaser = "Тизер :teaserID был востановлен";

    public $generatedStatistics = "Сгенерирована статистика за период :beginDate - :finishDate";

    public $generatedDetailedStatistics = "Сгенерирована детальная статистика за период :beginDate - :finishDate";

    public $changedMessagePattern = "Изменен шаблон сообщения :oldMessageTest на :newMessageText";

    /*BlackList General*/

    public $addedBlacklistGeneral = "Добавлено :qtyOfNumbers номер(ов) в Черный Список - Общий";

    public $updatedBlacklistGeneral = "Обновлено :qtyOfNumbers номер(ов) в Черном Списке - Общий";

    public $deletedBlacklistGeneral = " Удалено :qtyOfNumbers номер(ов) в Черном Списке - Общий";

    public $deletedAllBlacklistGeneral = "Удалены все (:qtyOfNumbers) номера(ов) в Черном Списке - Общий";

    public $fileUploadedToBlacklistGeneral = "Список :filename с количеством :qtyOfNumbers номеров добавлен в Черный Список - Общий";

    public $deletedSelectedBlacklistGeneral = "Удалено выбранное количество (:qtyOfNumbers) номеров в Черном Списке - Общий";


    /*BlackList Group*/

    public $addedBlacklistGroup = "Добавлена группа в Черный Список :groupName";

    public $deletedBlacklistGroup = "Удалена группа в Черном Списке :groupName";

    public $updatedBlacklistGroup = "Изменена группа в Черном Списке :groupName";

    public $addedNumberBlacklistGroup = "Добавлено :qtyOfNumbers номер(ов) в группу в Черном Списке :groupName";

    public $deletedNumberBlacklistGroup = "Удалено :qtyOfNumbers номер(ов) в группе в Черном Списке :groupName";

    public $updatedNumberBlacklistGroup = "Обновлено :qtyOfNumbers номер(ов) в группе в Черном Списке :groupName";

    public $deletedAllNumberBlacklistGroup = "Удалены все (:qtyOfNumbers) номер(ов) в группе в Черном Списке :groupName";

    public $fileUploadedToBlacklistGroup = "Список :filename с количеством :qtyOfNumbers номеров добавлен в группу в Черном Списке :groupName";

    public $deletedSelectedNumberBlacklistGroup = "Удалено выбранное количество (:qtyOfNumbers) номеров в группе в Черном Списке :groupName";


    /*Subscriber List*/

    public $addedSubscriberList = "Добавлена группа в Список Абонентов :listName";

    public $deletedSubscriberList = "Удалена группа в Списке Абонентов :listName";

    public $updateSubscriberList = "Изменена группа в Списке Абонентов :listName";

    public $addedNumberSubscriberList = "Добавлено :qtyOfNumbers номер(ов) в группу в Списке Абонентов :listName";

    public $updatedNumberSubscriberList = "Обновлено :qtyOfNumbers номер(ов) в группе в Списке Абонентов :listName";

    public $deletedNumberSubscriberList = "Удалено :qtyOfNumbers номер(ов) в группе в Списке Абонентов :listName";

    public $deletedAllNumberSubscriberList = "Удалены все (:qtyOfNumbers) номер(ов) в группе в Списке Абонентов :listName";

    public $fileUploadedToSubscriberList = "Список :filename с количеством :qtyOfNumbers номеров добавлен в группу в Списке Абонентов :listName";

    public $deletedSelectedSubscriberList = "Удалено выбранное количество (:qtyOfNumbers) номеров в группу в Списке Абонентов :listName";

    public $deletedAllArchiveTeaser = "Удалены все (:qtyOfNumbers) номер(ов) в Архиве Тизеров";

    public $addedMessageTemplate = "Добавлен шаблон сообщения :messageTemplateID :newMessageTemplate, тип сообщения :newMessageType, язык :newLanguage ";

    public $updatedMessageTemplate = "Обновлен шаблон сообщения ID :messageTemplateID :oldMessageTemplate, тип сообщения :oldMessageType, язык :oldLanguage на следующий :newMessageTemplate , тип сообщения :newMessageType, язык :newLanguage ";

    public $deletedMessageTemplate = "Удален шаблон сообщения ID :messageTemplateID :oldMessageTemplate, тип сообщения :oldMessageType, язык :oldLanguage ";

    public $addedAllocationCriteria = "Добавлен критерий :criteriaName  ID :criteriaID";

    public $updatedAllocationCriteria = "Обновлен критерий :criteriaName ID :criteriaID, с :oldCriteriaValue на :newCriteriaValue";

    public $deletedAllocationCriteria = "Удален критерий :criteriaName ID :criteriaID, :oldCriteriaValue";


    public $UpdatedBroadcastingDate = "Даты вещания :BroadcastingDates обновлены в тизере :teaserID";

    public $UpdatedBroadcastingDateMiniCalendar = "Даты вещания обновлены в тизере :teaserID";

    public $UpdatedTeaserStatus = "Изменен статус тизера :teaserID :TeaserStatus на странице :PageName";

    public $addedInteractivityAcivationOption = "Создана опция интерактивности :interactivityID";

    public $updatededInteractivityAcivationOption = "Обновлена опция интерактивности :interactivityID";

    public $deletedInteractivityAcivationOption = "Удалена опция интерактивности :interactivityID";

    public $UserGrantedAccessToModeratorToPageID = "Предоставлен доступ Модератору к странице :PageID";

    public $UserRevokeAccessToModeratorToPageID = "Ограничен доступ Модератора к странице :PageID";

    public $launchedTeaser = "Запущен тизер :teaserID";

    public $pausedTeaser = "Приостановлен тизер :teaserID";

    public $UpdatedTeaserPriority = "Задан приоритет :TeaserPriority тизеру :teaserID на странице :PageName";

    public $deletedSelectedArchiveTeaser = "Удалено выбранное количество (:qtyOfNumbers) номеров в Архиве Тизеров";

    public $termlessOption = "Бессрочная опция задана тизеру :teaserID";

    public $failedLogin = "Ошибка доступа :datetime | :ip  |  :marker  |  :login  |  :password";


    /* Time Criteria */
    public $deletedAllTimeCriteria = "Удалены все (:qtyOfNumbers) опции в критерии Времени";

    public $deletedSelectedTimeCriteria = "Удалено выбранное количество (:qtyOfNumbers) опций в критерии Времени";

    /* subBalance Criteria*/
    public $deletedAllSubscriberBalance = "Удалены все (:qtyOfNumbers) опции в критерии Баланса Абонента";

    public $deletedSelectedSubscriberBalance = "Удалено выбранное количество (:qtyOfNumbers) опций в критерии Баланса Абонента";


    /*Allocation Criteria MSISDN Prefix*/

    public $deletedAllMsisdnPrefix = "Удалены все (:qtyOfNumbers) опции в критерии Префикс MSISDN";

    public $deletedSelectedMsisdnPrefix = "Удалено выбранное количество (:qtyOfNumbers) опций в критерии Префикс MSISDN";


    /* subRegion Criteria*/
    public $deletedAllSubscribersRegion = "Удалены все (:qtyOfNumbers) опции в критерии Регион Абонента";

    public $deletedSelectedSubscribersRegion = "Удалено выбранное количество (:qtyOfNumbers) опций в критерии Регион Абонента";

    /*Allocation Criteria->TariffPlan delete selected*/
    public $deletedAllTariffPlan = "Удалены все (:qtyOfNumbers) опции в критерии Тарифный План";

    public $deletedSelectedTariffPlan = "Удалено выбранное количество (:qtyOfNumbers) опций в критерии Тарифный План";

    /*Allocation Criteria->SubscriberClub delete selected*/
    public $deletedAllSubscriberClub = "Удалены все (:qtyOfNumbers) опции в критерии Клуб Абонентов";

    public $deletedSelectedSubscriberClub = "Удалено выбранное количество (:qtyOfNumbers) опций в критерии Клуб Абонентов";

    /*Allocation Criteria->ActiveServices delete selected*/
    public $deletedAllActiveServices = "Удалены все (:qtyOfNumbers) опции в критерии Активные Сервисы";

    public $deletedSelectedActiveServices = "Удалено выбранное количество (:qtyOfNumbers) опций в критерии Активные Сервисы";

    public $addedSoapService = "Soap Service Added Successfully";

    public $updatedSoapService = "Soap Service Updated Successfully";

    public $deletedSoapService = "Soap Service Deleted Successfully";

    public  $addedActiveServices = "RU :User Added the Active Services";

    public  $updatededActiveServices = "RU :User Updated the Active Services  ID :activeServicesID";

    public  $deletedActiveServices = "RU :User Deleted the Active Services ID :activeServicesID";

    public  $addedLastRecharge = "RU :User Added LastRecharge  :lastRechargeID ";

    public  $updatedLastRecharge = "RU :User Updated LastRecharge :lastRechargeID";

    public  $deletedLastRecharge = "RU :User Deleted LastRecharge  :lastRechargeID ";

    /* Valid Till*/
    public  $deletedAllValidTill = "Deleted all (:qtyOfNumbers) number(s) from the Valid Till Criteria List";

    public  $deletedSelectedValidTill = "Deleted selected (:qtyOfNumbers) number(s) from the Valid Till Criteria List";

    public $createdNewServiceOptions = "RU Created new Service option \":name\" by user :userID";

    public $updatedServiceOptions = "RU Updated Service option to \":name\" from \":fromName\" by user :userID";

    public $deletedServiceOptions = "RU Deleted Service option :id by user :userID";

    public $deletedAllPricePlan = "Deleted all (:qtyOfNumbers) number(s) from the price plan";

    public $deletedSelectedPricePlan = "Deleted selected (:qtyOfNumbers) number(s) from the price plan";
    /* subBalance Criteria*/
    public $deletedAllARPU = "Deleted all (:qtyOfNumbers) number(s) from the ARPU Criteria List";

    public $deletedSelectedARPU = "Deleted selected (:qtyOfNumbers) number(s) from the ARPU Criteria List";

}
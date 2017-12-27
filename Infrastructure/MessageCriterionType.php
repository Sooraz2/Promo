<?php

namespace Infrastructure;

abstract class MessageCriterionType
{
    const TimeCriteria = 1;

    const SubBalance = 2;

    const ValidTill = 3;

    const SubRegion = 4;

    const MsisdnPrefix = 5;

    const TariffPlan = 6;

    const RoamingCriteria = 7;

    const USSDShortNumbers = 8;

    const OptionActivationCheck = 9;

    const Language = 10;

    const SubPoint = 11;

    const SubList = 12;

    const LastRecharge = 13;

    const PaidActions = 14;

    const BonusesBalance = 15;

    const ActiveServices = 16;

    const SubscriberClub = 17;

    const BlackListGroup = 18;

    const Roaming = 19;

    const Service = 20;

    const PricePlan = 21;

    const ARPU = 22;

}
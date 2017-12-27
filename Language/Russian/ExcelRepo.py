#!/usr/bin/python
# -*- coding: utf-8 -*-
import pymysql
import sys
import pprint


class ExportRepo:
    db = ""
    cursor = ""

    def __init__(self, hostName, userName, password, database, encoding):
        db = pymysql.connect(host=hostName, user=userName, passwd=password, db=database, charset=encoding)
        ExportRepo.cursor = db.cursor()

    def closeConnection(self):
        ExportRepo.db.close()
        ExportRepo.cursor.close()

    def getDetailedReport(self, dateFrom, dateTo, teaserID, region):
        concatSql = ""
        whereFromSS = "1=1"
        whereToSS = "1=1"
        whereTeaserID = "1=1"
        whereRegion = "1=1"
        whereFromSt = "1=1"
        whereToSt = "1=1"
        if dateFrom != "false":
            whereFromSS = "`datetime` >= '" + dateFrom + " 00:00:00' "
            whereFromSt = "`stamp` >= '" + dateFrom + " 00:00:00' "

        if dateTo != "false":
            whereToSS = "`datetime` <= '" + dateTo + " 23:59:59' "
            whereToSt = "`stamp` <= '" + dateTo + " 23:59:59' "

        if teaserID != "false":
            whereTeaserID = "`shank_id` = '" + teaserID + "' "

        if region != "false":
            arr = region.split(",")
            newarr = map(lambda x: "'" + x + "'", arr)
            inQuery = ", ".join(newarr)
            whereRegion = "`region` IN (" + inQuery + ")"

        condSessionArchive = "WHERE `shank_id` > 0 AND " + whereFromSS + " AND " + whereToSS + " AND " + whereTeaserID
        condStatisticArchive = "WHERE `shank_id` > 0 AND " + whereFromSt + " AND " + whereToSt + " AND " + whereTeaserID + " AND " + whereRegion

        sql = "SELECT DATE(`datetime`) AS `datetime`, subscriber,`shank_id` AS ID, SUM(`displays`) AS displays, SUM(`activations`) AS activations FROM ( SELECT `datetime`, `subscriber`, `shank_id`, '' AS displays, is_activated AS activations  FROM sessions_archive " + condSessionArchive + " UNION ALL SELECT `stamp` AS DATETIME, '' AS subscriber, `shank_id`, COUNT AS displays, '' AS activations FROM statistic_archive " + condStatisticArchive + ") a GROUP BY DATE(`datetime`), `shank_id`"

        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchall()
        return result

    def getAllDetails(self, dateFrom, dateTo, teaserID, region):
        concatSql = ""
        whereFromSS = "1=1"
        whereToSS = "1=1"
        whereTeaserID = "1=1"
        whereRegion = "1=1"
        whereFromSt = "1=1"
        whereToSt = "1=1"
        if dateFrom != "false":
            whereFromSS = "`datetime` >= '" + dateFrom + " 00:00:00' "
            whereFromSt = "`stamp` >= '" + dateFrom + " 00:00:00' "

        if dateTo != "false":
            whereToSS = "`datetime` <= '" + dateTo + " 23:59:59' "
            whereToSt = "`stamp` <= '" + dateTo + " 23:59:59' "

        if teaserID != "false":
            whereTeaserID = "`shank_id` = '" + teaserID + "' "

        if region != "false":
            arr = region.split(",")
            newarr = map(lambda x: "'" + x + "'", arr)
            inQuery = ", ".join(newarr)
            whereRegion = "`region` IN (" + inQuery + ")"

        condSessionArchive = "WHERE `shank_id` > 0 AND " + whereFromSS + " AND " + whereToSS + " AND " + whereTeaserID
        condStatisticArchive = "WHERE `shank_id` > 0 AND " + whereFromSt + " AND " + whereToSt + " AND " + whereTeaserID + " AND " + whereRegion

        sql = "SELECT DATE(`datetime`) AS `datetime`, `shank_id` AS ID, SUM(`displays`) AS displays, SUM(`activations`) AS activations FROM ( SELECT `datetime`, `shank_id`, '' AS displays, is_activated AS activations  FROM sessions_archive " + condSessionArchive + " UNION ALL SELECT `stamp` AS DATETIME, `shank_id`, COUNT AS displays, '' AS activations FROM statistic_archive " + condStatisticArchive + ") a GROUP BY DATE(`datetime`), `shank_id`"

        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchall()
        return result

    def TotalDisplays(self, dateFrom, dateTo, teaserID, region):
        concatSql = ""
        whereTeaserID = "1=1"
        whereRegion = "1=1"
        whereFromSt = "1=1"
        whereToSt = "1=1"
        if dateFrom != "false":
            whereFromSt = "`stamp` >= '" + dateFrom + " 00:00:00' "

        if dateTo != "false":
            whereToSt = "`stamp` <= '" + dateTo + " 23:59:59' "

        if teaserID != "false":
            whereTeaserID = "`shank_id` = '" + teaserID + "' "

        if region != "false":
            arr = region.split(",")
            newarr = map(lambda x: "'" + x + "'", arr)
            inQuery = ", ".join(newarr)
            whereRegion = "`region` IN (" + inQuery + ")"

        condStatisticArchive = "WHERE `shank_id` > 0 AND " + whereFromSt + " AND " + whereToSt + " AND " + whereTeaserID + " AND " + whereRegion

        sql = "SELECT SUM(COUNT) AS displays FROM statistic_archive " + condStatisticArchive
        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchone()
        return result

    def TotalActivations(self, dateFrom, dateTo, teaserID, region):
        concatSql = ""
        whereFromSS = "1=1"
        whereToSS = "1=1"
        whereTeaserID = "1=1"
        whereRegion = "1=1"
        if dateFrom != "false":
            whereFromSS = "`datetime` >= '" + dateFrom + " 00:00:00' "

        if dateTo != "false":
            whereToSS = "`datetime` <= '" + dateTo + " 23:59:59' "

        if teaserID != "false":
            whereTeaserID = "`shank_id` = '" + teaserID + "' "

        if region != "false":
            arr = region.split(",")
            newarr = map(lambda x: "'" + x + "'", arr)
            inQuery = ", ".join(newarr)
            whereRegion = "`region` IN (" + inQuery + ")"

        condSessionArchive = "WHERE `shank_id` > 0 AND " + whereFromSS + " AND " + whereToSS + " AND " + whereTeaserID

        sql = "SELECT SUM(`is_activated`) AS activations  FROM sessions_archive " + condSessionArchive
        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchone()
        return result

    def getBlackListGeneralReport(self):
        whereClause = " WHERE 1=1";

        sql = "SELECT `datetime_created`, " \
              "(SELECT `Username` FROM `login_user` WHERE BlackList.`created_by`=`login_user`.`ID`)AS Username," \
              "MSISDN " \
              " FROM black_list BlackList" + whereClause

        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchall()
        return result

    def getBlackListGroupReport(self, groupID):
        whereClause = " WHERE 1<>1";
        if groupID != "false":
            whereClause = " WHERE group_id= " + groupID

        sql = "SELECT `datetime_created`," \
              "(SELECT `Username` FROM `login_user` WHERE criterionSubscribersBlacklist.`created_by`=`login_user`.`ID`)AS Username," \
              "MSISDN " \
              "FROM criterion_subscribers_blacklist criterionSubscribersBlacklist" + whereClause

        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchall()
        return result

    def getSubscriberGroupMSISDN(self, groupID):
        whereClause = " WHERE 1<>1";
        if groupID != "false":
            whereClause = " WHERE group_id= " + groupID

        sql = "SELECT `datetime_created`," \
               "(SELECT `Username` FROM `login_user` WHERE CriterionSubscribers.`created_by`=`login_user`.`ID`)AS Username," \
               "MSISDN " \
               "FROM `criterion_subscribers` CriterionSubscribers" + whereClause

        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchall()
        return result

    def getActiveMsisdnListForIAT(self, teaser_id):
        whereClause = " WHERE 1<>1";

        if teaser_id != "false":
            whereClause = " WHERE shank_id= " + teaser_id

        sql = "SELECT subscriber FROM `sessions_archive` " + whereClause

        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchall()
        return result

    def getSummaryAndData(self):

        sql = "SELECT teaser_id, date_time,DATE_FORMAT(date_time,'%a'), id,`text`,displays,activations FROM test_statistics ORDER BY teaser_id"

        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchall()
        return result

    def getDetailsStatistics(self, TeaserIDArray, startdate, enddate, ishistory):

        sqlhourStart = ''
        sqlhourEnd = ''
        whereFromSS = '1=1'
        whereToSS = '1=1'
        whereFromSt = '1=1'
        whereToSt = '1=1'
        whereTeaserID = '1=1'
        history='1=1'

        if (startdate != "false" and enddate != "false" ):
            if (startdate == enddate):
                sqlhourStart = "SELECT  a1.* FROM hour INNER JOIN ( "
                sqlhourEnd = " )a1 ON a1.datetime=hour.Hour"

            whereFromSS = "`datetime` >= '" + startdate + " 00:00:00' "
            whereToSS = "`datetime` <= '" + enddate + " 23:59:59' "
            whereFromSt = "`stamp` >= '" + startdate + " 00:00:00' "
            whereToSt = "`stamp` <= '" + enddate + " 23:59:59' "

        if (TeaserIDArray != "false" and TeaserIDArray[0] != ""):
            whereTeaserID = "`shank_id` in (" + TeaserIDArray + ") "

        if(ishistory!='false'):
            history=' is_history=0 '

        condSessionArchive = "WHERE `shank_id` > 0 AND " + whereFromSS + " AND " + whereToSS + " AND " + whereTeaserID
        condStatisticArchive = "WHERE `shank_id` > 0 AND " + whereFromSt + " AND " + whereToSt + " AND " + whereTeaserID

        sql = ("SELECT table2.datetime,table2.Day,table2.ID,MM.text AS Text,table2.displays,table2.activations FROM messages_message MM INNER JOIN ( "+sqlhourStart + " SELECT  "
                               " datetime0 AS `datetime`,left(Day,3) AS  Day, "
                               " `shank_id`         AS ID, "
                               " (SELECT text from messages_message WHERE id=shank_id) AS Text, "
                               " ifnull(SUM(`displays`),0)    AS displays, "
                               " CASE "
                               " WHEN IFNULL(SUM(`activations`),0)=0 "
                               " THEN '-' "
                               " ELSE SUM(`activations`) "
                               " END                        AS activations "
                               " FROM (  "
                               " SELECT  "
                               " CASE  "
                                 " WHEN (' "  + startdate +  " ' !='false' && ' "  + enddate +  " ' !='false' && STR_TO_DATE(' "  + startdate +  " ', '%Y-%m-%d') = STR_TO_DATE(' "  + enddate +  " ','%Y-%m-%d')) "
                                 " THEN DATE_FORMAT(`datetime`, '%H:00') "
                                 " ELSE "   " DATE_FORMAT(`datetime`, '%Y-%m-%d') "
                               " END AS `datetime0`, "
                               " DAYNAME(`datetime`) AS Day, "
                               " `shank_id`, "
                               " 0 AS displays, "
                               " SUM(is_activated) AS activations "
                               " FROM sessions_archive  "  + condSessionArchive +  " GROUP BY shank_id, datetime0 "
                                " UNION ALL "
                              " SELECT CASE WHEN (' "  + startdate +  " ' !='false' && ' "  + enddate +  " '!='false' && STR_TO_DATE(' "  + startdate +  " ', '%Y-%m-%d') = STR_TO_DATE(' "  + enddate +  " ', '%Y-%m-%d')) "
                               " THEN DATE_FORMAT(`stamp`,'%H:00') "
                               " ELSE DATE_FORMAT(`stamp`, '%Y-%m-%d') "
                               " END                                       AS `datetime0`, "
                               " DAYNAME(`stamp`) AS Day, "
                               " `shank_id`, "
                               " SUM(`count`)   AS displays, "
                               " 0      AS activations "
                               " FROM statistic_archive  "  + condStatisticArchive +  " GROUP BY shank_id,datetime0) a "
                              " GROUP BY `datetime`, `shank_id`   "  + sqlhourEnd+" )table2 ON table2.ID=MM.id AND MM.is_perm_deleted!=1 AND"+history )+  " ORDER BY ID ASC,datetime ASC  "

        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchall()
        return result

    def getCustomerCareReport(self, msisdn, dateFrom, dateTo):

        concatSql = " WHERE `subscriber` = '"+msisdn+"'"

        if (dateFrom !="" and dateFrom !="false"):
            concatSql += " AND `datetime` >= '" + dateFrom + " 00:00:00' "


        if (dateTo != "" and dateTo != "false"):
            concatSql += " AND `datetime` <=  '" + dateTo + " 23:59:59' "
            dateTo += " 23:59:59"

        sql = "SELECT DATE_FORMAT(`datetime`, '%d.%m.%Y %H:%i:%s') as Date, `balance` as SubscriberBalance, carem_answer as UssdInput, teaser_answer as UssdTeaser, `status` as Status," \
              "`blacklist`," \
              "  CASE `blacklist`" \
              "  WHEN 0 THEN 'NO'" \
              "  WHEN 1 THEN CONCAT('YES / ', (SELECT DATE_FORMAT(`datetime_created`, '%d.%m.%Y %H:%i:%s') FROM `criterion_subscribers_blacklist` WHERE `msisdn`='"+msisdn+"'))" \
              "  WHEN 2 THEN CONCAT('YES / ', (SELECT DATE_FORMAT(`datetime_created`, '%d.%m.%Y %H:%i:%s') FROM `black_list` WHERE `msisdn`='"+msisdn+"'))" \
              "  END as BlacklistDate" \
              " FROM sessions_archive " +  concatSql + " ORDER BY `Date` DESC "

        ExportRepo.cursor.execute(sql)
        result = ExportRepo.cursor.fetchall()
        return result


		
    def getDetailStatisticsReport(self, teaser_id, MSISDN, StartDate, EndDate):

        WhereCondition = ''

        if ((StartDate != "0") and (EndDate != "0")):

            WhereCondition = " AND `datetime` >= '" + StartDate + " 00:00:00' AND `datetime` <= '" + EndDate + " 23:59:59'"

        elif (StartDate != "0"):
            WhereCondition = " AND `datetime` >= '" + StartDate + " 00:00:00'"

        elif (EndDate != "0"):

            WhereCondition = " AND `datetime` <= '" + EndDate + " 23:59:59'"

        whereTeaserID = ""

        if (teaser_id != "false" and teaser_id != ""):
            WhereCondition = WhereCondition + " AND  shank_id in ( " + teaser_id + " ) "
        # else:
        #
        # #WhereCondition = "AND `shank_id` IN (SELECT ID FROM messages_message WHERE (is_deleted=1 and is_perm_deleted !=1) OR (is_deleted=0 AND is_history=0))";
        Query = (
        "SELECT @row_number:=@row_number+1 as SNo,`datetime` stamp,`subscriber` subscriber,sessionID,`short_code` as ShortCode, `shank_id` TeaserID , `last_answer` LastAnswer, `last_input` LastInput "
        " FROM `sessions_archive`, (SELECT @row_number:=0) AS serial "
        " WHERE `subscriber`=" + MSISDN +  WhereCondition + " ORDER BY SNo ASC")


        #print Query

        # f = open('testsql.txt', 'w')
        # f.write(Query)
        # f.closed
        # sys.exit()


        ExportRepo.cursor.execute(Query)

        result = ExportRepo.cursor.fetchall()

        return result


    def getDayName(self,day,language):

        if language=="Russian":
         day = unicode('Дата', 'utf-8') if day=='Sun' else day
         day = unicode('Понедельник', 'utf-8') if day=='Mon' else day
         day = unicode('Вторник', 'utf-8') if day=='Tue' else day
         day = unicode('Среда', 'utf-8') if day=='Wed' else day
         day = unicode('Четверг', 'utf-8') if day=='Thu' else day
         day = unicode('Пятница', 'utf-8') if day=='Fri' else day
         day = unicode('Суббота', 'utf-8') if day=='Sat' else day


        return day



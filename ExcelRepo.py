#!/usr/bin/python
# -*- coding: utf-8 -*-
import pymysql
import sys
import datetime
from datetime import timedelta, date


class ExportRepo:
    db = ""
    cursor = ""
    db2 = ""
    cursor2 = ""
    cursor3 = ""

    def __init__(self, hostName, userName, password, database, encoding):
        db = pymysql.connect(host=hostName, user=userName, passwd=password, db=database, charset=encoding)

        ExportRepo.cursor = db.cursor(pymysql.cursors.DictCursor)

    def closeConnection(self):
        ExportRepo.db.close()
        ExportRepo.cursor.close()

    def GetCurrentDateTime(self):
        ExportRepo.cursor.execute("SELECT CURRENT_TIMESTAMP")

        result = ExportRepo.cursor.fetchall()

        result = result[0]

        return result[0]

    def daterange(self, start_date, end_date):
        for n in range(int((end_date - start_date).days) + 1):
            yield start_date + timedelta(n)



    def findallexport(self, dateFrom, dateTo, country=None, operator=None, service=None, promotion=None):

        sql = """
                 SELECT AllTable.*,IFNULL(Views,0) as Views1, CASE WHEN TotalCount = 1 THEN Activation ELSE ROUND( (TotalSumTable.TotalSum / TotalCount ) ) END AS Activation1,
                  ROUND((TotalSumTable.TotalSum/TotalCount)) AS Activation2, IFNULL(ROUND((((TotalSumTable.TotalSum/TotalCount) * 100) / Views), 2) ,0 ) AS ActivationPercent

                  FROM ( SELECT *,DAYNAME(`Date`) as DayName FROM

                     ((SELECT BD1.*, IFNULL(SUM(Inflow.`Inflow`),0) AS Activation, IFNULL(quantity,0) AS Views FROM

                      `operatorinflowoutflow` Inflow RIGHT JOIN (SELECT BD.`datefrom` AS `Date`, `country`.name AS Country, `operator`.name AS Operator, `service`.name AS Service, `text` AS PromotionText, BD.`promotion` AS Promotion, SUM(BPView.Views) AS quantity,BD.comments

                                FROM `broadcasting` BD JOIN `country` ON BD.`country` = country.id JOIN `operator` ON BD.`operator` = operator.id JOIN `service` ON BD.`service` = service.id /*JOIN `BalanceplusView` BPView ON TRIM(BPView.`PromotionText`) LIKE CONCAT(TRIM(BD.`text`),'%')*/

                                    JOIN `BalanceplusView` BPView ON TRIM(BPView.`PromotionText`) = TRIM(BD.`text`) AND BPView.`Date` = BD.`datefrom`

                                    WHERE """
        sql += " BD.`datefrom` >= '" + dateFrom + "' AND BD.`datefrom` <= '" + dateTo + "'";
        sql += " AND BPView.Date >= '" + dateFrom + "' AND BPView.Date <= '" + dateTo + "'";
        if promotion is not None:
             sql += " AND BD.promotion = '" + promotion + "'";

        if country is not None:
            sql += " AND country.`name` IN (" + country + ")";

        if operator is not None:
            sql += " AND operator.`name` IN (" + operator + ")";

        if service is not None:
            sql += " AND service.`name` IN (" + service + ")";


        sql+= """ GROUP BY `Date`,Country,Operator,Service,BD.text ORDER BY `Date` ) BD1

                                     ON Inflow.Date = BD1.`Date` AND Inflow.Country = BD1.Country AND Inflow.Operator = BD1.Operator AND Inflow.Service = BD1.Service GROUP BY BD1.Date, Inflow.`Country`, Inflow.`Operator`, Inflow.`Service`, PromotionText, Promotion )
                                     UNION ALL

                                       (SELECT BD1.*, IFNULL(SUM(Inflow.`Inflow`),0) AS Activation , quantity AS Views FROM `operatorinflowoutflow` Inflow RIGHT JOIN (SELECT BD.`datefrom` AS `Date`, `country`.name AS Country, `operator`.name AS Operator, `service`.name AS Service, `text` AS PromotionText, BD.`promotion` AS Promotion, SUM(quantity) as quantity,BD.comments FROM `broadcasting` BD

                                       JOIN `country` ON BD.`country` = country.id JOIN `operator` ON BD.`operator` = operator.id JOIN `service` ON BD.`service` = service.id """
        sql += " WHERE BD.`datefrom` >= '" + dateFrom + "' AND BD.`datefrom` <= '" + dateTo + "'";

        if country is not None:
            sql += " AND country.`name` IN (" + country + ")";

        if operator is not None:
            sql += " AND operator.`name` IN (" + operator + ")";

        if service is not None:
            sql += " AND service.`name` IN (" + service + ")";


        sql+= """ GROUP BY BD.datefrom,country.`name`,operator.`name`,service.`name`,BD.text ORDER BY `Date`) BD1
                      ON Inflow.Date = BD1.`Date` AND Inflow.Country = BD1.Country AND Inflow.Operator = BD1.Operator AND Inflow.Service = BD1.Service GROUP BY BD1.Date, Inflow.`Country`, Inflow.`Operator`, Inflow.`Service`, PromotionText, Promotion )

                                        UNION ALL

                                        ( SELECT BD.`datefrom` AS `Date`, `country`.name AS Country, `operator`.name AS Operator, `service`.name AS Service, `text` AS PromotionText, BD.`promotion` AS Promotion, quantity,BD.comments, 0 AS Activations, quantity AS Views FROM `broadcasting` BD JOIN `country` ON BD.`country` = country.id JOIN `operator` ON BD.`operator` = operator.id

                                            JOIN `service` ON BD.`service` = service.id """

        sql += " WHERE BD.`datefrom` >= '" + dateFrom + "' AND BD.`datefrom` <= '" + dateTo + "'";


        if country is not None:
            sql += " AND country.`name` IN (" + country + ")";

        if operator is not None:
            sql += " AND operator.`name` IN (" + operator + ")";

        if service is not None:
            sql += " AND service.`name` IN (" + service + ")";

        sql+= """  GROUP BY BD.datefrom,country.`name`,operator.`name`,service.`name`,BD.promotion,BD.text ))

                        `NewTable` GROUP BY `Date`,Country,Operator,Service,Promotion,PromotionText )

                        `AllTable`

                         RIGHT JOIN ( SELECT `Date` as DateSum ,Country,Operator,Service, Activation AS TotalSum FROM (SELECT * FROM ((SELECT BD1.*, IFNULL(SUM(Inflow.`Inflow`),0) AS Activation , quantity AS Views
                         FROM `operatorinflowoutflow` Inflow

                         RIGHT JOIN (SELECT BD.`datefrom` AS `Date`, `country`.name AS Country, `operator`.name AS Operator, `service`.name AS Service, `text` AS PromotionText, BD.`promotion` AS Promotion, quantity
                         FROM `broadcasting` BD
                         JOIN `country` ON BD.`country` = country.id JOIN `operator` ON BD.`operator` = operator.id JOIN `service` ON BD.`service` = service.id """

        sql += " WHERE BD.`datefrom` >= '" + dateFrom + "' AND BD.`datefrom` <= '" + dateTo + "'";


        if country is not None:
            sql += " AND country.`name` IN (" + country + ")";

        if operator is not None:
            sql += " AND operator.`name` IN (" + operator + ")";

        if service is not None:
            sql += " AND service.`name` IN (" + service + ")";

        sql+="""  ORDER BY `Date`) BD1 ON Inflow.Date = BD1.`Date` AND Inflow.Country = BD1.Country AND Inflow.Operator = BD1.Operator AND Inflow.Service = BD1.Service
                      AND Inflow.Service IN('Funbox','Humor') GROUP BY BD1.Date, Inflow.`Country`, Inflow.`Operator`, Inflow.Service, PromotionText, Promotion ) )p GROUP BY DATE, Country,Operator,Service ) p

                      GROUP BY DATE, Country,Operator,Service )

                      TotalSumTable ON AllTable.Date = TotalSumTable.DateSum AND AllTable.Country = TotalSumTable.Country AND AllTable.Operator = TotalSumTable.Operator AND AllTable.Service = TotalSumTable.Service

                      RIGHT JOIN ( SELECT BD.`datefrom` AS `DateCount`, country.`name` AS Country,operator.`name` AS Operator,service.`name` AS Service, COUNT(BD.`datefrom`) AS TotalCount FROM `broadcasting` BD JOIN `country` ON BD.`country` = country.id
                      JOIN `operator` ON BD.`operator` = operator.id JOIN `service` ON BD.`service` = service.id """



        sql += " WHERE BD.`datefrom` >= '" + dateFrom + "' AND BD.`datefrom` <= '" + dateTo + "'";


        if country is not None:
            sql += " AND country.`name` IN (" + country + ")";

        if operator is not None:
            sql += " AND operator.`name` IN (" + operator + ")";

        if service is not None:
            sql += " AND service.`name` IN (" + service + ")";

        sql+= """ GROUP BY BD.`datefrom`,country.`name`, operator.`name`,service.name
                   )TotalCount ON AllTable.Date = TotalCount.DateCount AND AllTable.Country = TotalCount.Country AND AllTable.Operator = TotalCount.Operator AND AllTable.Service = TotalCount.Service """

        if promotion is not None:
            sql += " WHERE AllTable.Promotion = '" + promotion + "'";

        sql+= """    ORDER BY `Date` ASC """

        #print sql
        #sys.exit()

        ExportRepo.cursor.execute(sql)
        return ExportRepo.cursor.fetchall()


    def findallBPexport(self, dateFrom, dateTo, country=None, operator=None, service=None, promotion=None):

        sql = """SELECT *,DAYNAME(Date) as DayName FROM BalanceplusView WHERE  """
        sql += " Date >= '" + dateFrom + "' AND Date <= '" + dateTo + "'";



        if country is not None:
            sql += " AND Country IN (" + country + ")";

        if operator is not None:
            sql += " AND Operator IN (" + operator + ")";

        if service is not None:
            sql += " AND Service IN (" + service + ")";



        sql+= """    ORDER BY `Date` ASC """

        #print sql
        #sys.exit()

        ExportRepo.cursor.execute(sql)
        return ExportRepo.cursor.fetchall()

<?php

namespace WebInterface\Modules\Statistics;


use System\Repositories\Repo;


class StatisticsRepository extends Repo
{


    function __construct()
    {

    }

    function GetArrayFromObjects($decodedObj)
    {
        try {
            $decodedObj = (array)$decodedObj;

            array_walk($decodedObj, function (&$object) {
                $object = (array)$object;
            });

            return $decodedObj;

        } catch (\Exception $e) {
            return "";
        }
    }


    function GetFromBalancePlus()
    {


        $sql = "SELECT * FROM black_list";


        $sqlQuery = $this->dbConnectionBalancePlus[0]->prepare($sql);

        $sqlQuery->execute();

        return $sqlQuery->fetch(\PDO::FETCH_ASSOC);

    }

    function GetInflowFromMSSQL()
    {
        $currentDate = $this->GetCurrentDate();

        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));

        $result = sqlsrv_query($this->dbConnectionMssql, "SELECT * FROM Unifun_Promo");

        while ($obj = sqlsrv_fetch_object($result)) {

            $dateObj = (array)$obj->Date;

            $date = $dateObj['date'];

            $sql = "INSERT INTO operatorinflowoutflow (Date,Country,Operator,Service,Inflow,Outflow) VALUES('$date','$obj->Country','$obj->Operator','$obj->Service',$obj->Inflow,$obj->Outflow)";

            $this->GetDbConnection()->query($sql);

        }
    }


    function GetBalancePlusViewFromMSSQL()
    {

        $currentDate = $this->GetCurrentDate();

        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));

        $result = sqlsrv_query($this->dbConnectBPlusMsSql, "SELECT  * FROM GeneralStatistics WHERE Date = '$yesterday'");

        while ($obj = sqlsrv_fetch_object($result)) {

            $dateObj = (array)$obj->Date;

            $date = $dateObj['date'];

            $teaserText = mysql_real_escape_string($obj->TeaserText);

            $sql = "INSERT INTO BalanceplusView (Date,Name,PromotionText,Views) VALUES('$date','$obj->Operator','$teaserText',$obj->Views)";

            $this->GetDbConnection()->query($sql);

        }


    }


    function GetAllCountry()
    {

        $sql = "SELECT * FROM country ";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }

    function GetCountryAndService()
    {

        $sql = "SELECT

           country.name AS Country,
           operator.`name` AS Operator
           FROM
           `operatorandservice` OS
           JOIN `country`
             ON country.id = OS.Country
           JOIN `operator`
             ON operator.`id` = OS.Operator
             GROUP BY Country,Operator ";


        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }


    function GetServiceByCountryOperator($Country, $Operator)
    {

        $sql = "SELECT
  service.`name` as Service
   FROM
  `operatorandservice` OS
  JOIN `country`
    ON country.id = OS.Country
  JOIN `operator`
    ON operator.`id` = OS.Operator
  JOIN `service`
    ON service.id = OS.Service
    WHERE country.name = '$Country'
    AND operator.name = '$Operator'";


        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }


    function GetServiceByCountryOperatorAll()
    {

        $sql = "SELECT
  country.name as Country,
  operator.name as Operator,
  service.`name` as Service
   FROM
  `operatorandservice` OS
  JOIN `country`
    ON country.id = OS.Country
  JOIN `operator`
    ON operator.`id` = OS.Operator
  JOIN `service`
    ON service.id = OS.Service
   ";


        $sqlQuery = $this->GetDbConnection()->query($sql);

        $AllRelation = array();

        while ($row = $sqlQuery->fetch(\PDO::FETCH_ASSOC)) {

            $testArray = array();

            $testArray[$row['Country']][$row['Operator']] = $row['Service'];

            array_push($AllRelation, $testArray);

        }

        return $AllRelation;
    }

    function GetAllOperator()
    {

        $sql = "SELECT * FROM operator";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);
    }

    function GetAllDistinctOperator()
    {

        $sql = "SELECT DISTINCT Operator  FROM operatorinflowoutflow";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_COLUMN);
    }

    function GetAllService()
    {

        $sql = "SELECT * FROM service ";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);
    }


    function ListAverageOperator2work($Date, $Country, $Operator, $Service)
    {


        $sql = "SELECT *, SUM(`Activation`) AS Activations,
                          SUM(`Outflow`) AS Outflow1,
                          (
                            SUM(Activation) - SUM(`Outflow`)
                          ) AS Difference1 ,
                          (SUM(Outflow)/DAY(LAST_DAY('$Date'))) AS Average1 FROM (

                          (
                          SELECT
                           BD.`datefrom` AS `Date`,

                            `country`.name AS Country,
                            `operator`.name AS Operator,
                            `service`.name AS Service,
                            `text` AS PromotionText,
                            BD.`promotion` AS Promotion,
                            SUM(quantity) AS Views,
                            SUM(Inflow.`Inflow`) AS Activation,
                              SUM(Inflow.`Outflow`) AS Outflow,
                          (
                            SUM(Inflow.`Inflow`) - SUM(Inflow.`Outflow`)
                          ) AS Difference ,
                          (SUM(Outflow)/DAY(LAST_DAY('$Date'))) AS Average,
                             'Others' AS FromTable

                          FROM
                            `broadcasting` BD

                            JOIN `country`
                              ON BD.`country` = country.id
                            JOIN `operator`
                              ON BD.`operator` = operator.id
                            JOIN `service`
                              ON BD.`service` = service.id

                              JOIN `operatorinflowoutflow` Inflow
                              ON Inflow.Country = country.`name`
                              AND Inflow.Operator = operator.`name`
                              AND Inflow.Service = service.`name`
                              AND Inflow.Date = BD.`datefrom`
                              WHERE BD.`promotion` != 'Balance Plus'  AND  YEAR('$Date') = YEAR(`BD`.`datefrom`)
                            AND MONTH('$Date') = MONTH(`BD`.`datefrom`)
                              GROUP BY  BD.`datefrom`, BD.`country`, BD.`operator`, BD.`service`
                          )

                          UNION ALL
                        (SELECT
                          a.Date,
                          a.Country,
                          a.Operator,
                          a.Service,
                          a.PromotionText,
                          a.Promotion,

                          a.Views,
                          SUM(OutIn.`Inflow`) AS Activation,
                          SUM(OutIn.`Outflow`) AS Outflow,
                          (
                            SUM(OutIn.`Inflow`) - SUM(OutIn.`Outflow`)
                          ) AS Difference ,
                          (SUM(Outflow)/DAY(LAST_DAY('$Date'))) AS Average,
                           'Balance Plus ' AS FromTable

                          FROM

                          (SELECT
                            BP.Date AS `Date`,
                            SUM(quantity) AS Quantity,
                            SUM(Views) AS Views,
                            `country`.name AS Country,
                            `operator`.name AS Operator,
                            `service`.name AS Service,
                            PromotionText,
                            `text`,
                            'Balance Plus' AS Promotion
                          FROM
                            `broadcasting` BD
                            JOIN `BalanceplusView` BP
                              ON BP.PromotionText = BD.`text`
                              AND BP.Date = BD.`datefrom`
                            JOIN `country`
                              ON BD.`country` = country.id
                            JOIN `operator`
                              ON BD.`operator` = operator.id
                            JOIN `service`
                              ON BD.`service` = service.id
                          WHERE BD.Promotion = 'Balance Plus' AND  YEAR('$Date') = YEAR(`BD`.`datefrom`)
                            AND MONTH('$Date') = MONTH(`BD`.`datefrom`) GROUP BY BP.`Date`,PromotionText) a
                          JOIN `operatorinflowoutflow` OutIn
                            ON a.`Date` = OutIn.Date
                            AND a.Country = OutIn.`Country`
                            AND a.Operator = OutIn.`Operator`
                            AND a.Service = OutIn.`Service`
                        GROUP BY a.Date,
                          a.Country,
                          a.Operator )
                          ) `All` WHERE 1=1 ";


        if ($Country != '') {

            $sql .= " AND `Country` IN ($Country) ";
        }

        if ($Operator != '') {

            $sql .= " AND Operator ='$Operator' ";

        }
        if ($Service != '') {
            $sql .= " AND Service IN ($Service) ";
        }

        $sql .= " GROUP BY MONTH(Date),Service";


        $sqlQuery = $this->GetDbConnection()->query($sql);

        $list['RowCount'] = $sqlQuery->rowCount();
        $list['Data'] = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        return $list;
    }

    function ListAverageOperator2($Date, $Country, $Operator, $Service)
    {

        $sql = " SELECT *,
          IFNULL(Activations,0) as Activations,
          IFNULL(Outflow1,0) as Outflow1,
          IFNULL(Difference1,0) as Difference1,
          IFNULL(Average1,0) as Average1,
           MONTHNAME('$Date') AS MonthName FROM

            ( SELECT
        Service as Service1,
        SUM(Inflow) AS Activations,
        SUM(Outflow) AS Outflow1,
        (SUM(Inflow) - SUM(Outflow)) AS Difference1,
        ROUND((SUM(Outflow)/DAY(LAST_DAY('$Date'))),1) AS Average1

        FROM
        operatorinflowoutflow
        WHERE YEAR('$Date') = YEAR(DATE)
        AND MONTH('$Date') = MONTH(`Date`) ";

        $sql .= " AND Operator ='$Operator' ";

        if ($Country != '') {

            $sql .= " AND Country  = '$Country' ";
        }

        if ($Service != '') {
            $sql .= " AND Service IN ($Service) ";
        }

        $sql .= " GROUP BY MONTH(DATE),Service ";

        $sql .= "
      ) a RIGHT JOIN

  (
  SELECT
  country.name AS Country,
  service.`name` AS Service
   FROM
  `operatorandservice` OS
  JOIN `country`
    ON country.id = OS.Country
  JOIN `operator`
    ON operator.`id` = OS.Operator
  JOIN `service`
    ON service.id = OS.Service

    AND country.name = '$Country'
    AND operator.name = '$Operator' ";

        if ($Service != '') {
            $sql .= " AND service.name IN ($Service) ";
        }

        $sql .= ") b ON a.Service1  = b.Service ";


        $sqlQuery = $this->GetDbConnection()->query($sql);

        $list['RowCount'] = $sqlQuery->rowCount();
        $list['Data'] = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);


        return $list;
    }


    function GetStatData2Work($Date, $Country, $Operator, $Service)
    {

        $sql = " SELECT calendar.Date AS CalendarDate,`All`.*,IFNULL(Activation,0) AS ActivationNew  FROM `calendar` LEFT JOIN  ( ";

        $sql .= "SELECT * FROM  (SELECT *,SUM(Activations) AS Activation FROM (

                          (
                          SELECT
                           BD.`datefrom` AS `Date`,

                            `country`.name AS Country,
                            `operator`.name AS Operator,
                            `service`.name AS Service,
                            `text` AS PromotionText,
                            BD.`promotion` AS Promotion,
                            SUM(quantity) AS Views,
                            SUM(Inflow.`Inflow`) AS Activations,
                              SUM(Inflow.`Outflow`) AS Outflow,
                          (
                            SUM(Inflow.`Inflow`) - SUM(Inflow.`Outflow`)
                          ) AS Difference ,
                          (SUM(Outflow)/DAY(LAST_DAY('$Date'))) AS Average,
                             'Others' AS FromTable

                          FROM
                            `broadcasting` BD

                            JOIN `country`
                              ON BD.`country` = country.id
                            JOIN `operator`
                              ON BD.`operator` = operator.id
                            JOIN `service`
                              ON BD.`service` = service.id

                              JOIN `operatorinflowoutflow` Inflow
                              ON Inflow.Country = country.`name`
                              AND Inflow.Operator = operator.`name`
                              AND Inflow.Service = service.`name`
                              AND Inflow.Date = BD.`datefrom`
                              WHERE BD.`promotion` != 'Balance Plus'  AND  YEAR('$Date') = YEAR(`BD`.`datefrom`)
                            AND MONTH('$Date') = MONTH(`BD`.`datefrom`)";

        if ($Service != '') {

            $sql .= "AND Inflow.`Service` IN ($Service) ";
        }

        $sql .= "    GROUP BY  BD.`datefrom` )

                          UNION ALL
                        (SELECT
                          a.Date,
                          a.Country,
                          a.Operator,
                          a.Service,
                          a.PromotionText,
                          a.Promotion,

                          a.Views,
                          SUM(OutIn.`Inflow`) AS Activations,
                          SUM(OutIn.`Outflow`) AS Outflow,
                          (
                            SUM(OutIn.`Inflow`) - SUM(OutIn.`Outflow`)
                          ) AS Difference ,
                          (SUM(Outflow)/DAY(LAST_DAY('$Date'))) AS Average,
                           'Balance Plus ' AS FromTable

                          FROM

                          (SELECT
                            BP.Date AS `Date`,
                            SUM(quantity) AS Quantity,
                            SUM(Views) AS Views,
                            `country`.name AS Country,
                            `operator`.name AS Operator,
                            `service`.name AS Service,
                            PromotionText,
                            `text`,
                            'Balance Plus' AS Promotion
                          FROM
                            `broadcasting` BD
                            JOIN `BalanceplusView` BP
                              ON BP.PromotionText = BD.`text`
                              AND BP.Date = BD.`datefrom`
                            JOIN `country`
                              ON BD.`country` = country.id
                            JOIN `operator`
                              ON BD.`operator` = operator.id
                            JOIN `service`
                              ON BD.`service` = service.id
                          WHERE BD.Promotion = 'Balance Plus' AND  YEAR('$Date') = YEAR(`BD`.`datefrom`)
                            AND MONTH('$Date') = MONTH(`BD`.`datefrom`) GROUP BY BP.`Date`,PromotionText) a
                          JOIN `operatorinflowoutflow` OutIn
                            ON a.`Date` = OutIn.Date
                            AND a.Country = OutIn.`Country`
                            AND a.Operator = OutIn.`Operator`
                            AND a.Service = OutIn.`Service`";

        $sql .= " GROUP BY a.Date,
                          a.Country,
                          a.Operator )
                          ) `All` WHERE 1=1 ";


        if ($Country != '') {

            $sql .= " AND `Country` IN ($Country) ";
        }

        if ($Operator != '') {

            $sql .= " AND Operator ='$Operator' ";

        }
        if ($Service != '') {
            $sql .= " AND Service IN ($Service) ";
        }


        $sql .= " GROUP BY Date";


        $sql .= " ) Stat LEFT JOIN

(SELECT
   `Date` AS PromotionDate, GROUP_CONCAT(promotion SEPARATOR ':') AS PromotionByDate
    FROM
    (SELECT BD.`datefrom` AS `Date`, BD.promotion FROM
      `broadcasting` BD
      JOIN `country`
        ON BD.`country` = country.id
      JOIN `operator`
        ON BD.`operator` = operator.id
      JOIN `service`
        ON BD.`service` = service.id
      JOIN `operatorinflowoutflow` Inflow
        ON Inflow.Country = country.`name`
        AND Inflow.Operator = operator.`name`
        AND Inflow.Service = service.`name`
        AND Inflow.Date = BD.`datefrom`

    WHERE BD.`promotion` != 'Balance Plus'

    AND Inflow.Operator = '$Operator' ";

        if ($Country != '') {

            $sql .= " AND Inflow.`Country` IN ($Country) ";
        }

        if ($Service != '') {

            $sql .= " AND Inflow.`Service`  IN ($Service) ";
        }


        $sql .= " AND  datefrom = `Date`

    UNION ALL

    SELECT BD.`datefrom` AS `Date`,BD.promotion FROM   `broadcasting` BD

     JOIN `country`
        ON BD.`country` = country.id
      JOIN `operator`
        ON BD.`operator` = operator.id
      JOIN `service`
        ON BD.`service` = service.id
      JOIN `operatorinflowoutflow` Inflow
        ON Inflow.Country = country.`name`
        AND Inflow.Operator = operator.`name`
        AND Inflow.Service = service.`name`
        AND Inflow.Date = BD.`datefrom`

        JOIN `BalanceplusView` BP ON BD.`text` = BP.PromotionText AND BD.`datefrom` = BP.Date
        WHERE BD.`promotion` = 'Balance Plus'  AND Inflow.Operator = '$Operator' ";


        if ($Country != '') {

            $sql .= " AND Inflow.`Country` IN ($Country) ";
        }
        if ($Service != '') {

            $sql .= " AND Inflow.`Service` IN ($Service) ";
        }

        $sql .= "AND BD.`datefrom` = BP.`Date` ) a  GROUP BY `Date`) PromotionDetails ON Stat.Date = PromotionDetails.PromotionDate ";

        $sql .= " ) `All` ON calendar.`Date` = All.Date  WHERE   YEAR('$Date')= YEAR(calendar.`Date`) AND MONTH('$Date') = MONTH(calendar.`Date`) ";

        //  echo $sql;

        //exit;

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);


    }

    function GetStatData($Date, $Country, $Operator, $Service)
    {

        $sql = "

SELECT * FROM (SELECT
  calendar.Date AS CalendarDate,
  `All`.*,
   IFNULL(Inflow,0) AS ActivationNew
FROM
  `calendar`
  LEFT JOIN
    (SELECT
      SUM(Inflow) AS Inflow,Operator,
      SUM(Outflow) AS Outflow,
      Country,
      DATE
    FROM
      operatorinflowoutflow
    WHERE YEAR('$Date') = YEAR(DATE)
      AND MONTH('$Date') = MONTH(`Date`) ";


        $sql .= " AND Operator = '$Operator' ";

        if ($Country != '') {

            $sql .= " AND `Country` = '$Country'";
        }

        if ($Service != '') {

            $sql .= " AND`Service`  IN ($Service) ";

        }


        $sql .= " GROUP BY `Date`) `All`
    ON calendar.`Date` = `All`.Date
WHERE YEAR('$Date') = YEAR(calendar.`Date`)
  AND MONTH('$Date') = MONTH(calendar.`Date`)) ActivationTable


 LEFT JOIN

    (SELECT
   `Date` AS PromotionDate, GROUP_CONCAT(promotion SEPARATOR ':') AS PromotionByDate
    FROM
    (SELECT BD.`datefrom` AS `Date`, BD.promotion FROM
      `broadcasting` BD
      JOIN `country`
        ON BD.`country` = country.id
      JOIN `operator`
        ON BD.`operator` = operator.id
      JOIN `service`
        ON BD.`service` = service.id


    WHERE

     operator.name = '$Operator' ";

        if ($Country != '') {

            $sql .= " AND country.`name` = '$Country' ";
        }

        if ($Service != '') {

            $sql .= " AND service.`name`  IN ($Service) ";
        }


        /* $sql .= "

     UNION ALL

     SELECT BD.`datefrom` AS `Date`,BD.promotion FROM   `broadcasting` BD

      JOIN `country`
         ON BD.`country` = country.id
       JOIN `operator`
         ON BD.`operator` = operator.id
       JOIN `service`
         ON BD.`service` = service.id
       JOIN `operatorinflowoutflow` Inflow
         ON Inflow.Country = country.`name`
     AND Inflow.Operator = operator.`name`
     AND Inflow.Service = service.`name`
     AND Inflow.Date = BD.`datefrom`

         JOIN `BalanceplusView` BP ON BD.`text` = BP.PromotionText AND BD.`datefrom` = BP.Date
         WHERE BD.`promotion` = 'Balance Plus'  AND Inflow.Operator = '$Operator' ";


         if ($Country != '') {

             $sql .= " AND Inflow.`Country`= '$Country' ";
         }
         if ($Service != '') {

             $sql .= " AND Inflow.`Service` IN ($Service)  ";
         }

         $sql.= " AND BD.`datefrom` = BP.`Date` ";*/

        $sql .= " ) a  GROUP BY `Date`) PromotionDetails ON ActivationTable.CalendarDate = PromotionDetails.PromotionDate ";

        //echo $sql;
        // exit;
        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }


    function ShowPromotionTableOLD($Date, $Country, $Operator, $Service)
    {


        $sql = <<<query
          SELECT * FROM (  SELECT
  * , DAYNAME(DATE) as DayName,
   CASE WHEN promotion='Balance Plus' THEN '<span class="teaser-icon balanceplus-boardcast-icon"></span>'
                       WHEN Promotion='IVR Broadcaster' THEN '<span class="teaser-icon ivr-boardcast-icon"></span>'
                       WHEN Promotion='ICB Broadcaster' THEN '<span class="teaser-icon icb-boardcast-icon"></span>'
                       WHEN Promotion='SMS Broadcast' THEN '<span class="teaser-icon sms-boardcast-icon"></span>'
                       WHEN Promotion='SNS' THEN '<span class="teaser-icon sns-boardcast-icon"></span>'
                       WHEN Promotion='Wrong IVR' THEN '<span class="teaser-icon wrongivr-boardcast-icon"></span>'
                       WHEN Promotion='Wrong Star(*)' THEN '<span class="teaser-icon wrongstar-boardcast-icon"></span>'
                       END  AS PromotionIcon,
                       ((`Activation` * 100)/Views) as ActivationPercent


 FROM
  (
    (SELECT
      BD.`datefrom` AS `Date`,
      `country`.name AS Country,
      `operator`.name AS Operator,
      `service`.name AS Service,
      `text` AS PromotionText,
      BD.`promotion` AS Promotion,
      quantity AS Views,
      SUM(Inflow.`Inflow`) AS Activation,
      SUM(Inflow.`Outflow`) AS Outflow,
      (
        SUM(Inflow.`Inflow`) - SUM(Inflow.`Outflow`)
      ) AS Difference,
      (
        Outflow / DAY(LAST_DAY('{$Date}'))
      ) AS Average

    FROM
      `broadcasting` BD
      JOIN `country`
        ON BD.`country` = country.id
      JOIN `operator`
        ON BD.`operator` = operator.id
      JOIN `service`
        ON BD.`service` = service.id
      JOIN `operatorinflowoutflow` Inflow

        ON Inflow.Date = BD.`datefrom`
        AND Inflow.Operator = operator.`name`


    WHERE BD.`promotion` != 'Balance Plus'
    AND Inflow.Operator = '$Operator'
    AND Inflow.Country = '$Country'
      AND BD.`datefrom` = '{$Date}'
query;


        if ($Service != '') {
            $sql .= " AND Inflow.Service IN ($Service) ";
        }

        $sql .= "GROUP BY BD.`promotion`
    )
    UNION
    ALL
    (SELECT
      a.Date,
      a.Country,
      a.Operator,
      a.Service,
      a.PromotionText,
      a.Promotion,
      a.Views,
      0 AS Activation,
      SUM(OutIn.`Outflow`) AS Outflow,
      (
        SUM(OutIn.`Inflow`) - SUM(OutIn.`Outflow`)
      ) AS Difference,
      (
        SUM(Outflow) / DAY(LAST_DAY('{$Date}'))
      ) AS Average

    FROM
      (SELECT
        BP.Date AS `Date`,
        SUM(quantity) AS Quantity,
        SUM(Views) AS Views,
        `country`.name AS Country,
        `operator`.name AS Operator,
        `service`.name AS Service,
        PromotionText,
        `text`,
        'Balance Plus' AS Promotion
      FROM
        `broadcasting` BD
        JOIN `BalanceplusView` BP
          ON BP.PromotionText = BD.`text`
          AND BP.Date = BD.`datefrom`
        JOIN `country`
          ON BD.`country` = country.id
        JOIN `operator`
          ON BD.`operator` = operator.id
        JOIN `service`
          ON BD.`service` = service.id
      WHERE BD.Promotion = 'Balance Plus'
       AND BD.`datefrom` = '{$Date}' ";

        $sql .= " AND operator.`name`  = '$Operator' ";
        $sql .= " AND country.`name`  = '$Country' ";

        $sql .= "   ) a
      JOIN `operatorinflowoutflow` OutIn
        ON a.`Date` = OutIn.Date
        AND a.Country = OutIn.`Country`
        AND a.Operator = OutIn.`Operator`
        AND a.Service = OutIn.`Service`
        AND  a.Date !=''
     )
  ) `All` WHERE 1 = 1 ";


        if ($Country != '') {

            $sql .= " AND `Country` = '$Country' ";
        }

        if ($Operator != '') {
            $sql .= " AND Operator  = '$Operator' ";
        }
        if ($Service != '') {
            $sql .= " AND Service IN ($Service) ";
        }


        $sql2 = <<<query

UNION ALL

 (SELECT
      BD.`datefrom` AS `Date`,
      `country`.name AS Country,
      `operator`.name AS Operator,
      `service`.name AS Service,
      `text` AS PromotionText,
      BD.`promotion` AS Promotion,
      quantity AS Views,
       0 AS Activation,
    0 AS Outflow,
    0 AS Difference,
    0 AS Average,
    DAYNAME(BD.`datefrom`) AS `DayName`,
    CASE WHEN promotion='Balance Plus' THEN '<span class="teaser-icon balanceplus-boardcast-icon"></span>'
                       WHEN Promotion='IVR Broadcaster' THEN '<span class="teaser-icon ivr-boardcast-icon"></span>'
                       WHEN Promotion='ICB Broadcaster' THEN '<span class="teaser-icon icb-boardcast-icon"></span>'
                       WHEN Promotion='SMS Broadcast' THEN '<span class="teaser-icon sms-boardcast-icon"></span>'
                       WHEN Promotion='SNS' THEN '<span class="teaser-icon sns-boardcast-icon"></span>'
                       WHEN Promotion='Wrong IVR' THEN '<span class="teaser-icon wrongivr-boardcast-icon"></span>'
                       WHEN Promotion='Wrong Star(*)' THEN '<span class="teaser-icon wrongstar-boardcast-icon"></span>'
                       END  AS PromotionIcon,
    0 AS ActivationPercent
    FROM
      `broadcasting` BD
      JOIN `country`
        ON BD.`country` = country.id
      JOIN `operator`
        ON BD.`operator` = operator.id
      JOIN `service`
        ON BD.`service` = service.id
    WHERE operator.`name` = '$Operator'
      AND country.`name` = '$Country'
      AND BD.`datefrom` = '$Date'




query;


        if ($Service != '') {
            $sql2 .= " AND service.`name` IN ($Service) ";
        }

        $sql2 .= " GROUP BY BD.`promotion`,BD.text) ) NoDateTable GROUP BY PromotionText";

        $sql .= $sql2;
        $sqlQuery = $this->GetDbConnection()->query($sql);

        $list['RowCount'] = $sqlQuery->rowCount();
        $list['Data'] = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        return $list;


    }


    function ShowPromotionTable($Date, $Country, $Operator, $Service)
    {


        $sql = <<<query
        SELECT AllTable.*,
        IF(Promotion='Wrong IVR' OR Promotion='Wrong Star(*)', Activation, ROUND(((TotalSumTable.TotalSum - IFNULL(TotalIVRSumTable.TotalIVRSumTotal,0))   / TotalCount))  ) Activation1 ,
        IFNULL(ROUND(((( IF(Promotion='Wrong IVR' OR Promotion='Wrong Star(*)', Activation, ROUND(((TotalSumTable.TotalSum - IFNULL(TotalIVRSumTable.TotalIVRSumTotal,0))   / TotalCount))  )) * 100) / Views), 2) ,0 ) AS ActivationPercent,
   CASE WHEN promotion='Balance Plus' THEN '<span class="teaser-icon balanceplus-boardcast-icon"></span>'
                       WHEN Promotion='IVR Broadcaster' THEN '<span class="teaser-icon ivr-boardcast-icon"></span>'
                       WHEN Promotion='ICB Broadcaster' THEN '<span class="teaser-icon icb-boardcast-icon"></span>'
                       WHEN Promotion='SMS Broadcast' THEN '<span class="teaser-icon sms-boardcast-icon"></span>'
                       WHEN Promotion='SNS' THEN '<span class="teaser-icon sns-boardcast-icon"></span>'
                       WHEN Promotion='Wrong IVR' THEN '<span class="teaser-icon wrongivr-boardcast-icon"></span>'
                       WHEN Promotion='Wrong Star(*)' THEN '<span class="teaser-icon wrongstar-boardcast-icon"></span>'
                       END  AS PromotionIcon,
                        TotalIVRSumTable.TotalIVRSumTotal


query;
        $sql .= "

        FROM (
              SELECT *,DAYNAME(`Date`) as DayName FROM ((SELECT
          BD1.*,

          IFNULL(SUM(Inflow.`Inflow`),0) AS Activation,
          IFNULL(quantity,0) AS Views

        FROM
          `operatorinflowoutflow` Inflow
          RIGHT JOIN (SELECT
              BD.`datefrom` AS `Date`,
              `country`.name AS Country,
              `operator`.name AS Operator,
              `service`.name AS Service,
              `text` AS PromotionText,
              BD.`promotion` AS Promotion,
              SUM(BPView.Views) AS quantity
              FROM
              `broadcasting` BD
              JOIN `country`
                ON BD.`country` = country.id
              JOIN `operator`
                ON BD.`operator` = operator.id
              JOIN `service`
                ON BD.`service` = service.id
                JOIN `BalanceplusView` BPView  ON TRIM(BPView.`PromotionText`) = TRIM(BD.`text`)
                AND BPView.`Date` = BD.`datefrom`

                WHERE BD.`promotion` = 'Balance Plus'
                  AND BD.datefrom = '$Date'
                  AND BPView.Date = '$Date' ";

        if ($Country != '') {
            $sql .= "  AND country.`name` = '$Country' ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`  = '$Operator' ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql .= "
         GROUP BY `Date`,
            Country,
            Operator,
            Service,
            BD.text
         ORDER BY `Date` ) BD1

            ON Inflow.Date = BD1.`Date`
            AND Inflow.Country = BD1.Country
            AND Inflow.Operator = BD1.Operator
            AND Inflow.Service = BD1.Service
        GROUP BY BD1.Date,
          Inflow.`Country`,
          Inflow.`Operator`,
          Inflow.`Service`,
          PromotionText,
          Promotion )

          UNION ALL


          (SELECT
        BD1.*,
        IFNULL(SUM(Inflow.`act_from_wrongs`), 0) AS Activation,
        IFNULL(SUM(Inflow.`calls_on_wrongs`), 0) AS Views
      FROM
        `wrongs_and_star_data` Inflow
         JOIN
          (SELECT
            BD.`datefrom` AS `Date`,
            `country`.name AS Country,
            `operator`.name AS Operator,
            `service`.name AS Service,
            `text` AS PromotionText,
            BD.`promotion` AS Promotion,
            BD.quantity

          FROM
            `broadcasting` BD
            JOIN `country`
              ON BD.`country` = country.id
            JOIN `operator`
              ON BD.`operator` = operator.id
            JOIN `service`
              ON BD.`service` = service.id
          WHERE BD.`promotion` = 'Wrong IVR'
            AND BD.datefrom = '$Date' ";

        if ($Country != '') {
            $sql .= "  AND country.`name` = '$Country' ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`  = '$Operator' ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql .= "
          GROUP BY `Date`,
            Country,
            Operator,
            Service,
            BD.text
          ORDER BY `Date`) BD1
           ON DATE_FORMAT(Inflow.datetime,'%Y-%m-%d') = BD1.`Date`
          AND Inflow.country = BD1.Country
          AND Inflow.operator = BD1.Operator
          AND Inflow.service = BD1.Service
      GROUP BY BD1.Date,
        Inflow.`country`,
        Inflow.`Operator`,
        Inflow.`service`,
        PromotionText,
        Promotion)

        UNION ALL

        (SELECT
        BD1.*,
        IFNULL(SUM(Inflow.`act_from_star`), 0) AS Activation,
        IFNULL(SUM(Inflow.`calls_on_star`), 0) AS Views
      FROM
        `wrongs_and_star_data` Inflow
         JOIN
          (SELECT
            BD.`datefrom` AS `Date`,
            `country`.name AS Country,
            `operator`.name AS Operator,
            `service`.name AS Service,
            `text` AS PromotionText,
            BD.`promotion` AS Promotion,
            BD.quantity

          FROM
            `broadcasting` BD
            JOIN `country`
              ON BD.`country` = country.id
            JOIN `operator`
              ON BD.`operator` = operator.id
            JOIN `service`
              ON BD.`service` = service.id
          WHERE BD.`promotion` = 'Wrong Star(*)'
            AND BD.datefrom = '$Date' ";
        if ($Country != '') {
            $sql .= "  AND country.`name` = '$Country' ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`  = '$Operator' ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }


        $sql .= "   GROUP BY `Date`,
            Country,
            Operator,
            Service,
            BD.text
          ORDER BY `Date`) BD1
           ON DATE_FORMAT(Inflow.datetime,'%Y-%m-%d') = BD1.`Date`
          AND Inflow.country = BD1.Country
          AND Inflow.operator = BD1.Operator
          AND Inflow.service = BD1.Service
      GROUP BY BD1.Date,
        Inflow.`country`,
        Inflow.`Operator`,
        Inflow.`service`,
        PromotionText,
        Promotion)

         UNION ALL

          (SELECT
          BD1.*,
          IFNULL(SUM(Inflow.`Inflow`),0) AS Activation ,
          quantity AS Views

        FROM
          `operatorinflowoutflow` Inflow
          RIGHT JOIN
            (SELECT
              BD.`datefrom` AS `Date`,
              `country`.name AS Country,
              `operator`.name AS Operator,
              `service`.name AS Service,
              `text` AS PromotionText,
              BD.`promotion` AS Promotion,
              SUM(quantity) as quantity
            FROM
              `broadcasting` BD
              JOIN `country`
                ON BD.`country` = country.id
              JOIN `operator`
                ON BD.`operator` = operator.id
              JOIN `service`
                ON BD.`service` = service.id

                WHERE BD.datefrom ='$Date'";

        if ($Country != '') {
            $sql .= "  AND country.`name` = '$Country' ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`  = '$Operator'";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }


        $sql .= " GROUP BY BD.datefrom,country.`name`,operator.`name`,service.`name`,BD.text ORDER BY `Date`) BD1
            ON Inflow.Date = BD1.`Date`
            AND Inflow.Country = BD1.Country
            AND Inflow.Operator = BD1.Operator
           AND Inflow.Service = BD1.Service
          GROUP BY BD1.Date,
          Inflow.`Country`,
          Inflow.`Operator`,
          Inflow.`Service`,
          PromotionText,
          Promotion )

          UNION ALL  (
               SELECT
              BD.`datefrom` AS `Date`,
              `country`.name AS Country,
              `operator`.name AS Operator,
              `service`.name AS Service,
              `text` AS PromotionText,
              BD.`promotion` AS Promotion,
              quantity,
              0 AS Activations,
              quantity AS Views
            FROM
              `broadcasting` BD
              JOIN `country`
                ON BD.`country` = country.id
              JOIN `operator`
                ON BD.`operator` = operator.id
              JOIN `service`
                ON BD.`service` = service.id

                WHERE  BD.datefrom = '$Date'  ";
        if ($Country != '') {
            $sql .= "  AND country.`name` = '$Country' ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`  = '$Operator' ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql .= " GROUP BY BD.datefrom,country.`name`,operator.`name`,service.`name`,BD.promotion,BD.text

                      )) `NewTable` GROUP BY  `Date`,Country,Operator,Service,Promotion,PromotionText  ) `AllTable` ";


        $sql .= "

        RIGHT JOIN
        (
          SELECT `Date` as DateSum ,Country,Operator,
          Activation AS TotalSum
          FROM (SELECT * FROM ((SELECT
          BD1.*,
          IFNULL(SUM(Inflow.`Inflow`),0) AS Activation ,
          quantity AS Views

         FROM
          `operatorinflowoutflow` Inflow
          RIGHT JOIN
            (SELECT
              BD.`datefrom` AS `Date`,
              `country`.name AS Country,
              `operator`.name AS Operator,
              `service`.name AS Service,
              `text` AS PromotionText,
              BD.`promotion` AS Promotion,
              quantity
            FROM
              `broadcasting` BD
              JOIN `country`
                ON BD.`country` = country.id
              JOIN `operator`
                ON BD.`operator` = operator.id
              JOIN `service`
                ON BD.`service` = service.id

                WHERE BD.datefrom = '$Date' ";
        if ($Country != '') {
            $sql .= "  AND country.`name` = '$Country' ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`  = '$Operator' ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql .= " ORDER BY `Date`) BD1
            ON Inflow.Date = BD1.`Date`
            AND Inflow.Country = BD1.Country
            AND Inflow.Operator = BD1.Operator ";


        if ($Service != '') {
            $sql .= " AND Inflow.Service IN($Service)   ";
        }

        $sql .= "  GROUP BY BD1.Date,
          Inflow.`Country`,
          Inflow.`Operator`,

          PromotionText,
          Promotion ) )p GROUP BY DATE, Country,Operator,Service ) p GROUP BY DATE, Country,Operator

             ) TotalSumTable ON AllTable.Date =  TotalSumTable.DateSum AND AllTable.Country = TotalSumTable.Country AND AllTable.Operator = TotalSumTable.Operator

                       LEFT JOIN

                      ( SELECT *, SUM(TotalIVRSum) AS TotalIVRSumTotal

                      FROM ((SELECT
      `Date` AS DateIVRSum,
      Country,
      Operator,
      Activation AS TotalIVRSum
    FROM
      (SELECT
        *
      FROM
        (
          (SELECT
            BD1.*,
            IFNULL(SUM(Inflow.`act_from_wrongs`), 0) AS Activation,
            quantity AS Views
          FROM
            `wrongs_and_star_data` Inflow
            RIGHT JOIN
              (SELECT
                BD.`datefrom` AS `Date`,
                `country`.name AS Country,
                `operator`.name AS Operator,
                `service`.name AS Service,
                `text` AS PromotionText,
                BD.`promotion` AS Promotion,
                quantity
              FROM
                `broadcasting` BD
                JOIN `country`
                  ON BD.`country` = country.id
                JOIN `operator`
                  ON BD.`operator` = operator.id
                JOIN `service`
                  ON BD.`service` = service.id
              WHERE BD.datefrom = '$Date'
             AND  BD.Promotion = 'Wrong IVR'";
        if ($Country != '') {
            $sql .= "  AND country.`name` = '$Country' ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`  = '$Operator' ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql .= " ORDER BY `Date`) BD1
              ON DATE_FORMAT(Inflow.datetime, '%Y-%m-%d') = BD1.`Date`
              AND Inflow.country = BD1.Country
              AND Inflow.operator = BD1.Operator
          GROUP BY BD1.Date,
            Inflow.`country`,
            Inflow.`operator`,
            Promotion)
        ) p
      GROUP BY DATE,
        Country,
        Operator,
        Service) p
    GROUP BY DATE,
      Country,
      Operator)

      UNION ALL


       (SELECT
      `Date` AS DateIVRSum,
      Country,
      Operator,
      Activation AS TotalIVRSum
    FROM
      (SELECT
        *
      FROM
        (
          (SELECT
            BD1.*,
            IFNULL(SUM(Inflow.`act_from_star`), 0) AS Activation,
            quantity AS Views
          FROM
            `wrongs_and_star_data` Inflow
            RIGHT JOIN
              (SELECT
                BD.`datefrom` AS `Date`,
                `country`.name AS Country,
                `operator`.name AS Operator,
                `service`.name AS Service,
                `text` AS PromotionText,
                BD.`promotion` AS Promotion,
                quantity
              FROM
                `broadcasting` BD
                JOIN `country`
                  ON BD.`country` = country.id
                JOIN `operator`
                  ON BD.`operator` = operator.id
                JOIN `service`
                  ON BD.`service` = service.id
              WHERE BD.datefrom = '$Date'
             AND  BD.Promotion = 'Wrong Star(*)'";
        if ($Country != '') {
            $sql .= "  AND country.`name` = '$Country' ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`  = '$Operator' ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql .= " ORDER BY `Date`) BD1
              ON DATE_FORMAT(Inflow.datetime, '%Y-%m-%d') = BD1.`Date`
              AND Inflow.country = BD1.Country
              AND Inflow.operator = BD1.Operator
          GROUP BY BD1.Date,
            Inflow.`country`,
            Inflow.`operator`,
            Promotion)
        ) p
      GROUP BY DATE,
        Country,
        Operator,
        Service) p
    GROUP BY DATE,
      Country,
      Operator) )a ) TotalIVRSumTable

      ON AllTable.Date =  TotalIVRSumTable.DateIVRSum AND AllTable.Country = TotalIVRSumTable.Country AND AllTable.Operator = TotalIVRSumTable.Operator

                       LEFT JOIN
                     ( SELECT
              BD.`datefrom` AS `DateCount`,
              country.`name` AS Country,operator.`name` AS Operator,service.`name` AS Service,
              COUNT(BD.`datefrom`) AS TotalCount
            FROM
              `broadcasting` BD
              JOIN `country`
                ON BD.`country` = country.id
              JOIN `operator`
                ON BD.`operator` = operator.id
              JOIN `service`
                ON BD.`service` = service.id

               WHERE BD.datefrom = '$Date'

               AND BD.promotion !='Wrong IVR'

               AND BD.promotion !='Wrong Star(*)' ";

        if ($Country != '') {
            $sql .= "  AND country.`name` = '$Country' ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`  = '$Operator' ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql .= "GROUP BY BD.`datefrom`,country.`name`, operator.`name` )TotalCount ON AllTable.Date =  TotalCount.DateCount AND AllTable.Country = TotalCount.Country AND AllTable.Operator = TotalCount.Operator ";


        //echo $sql;

        //exit; Right to Left Change in  Total Count

        $sqlQuery = $this->GetDbConnection()->query($sql);

        $list['Data'] = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];

        return $list;
    }
}
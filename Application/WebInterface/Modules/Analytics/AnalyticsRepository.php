<?php

namespace WebInterface\Modules\Analytics;

use Shared\Model\AjaxGrid;
use System\Repositories\Repo;


class AnalyticsRepository extends Repo
{

    function __construct()
    {

    }

    function GetAllCountry()
    {

        $sql = "SELECT * FROM country ";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }

    function GetOperatorByCountry($Country)
    {
        $sql = "SELECT operator.`id`,operator.`name` FROM  operatorandservice OS JOIN `operator` ON operator.`id` = OS.Operator WHERE Country IN($Country) GROUP BY operator.id";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }

    function GetServiceByOperator($Operator, $Country)
    {
        $sql = "SELECT service.id,service.`name` FROM  operatorandservice OS JOIN `service` ON service.`id` = OS.Service WHERE Operator IN($Operator) AND Country IN($Country)  GROUP BY service.id";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }


    function FindAll(AjaxGrid $ajaxGrid, $DateFrom, $DateTo, $Country, $Operator, $Service, $Promotion)
    {

        $sql = "
        SELECT TotalSumTable.TotalSum , AllTable.*,IFNULL(Views,0) as Views1,
        CASE WHEN TotalCount = 1 THEN Activation
        ELSE   IF(Promotion='Wrong IVR' OR Promotion='Wrong Star(*)', Activation, ROUND(((TotalSumTable.TotalSum - IFNULL(TotalIVRSumTable.TotalIVRSumTotal,0))   / TotalCount)))
        END AS Activation1,
        ROUND((TotalSumTable.TotalSum/TotalCount)) AS Activation2,
        IFNULL(ROUND((((TotalSumTable.TotalSum/TotalCount) * 100) / Views), 2) ,0 ) AS ActivationPercent


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
              SUM(BPView.Views) AS quantity,
              BD.comments
              FROM
              `broadcasting` BD
              JOIN `country`
                ON BD.`country` = country.id
              JOIN `operator`
                ON BD.`operator` = operator.id
              JOIN `service`
                ON BD.`service` = service.id
                /*JOIN `BalanceplusView` BPView  ON TRIM(BPView.`PromotionText`) LIKE CONCAT(TRIM(BD.`text`),'%')*/
                JOIN `BalanceplusView` BPView  ON TRIM(BPView.`PromotionText`) = TRIM(BD.`text`)
                AND BPView.`Date` = BD.`datefrom`

                WHERE BD.`promotion` = 'Balance Plus'
                  AND BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo'
                  AND BPView.Date >= '$DateFrom' AND  BPView.Date <= '$DateTo'

                  ";
        if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql .= "
          GROUP BY `Date`,Country,Operator,Service,BD.text
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
            BD.quantity,
            BD.comments

          FROM
            `broadcasting` BD
            JOIN `country`
              ON BD.`country` = country.id
            JOIN `operator`
              ON BD.`operator` = operator.id
            JOIN `service`
              ON BD.`service` = service.id
          WHERE BD.`promotion` = 'Wrong IVR'
            AND BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo' ";

        if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
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
            BD.quantity,
            BD.comments

          FROM
            `broadcasting` BD
            JOIN `country`
              ON BD.`country` = country.id
            JOIN `operator`
              ON BD.`operator` = operator.id
            JOIN `service`
              ON BD.`service` = service.id
          WHERE BD.`promotion` = 'Wrong Star(*)'
            AND BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo' ";
        if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
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
              SUM(quantity) as quantity,
               BD.comments
            FROM
              `broadcasting` BD
              JOIN `country`
                ON BD.`country` = country.id
              JOIN `operator`
                ON BD.`operator` = operator.id
              JOIN `service`
                ON BD.`service` = service.id

                WHERE BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo' ";

       /* if ($Promotion != '') {
            $sql .= "  AND BD.`promotion` = '$Promotion' ";
        }*/

        if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }

        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }


        $sql .= " GROUP BY BD.datefrom,country.`name`,operator.`name`,service.`name`,BD.text  ORDER BY `Date`) BD1
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
               BD.comments,
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

                WHERE  BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo' ";
        /*if ($Promotion != '') {
            $sql .= "  AND BD.`promotion` = '$Promotion' ";
        }*/

        if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }

        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql .= " GROUP BY BD.datefrom,country.`name`,operator.`name`,service.`name`,BD.promotion,BD.text

                      )) `NewTable` GROUP BY  `Date`,Country,Operator,Service,Promotion,PromotionText  ) `AllTable` ";



        $sql .= "

        RIGHT JOIN
        (
          SELECT `Date` as DateSum ,Country,Operator,Service,
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

                WHERE BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo'  ";
                 if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }

        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

           $sql.=  " ORDER BY `Date`) BD1
            ON Inflow.Date = BD1.`Date`
            AND Inflow.Country = BD1.Country
            AND Inflow.Operator = BD1.Operator
             /* AND Inflow.Service = BD1.Service */
              ";

        if ($Service != '') {
            $sql .= " AND Inflow.Service IN($Service)   ";
        }


          $sql.= "  GROUP BY BD1.Date,
          Inflow.`Country`,
          Inflow.`Operator`,
         /* Inflow.Service, */
          PromotionText,
          Promotion ) )p GROUP BY DATE, Country,Operator,Service ) p

          GROUP BY DATE, Country,Operator,Service

             ) TotalSumTable ON AllTable.Date =  TotalSumTable.DateSum AND AllTable.Country = TotalSumTable.Country AND AllTable.Operator = TotalSumTable.Operator AND  AllTable.Service = TotalSumTable.Service

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
              WHERE BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo'
             AND  BD.Promotion = 'Wrong IVR'";
        if ($Country != '') {
            $sql .= "  AND country.`name`  IN($Country) ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`   IN($Operator) ";
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
              WHERE BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo'
             AND  BD.Promotion = 'Wrong Star(*)'";
        if ($Country != '') {
            $sql .= "  AND country.`name`  IN($Country) ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name`   IN($Operator) ";
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

               WHERE BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo'

               AND BD.promotion !='Wrong IVR'

               AND BD.promotion !='Wrong Star(*)' ";

        if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql.=  "GROUP BY BD.`datefrom`,country.`name`, operator.`name` /* ,service.name */ )TotalCount ON AllTable.Date =  TotalCount.DateCount AND AllTable.Country = TotalCount.Country AND AllTable.Operator = TotalCount.Operator

        /* AND AllTable.Service = TotalCount.Service */  ";

        if ($Promotion != '') {
            $sql .= "  WHERE AllTable.Promotion = '$Promotion' ";
        }

        $sql2 = $sql;

        $sql .= " ORDER BY $ajaxGrid->sortExpression $ajaxGrid->sortOrder LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM ( $sql2 ) a");

        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;

    }



    function FindAll1(AjaxGrid $ajaxGrid, $DateFrom, $DateTo, $Country, $Operator, $Service, $Promotion)
    {

        $sql = "
              SELECT *,IFNULL(Views,0) as Views1,Activation as Activation1,DAYNAME(`Date`) as DayName,IFNULL(ROUND(((`Activation` * 100) / Views), 2) ,0 ) AS ActivationPercent FROM ((SELECT
          BD1.*,
          /*IFNULL(SUM(Inflow.`Inflow`),0) AS Activation,*/
          IFNULL(SUM(Inflow.`Inflow`),0) AS Activation,
          quantity AS Views

        FROM
          `operatorinflowoutflow` Inflow
          RIGHT JOIN (SELECT
              BD.`datefrom` AS `Date`,
              `country`.name AS Country,
              `operator`.name AS Operator,
              `service`.name AS Service,
              `text` AS PromotionText,
              BD.`promotion` AS Promotion,
              BPView.Views AS quantity


            FROM
              `broadcasting` BD
              JOIN `country`
                ON BD.`country` = country.id
              JOIN `operator`
                ON BD.`operator` = operator.id
              JOIN `service`
                ON BD.`service` = service.id
                JOIN `BalanceplusView` BPView  ON BD.`text` = BPView.`PromotionText`
                WHERE BD.`promotion` = 'Balance Plus'
                  AND BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo'
                  ";
        if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }
        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql.= " ORDER BY `Date` ) BD1

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

                WHERE BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo' ";

        if ($Promotion != '') {
            $sql .= "  AND BD.`promotion` = '$Promotion' ";
        }

        if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }

        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }


        $sql.= "ORDER BY `Date`) BD1
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

                WHERE  BD.datefrom >= '$DateFrom' AND  BD.datefrom <= '$DateTo' ";
        if ($Promotion != '') {
            $sql .= "  AND BD.`promotion` = '$Promotion' ";
        }

        if ($Country != '') {
            $sql .= "  AND country.`name` IN($Country) ";
        }

        if ($Operator != '') {
            $sql .= " AND operator.`name` IN($Operator) ";
        }
        if ($Service != '') {
            $sql .= " AND service.`name` IN($Service)   ";
        }

        $sql.=   " GROUP BY BD.datefrom,country.`name`,operator.`name`,service.`name`,BD.promotion,BD.text

                      )) `NewTable` GROUP BY  `Date`,Country,Operator,Service,Promotion,PromotionText  ";


        $sql2 = $sql;

        $sql .= " ORDER BY $ajaxGrid->sortExpression $ajaxGrid->sortOrder LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber";

        echo $sql;
        exit;
        $sqlQuery = $this->GetDbConnection()->query($sql);


        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM ( $sql2 ) a");

        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;

    }


}
<?php

namespace WebInterface\Modules\BalancePlus;

use Shared\Model\AjaxGrid;
use System\Repositories\Repo;


class BalancePlusRepository extends Repo
{

    function __construct()
    {

    }

    function GetAllCountry()
    {

      //  $sql = "SELECT * FROM country ";
        $sql = " SELECT country.`id` ,country.`name`  FROM `broadcasting` BD JOIN `country` ON country.id = BD.`country` AND BD.`promotion` = 'Balance Plus' GROUP BY country.`name` ";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }

    function GetOperatorByCountry($Country)
    {
       // $sql = "SELECT operator.`id`,operator.`name` FROM  operatorandservice OS JOIN `operator` ON operator.`id` = OS.Operator WHERE Country IN($Country) GROUP BY operator.id";

        $sql = "

        SELECT  operator.`id`,operator.`name` FROM  operatorandservice OS JOIN `operator` ON operator.`id` = OS.Operator

     JOIN `broadcasting` BD ON BD.`country` = OS.Country AND BD.`operator` = OS.Operator AND BD.`promotion` = 'Balance Plus'

       WHERE OS.Country IN($Country) GROUP BY operator.id ";


        $sql = "SELECT operator.`id`,operator.`name` FROM `country` JOIN `BalanceplusView` BP ON BP.Country = country.`name`
        JOIN `operator` ON BP.Operator = operator.`name` WHERE country.id IN ($Country) GROUP BY Operator ";


        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }

    function GetServiceByOperator($Operator, $Country)
    {
        $sql = "SELECT service.id,service.`name` FROM  operatorandservice OS JOIN `service` ON service.`id` = OS.Service WHERE Operator IN($Operator) AND Country IN($Country)  GROUP BY service.id";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }


    function FindAll(AjaxGrid $ajaxGrid, $DateFrom, $DateTo, $Country, $Operator,$Service)
    {
        $sql = " SELECT *,DAYNAME(Date) as DayName FROM BalanceplusView WHERE Date >= '$DateFrom' AND Date<= '$DateTo'";

        if($Country!=''){

          $sql.=  "AND Country IN ($Country)";
        }

        if($Operator!=''){

            $sql.=  "AND Operator IN ($Operator)";
        }

        if($Service!=''){

            $sql.=  "AND Service IN ($Service)";
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